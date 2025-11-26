@extends('layouts.app')

@section('title', 'Invoice #' . $order->order_number . ' - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white">
        <!-- Invoice Header -->
        <div class="border-b border-gray-200 pb-8 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Lunora Jewelry</h1>
                    <p class="text-gray-600 mt-2">Premium Jewelry Collection</p>
                    <div class="mt-4 text-sm text-gray-600">
                        <p>123 Jewelry Street</p>
                        <p>Karachi, Sindh 75000</p>
                        <p>Pakistan</p>
                        <p>Phone: +92 300 1234567</p>
                        <p>Email: info@lunora.com</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-900">INVOICE</h2>
                    <div class="mt-4 text-sm">
                        <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p><strong>Status:</strong> 
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        @php
            $shippingAddress = $order->addresses->where('type', 'shipping')->first();
            $billingAddress = $order->addresses->where('type', 'billing')->first();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Bill To -->
            @if($billingAddress)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Bill To:</h3>
                    <div class="text-gray-600">
                        <p class="font-medium text-gray-900">{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}</p>
                        <p>{{ $billingAddress->address_line_1 }}</p>
                        @if($billingAddress->address_line_2)
                            <p>{{ $billingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}</p>
                        <p>{{ $billingAddress->country }}</p>
                        @if($order->email)
                            <p class="mt-2">Email: {{ $order->email }}</p>
                        @endif
                        @if($order->phone)
                            <p>Phone: {{ $order->phone }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Ship To -->
            @if($shippingAddress)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Ship To:</h3>
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
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKU
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Qty
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit Price
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        @if($item->variant)
                                            <div class="text-sm text-gray-500">
                                                @foreach($item->variant->options_json ?? [] as $optionName => $optionValue)
                                                    {{ $optionName }}: {{ $optionValue }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </div>
                                        @endif
                                        @if($item->customizations)
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if(isset($item->customizations['engraving']) && $item->customizations['engraving'])
                                                    Engraving: {{ $item->customizations['engraving'] }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->product_sku }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                    PKR {{ number_format($item->unit_price_pkr / 100, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">
                                    PKR {{ number_format(($item->unit_price_pkr * $item->quantity) / 100, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="flex justify-end mb-8">
            <div class="w-full max-w-sm">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">PKR {{ number_format($order->subtotal_pkr / 100, 2) }}</span>
                        </div>
                        
                        @if($order->discount_pkr > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif:</span>
                                <span>-PKR {{ number_format($order->discount_pkr / 100, 2) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-medium">PKR {{ number_format($order->shipping_pkr / 100, 2) }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span>PKR {{ number_format($order->total_pkr / 100, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        @if($order->notes)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Notes</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-600">{{ $order->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-8 text-center text-sm text-gray-500">
            <p>Thank you for your business!</p>
            <p class="mt-2">This invoice was generated on {{ now()->format('M d, Y \a\t g:i A') }}</p>
            <p class="mt-4">
                For questions about this invoice, please contact us at 
                <a href="mailto:support@lunora.com" class="text-[#f59e0b] hover:text-emerald-700">support@lunora.com</a>
                or call +92 300 1234567
            </p>
        </div>

        <!-- Print Button (hidden when printing) -->
        <div class="mt-8 text-center no-print">
            <x-button onclick="window.print()" variant="gold" size="md">
                Print Invoice
            </x-button>
            <x-button href="{{ route('user.order-detail', $order->order_number) }}" variant="secondary" size="md" class="ml-4">
                Back to Order
            </x-button>
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
        color: black;
    }
    
    .container {
        max-width: none;
        padding: 0;
        margin: 0;
    }
    
    .bg-gray-50 {
        background-color: #f9f9f9 !important;
    }
    
    .shadow-md {
        box-shadow: none !important;
    }
    
    .rounded-lg {
        border-radius: 0 !important;
    }
    
    table {
        border-collapse: collapse;
    }
    
    th, td {
        border: 1px solid #ddd;
    }
}
</style>
@endsection