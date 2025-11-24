<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-h-full">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta_description', 'Lunora - Premium Jewelry eCommerce Platform')">
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
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') ===
            'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);

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
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.shop.footer')

    <!-- Modals and Overlays -->
    @include('partials.shop.modals')

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
