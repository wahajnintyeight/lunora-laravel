@extends('layouts.shop')

@section('title', $product->name . ' - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-[#f59e0b] hover:text-emerald-700">Home</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('products.index') }}" class="text-[#f59e0b] hover:text-emerald-700">Products</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('category.show', $product->category->slug) }}" class="text-[#f59e0b] hover:text-emerald-700">{{ $product->category->name }}</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-500">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div>
            @if($product->images->count() > 0)
                <div class="space-y-4">
                    <!-- Main Image -->
                    <div class="aspect-square">
                        <img id="main-image" 
                             src="{{ $product->images->first()->medium_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover rounded-lg">
                    </div>
                    
                    <!-- Thumbnail Images -->
                    @if($product->images->count() > 1)
                        <div class="flex gap-2 overflow-x-auto">
                            @foreach($product->images as $image)
                                <img src="{{ $image->thumbnail_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-20 h-20 object-cover rounded-md cursor-pointer border-2 {{ $loop->first ? 'border-emerald-500' : 'border-gray-200' }} hover:border-emerald-500 transition-colors"
                                     onclick="changeMainImage('{{ $image->medium_url }}', this)">
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-400 text-lg">No Image Available</span>
                </div>
            @endif
        </div>
        
        <!-- Product Details -->
        <div>
            <div class="mb-4">
                @if($product->is_featured)
                    <span class="bg-emerald-100 text-emerald-800 text-sm px-3 py-1 rounded-full">Featured</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            
            <div class="mb-4">
                @if($product->compare_at_price_pkr && $product->compare_at_price_pkr > $product->price_pkr)
                    <p class="text-lg text-gray-500 line-through">PKR {{ number_format($product->compare_at_price_pkr / 100, 2) }}</p>
                @endif
                <p id="current-price" class="text-3xl text-[#f59e0b] font-bold">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
            </div>
            
            @if($product->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                    <div class="text-gray-700 prose prose-sm">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

            <!-- Product Details -->
            <div class="mb-6 space-y-2">
                <p class="text-sm text-gray-600"><span class="font-medium">Category:</span> {{ $product->category->name }}</p>
                @if($product->sku)
                    <p class="text-sm text-gray-600"><span class="font-medium">SKU:</span> <span id="current-sku">{{ $product->sku }}</span></p>
                @endif
                @if($product->material)
                    <p class="text-sm text-gray-600"><span class="font-medium">Material:</span> {{ $product->material }}</p>
                @endif
                @if($product->brand)
                    <p class="text-sm text-gray-600"><span class="font-medium">Brand:</span> {{ $product->brand }}</p>
                @endif
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Stock:</span> 
                    <span id="current-stock" class="{{ $availableStock <= 5 ? 'text-orange-600' : 'text-green-600' }}">
                        {{ $availableStock }} available
                    </span>
                </p>
            </div>

            <form id="add-to-cart-form" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="selected-variant-id" value="">

                @if($hasVariants)
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Options</h3>
                        @foreach($productOptions as $optionName => $optionValues)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $optionName }}</label>
                                <select name="options[{{ $optionName }}]" 
                                        class="variant-option w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"
                                        data-option="{{ $optionName }}"
                                        required>
                                    <option value="">Select {{ $optionName }}</option>
                                    @foreach($optionValues as $value)
                                        <option value="{{ $value->value }}">{{ $value->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Customization Options -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Customization (Optional)</h3>
                    
                    <div>
                        <label for="engraving" class="block text-sm font-medium text-gray-700 mb-2">
                            Engraving Text <span class="text-gray-500">(Max 20 characters)</span>
                        </label>
                        <input type="text" 
                               id="engraving" 
                               name="customization[engraving]" 
                               maxlength="20"
                               placeholder="Enter text for engraving..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="text-xs text-gray-500 mt-1">Additional charges may apply for engraving</p>
                    </div>

                    <div>
                        <label for="special-instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Instructions
                        </label>
                        <textarea id="special-instructions" 
                                  name="customization[instructions]" 
                                  rows="3"
                                  placeholder="Any special requests or instructions..."
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                onclick="changeQuantity(-1)" 
                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $availableStock }}"
                               class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="button" 
                                onclick="changeQuantity(1)" 
                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <button type="submit" 
                        id="add-to-cart-btn"
                        class="w-full bg-[#f59e0b] text-white py-3 px-6 rounded-lg font-semibold hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $availableStock == 0 ? 'disabled' : '' }}>
                    @if($availableStock == 0)
                        Out of Stock
                    @else
                        Add to Cart
                    @endif
                </button>
            </form>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                            @if($relatedProduct->images->count() > 0)
                                <img src="{{ $relatedProduct->images->first()->medium_url }}" 
                                     alt="{{ $relatedProduct->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $relatedProduct->name }}</h3>
                                <p class="text-[#f59e0b] font-bold">PKR {{ number_format($relatedProduct->price_pkr / 100, 2) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
// Change main image when thumbnail is clicked
function changeMainImage(imageSrc, thumbnail) {
    document.getElementById('main-image').src = imageSrc;
    
    // Update thumbnail borders
    document.querySelectorAll('.aspect-square + div img').forEach(img => {
        img.classList.remove('border-emerald-500');
        img.classList.add('border-gray-200');
    });
    thumbnail.classList.remove('border-gray-200');
    thumbnail.classList.add('border-emerald-500');
}

// Change quantity
function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const newValue = currentValue + change;
    const maxStock = parseInt(quantityInput.getAttribute('max'));
    
    if (newValue >= 1 && newValue <= maxStock) {
        quantityInput.value = newValue;
    }
}

// Handle variant selection
document.addEventListener('DOMContentLoaded', function() {
    const variantOptions = document.querySelectorAll('.variant-option');
    const productId = {{ $product->id }};
    
    variantOptions.forEach(option => {
        option.addEventListener('change', function() {
            updateVariantInfo();
        });
    });
    
    function updateVariantInfo() {
        const selectedOptions = {};
        let allSelected = true;
        
        variantOptions.forEach(option => {
            if (option.value) {
                selectedOptions[option.dataset.option] = option.value;
            } else {
                allSelected = false;
            }
        });
        
        if (allSelected && Object.keys(selectedOptions).length > 0) {
            // Fetch variant information
            fetch(`/products/${productId}/variants?${new URLSearchParams({options: JSON.stringify(selectedOptions)})}`)
                .then(response => response.json())
                .then(data => {
                    if (data.variants && data.variants.length > 0) {
                        const variant = data.variants[0];
                        
                        // Update price
                        document.getElementById('current-price').textContent = variant.formatted_price;
                        
                        // Update SKU
                        if (document.getElementById('current-sku')) {
                            document.getElementById('current-sku').textContent = variant.sku;
                        }
                        
                        // Update stock
                        const stockElement = document.getElementById('current-stock');
                        stockElement.textContent = `${variant.stock} available`;
                        stockElement.className = variant.stock <= 5 ? 'text-orange-600' : 'text-green-600';
                        
                        // Update quantity max
                        const quantityInput = document.getElementById('quantity');
                        quantityInput.setAttribute('max', variant.stock);
                        
                        // Update add to cart button
                        const addToCartBtn = document.getElementById('add-to-cart-btn');
                        const variantIdInput = document.getElementById('selected-variant-id');
                        
                        if (variant.stock > 0) {
                            addToCartBtn.disabled = false;
                            addToCartBtn.textContent = 'Add to Cart';
                            variantIdInput.value = variant.id;
                        } else {
                            addToCartBtn.disabled = true;
                            addToCartBtn.textContent = 'Out of Stock';
                            variantIdInput.value = '';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching variant info:', error);
                });
        }
    }
});
</script>
@endsection