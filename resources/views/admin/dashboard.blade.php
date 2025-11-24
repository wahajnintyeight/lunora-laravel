@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Dashboard
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Welcome back, {{ auth()->user()->name }}! Here's what's happening with your store.
        </p>
    </div>
</div>
<!-- End Page Header -->

<!-- Stats Cards -->
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-5">
    <!-- Total Revenue Card -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-x-2">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                    Total Revenue
                </p>
            </div>

            <div class="mt-1 flex items-center gap-x-2">
                <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                    PKR {{ number_format($totalRevenue / 100, 2) }}
                </h3>
            </div>
        </div>
    </div>
    <!-- End Total Revenue Card -->

    <!-- Monthly Revenue Card -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-x-2">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                    Monthly Revenue
                </p>
            </div>

            <div class="mt-1 flex items-center gap-x-2">
                <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                    PKR {{ number_format($monthlyRevenue / 100, 2) }}
                </h3>
                @if($revenueChange != 0)
                    <span class="flex items-center gap-x-1 text-{{ $revenueChange >= 0 ? 'green' : 'red' }}-600">
                        <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            @if($revenueChange >= 0)
                                <polyline points="22,7 13.5,15.5 8.5,10.5 2,17"/>
                                <polyline points="16,7 22,7 22,13"/>
                            @else
                                <polyline points="22,17 13.5,8.5 8.5,13.5 2,7"/>
                                <polyline points="16,17 22,17 22,11"/>
                            @endif
                        </svg>
                        <span class="inline-block text-xs">
                            {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}%
                        </span>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <!-- End Monthly Revenue Card -->

    <!-- Total Orders Card -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-x-2">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                    Total Orders
                </p>
            </div>

            <div class="mt-1 flex items-center gap-x-2">
                <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                    {{ number_format($totalOrders) }}
                </h3>
            </div>
        </div>
    </div>
    <!-- End Total Orders Card -->

    <!-- Low Stock Alert Card -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 md:p-5">
            <div class="flex items-center gap-x-2">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                    Low Stock Items
                </p>
            </div>

            <div class="mt-1 flex items-center gap-x-2">
                <h3 class="text-xl sm:text-2xl font-medium text-{{ $lowStockCount > 0 ? 'red' : 'gray' }}-800 dark:text-neutral-200">
                    {{ number_format($lowStockCount) }}
                </h3>
                @if($lowStockCount > 0)
                    <span class="inline-flex items-center gap-x-1 py-1 px-2 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        Alert
                    </span>
                @endif
            </div>
        </div>
    </div>
    <!-- End Low Stock Alert Card -->
</div>
<!-- End Stats Cards -->

<!-- Charts and Recent Orders -->
<div class="grid lg:grid-cols-2 gap-4 sm:gap-6 mb-5">
    <!-- Recent Orders -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                Recent Orders
            </h2>
        </div>

        <div class="p-1.5">
            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Order</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Customer</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Status</th>
                                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ $order->user ? $order->user->name : $order->email }}
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
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        PKR {{ number_format($order->total_pkr / 100, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No orders yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Get started by creating your first product.</p>
                </div>
            @endif
        </div>
    </div>
    <!-- End Recent Orders -->

    <!-- Quick Actions -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                Quick Actions
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.products.create') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 dark:border-neutral-600 dark:hover:border-neutral-500 transition-colors">
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-500 dark:text-neutral-500 dark:group-hover:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">Add Product</span>
                </a>

                <a href="{{ route('admin.categories.create') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 dark:border-neutral-600 dark:hover:border-neutral-500 transition-colors">
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-500 dark:text-neutral-500 dark:group-hover:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">Add Category</span>
                </a>

                <a href="{{ route('admin.coupons.create') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 dark:border-neutral-600 dark:hover:border-neutral-500 transition-colors">
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-500 dark:text-neutral-500 dark:group-hover:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">Add Coupon</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="group flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 dark:border-neutral-600 dark:hover:border-neutral-500 transition-colors">
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-gray-500 dark:text-neutral-500 dark:group-hover:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">View Orders</span>
                </a>
            </div>
        </div>
    </div>
    <!-- End Quick Actions -->
</div>
<!-- End Charts and Recent Orders -->
@endsection