@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Users
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('admin.users.show', $user) }}" class="ml-1 text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">{{ $user->name }}</a>
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
            Edit User
        </h1>
    </div>
</div>
<!-- End Page Header -->

<div class="max-w-2xl">
    <!-- Form -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                User Details
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Update user information and settings
            </p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- User Avatar -->
                <div class="flex items-center gap-x-4">
                    @if($user->avatar_url)
                        <img class="inline-block size-16 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                    @else
                        <div class="inline-block size-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-800 leading-none">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Member since {{ $user->created_at->format('M j, Y') }}</p>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium mb-2 dark:text-white">Role</label>
                    <select name="role" id="role" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            You cannot change your own role
                        </p>
                    @else
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            Admin users have full access to the admin panel
                        </p>
                    @endif
                    @error('role')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active account</label>
                    </div>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="is_active" value="1">
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            You cannot deactivate your own account
                        </p>
                    @else
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            Inactive accounts cannot log in
                        </p>
                    @endif
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
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $user->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                {{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Email Verified</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                {{ $user->email_verified_at ? 'Yes (' . $user->email_verified_at->format('M j, Y') . ')' : 'No' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Google Account</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                {{ $user->google_id ? 'Linked' : 'Not Linked' }}
                            </dd>
                        </div>
                        @if($user->role === 'customer')
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Orders</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $user->orders->count() }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700 flex justify-end gap-x-2">
                <a href="{{ route('admin.users.show', $user) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Cancel
                </a>
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17,21 17,13 7,13 7,21"/>
                        <polyline points="7,3 7,8 15,8"/>
                    </svg>
                    Update User
                </button>
            </div>
        </form>
    </div>

    <!-- Password Reset Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700 mt-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                Reset Password
            </h2>
            <p class="text-sm text-gray-600 dark:text-neutral-400">
                Set a new password for this user
            </p>
        </div>

        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2 dark:text-white">New Password</label>
                    <input type="password" id="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2 dark:text-white">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700 flex justify-end">
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4"/>
                        <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                        <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                        <path d="M12 3v6"/>
                        <path d="M12 15v6"/>
                    </svg>
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection