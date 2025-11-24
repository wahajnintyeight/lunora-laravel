<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-h-full">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta_description', 'Sign in to your Lunora account to access premium jewelry collections')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Social Media Meta Tags -->
    <meta name="twitter:site" content="@lunora">
    <meta name="twitter:creator" content="@lunora">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Premium Jewelry eCommerce Platform')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/og-image.png'))">

    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('meta_title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Premium Jewelry eCommerce Platform')">
    <meta property="og:image" content="@yield('meta_image', asset('images/og-image.png'))">

    <!-- Title -->
    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')

    <!-- Theme Check and Update -->
    <script>
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
    </script>
    
    <style>
        @keyframes typing {
            0% {
                opacity: 1;
                scale: 1;
            }
            50% {
                opacity: 0.75;
                scale: 0.75;
            }
            100% {
                opacity: 1;
                scale: 1;
            }
        }
    </style>
</head>

<body class="dark:bg-neutral-900">
    <!-- Header -->
    @include('partials.shop.header')

    <!-- Main Content -->
    <main class="relative overflow-hidden min-h-screen">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-emerald-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23059669" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] dark:bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        
        <!-- Content -->
        <div class="relative flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 min-h-[calc(100vh-200px)]">
            <div class="w-full max-w-md mx-auto">
                <!-- Page Title -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        @yield('page_title')
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                        @yield('page_subtitle')
                    </p>
                </div>

                <!-- Auth Card -->
                <div class="bg-white dark:bg-neutral-800 py-6 px-4 shadow-xl shadow-gray-100/50 dark:shadow-neutral-900/50 rounded-2xl border border-gray-100 dark:border-neutral-700 sm:py-8 sm:px-8">
                    <!-- Flash Messages -->
                    @include('partials.auth.flash-messages')

                    <!-- Content -->
                    @yield('content')
                </div>

                <!-- Additional Links -->
                <div class="mt-6 text-center">
                    @yield('additional_links')
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.shop.footer')

    <!-- Modals and Overlays -->
    @include('partials.shop.modals')

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>ain>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>