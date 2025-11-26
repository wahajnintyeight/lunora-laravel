@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Orders
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Manage customer orders and fulfillment
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.orders.export', request()->query()) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7,10 12,15 17,10"/>
                <line x1="12" x2="12" y1="15" y2="3"/>
            </svg>
            Export
        </a>
    </div>
</div>
<!-- End Page Header -->

<!-- Status Tabs -->
<div class="border-b border-gray-200 dark:border-neutral-700 mb-5">
    <nav class="-mb-px flex space-x-8">
        <a href="{{ route('admin.orders.index') }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} dark:text-neutral-400 dark:hover:text-neutral-300">
            All Orders
            <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ !request('status') ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' }} dark:bg-neutral-700 dark:text-neutral-300">
                {{ $statusCounts['all'] }}
            </span>
        </a>
        
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} dark:text-neutral-400 dark:hover:text-neutral-300">
            Pending
            <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ request('status') === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-900' }} dark:bg-neutral-700 dark:text-neutral-300">
                {{ $statusCounts['pending'] }}
            </span>
        </a>
        
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'processing' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} dark:text-neutral-400 dark:hover:text-neutral-300">
            Processing
            <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ request('status') === 'processing' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' }} dark:bg-neutral-700 dark:text-neutral-300">
                {{ $statusCounts['processing'] }}
            </span>
        </a>
        
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'shipped' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} dark:text-neutral-400 dark:hover:text-neutral-300">
            Shipped
            <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ request('status') === 'shipped' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-900' }} dark:bg-neutral-700 dark:text-neutral-300">
                {{ $statusCounts['shipped'] }}
            </span>
        </a>
        
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="py-2 px-1 border-b-2 font-medium text-sm {{ request('status') === 'delivered' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} dark:text-neutral-400 dark:hover:text-neutral-300">
            Delivered
            <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ request('status') === 'delivered' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-900' }} dark:bg-neutral-700 dark:text-neutral-300">
                {{ $statusCounts['delivered'] }}
            </span>
        </a>
    </nav>
</div>
<!-- End Status Tabs -->

<!-- Filters -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700 mb-5">
    <div class="px-6 py-4">
        <form method="GET" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <input type="hidden" name="status" value="{{ request('status') }}">
            
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium mb-2 dark:text-white">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Order number, email...">
            </div>

            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium mb-2 dark:text-white">Date From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
            </div>

            <!-- Date To -->
            <div>
                <label for="date_to" class="block text-sm font-medium mb-2 dark:text-white">Date To</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-x-2">
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>
<!-- End Filters -->

<!-- Orders Table -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
            Orders ({{ $orders->total() }})
        </h2>
    </div>

    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Actions</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Order</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Customer</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Items</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Status</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Total</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:text-blue-500">
                                    View
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                        #{{ $order->order_number }}
                                    </div>
                                    @if($order->coupon_code)
                                        <div class="text-sm text-gray-500 dark:text-neutral-400">
                                            Coupon: {{ $order->coupon_code }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                        {{ $order->user ? $order->user->name : 'Guest' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-neutral-400">
                                        {{ $order->email }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-neutral-200">
                                {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium 
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-200">
                                PKR {{ number_format($order->total_pkr / 100, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                {{ $order->created_at->format('M j, Y') }}
                                <div class="text-xs">
                                    {{ $order->created_at->format('g:i A') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No orders found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
                @if(request('status') || request('search') || request('date_from') || request('date_to'))
                    Try adjusting your filters to see more results.
                @else
                    Orders will appear here once customers start placing them.
                @endif
            </p>
        </div>
    @endif
</div>
<!-- End Orders Table -->
@endsection