<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lunora') }} - @yield('title', 'Premium Jewelry')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">
                            Lunora
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="relative group">
                                <button class="flex items-center text-gray-700 hover:text-gray-900">
                                    Hello, {{ auth()->user()->name }}
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Login</a>
                            <a href="{{ route('register') }}" class="bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>