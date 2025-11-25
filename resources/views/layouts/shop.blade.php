<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-h-full">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
    <meta name="description" content="@yield('meta_description', 'Lunora - Premium Jewelry eCommerce Platform')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="search-suggestions-endpoint" content="{{ route('products.suggestions') }}">
    <meta name="categories-endpoint" content="{{ route('categories.suggestions') }}">

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

    <!-- Preline UI JavaScript -->
    <script src="{{ asset('vendor/preline/dist/index.js') }}"></script>
    
    <!-- Preline UI CDN Fallback -->
    <script>
        // Check if Preline UI loaded, if not load from CDN
        if (typeof window.HSStaticMethods === 'undefined') {
            const script = document.createElement('script');
            script.src = '//cdn.jsdelivr.net/npm/preline@latest/dist/preline.js';
            script.onload = function() {
                if (typeof window.HSStaticMethods !== 'undefined') {
                    window.HSStaticMethods.autoInit();
                }
            };
            document.head.appendChild(script);
        }
    </script>

    <!-- Preline UI Initialization -->
    <script>
        // Initialize Preline UI components after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Preline UI
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
            
            // Force re-initialization for dropdowns specifically
            setTimeout(() => {
                if (typeof window.HSDropdown !== 'undefined') {
                    window.HSDropdown.autoInit();
                    
                    // Force initialize all dropdown elements
                    document.querySelectorAll('.hs-dropdown').forEach(dropdown => {
                        try {
                            new window.HSDropdown(dropdown);
                        } catch (e) {
                            // Ignore errors for already initialized dropdowns
                        }
                    });
                }
            }, 100);
        });

        // Re-initialize on window load for any late-loading elements
        window.addEventListener('load', () => {
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
            
            // Additional initialization for specific components
            if (typeof window.HSDropdown !== 'undefined') {
                window.HSDropdown.autoInit();
                
                // Force initialize all dropdown elements again
                document.querySelectorAll('.hs-dropdown').forEach(dropdown => {
                    try {
                        new window.HSDropdown(dropdown);
                    } catch (e) {
                        // Ignore errors for already initialized dropdowns
                    }
                });
            }
        });

        // Handle dynamic content initialization
        function initializePrelineComponents() {
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
        }

        // Export for global use
        window.initializePrelineComponents = initializePrelineComponents;
    </script>

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
            
            /* Safe area handling for notched devices */
            body {
                padding-left: env(safe-area-inset-left);
                padding-right: env(safe-area-inset-right);
            }
        }

        /* Smooth scrolling for better UX */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced focus styles for accessibility */
        *:focus {
            outline: 3px solid #10b981;
            outline-offset: 2px;
        }

        /* Better focus for mobile */
        @media (max-width: 767px) {
            *:focus {
                outline-width: 4px;
            }
        }

        /* Loading state for better perceived performance */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        /* Mobile menu overlay */
        #mobile-menu-overlay {
            backdrop-filter: blur(4px);
        }

        /* Prevent body scroll when mobile menu is open */
        body.mobile-menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
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
        // Enhanced mobile support
        document.addEventListener('DOMContentLoaded', function() {
            // Detect mobile device
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile) {
                document.body.classList.add('mobile-device');
            }
            
            // Handle viewport height on mobile (address bar issue)
            function setViewportHeight() {
                const vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }
            
            setViewportHeight();
            window.addEventListener('resize', setViewportHeight);
            window.addEventListener('orientationchange', () => {
                setTimeout(setViewportHeight, 100);
            });
            
            // Prevent zoom on double tap for iOS
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function (event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
            
            // Enhanced performance for mobile
            if (isMobile) {
                // Reduce animations on low-end devices
                const isLowEndDevice = navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2;
                if (isLowEndDevice) {
                    document.body.classList.add('reduce-motion');
                }
                
                // Optimize scroll performance
                let ticking = false;
                function updateScrollPosition() {
                    // Add scroll-based optimizations here
                    ticking = false;
                }
                
                window.addEventListener('scroll', function() {
                    if (!ticking) {
                        requestAnimationFrame(updateScrollPosition);
                        ticking = true;
                    }
                }, { passive: true });
            }
        });
    </script>

    <!-- Mobile Cart and Checkout Optimization -->
    <script src="{{ asset('js/mobile-cart-checkout.js') }}"></script>
</body>

</html>
