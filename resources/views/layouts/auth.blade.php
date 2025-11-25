<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
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

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Theme Check and Update -->
    <script>
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme')
            === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme')
            === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
    </script>

    <style>
        /* Mobile-first responsive form inputs */
        .form-input {
            min-height: 44px;
            /* Touch-friendly minimum height */
        }

        /* Touch-friendly class for links and buttons */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1rem;
        }

        /* Prevent zoom on iOS when input is focused */
        @media screen and (max-width: 767px) {

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"],
            input[type="number"],
            select,
            textarea {
                font-size: 16px !important;
                /* Prevents iOS zoom */
                -webkit-appearance: none;
                border-radius: 0.5rem;
            }

            /* Enhanced touch targets for mobile */
            button,
            a,
            input[type="button"],
            input[type="submit"],
            .touch-target {
                min-height: 44px;
                min-width: 44px;
                touch-action: manipulation;
                /* Improves touch responsiveness */
            }

            /* Better spacing for mobile */
            .auth-form-spacing {
                padding: 1rem;
            }

            /* Prevent horizontal scroll */
            html,
            body {
                overflow-x: hidden;
                -webkit-overflow-scrolling: touch;
                /* Smooth scrolling on iOS */
            }

            /* Safe area handling for notched devices */
            body {
                padding-left: env(safe-area-inset-left);
                padding-right: env(safe-area-inset-right);
                padding-top: env(safe-area-inset-top);
                padding-bottom: env(safe-area-inset-bottom);
            }

            /* Mobile-specific form improvements */
            .form-input {
                padding: 0.875rem 1rem;
                line-height: 1.5;
                border-width: 1px;
                transition: all 0.2s ease-in-out;
            }

            /* Better mobile focus states */
            .form-input:focus {
                transform: none;
                /* Prevent layout shift */
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            }

            /* Mobile-optimized buttons */
            button[type="submit"],
            .btn-primary {
                padding: 1rem 1.5rem;
                font-size: 1rem;
                font-weight: 600;
                border-radius: 0.75rem;
                transition: all 0.2s ease-in-out;
            }

            /* Improved mobile typography */
            .auth-card h1 {
                font-size: 1.75rem;
                line-height: 1.2;
            }

            .auth-card p {
                font-size: 1rem;
                line-height: 1.5;
            }

            /* Mobile-specific spacing */
            .space-y-6>*+* {
                margin-top: 1.5rem;
            }

            /* Better mobile error states */
            .form-input.error {
                border-color: #ef4444;
                background-color: #fef2f2;
            }
        }

        /* Enhanced focus styles for mobile accessibility */
        @media (max-width: 767px) {
            *:focus {
                outline: 3px solid #10b981;
                outline-offset: 2px;
                border-radius: 0.25rem;
            }

            /* Remove focus outline for mouse users */
            *:focus:not(:focus-visible) {
                outline: none;
            }
        }

        /* Loading animation */
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

        /* Mobile-optimized auth card */
        @media (max-width: 640px) {
            .auth-card {
                margin: 0.5rem;
                border-radius: 1rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .auth-content {
                padding: 1.5rem;
            }

            /* Mobile-specific layout adjustments */
            .min-h-[calc(100vh-200px)] {
                min-height: calc(100vh - 120px);
            }
        }

        /* Better error display on mobile */
        .mobile-error {
            animation: slideInError 0.3s ease-out;
            font-size: 0.875rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        @keyframes slideInError {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tablet-specific optimizations */
        @media (min-width: 768px) and (max-width: 1023px) {
            .auth-card {
                max-width: 28rem;
                margin: 0 auto;
            }

            .form-input {
                font-size: 1rem;
            }

            button[type="submit"] {
                font-size: 1rem;
            }
        }

        /* Desktop optimizations */
        @media (min-width: 1024px) {
            .auth-card {
                max-width: 32rem;
            }
        }

        /* High DPI display optimizations */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .auth-card {
                border-width: 0.5px;
            }
        }

        /* Landscape mobile optimizations */
        @media (max-width: 767px) and (orientation: landscape) {
            .min-h-[calc(100vh-200px)] {
                min-height: calc(100vh - 80px);
            }

            .py-4 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }

        /* Dark mode mobile optimizations */
        @media (max-width: 767px) and (prefers-color-scheme: dark) {
            .form-input {
                background-color: #374151;
                border-color: #4b5563;
                color: #f9fafb;
            }

            .form-input:focus {
                border-color: #10b981;
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            }
        }
    </style>
</head>

<body class="h-full dark:bg-neutral-900">
    <!-- Header -->
    @include('partials.shop.header')

    <!-- Main Content -->
    <main class="relative overflow-hidden min-h-screen">
        <!-- Background Pattern -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-emerald-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900">
        </div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60"
            xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23059669"
            fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="2" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]
            dark:bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60"
            xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff"
            fill-opacity="0.02"%3E%3Ccircle cx="30" cy="30" r="2" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]">
        </div>

        <!-- Content -->
        <div class="relative flex flex-col justify-center py-4 px-4 sm:py-12 sm:px-6 lg:px-8 min-h-[calc(100vh-200px)]">
            <div class="w-full max-w-md mx-auto">
                <!-- Page Title -->
                <div class="text-center mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-gray-900 dark:text-white">
                        @yield('page_title')
                    </h1>
                    <p class="mt-3 text-base sm:text-lg text-gray-600 dark:text-neutral-400 leading-relaxed">
                        @yield('page_subtitle')
                    </p>
                </div>

                <!-- Auth Card -->
                <div
                    class="auth-card bg-white dark:bg-neutral-800 py-6 px-4 shadow-xl shadow-gray-100/50 dark:shadow-neutral-900/50 rounded-2xl border border-gray-100 dark:border-neutral-700 sm:py-8 sm:px-8">
                    <!-- Flash Messages -->
                    @include('partials.auth.flash-messages')

                    <!-- Content -->
                    <div class="auth-content">
                        @yield('content')
                    </div>
                </div>

                <!-- Additional Links -->
                <div class="mt-6 sm:mt-8 text-center px-2">
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

    <!-- Mobile Authentication Enhancements -->
    <script>
        // Debug Preline UI loading (remove in production)
        if (window.location.search.includes('debug=preline')) {
            console.log('Preline UI Debug:', {
                HSStaticMethods: typeof window.HSStaticMethods,
                HSDropdown: typeof window.HSDropdown,
                HSCollapse: typeof window.HSCollapse
            });
        }

        // Mobile-specific enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent zoom on iOS when focusing inputs
            const inputs = document.querySelectorAll(
                'input[type="text"], input[type="email"], input[type="password"], input[type="tel"], input[type="number"], select, textarea'
                );
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (window.innerWidth <= 767) {
                        document.querySelector('meta[name="viewport"]').setAttribute('content',
                            'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
                            );
                    }
                });

                input.addEventListener('blur', function() {
                    if (window.innerWidth <= 767) {
                        document.querySelector('meta[name="viewport"]').setAttribute('content',
                            'width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover'
                            );
                    }
                });
            });

            // Enhanced mobile menu toggle with Preline UI fallback
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuToggle && mobileMenu) {
                // Check if Preline UI is handling this
                let prelineHandled = false;
                
                // Wait a bit to see if Preline UI initializes
                setTimeout(() => {
                    if (typeof window.HSCollapse !== 'undefined') {
                        try {
                            // Try to initialize with Preline UI
                            window.HSCollapse.autoInit();
                            prelineHandled = true;
                            // Preline UI mobile menu initialized successfully
                        } catch (e) {
                            console.warn('Preline UI initialization failed:', e);
                        }
                    }
                    
                    // Fallback manual toggle if Preline UI didn't handle it
                    if (!prelineHandled) {
                        // Using fallback mobile menu toggle
                        mobileMenuToggle.addEventListener('click', function(e) {
                            e.preventDefault();
                            const isOpen = !mobileMenu.classList.contains('hidden');

                            if (isOpen) {
                                mobileMenu.classList.add('hidden');
                                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                                document.body.classList.remove('overflow-hidden');
                            } else {
                                mobileMenu.classList.remove('hidden');
                                mobileMenuToggle.setAttribute('aria-expanded', 'true');
                                document.body.classList.add('overflow-hidden');
                            }
                        });
                    }
                }, 200);
            }

            // Enhanced dropdown handling with fallback
            const dropdownToggles = document.querySelectorAll('.hs-dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                const dropdown = toggle.nextElementSibling;
                if (dropdown && dropdown.classList.contains('hs-dropdown-menu')) {
                    // Add fallback click handler
                    setTimeout(() => {
                        if (typeof window.HSDropdown === 'undefined') {
                            // Using fallback dropdown toggle
                            toggle.addEventListener('click', function(e) {
                                e.preventDefault();
                                const isOpen = !dropdown.classList.contains('hidden');
                                
                                // Close all other dropdowns
                                document.querySelectorAll('.hs-dropdown-menu').forEach(menu => {
                                    if (menu !== dropdown) {
                                        menu.classList.add('hidden');
                                        menu.classList.remove('opacity-100');
                                        menu.classList.add('opacity-0');
                                    }
                                });
                                
                                if (isOpen) {
                                    dropdown.classList.add('hidden');
                                    dropdown.classList.remove('opacity-100');
                                    dropdown.classList.add('opacity-0');
                                    toggle.setAttribute('aria-expanded', 'false');
                                } else {
                                    dropdown.classList.remove('hidden');
                                    dropdown.classList.add('opacity-100');
                                    dropdown.classList.remove('opacity-0');
                                    toggle.setAttribute('aria-expanded', 'true');
                                }
                            });
                            
                            // Close dropdown when clicking outside
                            document.addEventListener('click', function(e) {
                                if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                                    dropdown.classList.add('hidden');
                                    dropdown.classList.remove('opacity-100');
                                    dropdown.classList.add('opacity-0');
                                    toggle.setAttribute('aria-expanded', 'false');
                                }
                            });
                        }
                    }, 200);
                }
            });

            // Handle form submission loading states
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                // Mark form as handled to prevent duplicate handlers from app.js
                form.setAttribute('data-loading-handled', 'true');
                
                form.addEventListener('submit', function(e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.hasAttribute('data-loading')) {
                        submitButton.setAttribute('data-loading', 'true');
                        submitButton.disabled = true;
                        const originalText = submitButton.textContent;
                        submitButton.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        `;

                        // Reset after timeout in case of error
                        setTimeout(() => {
                            if (submitButton.disabled) {
                                submitButton.disabled = false;
                                submitButton.textContent = originalText;
                                submitButton.removeAttribute('data-loading');
                            }
                        }, 10000);
                    }
                }, { once: true });
            });

            // Enhanced touch feedback
            const touchElements = document.querySelectorAll('button, .touch-target, a[href]');
            touchElements.forEach(element => {
                element.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                    this.style.transition = 'transform 0.1s ease';
                });

                element.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 100);
                });

                element.addEventListener('touchcancel', function() {
                    this.style.transform = '';
                });
            });

            // Keyboard navigation improvements
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });

            document.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });

            // Auto-scroll to focused input on mobile
            const allInputs = document.querySelectorAll('input, textarea, select');
            allInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (window.innerWidth <= 767) {
                        setTimeout(() => {
                            this.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }, 300);
                    }
                });
            });
        });
    </script>
</body>

</html>
