@extends('layouts.shop')

@section('title', 'Thank You - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Thank You for Your Order!</h1>
            <p class="text-lg text-gray-600">Your order has been successfully placed. We'll send you a confirmation email shortly.</p>
        </div>

        <!-- Account Creation Suggestion -->
        @guest
            <div class="mb-8 bg-emerald-50 border border-emerald-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-emerald-900 mb-2">Create an Account to Track Your Order</h3>
                        <p class="text-emerald-800 mb-4">
                            Create an account to easily track your order status, view order history, and enjoy faster checkout for future purchases.
                        </p>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#f59e0b] text-white font-semibold rounded-lg hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Create Account
                        </a>
                    </div>
                </div>
            </div>
        @endguest

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-4 lg:space-y-6 order-2 lg:order-1">
                <!-- Order Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Order Number</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-600 mt-1">Keep this number for your records</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Order Date</h3>
                            <p class="text-gray-900">{{ $order->placed_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Email</h3>
                            <p class="text-gray-900">{{ $order->email }}</p>
                            <p class="text-sm text-gray-600 mt-1">Confirmation will be sent here</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Total Amount</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->getFormattedTotalAttribute() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-start space-x-4 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                                <div class="flex-shrink-0">
                                    @if($item->product->images->count() > 0)
                                        <img src="{{ $item->product->images->first()->image_path }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
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
                                        </div>
                                    @endif
                                    
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</span>
                                        <span class="text-lg font-semibold text-gray-900">
                                            PKR {{ number_format(($item->unit_price_pkr * $item->quantity) / 100, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                @php
                    $shippingAddress = $order->addresses->where('type', 'shipping')->first();
                    $billingAddress = $order->addresses->where('type', 'billing')->first();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Address</h2>
                        
                        @if($shippingAddress)
                            <div class="text-gray-700">
                                <p class="font-medium">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</p>
                                <p>{{ $shippingAddress->address_line_1 }}</p>
                                @if($shippingAddress->address_line_2)
                                    <p>{{ $shippingAddress->address_line_2 }}</p>
                                @endif
                                <p>{{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}</p>
                                <p>{{ $shippingAddress->country }}</p>
                                @if($shippingAddress->phone)
                                    <p class="mt-2">Phone: {{ $shippingAddress->phone }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Billing Address</h2>
                        
                        @if($billingAddress)
                            <div class="text-gray-700">
                                <p class="font-medium">{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}</p>
                                <p>{{ $billingAddress->address_line_1 }}</p>
                                @if($billingAddress->address_line_2)
                                    <p>{{ $billingAddress->address_line_2 }}</p>
                                @endif
                                <p>{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}</p>
                                <p>{{ $billingAddress->country }}</p>
                            </div>
                        @else
                            <p class="text-gray-600">Same as shipping address</p>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Notes</h2>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 lg:sticky lg:top-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>PKR {{ number_format($order->subtotal_pkr / 100, 2) }}</span>
                        </div>
                        
                        @if($order->discount_pkr > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span>
                                <span>-PKR {{ number_format($order->discount_pkr / 100, 2) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>
                                @if($order->shipping_pkr > 0)
                                    PKR {{ number_format($order->shipping_pkr / 100, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-3">
                            <span>Total</span>
                            <span>{{ $order->getFormattedTotalAttribute() }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('products.index') }}" 
                           class="w-full bg-[#f59e0b] text-white py-3 px-6 rounded-lg font-semibold hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-center block">
                            Continue Shopping
                        </a>
                        
                        @guest
                            <a href="{{ route('register') }}" 
                               class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center block">
                                Create Account
                            </a>
                        @endguest
                    </div>

                    <!-- Contact Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-2">
                            If you have any questions about your order, please contact us:
                        </p>
                        <div class="text-sm text-gray-600">
                            <p>Email: support@lunora.com</p>
                            <p>Phone: +92 300 1234567</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Reference your order number: {{ $order->order_number }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">What happens next?</h3>
            <div class="text-blue-800 space-y-2">
                <p>• You'll receive an order confirmation email at {{ $order->email }}</p>
                <p>• We'll prepare your jewelry items with care</p>
                <p>• You'll get a shipping notification when your order is dispatched</p>
                <p>• Your order will be delivered within 3-7 business days</p>
                @if($order->items->where('customizations', '!=', null)->count() > 0)
                    <p>• Custom items may require additional processing time (5-10 business days)</p>
                @endif
            </div>
        </div>

        <!-- Order Tracking -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Track Your Order</h3>
            <p class="text-gray-700 mb-4">
                You can track your order status using your order number and email address.
            </p>
            <a href="{{ route('user.track-order') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                Track Order
            </a>
        </div>
    </div>
</div>
@endsection