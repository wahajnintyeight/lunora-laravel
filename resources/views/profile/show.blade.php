@extends('layouts.shop')

@section('title', 'My Profile - ' . config('app.name'))
@section('meta_description', 'Manage your Lunora account settings and view your jewelry collection preferences.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Profile</h1>
        <p class="mt-2 text-gray-600 dark:text-neutral-400">Manage your account settings and preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm border border-gray-100 dark:border-neutral-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Account Information</h2>
                </div>

                <div class="space-y-6">
                    <!-- Profile Picture -->
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            @if(auth()->user()->avatar_url)
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-emerald-100 dark:bg-emerald-900/20 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-[#f59e0b] dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-neutral-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Full Name</label>
                            <div class="px-3 py-2 border border-gray-300 dark:border-neutral-600 rounded-lg bg-gray-50 dark:bg-neutral-700 text-gray-900 dark:text-white">
                                {{ auth()->user()->name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Email Address</label>
                            <div class="px-3 py-2 border border-gray-300 dark:border-neutral-600 rounded-lg bg-gray-50 dark:bg-neutral-700 text-gray-900 dark:text-white">
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Account Type</label>
                            <div class="px-3 py-2 border border-gray-300 dark:border-neutral-600 rounded-lg bg-gray-50 dark:bg-neutral-700">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400' }}">
                                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Customer' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Email Status</label>
                            <div class="px-3 py-2 border border-gray-300 dark:border-neutral-600 rounded-lg bg-gray-50 dark:bg-neutral-700">
                                @if(auth()->user()->hasVerifiedEmail())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Account Actions -->
            <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm border border-gray-100 dark:border-neutral-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if(!auth()->user()->hasVerifiedEmail())
                        <a href="{{ route('verification.notice') }}" class="flex items-center p-3 text-sm font-medium text-yellow-800 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800 dark:hover:bg-yellow-900/30">
                            Verify Email Address
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 text-sm font-medium text-purple-800 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 dark:bg-purple-900/20 dark:text-purple-400 dark:border-purple-800 dark:hover:bg-purple-900/30">
                            Admin Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection