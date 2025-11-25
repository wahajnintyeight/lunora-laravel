@extends('admin.layouts.app')

@section('title', 'Coupon Details')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.coupons.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Coupons
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">{{ $coupon->code }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Coupon Details
        </h1>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-11"/>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4Z"/>
            </svg>
            Edit Coupon
        </a>
    </div>
</div>
<!-- End Page Header -->

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Coupon Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Coupon Information
                    </h2>
                    @php
                        $now = now();
                        $status = 'inactive';
                        $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500';
                        
                        if (!$coupon->is_active) {
                            $status = 'inactive';
                            $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500';
                        } elseif ($coupon->expires_at < $now) {
                            $status = 'expired';
                            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500';
                        } elseif ($coupon->starts_at > $now) {
                            $status = 'upcoming';
                            $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500';
                        } else {
                            $status = 'active';
                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500';
                        }
                    @endphp
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $statusClass }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Coupon Code</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-neutral-200">{{ $coupon->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Discount Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $coupon->type === 'fixed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500' : 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' }}">
                                {{ $coupon->type === 'fixed' ? 'Fixed Amount' : 'Percentage' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Discount Value</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-neutral-200">
                            @if($coupon->type === 'fixed')
                                PKR {{ number_format($coupon->value / 100, 2) }}
                            @else
                                {{ $coupon->value }}%
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Minimum Order</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            @if($coupon->minimum_order_amount_pkr)
                                PKR {{ number_format($coupon->minimum_order_amount_pkr / 100, 2) }}
                            @else
                                No minimum
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Valid Period</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $coupon->starts_at->format('M j, Y g:i A') }}
                            <br>
                            <span class="text-gray-500 dark:text-neutral-400">to</span>
                            {{ $coupon->expires_at->format('M j, Y g:i A') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Usage Limits</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            @if($coupon->usage_limit_per_user)
                                {{ $coupon->usage_limit_per_user }} per user
                            @else
                                Unlimited per user
                            @endif
                            <br>
                            @if($coupon->usage_limit_total)
                                {{ $coupon->usage_limit_total }} total
                            @else
                                Unlimited total
                            @endif
                        </dd>
                    </div>
                </div>

                @if($coupon->description)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-neutral-700">
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $coupon->description }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Usage History -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Usage History
                </h2>
            </div>
            @if($coupon->redemptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Customer</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Order</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Discount</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @foreach($coupon->redemptions as $redemption)
                                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($redemption->user)
                                            <div class="flex items-center gap-x-3">
                                                @if($redemption->user->avatar_url)
                                                    <img class="inline-block size-8 rounded-full" src="{{ $redemption->user->avatar_url }}" alt="{{ $redemption->user->name }}">
                                                @else
                                                    <div class="inline-block size-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-medium text-gray-800 leading-none">
                                                            {{ strtoupper(substr($redemption->user->name, 0, 2)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">{{ $redemption->user->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-neutral-400">{{ $redemption->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-neutral-400">Guest User</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($redemption->order)
                                            <a href="{{ route('admin.orders.show', $redemption->order) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                                #{{ $redemption->order->order_number }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-neutral-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-neutral-200">
                                        PKR {{ number_format($redemption->discount_amount_pkr / 100, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                        {{ $redemption->created_at->format('M j, Y g:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No usage yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">This coupon hasn't been used by any customers.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Sidebar -->
    <div class="space-y-6">
        <!-- Usage Statistics -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Statistics
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Redemptions</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">{{ $totalRedemptions }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Discount Given</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">
                        PKR {{ number_format($totalDiscountGiven / 100, 2) }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Unique Users</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">{{ $uniqueUsers }}</dd>
                </div>
                @if($coupon->usage_limit_total)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Remaining Uses</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">
                            {{ max(0, $coupon->usage_limit_total - $totalRedemptions) }}
                        </dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Quick Actions
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-11"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
                    Edit Coupon
                </a>
                
                @if(!$totalRedemptions)
                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-600 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:bg-neutral-800 dark:border-red-700 dark:text-red-500 dark:hover:bg-red-900/20 dark:focus:bg-red-900/20">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                            Delete Coupon
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection