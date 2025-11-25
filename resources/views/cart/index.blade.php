@extends('layouts.shop')

@section('title', 'Shopping Cart - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-4 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8 responsive-text-3xl">Shopping Cart</h1>

    @if(!empty($validationErrors))
        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Cart Updated</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($validationErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" data-cart-container>
            <!-- Cart Items -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 responsive-text-xl">Cart Items ({{ $cart->items->sum('quantity') }})</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($cart->items as $item)
                            <div class="p-4 sm:p-6" id="cart-item-{{ $item->id }}" data-cart-item="{{ $item->id }}">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product->images->count() > 0)
                                            <div class="product-gallery" data-product-gallery>
                                                @foreach($item->product->images as $index => $image)
                                                    <img src="{{ $image->image_path }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-lg {{ $index === 0 ? 'active' : 'hidden' }}"
                                                         data-gallery-image="{{ $index }}">
                                                @endforeach
                                                @if($item->product->images->count() > 1)
                                                    <div class="gallery-indicators">
                                                        @foreach($item->product->images as $index => $image)
                                                            <span class="indicator {{ $index === 0 ? 'active' : '' }}" data-gallery-indicator="{{ $index }}"></span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-[#f59e0b]">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                                
                                                @if($item->variant)
                                                    <div class="mt-1">
                                                        @foreach($item->variant->options_json ?? [] as $optionName => $optionValue)
                                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1">
                                                                {{ $optionName }}: {{ $optionValue }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if($item->customizations)
                                                    <div class="mt-2 text-sm text-gray-600">
                                                        @if(isset($item->customizations['engraving']) && $item->customizations['engraving'])
                                                            <p><strong>Engraving:</strong> {{ $item->customizations['engraving'] }}</p>
                                                        @endif
                                                        @if(isset($item->customizations['instructions']) && $item->customizations['instructions'])
                                                            <p><strong>Instructions:</strong> {{ $item->customizations['instructions'] }}</p>
                                                        @endif
                                                        @if(isset($item->customizations['ring_size']) && $item->customizations['ring_size'])
                                                            <p><strong>Ring Size:</strong> {{ $item->customizations['ring_size'] }}</p>
                                                        @endif
                                                        @if(isset($item->customizations['chain_length']) && $item->customizations['chain_length'])
                                                            <p><strong>Chain Length:</strong> {{ $item->customizations['chain_length'] }}</p>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Customization Options -->
                                                <div class="mt-3">
                                                    <button type="button" 
                                                            onclick="toggleCustomization({{ $item->id }})"
                                                            class="text-sm text-[#f59e0b] hover:text-emerald-700 font-medium">
                                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                        </svg>
                                                        {{ $item->customizations ? 'Edit Customization' : 'Add Customization' }}
                                                    </button>
                                                </div>

                                                <!-- Customization Form (Hidden by default) -->
                                                <div id="customization-{{ $item->id }}" class="mt-4 p-4 bg-gray-50 rounded-lg" style="display: none;">
                                                    <form onsubmit="updateCustomization(event, {{ $item->id }})">
                                                        @csrf
                                                        <div class="space-y-4">
                                                            <!-- Engraving -->
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Engraving Text (Optional)
                                                                </label>
                                                                <input type="text" 
                                                                       name="engraving" 
                                                                       value="{{ $item->customizations['engraving'] ?? '' }}"
                                                                       maxlength="50"
                                                                       placeholder="Enter text to engrave (max 50 characters)"
                                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                                <p class="text-xs text-gray-500 mt-1">Additional charges may apply for engraving</p>
                                                            </div>

                                                            <!-- Ring Size (for rings) -->
                                                            @if(stripos($item->product->name, 'ring') !== false || stripos($item->product->category->name, 'ring') !== false)
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Ring Size
                                                                    </label>
                                                                    <select name="ring_size" 
                                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                                        <option value="">Select Size</option>
                                                                        @for($size = 4; $size <= 12; $size += 0.5)
                                                                            <option value="{{ $size }}" {{ ($item->customizations['ring_size'] ?? '') == $size ? 'selected' : '' }}>
                                                                                {{ $size }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            <!-- Chain Length (for necklaces) -->
                                                            @if(stripos($item->product->name, 'necklace') !== false || stripos($item->product->name, 'chain') !== false || stripos($item->product->category->name, 'necklace') !== false)
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Chain Length
                                                                    </label>
                                                                    <select name="chain_length" 
                                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                                        <option value="">Select Length</option>
                                                                        <option value="16 inches" {{ ($item->customizations['chain_length'] ?? '') == '16 inches' ? 'selected' : '' }}>16 inches</option>
                                                                        <option value="18 inches" {{ ($item->customizations['chain_length'] ?? '') == '18 inches' ? 'selected' : '' }}>18 inches</option>
                                                                        <option value="20 inches" {{ ($item->customizations['chain_length'] ?? '') == '20 inches' ? 'selected' : '' }}>20 inches</option>
                                                                        <option value="22 inches" {{ ($item->customizations['chain_length'] ?? '') == '22 inches' ? 'selected' : '' }}>22 inches</option>
                                                                        <option value="24 inches" {{ ($item->customizations['chain_length'] ?? '') == '24 inches' ? 'selected' : '' }}>24 inches</option>
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            <!-- Special Instructions -->
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Special Instructions (Optional)
                                                                </label>
                                                                <textarea name="instructions" 
                                                                          rows="3"
                                                                          maxlength="200"
                                                                          placeholder="Any special requests or instructions..."
                                                                          class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-emerald-500 focus:border-emerald-500">{{ $item->customizations['instructions'] ?? '' }}</textarea>
                                                                <p class="text-xs text-gray-500 mt-1">Max 200 characters</p>
                                                            </div>

                                                            <!-- Action Buttons -->
                                                            <div class="flex space-x-3">
                                                                <button type="submit" 
                                                                        class="px-4 py-2 bg-[#f59e0b] text-white text-sm font-medium rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                                    Save Customization
                                                                </button>
                                                                <button type="button" 
                                                                        onclick="toggleCustomization({{ $item->id }})"
                                                                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Remove Button - Mobile Optimized -->
                                            <button type="button" 
                                                    onclick="removeItem({{ $item->id }})"
                                                    class="touch-target text-gray-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md p-1 ml-2 sm:ml-4 transition-colors duration-200"
                                                    aria-label="Remove item from cart">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Quantity and Price - Mobile Optimized -->
                                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                                            <div class="flex items-center space-x-3">
                                                <label class="text-sm font-medium text-gray-700">Qty:</label>
                                                <div class="quantity-control flex items-center border border-gray-300 rounded-lg overflow-hidden" data-quantity-control>
                                                    <button type="button" 
                                                            onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                            data-quantity-decrease
                                                            class="touch-target w-12 h-12 sm:w-10 sm:h-10 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-inset transition-colors duration-200 active:bg-gray-100"
                                                            aria-label="Decrease quantity">
                                                        <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                    <input type="number" 
                                                           id="quantity-{{ $item->id }}"
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="100"
                                                           class="w-16 sm:w-14 text-center border-0 py-3 sm:py-2 text-lg sm:text-base focus:ring-0 focus:outline-none"
                                                           onchange="updateQuantity({{ $item->id }}, this.value)"
                                                           aria-label="Quantity">
                                                    <button type="button" 
                                                            onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                            data-quantity-increase
                                                            class="touch-target w-12 h-12 sm:w-10 sm:h-10 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-inset transition-colors duration-200 active:bg-gray-100"
                                                            aria-label="Increase quantity">
                                                        <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="text-left sm:text-right">
                                                <p class="text-lg font-semibold text-gray-900">
                                                    PKR {{ number_format(($item->unit_price_pkr * $item->quantity) / 100, 2) }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    PKR {{ number_format($item->unit_price_pkr / 100, 2) }} each
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center text-[#f59e0b] hover:text-emerald-700 font-medium">
                        <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 sticky top-4" data-cart-summary>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 responsive-text-xl">Order Summary</h2>
                    <div data-summary-content>
                    
                    <!-- Coupon Code -->
                    <div class="mb-6">
                        <form id="coupon-form" class="space-y-3">
                            @csrf
                            <div>
                                <label for="coupon-code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Coupon Code
                                </label>
                                <div class="flex">
                                    <input type="text" 
                                           id="coupon-code" 
                                           name="coupon_code"
                                           value="{{ $cart->coupon_code }}"
                                           placeholder="Enter coupon code"
                                           class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    @if($cart->coupon_code)
                                        <button type="button" 
                                                onclick="removeCoupon()"
                                                class="bg-red-600 text-white px-4 py-2 rounded-r-md hover:bg-red-700">
                                            Remove
                                        </button>
                                    @else
                                        <button type="submit" 
                                                class="bg-[#f59e0b] text-white px-4 py-2 rounded-r-md hover:bg-emerald-700">
                                            Apply
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <div id="coupon-message" class="mt-2 text-sm"></div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotal">{{ $totals['formatted']['subtotal'] }}</span>
                        </div>
                        
                        @if($cart->coupon_code && $cart->discount_pkr > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount ({{ $cart->coupon_code }})</span>
                                <span id="discount">-{{ $totals['formatted']['discount'] }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-sm text-gray-500">Calculated at checkout</span>
                        </div>
                        
                        <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-3">
                            <span>Total</span>
                            <span id="total">{{ $totals['formatted']['total'] }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button - Mobile Optimized -->
                    <div class="mt-6">
                        <a href="{{ route('checkout.index') }}" 
                           class="touch-target w-full bg-[#f59e0b] text-white py-4 sm:py-3 px-6 rounded-lg font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-center block text-base sm:text-sm transition-colors duration-200"
                           data-checkout-button>
                            Proceed to Checkout
                        </a>
                    </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-4 text-center">
                        <div class="flex items-center justify-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Secure Checkout
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start adding some items to your cart to continue shopping.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#f59e0b] hover:bg-emerald-700">
                        <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6V5a2 2 0 114 0v1H8zm2 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        Start Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Update item quantity
function updateQuantity(itemId, quantity) {
    if (quantity < 0) return;
    
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: parseInt(quantity) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.item_removed) {
                document.getElementById(`cart-item-${itemId}`).remove();
            } else {
                document.getElementById(`quantity-${itemId}`).value = quantity;
            }
            
            // Update totals
            updateTotals(data.totals);
            
            // Update cart count in header
            updateCartCount(data.cart_count);
            
            // Show success message
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while updating the cart', 'error');
    });
}

// Remove item from cart
function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`cart-item-${itemId}`).remove();
            
            // Update totals
            updateTotals(data.totals);
            
            // Update cart count in header
            updateCartCount(data.cart_count);
            
            // Show success message
            showMessage(data.message, 'success');
            
            // Reload page if cart is empty
            if (data.cart_count === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while removing the item', 'error');
    });
}

// Apply coupon
document.getElementById('coupon-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const couponCode = document.getElementById('coupon-code').value.trim();
    if (!couponCode) {
        showMessage('Please enter a coupon code', 'error');
        return;
    }
    
    fetch('/cart/apply-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon_code: couponCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update totals
            updateTotals(data.totals);
            
            // Update coupon UI
            const form = document.getElementById('coupon-form');
            form.innerHTML = `
                <div>
                    <label for="coupon-code" class="block text-sm font-medium text-gray-700 mb-1">
                        Coupon Code
                    </label>
                    <div class="flex">
                        <input type="text" 
                               id="coupon-code" 
                               name="coupon_code"
                               value="${data.coupon.code}"
                               readonly
                               class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 bg-gray-50">
                        <button type="button" 
                                onclick="removeCoupon()"
                                class="bg-red-600 text-white px-4 py-2 rounded-r-md hover:bg-red-700">
                            Remove
                        </button>
                    </div>
                </div>
            `;
            
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while applying the coupon', 'error');
    });
});

// Remove coupon
function removeCoupon() {
    fetch('/cart/remove-coupon', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update totals
            updateTotals(data.totals);
            
            // Update coupon UI
            const form = document.getElementById('coupon-form');
            form.innerHTML = `
                <div>
                    <label for="coupon-code" class="block text-sm font-medium text-gray-700 mb-1">
                        Coupon Code
                    </label>
                    <div class="flex">
                        <input type="text" 
                               id="coupon-code" 
                               name="coupon_code"
                               placeholder="Enter coupon code"
                               class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit" 
                                class="bg-[#f59e0b] text-white px-4 py-2 rounded-r-md hover:bg-emerald-700">
                            Apply
                        </button>
                    </div>
                </div>
            `;
            
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while removing the coupon', 'error');
    });
}

// Update totals display
function updateTotals(totals) {
    document.getElementById('subtotal').textContent = totals.subtotal;
    document.getElementById('total').textContent = totals.total;
    
    const discountElement = document.getElementById('discount');
    if (discountElement) {
        discountElement.textContent = '-' + totals.discount;
    }
}

// Update cart count in header
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
    });
}

// Show message
function showMessage(message, type) {
    const messageDiv = document.getElementById('coupon-message');
    messageDiv.className = `mt-2 text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
    messageDiv.textContent = message;
    
    setTimeout(() => {
        messageDiv.textContent = '';
    }, 5000);
}

// Toggle customization form
function toggleCustomization(itemId) {
    const form = document.getElementById(`customization-${itemId}`);
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

// Update customization
function updateCustomization(event, itemId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const customizations = {};
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            customizations[key] = value.trim();
        }
    }
    
    fetch(`/cart/update-customization/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ customizations: customizations })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide the form
            toggleCustomization(itemId);
            
            // Reload the page to show updated customizations
            window.location.reload();
        } else {
            showMessage(data.message || 'An error occurred while updating customization', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while updating customization', 'error');
    });
}
</script>
@endsection