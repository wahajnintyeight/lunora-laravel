@extends('admin.layouts.app')

@section('title', 'Create Coupon')

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
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">Create</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Create Coupon
        </h1>
    </div>
</div>
<!-- End Page Header -->

<div class="max-w-2xl">
    <!-- Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                Coupon Details
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Create a new discount coupon for your store
            </p>
        </div>

        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Coupon Code -->
                <div>
                    <label for="code" class="block text-sm font-medium mb-2 dark:text-white">Coupon Code</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="e.g., SAVE20" required>
                    <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                        Code will be automatically converted to uppercase
                    </p>
                    @error('code')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium mb-2 dark:text-white">Description</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Brief description of the coupon">
                    @error('description')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Type and Value -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium mb-2 dark:text-white">Discount Type</label>
                        <select name="type" id="type" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required onchange="updateValueLabel()">
                            <option value="">Select type</option>
                            <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="value" class="block text-sm font-medium mb-2 dark:text-white">
                            <span id="value-label">Discount Value</span>
                        </label>
                        <input type="number" id="value" name="value" value="{{ old('value') }}" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                        @error('value')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Minimum Order Amount -->
                <div>
                    <label for="minimum_order_amount_pkr" class="block text-sm font-medium mb-2 dark:text-white">Minimum Order Amount (PKR)</label>
                    <input type="number" id="minimum_order_amount_pkr" name="minimum_order_amount_pkr" value="{{ old('minimum_order_amount_pkr') }}" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="0.00">
                    <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                        Leave empty for no minimum order requirement
                    </p>
                    @error('minimum_order_amount_pkr')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Usage Limits -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="usage_limit_per_user" class="block text-sm font-medium mb-2 dark:text-white">Usage Limit Per User</label>
                        <input type="number" id="usage_limit_per_user" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" min="1" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Unlimited">
                        @error('usage_limit_per_user')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="usage_limit_total" class="block text-sm font-medium mb-2 dark:text-white">Total Usage Limit</label>
                        <input type="number" id="usage_limit_total" name="usage_limit_total" value="{{ old('usage_limit_total') }}" min="1" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Unlimited">
                        @error('usage_limit_total')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Valid Period -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium mb-2 dark:text-white">Start Date</label>
                        <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required>
                        @error('starts_at')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expires_at" class="block text-sm font-medium mb-2 dark:text-white">End Date</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at', now()->addDays(30)->format('Y-m-d\TH:i')) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required>
                        @error('expires_at')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Active Status -->
                <div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active coupon</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                        Inactive coupons cannot be used by customers
                    </p>
                    @error('is_active')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700 flex justify-end gap-x-2">
                <a href="{{ route('admin.coupons.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Cancel
                </a>
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="M12 5v14"/>
                    </svg>
                    Create Coupon
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateValueLabel() {
    const typeSelect = document.getElementById('type');
    const valueLabel = document.getElementById('value-label');
    const valueInput = document.getElementById('value');
    
    if (typeSelect.value === 'fixed') {
        valueLabel.textContent = 'Amount (PKR)';
        valueInput.placeholder = '100.00';
    } else if (typeSelect.value === 'percentage') {
        valueLabel.textContent = 'Percentage (%)';
        valueInput.placeholder = '20';
        valueInput.max = '100';
    } else {
        valueLabel.textContent = 'Discount Value';
        valueInput.placeholder = '';
        valueInput.removeAttribute('max');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateValueLabel();
});
</script>
@endsection