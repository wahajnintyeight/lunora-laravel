@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-gray-600 mt-2">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            
            <!-- Breadcrumb -->
            <nav class="mt-4">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('user.profile') }}" class="text-[#f59e0b] hover:text-emerald-700">Account</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ route('user.orders') }}" class="text-[#f59e0b] hover:text-emerald-700">Orders</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-500">#{{ $order->order_number }}</li>
                </ol>
            </nav>
        </div>

        <!-- Order Status Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h2>
            
            <div class="flex items-center justify-between">
                @php
                    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentStatusIndex = array_search($order->status, $statuses);
                    if ($order->status === 'cancelled') {
                        $currentStatusIndex = -1;
                    }
                @endphp
                
                @foreach($statuses as $index => $status)
                    <div class="flex flex-col items-center {{ $index <= $currentStatusIndex ? 'text-[#f59e0b]' : 'text-gray-400' }}">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center {{ $index <= $currentStatusIndex ? 'border-[#f59e0b] bg-[#f59e0b] text-white' : 'border-gray-300' }}">
                            @if($index < $currentStatusIndex)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <span class="text-xs mt-2 text-center">{{ ucfirst($status) }}</span>
                    </div>
                    
                    @if(!$loop->last)
                        <div class="flex-1 h-0.5 mx-4 {{ $index < $currentStatusIndex ? 'bg-[#f59e0b]' : 'bg-gray-300' }}"></div>
                    @endif
                @endforeach
            </div>
            
            @if($order->status === 'cancelled')
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-800 text-sm">This order has been cancelled.</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                
                <div class="space-y-3 border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>PKR {{ number_format($order->subtotal_pkr / 100, 2) }}</span>
                    </div>
                    
                    @if($order->discount_pkr > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif:</span>
                            <span>-PKR {{ number_format($order->discount_pkr / 100, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping:</span>
                        <span>PKR {{ number_format($order->shipping_pkr / 100, 2) }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total:</span>
                    <span>PKR {{ number_format($order->total_pkr / 100, 2) }}</span>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <span class="ml-2 font-medium">{{ $order->email }}</span>
                    </div>
                    
                    @if($order->phone)
                        <div>
                            <span class="text-gray-600">Phone:</span>
                            <span class="ml-2 font-medium">{{ $order->phone }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Order Items ({{ $order->items->sum('quantity') }})</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                @if($item->product->images->count() > 0)
                                    <img src="{{ $item->product->images->first()->image_path }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
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
                                    </div>
                                @endif
                                
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</span>
                                    <span class="text-sm text-gray-600">Unit Price: PKR {{ number_format($item->unit_price_pkr / 100, 2) }}</span>
                                </div>
                            </div>

                            <!-- Item Total -->
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">
                                    PKR {{ number_format(($item->unit_price_pkr * $item->quantity) / 100, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Addresses -->
        @php
            $shippingAddress = $order->addresses->where('type', 'shipping')->first();
            $billingAddress = $order->addresses->where('type', 'billing')->first();
        @endphp

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            @if($shippingAddress)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h2>
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
            @endif

            @if($billingAddress)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h2>
                    <div class="text-gray-600">
                        <p class="font-medium text-gray-900">{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}</p>
                        <p>{{ $billingAddress->address_line_1 }}</p>
                        @if($billingAddress->address_line_2)
                            <p>{{ $billingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}</p>
                        <p>{{ $billingAddress->country }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Notes -->
        @if($order->notes)
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Notes</h2>
                <p class="text-gray-600">{{ $order->notes }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
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
            
            @if(in_array($order->status, ['delivered', 'cancelled']))
                <form method="POST" action="{{ route('user.order.reorder', $order->order_number) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-[#f59e0b] text-white px-6 py-3 rounded-lg font-semibold hover:bg-emerald-700">
                        Reorder Items
                    </button>
                </form>
            @endif
            
            <a href="{{ route('user.order.invoice', $order->order_number) }}" 
               target="_blank"
               class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">
                Download Invoice
            </a>
            
            <button onclick="window.print()" 
                    class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">
                Print Order
            </button>
            
            <a href="{{ route('user.orders') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">
                Back to Orders
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .container {
        max-width: none;
        padding: 0;
    }
}
</style>
@endsection