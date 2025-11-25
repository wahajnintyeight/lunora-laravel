@extends('layouts.app')

@section('title', 'My Addresses - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
            <p class="text-gray-600 mt-2">Manage your saved addresses</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <nav class="bg-white rounded-lg shadow-md p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('user.profile') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-[#f59e0b] hover:bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.orders') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-[#f59e0b] hover:bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Order History
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.addresses') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-[#f59e0b] bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Addresses
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.settings') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-[#f59e0b] hover:bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                </svg>
                                Settings
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                @if($addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($addresses as $address)
                            <div class="bg-white rounded-lg shadow-md p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ ucfirst($address->type) }} Address
                                        </h3>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $address->type === 'shipping' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($address->type) }}
                                    </span>
                                </div>
                                
                                <div class="text-gray-600 space-y-1">
                                    <p class="font-medium text-gray-900">{{ $address->first_name }} {{ $address->last_name }}</p>
                                    <p>{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p>{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-xs text-gray-500">
                                        Used in recent orders
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No addresses saved</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Your addresses from previous orders will appear here.
                        </p>
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
                @endif

                <!-- Information Card -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">About Your Addresses</h3>
                    <div class="text-blue-800 space-y-2">
                        <p>• Addresses are automatically saved when you place orders</p>
                        <p>• We use these addresses to speed up your checkout process</p>
                        <p>• Your address information is kept secure and private</p>
                        <p>• You can use saved addresses for faster checkout on future orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection