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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        @include('components.navbar')

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative mx-4 mt-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mx-4 mt-4"
                role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded relative mx-4 mt-4"
                role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>

    @stack('scripts')

    <!-- Full Screen Search Overlay -->
    <div x-data="{
        open: false,
        query: '',
        openSearch() {
            this.open = true;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.searchInput.focus());
        }
    }" 
    x-on:open-search-modal.window="openSearch()" 
    x-on:keydown.escape.window="open = false"
    x-effect="if (!open) document.body.style.overflow = ''"
    x-show="open" 
    style="display: none;" 
    class="fixed inset-0 z-[9999]" 
    role="dialog" 
    aria-modal="true">

        <!-- Backdrop with Blur (transparent with blur effect) -->
        <div x-show="open" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-lg transition-opacity"
            @click="open = false"></div>

        <!-- Search Container (centered vertically and horizontally) -->
        <div class="fixed inset-0 flex items-start justify-center overflow-y-auto px-4 py-6 sm:px-6 sm:py-12">
            <div x-show="open" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" 
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" 
                x-transition:leave-end="opacity-0 scale-95"
                @click.away="open = false"
                class="relative w-full max-w-2xl transform divide-y divide-gray-200 overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transition-all">

                <!-- Search Input Form -->
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <div class="relative flex items-center">
                        <!-- Search Icon (Left) -->
                        <div class="absolute left-0 flex items-center justify-center pl-5 pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400 transition-colors duration-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        
                        <!-- Search Input -->
                        <input x-ref="searchInput" 
                               x-model="query" 
                               type="text" 
                               name="q"
                               class="h-16 w-full border-0 bg-transparent pl-14 pr-20 text-gray-900 placeholder-gray-400 focus:ring-0 text-base sm:text-lg transition-all duration-200"
                               placeholder="Search for jewelry, rings, necklaces..." 
                               autocomplete="off"
                               @focus="$el.parentElement.querySelector('svg').classList.add('text-gray-600')"
                               @blur="$el.parentElement.querySelector('svg').classList.remove('text-gray-600')">

                        <!-- Action Buttons Container (Right) -->
                        <div class="absolute right-0 flex items-center gap-2 pr-4">
                            <!-- Clear Button (shows when there's text) -->
                            <button type="button" 
                                    x-show="query.length > 0"
                                    @click="query = ''; $refs.searchInput.focus()"
                                    x-transition:enter="ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="ease-in duration-100"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-90"
                                    class="flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200"
                                    aria-label="Clear search">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                            <!-- Close Button -->
                            <button type="button" 
                                    @click="open = false"
                                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-400 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200"
                                    aria-label="Close search">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Results / Suggestions Area -->
                <div x-show="query.length === 0" class="px-4 py-4 max-h-96 overflow-y-auto">
                    <h3 class="mb-3 px-2 text-xs font-semibold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Recommended For You
                    </h3>
                    <ul class="space-y-1">
                        @php
                            // Optimized query to get products with their images
                            $suggestedProducts = \App\Models\Product::query()
                                ->select('id', 'name', 'slug')
                                ->with('images:id,product_id,file_path,is_primary')
                                ->active()
                                ->latest()
                                ->limit(5)
                                ->get();
                        @endphp
                        @foreach ($suggestedProducts as $product)
                            <li class="group">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="flex cursor-pointer select-none items-center rounded-lg p-3 hover:bg-gray-50 active:bg-gray-100 transition-all duration-200 gap-3">
                                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 overflow-hidden ring-1 ring-gray-200 group-hover:ring-gray-300 transition-all">
                                        <img src="{{ $product->featured_image ?? '/images/placeholder.jpg' }}"
                                            alt="{{ $product->name }}"
                                            class="h-full w-full object-cover">
                                    </div>
                                    <span class="flex-auto truncate text-gray-900 font-medium text-sm group-hover:text-gray-700 transition-colors">
                                        {{ $product->name }}
                                    </span>
                                    <svg class="ml-auto h-5 w-5 flex-shrink-0 text-gray-400 opacity-0 group-hover:opacity-100 group-hover:text-gray-600 transition-all duration-200"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Empty State (Optional: Show if no results found) -->
                <div x-show="query.length > 0" class="px-6 py-16 text-center sm:px-14">
                    <div class="flex justify-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">
                        Press <kbd class="mx-1 rounded-md border border-gray-300 bg-white px-2.5 py-1 font-sans text-xs font-semibold text-gray-700 shadow-sm">Enter</kbd> 
                        to search for 
                        <span x-text="query" class="font-semibold text-gray-900"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
