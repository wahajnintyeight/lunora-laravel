@extends('layouts.shop')

@section('title', 'My Account - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Manage your account and view your order history</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->orders->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Spent</p>
                        <p class="text-2xl font-semibold text-gray-900">PKR {{ number_format(auth()->user()->orders->sum('total_pkr') / 100, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->orders->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->orders->where('status', 'delivered')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('user.orders') }}" 
                   class="flex items-center justify-center px-4 py-3 min-h-[44px] border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors touch-target">
                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">View Orders</span>
                </a>

                <a href="{{ route('user.profile') }}" 
                   class="flex items-center justify-center px-4 py-3 min-h-[44px] border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors touch-target">
                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Edit Profile</span>
                </a>

                <a href="{{ route('user.addresses') }}" 
                   class="flex items-center justify-center px-4 py-3 min-h-[44px] border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors touch-target">
                    <svg class="h-5 w-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Manage Addresses</span>
                </a>

                <a href="{{ route('products.index') }}" 
                   class="flex items-center justify-center px-4 py-3 min-h-[44px] bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors touch-target">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6V5a2 2 0 114 0v1H8zm2 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">Continue Shopping</span>
                </a>
            </div>
        </div>

        <!-- Recent Orders -->
        @if(auth()->user()->orders->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Recent Orders</h2>
                <a href="{{ route('user.orders') }}" 
                   class="text-sm font-medium text-emerald-600 hover:text-emerald-500 touch-target">
                    View all orders →
                </a>
            </div>

            <div class="space-y-4">
                @foreach(auth()->user()->orders->take(3) as $order)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="mb-2 sm:mb-0">
                                <h3 class="text-sm font-medium text-gray-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
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
                                <span class="text-sm font-medium text-gray-900">PKR {{ number_format($order->total_pkr / 100, 2) }}</span>
                                <a href="{{ route('user.order-detail', $order->order_number) }}" 
                                   class="text-sm font-medium text-emerald-600 hover:text-emerald-500 touch-target">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h3>
            <p class="mt-1 text-sm text-gray-500">Start shopping to see your orders here.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-6 py-3 min-h-[44px] border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors touch-target">
                    <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6V5a2 2 0 114 0v1H8zm2 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    Start Shopping
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection