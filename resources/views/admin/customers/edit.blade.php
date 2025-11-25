@extends('admin.layouts.app')

@section('title', 'Edit Customer')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.customers.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Customers
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="ml-1 text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">{{ $customer->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Edit Customer
        </h1>
    </div>
</div>
<!-- End Page Header -->

<div class="max-w-2xl">
    <!-- Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                Customer Information
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Update customer details and account status
            </p>
        </div>

        <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Customer Avatar -->
                <div class="flex items-center gap-x-4">
                    @if($customer->avatar_url)
                        <img class="inline-block size-16 rounded-full" src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}">
                    @else
                        <div class="inline-block size-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-800 leading-none">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Customer since {{ $customer->created_at->format('M j, Y') }}</p>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Status -->
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white">Account Status</label>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active account</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                        Inactive accounts cannot log in to the store
                    </p>
                    @error('is_active')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Information (Read-only) -->
                <div class="border-t border-gray-200 dark:border-neutral-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-neutral-200 mb-4">Account Information</h3>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Registration Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $customer->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                {{ $customer->last_login_at ? $customer->last_login_at->format('M j, Y g:i A') : 'Never' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Email Verified</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                {{ $customer->email_verified_at ? 'Yes (' . $customer->email_verified_at->format('M j, Y') . ')' : 'No' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Google Account</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                {{ $customer->google_id ? 'Linked' : 'Not Linked' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Orders</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $customer->orders->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ ucfirst($customer->role) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700 flex justify-end gap-x-2">
                <a href="{{ route('admin.customers.show', $customer) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Cancel
                </a>
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17,21 17,13 7,13 7,21"/>
                        <polyline points="7,3 7,8 15,8"/>
                    </svg>
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection