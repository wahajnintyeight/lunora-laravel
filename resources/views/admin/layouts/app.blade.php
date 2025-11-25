<!DOCTYPE html>
<html lang="en" class="relative min-h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lunora Admin Panel - Jewelry E-commerce Management">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Lunora Admin</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
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

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-neutral-900">
    <!-- Header -->
    @include('admin.partials.header')

    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="lg:ms-[260px] pt-[60px]">
        <div class="p-2 sm:p-5 sm:py-0 md:pt-5 space-y-5">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // Theme handling
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
    </script>

    <!-- Preline UI Initialization -->
    <script>
        // Initialize Preline UI components after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
            
            // Force re-initialization for dropdowns and other components
            setTimeout(() => {
                if (typeof window.HSDropdown !== 'undefined') {
                    window.HSDropdown.autoInit();
                }
                if (typeof window.HSCollapse !== 'undefined') {
                    window.HSCollapse.autoInit();
                }
                if (typeof window.HSTabs !== 'undefined') {
                    window.HSTabs.autoInit();
                }
                if (typeof window.HSAccordion !== 'undefined') {
                    window.HSAccordion.autoInit();
                }
            }, 100);
        });

        // Re-initialize on window load for any late-loading elements
        window.addEventListener('load', () => {
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
        });

        // Helper function to reinitialize Preline components (useful for dynamic content)
        window.initializePrelineComponents = function() {
            if (typeof window.HSStaticMethods !== 'undefined') {
                window.HSStaticMethods.autoInit();
            }
        };
    </script>

    @stack('scripts')
</body>

</html>
