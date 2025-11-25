@extends('layouts.auth')

@section('title', 'Forgot Password - ' . config('app.name'))
@section('meta_description', 'Reset your Lunora account password to regain access to your jewelry collection.')

@section('page_title', 'Forgot your password?')
@section('page_subtitle', 'No worries, we\'ll send you reset instructions')

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
            Email address
        </label>
        <div class="relative">
            <input id="email" 
                   name="email" 
                   type="email" 
                   value="{{ old('email') }}" 
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
        <p class="mt-2 text-sm text-gray-500 dark:text-neutral-400">
            We'll send a password reset link to this email address.
        </p>
    </div>

    <!-- Send Reset Link Button -->
    <div>
        <button type="submit" 
                class="group relative w-full flex justify-center py-3 px-4 min-h-[44px] border border-transparent text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-emerald-500 group-hover:text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M3 4a2 2 0 012-2h10a2 2 0 012 2v1.586l-4.293 4.293a1 1 0 01-1.414 0L7 5.586V4z" />
                    <path d="M3 8.414V16a2 2 0 002 2h10a2 2 0 002-2V8.414l-4.293 4.293a3 3 0 01-4.242 0L3 8.414z" />
                </svg>
            </span>
            Send reset link
        </button>
    </div>
</form>
@endsection

@section('additional_links')
<div class="text-center space-y-4">
    <div class="space-y-3">
        <p class="text-sm sm:text-base text-gray-600 dark:text-neutral-400 leading-relaxed">
            Remember your password? 
            <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 touch-target transition-colors duration-200 underline decoration-2 underline-offset-2">
                Back to sign in
            </a>
        </p>
        <p class="text-sm sm:text-base text-gray-600 dark:text-neutral-400 leading-relaxed">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 touch-target transition-colors duration-200 underline decoration-2 underline-offset-2">
                Create one here
            </a>
        </p>
    </div>
    <div class="pt-3 border-t border-gray-200 dark:border-neutral-700">
        <p class="text-xs sm:text-sm text-gray-500 dark:text-neutral-500 leading-relaxed">
            Having trouble? Contact our 
            <a href="#" class="text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 underline">support team</a>
        </p>
    </div>
</div>
@endsection