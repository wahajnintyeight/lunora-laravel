@extends('layouts.auth')

@section('title', 'Reset Password - ' . config('app.name'))
@section('meta_description', 'Create a new password for your Lunora account.')

@section('page_title', 'Reset your password')
@section('page_subtitle', 'Enter your new password below')

@section('content')
<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $token }}">

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
            Email address
        </label>
        <div class="relative">
            <input id="email" 
                   name="email" 
                   type="email" 
                   value="{{ $email ?? old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="email"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500 @error('email') border-red-300 dark:border-red-600 @enderror"
                   placeholder="Enter your email address">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M3 4a2 2 0 012-2h10a2 2 0 012 2v1.586l-4.293 4.293a1 1 0 01-1.414 0L7 5.586V4z" />
                    <path d="M3 8.414V16a2 2 0 002 2h10a2 2 0 002-2V8.414l-4.293 4.293a3 3 0 01-4.242 0L3 8.414z" />
                </svg>
            </div>
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
            New password
        </label>
        <div class="relative">
            <input id="password" 
                   name="password" 
                   type="password" 
                   required 
                   autocomplete="new-password"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500 @error('password') border-red-300 dark:border-red-600 @enderror"
                   placeholder="Enter your new password">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">
            Must be at least 8 characters long
        </p>
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
            Confirm new password
        </label>
        <div class="relative">
            <input id="password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   required 
                   autocomplete="new-password"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                   placeholder="Confirm your new password">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Reset Password Button -->
    <div>
        <button type="submit" 
                class="group relative w-full flex justify-center py-3 px-4 min-h-[44px] border border-transparent text-base font-medium rounded-lg text-white bg-[#f59e0b] hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-emerald-500 group-hover:text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </span>
            Reset password
        </button>
    </div>
</form>
@endsection

@section('additional_links')
<div class="text-center space-y-3">
    <p class="text-sm sm:text-base text-gray-600 dark:text-neutral-400 leading-relaxed">
        Remember your password? 
        <a href="{{ route('login') }}" class="font-medium text-[#f59e0b] hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 touch-target transition-colors duration-200 underline decoration-2 underline-offset-2">
            Back to sign in
        </a>
    </p>
    <div class="pt-2 border-t border-gray-200 dark:border-neutral-700">
        <p class="text-xs sm:text-sm text-gray-500 dark:text-neutral-500 leading-relaxed">
            Need help? Contact our 
            <a href="#" class="text-[#f59e0b] hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 underline">support team</a>
        </p>
    </div>
</div>
@endsection