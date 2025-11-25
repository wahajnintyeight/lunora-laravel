@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-neutral-400 dark:hover:text-white">
                        Orders
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-neutral-400">#{{ $order->order_number }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Order #{{ $order->order_number }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        @if($order->canBeCancelled())
            <button type="button" onclick="openCancelModal()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-800 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m15 9-6 6"/>
                    <path d="m9 9 6 6"/>
                </svg>
                Cancel Order
            </button>
        @endif

        @if($order->canBeRefunded())
            <button type="button" onclick="openRefundModal()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-orange-200 bg-white text-orange-800 shadow-sm hover:bg-orange-50 focus:outline-none focus:bg-orange-50">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18l-2 13H5L3 6z"/>
                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Process Refund
            </button>
        @endif
    </div>
</div>
<!-- End Page Header -->
<div c
lass="grid lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Status -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Order Status
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center gap-x-1.5 py-2 px-4 rounded-full text-sm font-medium 
                        @switch($order->status)
                            @case('pending')
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500
                                @break
                            @case('processing')
                                bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500
                                @break
                            @case('shipped')
                                bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-500
                                @break
                            @case('delivered')
                                bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500
                                @break
                            @case('cancelled')
                                bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500
                                @break
                            @default
                                bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500
                        @endswitch
                    ">
                        {{ ucfirst($order->status) }}
                    </span>
                    
                    @if(!in_array($order->status, ['cancelled', 'delivered']))
                        <button type="button" onclick="openStatusModal()" class="py-1.5 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Update Status
                        </button>
                    @endif
                </div>

                @if($order->notes)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg dark:bg-neutral-700">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-neutral-200 mb-2">Notes:</h4>
                        <p class="text-sm text-gray-600 dark:text-neutral-400">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>  
      <!-- Order Items -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Order Items ({{ $order->items->count() }})
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg dark:border-neutral-700">
                            @if($item->product->featured_image)
                                <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center dark:bg-neutral-700">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                    {{ $item->product->name }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-neutral-400">
                                    SKU: {{ $item->product_sku }}
                                </p>
                                
                                @if($item->variant && $item->variant->options_json)
                                    <div class="mt-1">
                                        @foreach(json_decode($item->variant->options_json, true) as $option => $value)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300 mr-1">
                                                {{ $option }}: {{ $value }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($item->customizations)
                                    <div class="mt-2 p-2 bg-blue-50 rounded-lg dark:bg-blue-900/20">
                                        <h5 class="text-xs font-medium text-blue-900 dark:text-blue-300 mb-1">Customizations:</h5>
                                        @foreach(json_decode($item->customizations, true) as $key => $value)
                                            <p class="text-xs text-blue-800 dark:text-blue-400">{{ $key }}: {{ $value }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                    PKR {{ number_format($item->unit_price_pkr / 100, 2) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-neutral-400">
                                    Qty: {{ $item->quantity }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 dark:text-neutral-200 mt-1">
                                    Total: PKR {{ number_format($item->total_price_pkr / 100, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>       
 <!-- Shipping Addresses -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Addresses
                </h2>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($order->addresses as $address)
                        <div class="border border-gray-200 rounded-lg p-4 dark:border-neutral-700">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-neutral-200 mb-2">
                                {{ ucfirst($address->type) }} Address
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-neutral-400 space-y-1">
                                <p class="font-medium text-gray-900 dark:text-neutral-200">{{ $address->full_name }}</p>
                                @if($address->company)
                                    <p>{{ $address->company }}</p>
                                @endif
                                <p>{{ $address->address_line_1 }}</p>
                                @if($address->address_line_2)
                                    <p>{{ $address->address_line_2 }}</p>
                                @endif
                                <p>{{ $address->city }}, {{ $address->state_province }} {{ $address->postal_code }}</p>
                                <p>{{ $address->country }}</p>
                                @if($address->phone)
                                    <p>Phone: {{ $address->phone }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>    <!
-- Order Summary -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Customer
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                            {{ $order->user ? $order->user->name : 'Guest Customer' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">
                            {{ $order->email }}
                        </p>
                    </div>
                    
                    @if($order->phone)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-neutral-400">Phone:</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                {{ $order->phone }}
                            </p>
                        </div>
                    @endif

                    @if($order->user)
                        <div class="pt-3 border-t border-gray-200 dark:border-neutral-700">
                            <a href="{{ route('admin.customers.show', $order->user) }}" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                View Customer Profile →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div> 
       <!-- Order Summary -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Order Summary
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-neutral-400">Subtotal:</span>
                        <span class="font-medium text-gray-900 dark:text-neutral-200">PKR {{ number_format($order->subtotal_pkr / 100, 2) }}</span>
                    </div>
                    
                    @if($order->discount_pkr > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-neutral-400">
                                Discount
                                @if($order->coupon_code)
                                    ({{ $order->coupon_code }})
                                @endif
                                :
                            </span>
                            <span class="font-medium text-green-600">-PKR {{ number_format($order->discount_pkr / 100, 2) }}</span>
                        </div>
                    @endif
                    
                    @if($order->shipping_pkr > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-neutral-400">Shipping:</span>
                            <span class="font-medium text-gray-900 dark:text-neutral-200">PKR {{ number_format($order->shipping_pkr / 100, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="border-t border-gray-200 dark:border-neutral-700 pt-3">
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900 dark:text-neutral-200">Total:</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-neutral-200">PKR {{ number_format($order->total_pkr / 100, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
     <!-- Order Timeline -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Order Timeline
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-neutral-200">Order Placed</p>
                            <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($order->placed_at && $order->placed_at != $order->created_at)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-neutral-200">Payment Confirmed</p>
                                <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $order->placed_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->updated_at != $order->created_at)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-neutral-200">Last Updated</p>
                                <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $order->updated_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div><!-- St
atus Update Modal -->
<div id="statusModal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Update Order Status
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                        Change the status of order #{{ $order->order_number }}
                    </p>
                </div>

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-5">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status</label>
                            <select id="status" name="status" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium mb-2 dark:text-white">Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" placeholder="Add any notes about this status change...">{{ $order->notes }}</textarea>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end gap-x-2">
                        <button type="button" onclick="closeStatusModal()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Cancel
                        </button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-
- Cancel Order Modal -->
<div id="cancelModal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Cancel Order
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                        Are you sure you want to cancel order #{{ $order->order_number }}?
                    </p>
                </div>

                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="mt-5">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="restore_stock" name="restore_stock" type="checkbox" checked class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            <label for="restore_stock" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Restore stock quantities</label>
                        </div>

                        <div>
                            <label for="cancel_reason" class="block text-sm font-medium mb-2 dark:text-white">Reason (Optional)</label>
                            <textarea id="cancel_reason" name="reason" rows="3" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" placeholder="Reason for cancellation..."></textarea>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end gap-x-2">
                        <button type="button" onclick="closeCancelModal()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Cancel
                        </button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700">
                            Cancel Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!--
 Refund Order Modal -->
<div id="refundModal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Process Refund
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-neutral-400">
                        Process a refund for order #{{ $order->order_number }}
                    </p>
                </div>

                <form action="{{ route('admin.orders.refund', $order) }}" method="POST" class="mt-5">
                    @csrf
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="restore_stock_refund" name="restore_stock" type="checkbox" checked class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            <label for="restore_stock_refund" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Restore stock quantities</label>
                        </div>

                        <div>
                            <label for="refund_reason" class="block text-sm font-medium mb-2 dark:text-white">Reason (Optional)</label>
                            <textarea id="refund_reason" name="reason" rows="3" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" placeholder="Reason for refund..."></textarea>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end gap-x-2">
                        <button type="button" onclick="closeRefundModal()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                            Cancel
                        </button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-orange-600 text-white hover:bg-orange-700 focus:outline-none focus:bg-orange-700">
                            Process Refund
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><sc
ript>
function openStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('hs-overlay-open');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('hs-overlay-open');
}

function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('hs-overlay-open');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('hs-overlay-open');
}

function openRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
    document.getElementById('refundModal').classList.add('hs-overlay-open');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
    document.getElementById('refundModal').classList.remove('hs-overlay-open');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const modals = ['statusModal', 'cancelModal', 'refundModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('hs-overlay-open');
        }
    });
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = ['statusModal', 'cancelModal', 'refundModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('hs-overlay-open');
        });
    }
});
</script>
@endsection