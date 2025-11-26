@extends('layouts.app')

@section('title', $product->name . ' - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-12 lg:pt-20 lg:pb-20">
    <!-- Breadcrumbs -->
    <nav class="mb-12">
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
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
                                     class="w-20 h-20 object-cover rounded-md cursor-pointer border-2 {{ $loop->first ? 'border-gold-500' : 'border-gray-200' }} hover:border-gold-500 transition-colors"
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
            <div class="mb-6">
                @if($product->is_featured)
                    <span class="bg-gold-100 text-gold-800 text-sm px-3 py-1 rounded-full">Featured</span>
                @endif
            </div>

            <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $product->name }}</h1>
            
            <div class="mb-8">
                @if($product->compare_at_price_pkr && $product->compare_at_price_pkr > $product->price_pkr)
                    <p class="text-lg text-gray-500 line-through">PKR {{ number_format($product->compare_at_price_pkr / 100, 2) }}</p>
                @endif
                <p id="current-price" class="text-3xl text-gold-500 font-bold">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
            </div>
            
            @if($product->description)
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <div class="text-gray-700 prose prose-sm">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

            <!-- Product Details -->
            <div class="mb-10 space-y-3">
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

            <form id="add-to-cart-form" class="space-y-8 mt-10">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="selected-variant-id" value="">

                @if($hasVariants)
                    <div class="space-y-5">
                        <h3 class="text-lg font-semibold text-gray-900">Options</h3>
                        @foreach($productOptions as $optionName => $optionValues)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $optionName }}</label>
                                <select name="options[{{ $optionName }}]" 
                                        class="variant-option w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-gold-500 focus:border-gold-500"
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
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-gold-500 focus:border-gold-500">
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
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-gold-500 focus:border-gold-500"></textarea>
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
                               class="w-20 text-center border border-gray-300 rounded-md px-3 py-2 focus:ring-gold-500 focus:border-gold-500">
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
                        class="w-full bg-gold-500 text-white py-4 px-6 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:bg-gold-600 hover:-translate-y-0.5 focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg disabled:hover:translate-y-0 transition-all duration-200"
                        {{ $availableStock == 0 ? 'disabled' : '' }}>
                    @if($availableStock == 0)
                        Out of Stock
                    @else
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Add to Cart
                        </span>
                    @endif
                </button>
            </form>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="mt-16 lg:mt-20">
            <h2 class="text-2xl font-semibold text-gray-900 mb-8">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <x-product-card 
                        :product="$relatedProduct"
                        :showRating="false"
                        imageHeight="h-48"
                    />
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
        img.classList.remove('border-gold-500');
        img.classList.add('border-gray-200');
    });
    thumbnail.classList.remove('border-gray-200');
    thumbnail.classList.add('border-gold-500');
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