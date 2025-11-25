@extends('layouts.shop')

@section('title', 'Order History - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Order History</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">View and manage your orders</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                @include('partials.user.navigation')
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
                    <form method="GET" action="{{ route('user.orders') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="sm:col-span-2 lg:col-span-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by order number..."
                                       class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-gold-500 focus:border-gold-500">
                            </div>
                            
                            @if($statuses->count() > 0)
                            <div>
                                <select name="status" class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-gold-500 focus:border-gold-500">
                                    <option value="">All Statuses</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            <div>
                                <select name="sort" class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-gold-500 focus:border-gold-500">
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date</option>
                                    <option value="order_number" {{ request('sort') == 'order_number' ? 'selected' : '' }}>Order Number</option>
                                    <option value="total_pkr" {{ request('sort') == 'total_pkr' ? 'selected' : '' }}>Total Amount</option>
                                </select>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-[#450a0a] text-white px-4 py-3 min-h-[44px] rounded-md hover:bg-[#450a0a] transition-colors touch-target">
                                    Filter
                                </button>
                                <a href="{{ route('user.orders') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-3 min-h-[44px] rounded-md hover:bg-gray-300 transition-colors touch-target text-center flex items-center justify-center">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Orders List -->
                @if($orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <!-- Order Header -->
                                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    Order #{{ $order->order_number }}
                                                </h3>
                                                <p class="text-sm text-gray-600">
                                                    Placed on {{ $order->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'processing' => 'bg-blue-100 text-blue-800',
                                                        'shipped' => 'bg-purple-100 text-purple-800',
                                                        'delivered' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 md:mt-0 flex items-center space-x-4">
                                            <div class="text-right">
                                                <p class="text-lg font-semibold text-gray-900">
                                                    PKR {{ number_format($order->total_pkr / 100, 2) }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    {{ $order->items->sum('quantity') }} items
                                                </p>
                                            </div>
                                            <a href="{{ route('user.order-detail', $order->order_number) }}" 
                                               class="bg-[#450a0a] text-white px-4 py-2 rounded-md hover:bg-[#450a0a] text-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items Preview -->
                                <div class="px-6 py-4">
                                    <div class="flex items-center space-x-4 overflow-x-auto">
                                        @foreach($order->items->take(4) as $item)
                                            <div class="flex-shrink-0 flex items-center space-x-2">
                                                @if($item->product->images->count() > 0)
                                                    <img src="{{ $item->product->images->first()->image_path }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="w-12 h-12 object-cover rounded">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No Image</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 truncate max-w-32">
                                                        {{ $item->product->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($order->items->count() > 4)
                                            <div class="flex-shrink-0 text-sm text-gray-600">
                                                +{{ $order->items->count() - 4 }} more items
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('user.order-detail', $order->order_number) }}" 
                                           class="text-[#450a0a] hover:text-[#450a0a] text-sm font-medium">
                                            View Details
                                        </a>
                                        
                                        @if($order->status === 'pending')
                                            <form method="POST" action="{{ route('user.order.cancel', $order->order_number) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to cancel this order?')"
                                                        class="text-red-600 hover:text-red-700 text-sm font-medium">
                                                    Cancel Order
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(in_array($order->status, ['delivered', 'cancelled']))
                                            <form method="POST" action="{{ route('user.order.reorder', $order->order_number) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                    Reorder
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('user.order.invoice', $order->order_number) }}" 
                                           target="_blank"
                                           class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                                            Download Invoice
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request()->hasAny(['search', 'status']))
                                Try adjusting your search criteria or 
                                <a href="{{ route('user.orders') }}" class="text-[#450a0a] hover:text-[#450a0a]">clear filters</a>.
                            @else
                                You haven't placed any orders yet.
                            @endif
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#450a0a] hover:bg-[#450a0a]">
                                <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6V5a2 2 0 114 0v1H8zm2 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                Start Shopping
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection