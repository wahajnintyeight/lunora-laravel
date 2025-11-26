@extends('layouts.app')

@section('title', 'Products - Lunora Jewelry')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#f8fafc] via-white to-[#fffbeb]">
        <!-- Animated Header Section -->
        <div class="relative bg-white/80 backdrop-blur-sm border-b border-[#fef3c7]/50 sticky top-0 z-20">
            <div
                class="absolute inset-0 bg-gradient-to-r from-[#f59e0b]/5 via-transparent to-[#f43f5e]/5 pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 relative">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                    <!-- Header Content -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-8 bg-gradient-to-b from-[#f59e0b] to-[#e11d48] rounded-full"></div>
                            <h1
                                class="text-4xl sm:text-5xl font-bold text-gradient bg-gradient-to-r from-[#0f172a] via-[#881337] to-[#78350f] bg-clip-text text-transparent">
                                @if (request('search'))
                                    Search Results
                                @elseif(request('category'))
                                    {{ ucfirst(request('category')) }}
                                @else
                                    Our Collection
                                @endif
                            </h1>
                        </div>
                        <p class="text-[#475569] text-sm sm:text-base leading-relaxed max-w-lg">
                            @if (request('search'))
                                <span class="text-[#b45309] font-semibold">{{ $products->total() }}</span> results for
                                "<span class="text-[#be123c] font-medium">{{ request('search') }}</span>"
                            @else
                                Discover <span class="text-[#b45309] font-semibold">{{ $products->total() }}</span>
                                exquisite pieces crafted with passion
                            @endif
                        </p>
                    </div>

                    <!-- Mobile Filter Toggle -->
                    <x-button type="button" id="mobile-filter-toggle" variant="primary" size="lg" icon="filter" iconPosition="left" fullWidth class="lg:hidden group">
                        Filters
                    </x-button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Enhanced Filters Sidebar -->
                <aside class="lg:w-80 flex-shrink-0">
                    <div id="filters-panel"
                        class="hidden lg:block fixed lg:static bottom-0 left-0 right-0 lg:bottom-auto lg:left-auto lg:right-auto bg-white/95 backdrop-blur-sm rounded-t-3xl lg:rounded-2xl shadow-2xl lg:shadow-xl border border-[#fef3c7]/50 overflow-hidden lg:overflow-visible max-h-[90vh] lg:max-h-none overflow-y-auto lg:overflow-y-visible">

                        <!-- Filter Header -->
                        <div
                            class="sticky top-0 bg-gradient-to-r from-[#881337] via-[#78350f] to-[#881337] px-6 py-5 flex items-center justify-between lg:rounded-t-xl shadow-md z-10">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2.5">
                                <x-svg-icon name="filter" class="w-5 h-5 text-[#fcd34d]" stroke-width="2" />
                                Refine Search
                            </h2>
                            <x-button type="button" id="mobile-filter-close" variant="ghost" size="sm" icon="close" class="lg:hidden text-white/80 hover:text-white p-1" />
                        </div>

                        <form method="GET" action="{{ route('products.index') }}" id="filter-form"
                            class="p-6 lg:p-7 space-y-7 pb-24 lg:pb-6">
                            <!-- Search -->
                            <div class="space-y-2.5">
                                <label for="search" class="block text-sm font-bold text-[#0f172a] tracking-wide">Search
                                    Products</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center justify-center pl-4 pointer-events-none">
                                        <x-svg-icon name="search" class="h-5 w-5 text-[#d97706]/40 group-focus-within:text-[#d97706] transition-colors" />
                                    </div>
                                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                                        placeholder="Search pieces..."
                                        class="w-full border-2 border-[#e2e8f0] rounded-xl px-4 py-3 pl-12 text-[#0f172a] placeholder-[#94a3b8] focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition-all duration-300 bg-white/50 hover:bg-white focus:bg-white">
                                </div>
                            </div>

                            <!-- Categories -->
                            @if ($categories->count() > 0)
                                <div class="space-y-2.5">
                                    <label for="category"
                                        class="block text-sm font-bold text-[#0f172a] tracking-wide">Category</label>
                                    <select id="category" name="category"
                                        class="w-full border-2 border-[#e2e8f0] rounded-xl px-4 py-3 text-[#0f172a] focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition-all duration-300 bg-white/50 hover:bg-white focus:bg-white appearance-none cursor-pointer font-medium"
                                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23854d0e%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3e%3cpolyline points=%226 9 12 15 18 9%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->slug }}"
                                                {{ request('category') == $category->slug ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ $category->products_count }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Price Range -->
                            @if ($priceRange && $priceRange->min_price && $priceRange->max_price)
                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-[#0f172a] tracking-wide">Price Range
                                        (PKR)</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                                            placeholder="Min"
                                            class="border-2 border-[#e2e8f0] rounded-xl px-4 py-3 text-[#0f172a] placeholder-[#94a3b8] focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition-all duration-300 bg-white/50 hover:bg-white focus:bg-white font-medium">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                                            placeholder="Max"
                                            class="border-2 border-[#e2e8f0] rounded-xl px-4 py-3 text-[#0f172a] placeholder-[#94a3b8] focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition-all duration-300 bg-white/50 hover:bg-white focus:bg-white font-medium">
                                    </div>
                                </div>
                            @endif

                            <!-- Material -->
                            @if ($materials->count() > 0)
                                <div class="space-y-2.5">
                                    <label for="material"
                                        class="block text-sm font-bold text-[#0f172a] tracking-wide">Material</label>
                                    <select id="material" name="material"
                                        class="w-full border-2 border-[#e2e8f0] rounded-xl px-4 py-3 text-[#0f172a] focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition-all duration-300 bg-white/50 hover:bg-white focus:bg-white appearance-none cursor-pointer font-medium"
                                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23854d0e%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3e%3cpolyline points=%226 9 12 15 18 9%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;">
                                        <option value="">All Materials</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material }}"
                                                {{ request('material') == $material ? 'selected' : '' }}>
                                                {{ $material }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Brand -->
                            @if ($brands->count() > 0)
                                <div class="space-y-2.5">
                                    <label for="brand"
                                        class="block text-sm font-bold text-[#0f172a] tracking-wide">Brand</label>
                                    <select id="brand" name="brand"
                                        class="w-full border-2 border-[#e2e8f0] rounded-xl px-4 py-3 text-[#0f172a] focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition-all duration-300 bg-white/50 hover:bg-white focus:bg-white appearance-none cursor-pointer font-medium"
                                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23854d0e%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3e%3cpolyline points=%226 9 12 15 18 9%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;">
                                        <option value="">All Brands</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand }}"
                                                {{ request('brand') == $brand ? 'selected' : '' }}>
                                                {{ $brand }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="pt-2 space-y-3">
                                <x-button type="submit" variant="primary" size="lg" fullWidth>
                                    Apply Filters
                                </x-button>
                                <x-button href="{{ route('products.index') }}" variant="secondary" size="lg" fullWidth>
                                    Clear All
                                </x-button>
                            </div>
                        </form>
                    </div>
                </aside>

                <!-- Enhanced Products Section -->
                <main class="flex-1">
                    <!-- Active Filters Display -->
                    @if (request()->hasAny(['search', 'category', 'material', 'brand', 'min_price', 'max_price']))
                        <div class="mb-8 space-y-3">
                            <div class="flex flex-wrap items-center gap-2.5">
                                @if (request('search'))
                                    <span
                                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#fffbeb] to-[#fef3c7]/50 text-[#78350f] text-xs sm:text-sm px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[#fde68a]/60 font-semibold shadow-sm hover:shadow-md transition-all max-w-full group">
                                        <x-svg-icon name="search" class="w-4 h-4 flex-shrink-0" />
                                        <span class="truncate">{{ request('search') }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                            class="flex-shrink-0 text-[#d97706] hover:text-[#92400e] opacity-60 hover:opacity-100 transition-all flex items-center">
                                            <x-svg-icon name="close" class="w-4 h-4" stroke-width="2.5" />
                                        </a>
                                    </span>
                                @endif
                                @if (request('category'))
                                    <span
                                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#fff1f2] to-[#ffe4e6]/50 text-[#881337] text-xs sm:text-sm px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[#fecdd3]/60 font-semibold shadow-sm hover:shadow-md transition-all max-w-full group">
                                        <x-svg-icon name="category" class="w-4 h-4 flex-shrink-0" />
                                        <span class="truncate">{{ ucfirst(request('category')) }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                                            class="flex-shrink-0 text-[#e11d48] hover:text-[#9f1239] opacity-60 hover:opacity-100 transition-all flex items-center">
                                            <x-svg-icon name="close" class="w-4 h-4" stroke-width="2.5" />
                                        </a>
                                    </span>
                                @endif
                                @if (request('material'))
                                    <span
                                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#f5f3ff] to-[#ede9fe]/50 text-[#581c87] text-xs sm:text-sm px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[#ddd6fe]/60 font-semibold shadow-sm hover:shadow-md transition-all max-w-full group">
                                        <span class="truncate">{{ request('material') }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['material' => null]) }}"
                                            class="flex-shrink-0 text-[#9333ea] hover:text-[#6b21a8] opacity-60 hover:opacity-100 transition-all flex items-center">
                                            <x-svg-icon name="close" class="w-4 h-4" stroke-width="2.5" />
                                        </a>
                                    </span>
                                @endif
                                @if (request('brand'))
                                    <span
                                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ecfeff] to-[#cffafe]/50 text-[#164e63] text-xs sm:text-sm px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[#a5f3fc]/60 font-semibold shadow-sm hover:shadow-md transition-all max-w-full group">
                                        <span class="truncate">{{ request('brand') }}</span>
                                        <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}"
                                            class="flex-shrink-0 text-[#0891b2] hover:text-[#155e75] opacity-60 hover:opacity-100 transition-all flex items-center">
                                            <x-svg-icon name="close" class="w-4 h-4" stroke-width="2.5" />
                                        </a>
                                    </span>
                                @endif
                                @if (request('min_price') || request('max_price'))
                                    <span
                                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ecfdf5] to-[#d1fae5]/50 text-[#064e3b] text-xs sm:text-sm px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-[#a7f3d0]/60 font-semibold shadow-sm hover:shadow-md transition-all max-w-full group">
                                        <span class="truncate">
                                            @if (request('min_price') && request('max_price'))
                                                {{ number_format(request('min_price')) }} -
                                                {{ number_format(request('max_price')) }} PKR
                                            @elseif (request('min_price'))
                                                Min: {{ number_format(request('min_price')) }} PKR
                                            @else
                                                Max: {{ number_format(request('max_price')) }} PKR
                                            @endif
                                        </span>
                                        <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}"
                                            class="flex-shrink-0 text-[#059669] hover:text-[#047857] opacity-60 hover:opacity-100 transition-all flex items-center">
                                            <x-svg-icon name="close" class="w-4 h-4" stroke-width="2.5" />
                                        </a>
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-[#475569] hover:text-[#0f172a] transition-colors group">
                                <x-svg-icon name="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform" stroke-width="2" />
                                Reset all filters
                            </a>
                        </div>
                    @endif

                    <!-- Sort Bar -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-[#fef3c7]/50 p-4 mb-6">
                        <form method="GET" action="{{ route('products.index') }}"
                            class="flex items-center justify-between gap-3">
                            @foreach (request()->except(['sort']) as $key => $value)
                                @if ($value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label for="sort"
                                class="text-sm font-semibold text-[#334155] whitespace-nowrap">Sort:</label>
                            <select name="sort" id="sort" onchange="this.form.submit()"
                                class="flex-1 sm:flex-initial border-2 border-[#e2e8f0] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] bg-white/50 hover:bg-white font-medium transition-all">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price: Low to
                                    High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price:
                                    High to Low</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured
                                </option>
                            </select>
                        </form>
                    </div>

                    <!-- Products Grid -->
                    @if ($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 items-stretch">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" :showRating="false" :badgeText="$product->is_featured
                                    ? 'Featured'
                                    : ($product->stock == 0
                                        ? 'Out of Stock'
                                        : null)" :badgeColor="$product->is_featured ? 'gold' : 'red'"
                                    imageHeight="h-72" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($products->hasPages())
                            <div class="mt-12 pt-8 border-t border-[#e2e8f0]">
                                {{ $products->withQueryString()->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div
                            class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-[#fef3c7]/50 py-16 px-8 text-center">
                            <div
                                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-[#f1f5f9] to-[#f8fafc] rounded-full mb-6">
                                <svg class="w-10 h-10 text-[#94a3b8]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-[#0f172a] mb-2">No products found</h3>
                            <p class="text-[#475569] mb-8 max-w-md mx-auto">
                                @if (request('search'))
                                    We couldn't find any products matching "<span
                                        class="font-semibold">{{ request('search') }}</span>". Try adjusting your search
                                    or filters.
                                @else
                                    Try adjusting your filter criteria to find what you're looking for.
                                @endif
                            </p>
                            <x-button href="{{ route('products.index') }}" variant="primary" size="lg" icon="arrow-left" iconPosition="left">
                                View All Products
                            </x-button>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Mobile filter panel styles */
            @media (max-width: 1023px) {
                #filters-panel {
                    position: fixed;
                    top: auto;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background: white;
                    z-index: 50;
                    overflow-y: auto;
                    transform: translateY(100%);
                    transition: transform 0.3s ease-out;
                }

                #filters-panel.show,
                #filters-panel:not(.hidden) {
                    transform: translateY(0);
                }
            }

            /* Staggered animation for products */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .grid>* {
                animation: fadeInUp 0.4s ease-out forwards;
            }

            .grid>*:nth-child(1) {
                animation-delay: 0.05s;
            }

            .grid>*:nth-child(2) {
                animation-delay: 0.1s;
            }

            .grid>*:nth-child(3) {
                animation-delay: 0.15s;
            }

            .grid>*:nth-child(4) {
                animation-delay: 0.2s;
            }

            .grid>*:nth-child(5) {
                animation-delay: 0.25s;
            }

            .grid>*:nth-child(6) {
                animation-delay: 0.3s;
            }

            .grid>*:nth-child(n+7) {
                animation-delay: 0.35s;
            }

            #mobile-filter-toggle.active {
                @apply from-[#9f1239] to-[#92400e];
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileToggle = document.getElementById('mobile-filter-toggle');
                const filtersPanel = document.getElementById('filters-panel');
                const mobileFilterClose = document.getElementById('mobile-filter-close');
                const filterForm = document.getElementById('filter-form');

                // Toggle mobile filters
                if (mobileToggle) {
                    mobileToggle.addEventListener('click', () => {
                        filtersPanel.classList.toggle('hidden');
                        filtersPanel.classList.toggle('show');
                        mobileToggle.classList.toggle('active');
                        document.body.style.overflow = filtersPanel.classList.contains('show') ? 'hidden' : '';
                    });
                }

                // Close mobile filters
                if (mobileFilterClose) {
                    mobileFilterClose.addEventListener('click', () => {
                        filtersPanel.classList.add('hidden');
                        filtersPanel.classList.remove('show');
                        mobileToggle?.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                }

                // Close filters when a filter is applied on mobile
                if (filterForm) {
                    filterForm.addEventListener('submit', () => {
                        if (window.innerWidth < 1024) {
                            filtersPanel.classList.add('hidden');
                            filtersPanel.classList.remove('show');
                            mobileToggle?.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                    });
                }

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        filtersPanel.classList.remove('show', 'hidden');
                        document.body.style.overflow = '';
                        mobileToggle?.classList.remove('active');
                    } else if (!filtersPanel.classList.contains('show')) {
                        filtersPanel.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
@endsection
