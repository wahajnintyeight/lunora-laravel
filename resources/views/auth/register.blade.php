@extends('layouts.auth')

@section('title', 'Create Account - ' . config('app.name'))
@section('meta_description', 'Create your Lunora account to access exclusive jewelry collections and personalized shopping experience.')

@section('page_title', 'Create your account')
@section('page_subtitle', 'Join Lunora to discover exquisite jewelry collections')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
            Full name
        </label>
        <div class="relative">
            <input id="name" 
                   name="name" 
                   type="text" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500 @error('name') border-red-300 dark:border-red-600 @enderror"
                   placeholder="Enter your full name">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

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
                   autocomplete="email"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500 @error('email') border-red-300 dark:border-red-600 @enderror"
                   placeholder="Enter your email">
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
            Password
        </label>
        <div class="relative">
            <input id="password" 
                   name="password" 
                   type="password" 
                   required 
                   autocomplete="new-password"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500 @error('password') border-red-300 dark:border-red-600 @enderror"
                   placeholder="Create a password">
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
            Confirm password
        </label>
        <div class="relative">
            <input id="password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   required 
                   autocomplete="new-password"
                   class="form-input block w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-emerald-500 dark:focus:border-emerald-500"
                   placeholder="Confirm your password">
        </div>
    </div>

    <!-- Create Account Button -->
    <div>
        <button type="submit" 
                class="group relative w-full flex justify-center py-3 px-4 min-h-[44px] border border-transparent text-base font-medium rounded-lg text-white bg-[#f59e0b] hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
            Create account
        </button>
    </div>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-neutral-600"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white dark:bg-neutral-800 text-gray-500 dark:text-neutral-400">Or continue with</span>
        </div>
    </div>

    <!-- Google OAuth -->
    <div>
        <a href="{{ route('auth.google') }}" 
           class="w-full inline-flex justify-center items-center py-3 px-4 min-h-[44px] border border-gray-300 dark:border-neutral-600 rounded-lg shadow-sm bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200 touch-target">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span class="truncate">Continue with Google</span>
        </a>
    </div>
</form>
@endsection

@section('additional_links')
<div class="text-center space-y-3">
    <p class="text-sm sm:text-base text-gray-600 dark:text-neutral-400 leading-relaxed">
        Already have an account? 
        <a href="{{ route('login') }}" class="font-medium text-[#f59e0b] hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 touch-target transition-colors duration-200 underline decoration-2 underline-offset-2">
            Sign in here
        </a>
    </p>
    <div class="pt-2 border-t border-gray-200 dark:border-neutral-700">
        <p class="text-xs sm:text-sm text-gray-500 dark:text-neutral-500 leading-relaxed">
            By creating an account, you agree to our 
            <a href="#" class="text-[#f59e0b] hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 underline">Terms of Service</a> 
            and 
            <a href="#" class="text-[#f59e0b] hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 underline">Privacy Policy</a>
        </p>
    </div>
</div>
@endsection