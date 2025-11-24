@extends('layouts.app')

@section('title', 'Premium Jewelry Collection')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Welcome to Lunora
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Your premier destination for exquisite jewelry
        </p>
        
        @guest
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="bg-gray-900 text-white px-6 py-3 rounded-md text-lg font-medium hover:bg-gray-800">
                    Get Started
                </a>
                <a href="{{ route('login') }}" class="text-gray-900 border border-gray-300 px-6 py-3 rounded-md text-lg font-medium hover:bg-gray-50">
                    Sign In
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 max-w-md mx-auto">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-600">You are successfully logged in to your Lunora account.</p>
                
                @if (!auth()->user()->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-yellow-800 text-sm">
                            Please verify your email address to access all features.
                            <a href="{{ route('verification.notice') }}" class="underline font-medium">
                                Verify now
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        @endguest
    </div>
</div>
@endsection