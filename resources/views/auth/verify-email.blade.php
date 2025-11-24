@extends('layouts.auth')

@section('title', 'Verify Email - ' . config('app.name'))
@section('meta_description', 'Verify your email address to complete your Lunora account setup.')

@section('page_title', 'Verify your email')
@section('page_subtitle', 'We\'ve sent a verification link to your email address')

@section('content')
<div class="text-center space-y-6">
    <!-- Email Icon -->
    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 dark:bg-emerald-900/20">
        <svg class="h-8 w-8 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>
    </div>

    <!-- Message -->
    <div class="space-y-3">
        <p class="text-sm sm:text-base text-gray-600 dark:text-neutral-400">
            Before continuing, please check your email for a verification link. If you didn't receive the email, we can send you another one.
        </p>
    </div>

    <!-- Resend Verification Email -->
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" 
                class="w-full flex justify-center py-3 px-4 min-h-[44px] border border-transparent text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 touch-target">
            Resend verification email
        </button>
    </form>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-neutral-600"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white dark:bg-neutral-800 text-gray-500 dark:text-neutral-400">Or</span>
        </div>
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="w-full flex justify-center py-3 px-4 min-h-[44px] border border-gray-300 dark:border-neutral-600 text-base font-medium rounded-lg text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200 touch-target">
            Sign out
        </button>
    </form>
</div>
@endsection

@section('additional_links')
<div class="text-center">
    <p class="text-sm sm:text-base text-gray-600 dark:text-neutral-400">
        Need help? 
        <a href="#" class="font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 touch-target">
            Contact support
        </a>
    </p>
</div>
@endsection