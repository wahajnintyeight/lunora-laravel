@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Coupons
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Manage discount coupons and promotions
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.coupons.create') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Add Coupon
        </a>
    </div>
</div>
<!-- End Page Header -->

<!-- Filters -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700 mb-5">
    <div class="px-6 py-4">
        <form method="GET" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium mb-2 dark:text-white">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Coupon code...">
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium mb-2 dark:text-white">Type</label>
                <select name="type" id="type" class="py-2 px-3 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                    <option value="">All Types</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status</label>
                <select name="status" id="status" class="py-2 px-3 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-x-2">
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>
<!-- End Filters -->

<!-- Coupons Table -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
            Coupons ({{ $coupons->total() }})
        </h2>
    </div>

    @if($coupons->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Code</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Type</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Value</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Usage</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Valid Period</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Status</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($coupons as $coupon)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                        {{ $coupon->code }}
                                    </div>
                                    @if($coupon->description)
                                        <div class="text-sm text-gray-500 dark:text-neutral-400">
                                            {{ Str::limit($coupon->description, 30) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $coupon->type === 'fixed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500' : 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' }}">
                                    {{ $coupon->type === 'fixed' ? 'Fixed' : 'Percentage' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-neutral-200">
                                @if($coupon->type === 'fixed')
                                    PKR {{ number_format($coupon->value / 100, 2) }}
                                @else
                                    {{ $coupon->value }}%
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-neutral-200">
                                {{ $coupon->redemptions_count }}
                                @if($coupon->usage_limit_total)
                                    / {{ $coupon->usage_limit_total }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                <div>{{ $coupon->starts_at->format('M j, Y') }}</div>
                                <div>{{ $coupon->expires_at->format('M j, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                <div class="hs-dropdown relative inline-block">
                                    <button id="hs-table-dropdown-{{ $coupon->id }}" type="button" class="hs-dropdown-toggle py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        Actions
                                        <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </button>

                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-table-dropdown-{{ $coupon->id }}">
                                        <div class="py-2 first:pt-0 last:pb-0">
                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="{{ route('admin.coupons.show', $coupon) }}">
                                                View
                                            </a>
                                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="{{ route('admin.coupons.edit', $coupon) }}">
                                                Edit
                                            </a>
                                        </div>
                                        @if(!$coupon->redemptions_count)
                                            <div class="py-2 first:pt-0 last:pb-0">
                                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-red-500 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
            {{ $coupons->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No coupons found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Get started by creating your first coupon.</p>
            <div class="mt-6">
                <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add Coupon
                </a>
            </div>
        </div>
    @endif
</div>
<!-- End Coupons Table -->
@endsection