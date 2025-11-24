<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-h-full">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta name="description" content="@yield('meta_description', 'Lunora - Premium Jewelry eCommerce Platform')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Web App Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

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
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

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

        /* Mobile-first responsive design improvements */
        @media (max-width: 640px) {
            /* Ensure touch targets are at least 44px */
            button, a, input[type="button"], input[type="submit"] {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Improve text readability on mobile */
            body {
                -webkit-text-size-adjust: 100%;
                text-size-adjust: 100%;
            }
            
            /* Prevent horizontal scroll */
            html, body {
                overflow-x: hidden;
            }
        }

        /* Smooth scrolling for better UX */
        html {
            scroll-behavior: smooth;
        }

        /* Focus styles for accessibility */
        *:focus {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }

        /* Loading state for better perceived performance */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
</head>

<body class="dark:bg-neutral-900 flex flex-col min-h-screen">
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-emerald-600 text-white px-4 py-2 rounded-md z-50">
        Skip to main content
    </a>

    <!-- Header -->
    @include('partials.shop.header')

    <!-- Main Content -->
    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.shop.footer')

    <!-- Modals and Overlays -->
    @include('partials.shop.modals')

    <!-- Scripts -->
    @stack('scripts')

    <!-- Mobile-specific JavaScript -->
    <script>
        // Prevent zoom on input focus for iOS
        document.addEventListener('touchstart', function() {}, true);
        
        // Handle mobile menu interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                const mobileMenu = document.getElementById('mobile-menu');
                const menuToggle = document.querySelector('[data-hs-collapse="#mobile-menu"]');
                
                if (mobileMenu && !mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                    if (!mobileMenu.classList.contains('hidden')) {
                        menuToggle.click();
                    }
                }
            });

            // Handle cart count updates
            window.updateCartCount = function(count) {
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(element => {
                    element.textContent = count;
                    if (count > 0) {
                        element.style.display = 'flex';
                    } else {
                        element.style.display = 'none';
                    }
                });
            };

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
