@extends('layouts.app')

@section('title', 'Premium Jewelry Collection')

@section('content')
    <!-- Hero Section -->
    @include('components.hero-search')

    <!-- Categories Section -->
    @include('components.categories')

    <!-- Welcome Message -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                @guest
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Join the Lunora Family
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Create an account to access exclusive collections and personalized recommendations
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('register') }}" class="bg-gradient-luxury text-white px-8 py-3 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="text-gray-900 border-2 border-gray-300 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-colors">
                            Sign In
                        </a>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-luxury rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-crown text-white text-2xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="text-gray-600 mb-6">You are successfully logged in to your Lunora account.</p>
                            
                            @if (!auth()->user()->hasVerifiedEmail())
                                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Please verify your email address to access all features.
                                        <a href="{{ route('verification.notice') }}" class="underline font-semibold hover:text-yellow-900">
                                            Verify now
                                        </a>
                                    </p>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <a href="{{ route('shop.index') }}" class="bg-gradient-luxury text-white p-4 rounded-lg hover:opacity-90 transition-opacity">
                                    <i class="fas fa-shopping-bag text-2xl mb-2"></i>
                                    <div class="font-semibold">Shop Now</div>
                                </a>
                                <a href="{{ route('user.orders') }}" class="bg-gray-100 text-gray-800 p-4 rounded-lg hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-box text-2xl mb-2"></i>
                                    <div class="font-semibold">My Orders</div>
                                </a>
                                <a href="{{ route('user.profile') }}" class="bg-gray-100 text-gray-800 p-4 rounded-lg hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-user text-2xl mb-2"></i>
                                    <div class="font-semibold">My Profile</div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </section>
@endsection