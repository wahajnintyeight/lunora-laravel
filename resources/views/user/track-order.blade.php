@extends('layouts.shop')

@section('title', 'Track Order - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Track Your Order</h1>
            <p class="text-gray-600 mt-2">Enter your order number to check the status of your order</p>
        </div>

        <!-- Track Order Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('user.track-order') }}" class="max-w-md mx-auto">
                <div class="flex">
                    <input type="text" 
                           name="order_number" 
                           value="{{ $orderNumber }}"
                           placeholder="Enter order number (e.g., ORD-2024-001)"
                           required
                           class="flex-1 border border-gray-300 rounded-l-md px-4 py-3 focus:ring-gold-500 focus:border-gold-500">
                    <button type="submit" 
                            class="bg-[#f59e0b] text-white px-6 py-3 rounded-r-md hover:bg-[#f59e0b]-700 focus:ring-2 focus:ring-gold-500 focus:ring-offset-2">
                        Track Order
                    </button>
                </div>
            </form>
        </div>

        @if($orderNumber && !$order)
            <!-- Order Not Found -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-red-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-lg font-semibold text-red-900 mb-2">Order Not Found</h3>
                <p class="text-red-700">
                    We couldn't find an order with the number "{{ $orderNumber }}". 
                    Please check the order number and try again.
                </p>
                <div class="mt-4">
                    <p class="text-sm text-red-600">
                        Make sure you're logged into the account that placed the order, or 
                        <a href="{{ route('login') }}" class="font-medium underline">sign in here</a>.
                    </p>
                </div>
            </div>
        @endif

        @if($order)
            <!-- Order Found - Display Tracking Information -->
            <div class="space-y-8">
                <!-- Order Header -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                            <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-purple-100 text-purple-800',
                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Status Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Order Progress</h3>
                    
                    <div class="relative">
                        @php
                            $statuses = [
                                'pending' => 'Order Placed',
                                'confirmed' => 'Order Confirmed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered'
                            ];
                            
                            $statusKeys = array_keys($statuses);
                            $currentStatusIndex = array_search($order->status, $statusKeys);
                            
                            if ($order->status === 'cancelled') {
                                $currentStatusIndex = -1;
                            }
                        @endphp
                        
                        <div class="flex items-center justify-between">
                            @foreach($statuses as $statusKey => $statusLabel)
                                @php
                                    $index = array_search($statusKey, $statusKeys);
                                    $isCompleted = $index <= $currentStatusIndex;
                                    $isCurrent = $index === $currentStatusIndex;
                                @endphp
                                
                                <div class="flex flex-col items-center {{ $isCompleted ? 'text-gold-600' : 'text-gray-400' }}">
                                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center mb-2 
                                        {{ $isCompleted ? 'border-gold-600 bg-[#f59e0b] text-white' : 'border-gray-300' }}
                                        {{ $isCurrent ? 'ring-4 ring-gold-200' : '' }}">
                                        @if($isCompleted && !$isCurrent)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <span class="text-xs text-center font-medium">{{ $statusLabel }}</span>
                                    @if($isCurrent)
                                        <span class="text-xs text-gold-600 mt-1">Current</span>
                                    @endif
                                </div>
                                
                                @if(!$loop->last)
                                    <div class="flex-1 h-0.5 mx-4 {{ $index < $currentStatusIndex ? 'bg-[#f59e0b]' : 'bg-gray-300' }}"></div>
                                @endif
                            @endforeach
                        </div>
                        
                        @if($order->status === 'cancelled')
                            <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-red-800 font-medium">This order has been cancelled.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items Preview -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items ({{ $order->items->sum('quantity') }})</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->items->take(3) as $item)
                            <div class="flex items-center space-x-4">
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
                                    <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    @if($item->variant)
                                        <p class="text-xs text-gray-500">
                                            @foreach($item->variant->options_json ?? [] as $optionName => $optionValue)
                                                {{ $optionName }}: {{ $optionValue }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    PKR {{ number_format(($item->unit_price_pkr * $item->quantity) / 100, 2) }}
                                </div>
                            </div>
                        @endforeach
                        
                        @if($order->items->count() > 3)
                            <div class="text-center pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600">
                                    +{{ $order->items->count() - 3 }} more items
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Order Total:</span>
                            <span class="text-lg font-bold text-gold-600">
                                PKR {{ number_format($order->total_pkr / 100, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                @php
                    $shippingAddress = $order->addresses->where('type', 'shipping')->first();
                @endphp
                
                @if($shippingAddress)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Delivery Address</h4>
                                <div class="text-gray-600">
                                    <p class="font-medium text-gray-900">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</p>
                                    <p>{{ $shippingAddress->address_line_1 }}</p>
                                    @if($shippingAddress->address_line_2)
                                        <p>{{ $shippingAddress->address_line_2 }}</p>
                                    @endif
                                    <p>{{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}</p>
                                    <p>{{ $shippingAddress->country }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Estimated Delivery</h4>
                                <div class="text-gray-600">
                                    @if($order->status === 'delivered')
                                        <p class="text-green-600 font-medium">✓ Delivered</p>
                                    @elseif($order->status === 'shipped')
                                        <p>{{ $order->created_at->addDays(5)->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-500">3-7 business days from ship date</p>
                                    @elseif($order->status === 'cancelled')
                                        <p class="text-red-600">Order cancelled</p>
                                    @else
                                        <p>{{ $order->created_at->addDays(7)->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-500">5-10 business days from order date</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('user.order-detail', $order->order_number) }}" 
                       class="bg-[#f59e0b] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#f59e0b]-700">
                        View Full Order Details
                    </a>
                    
                    @if($order->status === 'pending')
                        <form method="POST" action="{{ route('user.order.cancel', $order->order_number) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to cancel this order?')"
                                    class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('products.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @endif

        @if(!$orderNumber)
            <!-- Help Section -->
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                <div class="space-y-3 text-gray-600">
                    <p>If you can't find your order number, check your email for the order confirmation.</p>
                    <p>Order numbers typically start with "ORD-" followed by the year and order number.</p>
                    <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0 justify-center">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <span>support@lunora.com</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            <span>+92 300 1234567</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection