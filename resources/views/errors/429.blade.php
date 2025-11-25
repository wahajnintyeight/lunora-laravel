@extends('layouts.shop')

@section('title', 'Too Many Requests')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-24 w-24 text-red-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Too Many Requests
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ $message ?? 'You have made too many requests. Please slow down.' }}
            </p>
            @if(isset($retry_after))
                <p class="mt-2 text-sm text-gray-500">
                    Please try again in {{ $retry_after }} seconds.
                </p>
            @endif
        </div>
        
        <div class="mt-8 space-y-4">
            <button onclick="history.back()" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Go Back
            </button>
            
            <a href="{{ route('home') }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Return to Home
            </a>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                If you believe this is an error, please contact our support team.
            </p>
        </div>
    </div>
</div>

@if(isset($retry_after))
<script>
    // Auto-refresh after retry period
    setTimeout(function() {
        window.location.reload();
    }, {{ $retry_after * 1000 }});
</script>
@endif
@endsection