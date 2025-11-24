@extends('layouts.shop')

@section('title', 'My Profile - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Manage your account information and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                @include('partials.user.navigation')
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-6 lg:space-y-8">
                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>
                    
                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm text-gray-600">
                                <strong>Member since:</strong> {{ $user->created_at->format('M d, Y') }}
                            </p>
                            @if($user->email_verified_at)
                                <p class="text-sm text-green-600 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Email verified
                                </p>
                            @else
                                <p class="text-sm text-orange-600 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Email not verified
                                </p>
                            @endif
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    class="bg-emerald-600 text-white px-6 py-3 min-h-[44px] rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors touch-target">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h2>
                    
                    <form method="POST" action="{{ route('user.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Password
                                </label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       required
                                       class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-emerald-500 focus:border-emerald-500 @error('current_password') border-red-500 @enderror">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required
                                       class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    Must be at least 8 characters long
                                </p>
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm New Password
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       class="w-full border border-gray-300 rounded-md px-4 py-3 text-base min-h-[44px] focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    class="bg-emerald-600 text-white px-6 py-3 min-h-[44px] rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors touch-target">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Statistics -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Overview</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-xl sm:text-2xl font-bold text-emerald-600">{{ $user->orders->count() }}</div>
                            <div class="text-sm text-gray-600">Total Orders</div>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-xl sm:text-2xl font-bold text-emerald-600">
                                PKR {{ number_format($user->orders->sum('total_pkr') / 100, 2) }}
                            </div>
                            <div class="text-sm text-gray-600">Total Spent</div>
                        </div>
                        
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-xl sm:text-2xl font-bold text-emerald-600">
                                {{ $user->orders->where('status', 'pending')->count() }}
                            </div>
                            <div class="text-sm text-gray-600">Pending Orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection