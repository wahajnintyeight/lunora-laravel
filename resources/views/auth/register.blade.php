@extends('layouts.auth')

@section('title', 'Create Account - ' . config('app.name'))
@section('meta_description', 'Create your Lunora account to access exclusive jewelry collections and personalized shopping experience.')

@section('page_title', 'Create your account')
@section('page_subtitle', 'Join Lunora and discover exquisite collections')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf
    @honeypot

    <div>
        <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Full name
        </label>
        <div class="relative">
            <input 
                id="name" 
                name="name" 
                type="text" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder:text-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 dark:bg-maroon-950 dark:border-maroon-900 dark:text-white dark:placeholder:text-neutral-400 dark:focus:ring-gold-500 @error('name') border-red-500 dark:border-red-500 @enderror"
                placeholder="John Doe">
            <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 dark:text-neutral-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Email address
        </label>
        <div class="relative">
            <input 
                id="email" 
                name="email" 
                type="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="email"
                class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder:text-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 dark:bg-maroon-950 dark:border-maroon-900 dark:text-white dark:placeholder:text-neutral-400 dark:focus:ring-gold-500 @error('email') border-red-500 dark:border-red-500 @enderror"
                placeholder="you@example.com">
            <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400 dark:text-neutral-500 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Password
        </label>
        <div class="relative">
            <input 
                id="password" 
                name="password" 
                type="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder:text-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 dark:bg-maroon-950 dark:border-maroon-900 dark:text-white dark:placeholder:text-neutral-400 dark:focus:ring-gold-500 @error('password') border-red-500 dark:border-red-500 @enderror"
                placeholder="••••••••"
                x-data="{ show: false }" 
                :type="show ? 'text' : 'password'">
            <button 
                type="button" 
                @click="show = !show"
                class="absolute right-3 top-3.5 text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300 transition-colors focus:outline-none">
                <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.048l-1.781-1.755zM9 13a1 1 0 112 0 1 1 0 01-2 0zm9.541-5c-1.274-4.057-5.064-7-9.541-7a9.968 9.968 0 00-2.846.434l2.882 2.882a3 3 0 014.24 4.24l2.881 2.881c.317-.59.604-1.202.859-1.834z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
        <p class="mt-1.5 text-xs text-gray-600 dark:text-neutral-400">
            At least 8 characters
        </p>
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Confirm password
        </label>
        <div class="relative">
            <input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder:text-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 dark:bg-maroon-950 dark:border-maroon-900 dark:text-white dark:placeholder:text-neutral-400 dark:focus:ring-gold-500"
                placeholder="••••••••"
                x-data="{ show: false }" 
                :type="show ? 'text' : 'password'">
            <button 
                type="button" 
                @click="show = !show"
                class="absolute right-3 top-3.5 text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300 transition-colors focus:outline-none">
                <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                <svg x-show="show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.048l-1.781-1.755zM9 13a1 1 0 112 0 1 1 0 01-2 0zm9.541-5c-1.274-4.057-5.064-7-9.541-7a9.968 9.968 0 00-2.846.434l2.882 2.882a3 3 0 014.24 4.24l2.881 2.881c.317-.59.604-1.202.859-1.834z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <x-button type="submit" variant="primary" size="md" fullWidth>
        Create account
    </x-button>

    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-neutral-700"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="px-2 bg-white dark:bg-neutral-900 text-sm text-gray-500 dark:text-neutral-400">
                Or continue with
            </span>
        </div>
    </div>

    <x-button href="{{ route('auth.google') }}" variant="secondary" size="md" fullWidth class="border border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Google
    </x-button>
</form>
@endsection

@section('additional_links')
<p class="text-center text-gray-600 dark:text-neutral-400">
    Already have an account? 
    <a href="{{ route('login') }}" class="font-semibold text-maroon-950 hover:text-gold-500 dark:text-gold-500 dark:hover:text-gold-300 transition-colors">
        Sign in
    </a>
</p>
@endsection