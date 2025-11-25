@extends('layouts.app')

@section('title', 'Products - Lunora Jewelry')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">
                        @if(request('search'))
                            Search Results
                        @elseif(request('category'))
                            {{ ucfirst(request('category')) }}
                        @else
                            Our Collection
                        @endif
                    </h1>
                    <div class="h-1 w-16 bg-gradient-to-r from-gold-500 to-maroon-950 rounded-full mt-3"></div>
                    <p class="text-gray-600 mt-3">
                        @if(request('search'))
                            <span class="font-semibold text-gray-900">{{ $products->total() }}</span> results for 
                            "<span class="text-maroon-950 font-medium">{{ request('search') }}</span>"
                        @else
                            Discover <span class="font-semibold text-gray-900">{{ $products->total() }}</span> exquisite pieces
                        @endif
                    </p>
                </div>
                
                <!-- Mobile Filter Toggle -->
                <button type="button" 
                        id="mobile-filter-toggle"
                        class="lg:hidden flex items-center justify-center gap-2 bg-maroon-950 text-white rounded-lg px-5 py-3 font-semibold hover:bg-maroon-900 transition-colors border-2 border-gold-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filters
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <aside class="lg:w-72 flex-shrink-0">
                <div id="filters-panel" class="hidden lg:block bg-white rounded-xl shadow-lg border-2 border-gold-500/30 overflow-hidden">
                    <!-- Filter Header -->
                    <div class="bg-gradient-to-r from-maroon-950 to-maroon-900 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </h2>
                        <button type="button" id="mobile-filter-close" class="lg:hidden text-white hover:text-gold-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form method="GET" action="{{ route('products.index') }}" id="filter-form" class="p-6 space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-semibold text-gray-900 mb-2">Search Products</label>
                            <div class="relative">
                                <input type="text" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search..."
                                       class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 pl-10 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-colors">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Categories -->
                        @if($categories->count() > 0)
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                            <select id="category" name="category" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-colors bg-white">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->products_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Price Range -->
                        @if($priceRange && $priceRange->min_price && $priceRange->max_price)
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Price Range (PKR)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}"
                                       placeholder="Min"
                                       class="border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-colors">
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}"
                                       placeholder="Max"
                                       class="border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-colors">
                            </div>
                        </div>
                        @endif

                        <!-- Material -->
                        @if($materials->count() > 0)
                        <div>
                            <label for="material" class="block text-sm font-semibold text-gray-900 mb-2">Material</label>
                            <select id="material" name="material" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-colors bg-white">
                                <option value="">All Materials</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                                        {{ $material }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Brand -->
                        @if($brands->count() > 0)
                        <div>
                            <label for="brand" class="block text-sm font-semibold text-gray-900 mb-2">Brand</label>
                            <select id="brand" name="brand" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-colors bg-white">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3">
                            <button type="submit" class="w-full bg-maroon-950 text-white py-3 px-4 rounded-lg font-semibold hover:bg-maroon-900 transition-colors border-2 border-gold-500 hover:border-gold-400">
                                Apply Filters
                            </button>
                            <a href="{{ route('products.index') }}" class="block w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg text-center font-medium hover:bg-gray-200 transition-colors">
                                Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Products Section -->
            <main class="flex-1">
                <!-- Sort & Active Filters Bar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Active Filters -->
                        @if(request()->hasAny(['search', 'category', 'material', 'brand', 'min_price', 'max_price']))
                        <div class="flex flex-wrap items-center gap-2">
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 bg-gold-100 text-gold-800 text-sm px-3 py-1.5 rounded-full font-medium">
                                    "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-gold-600 hover:text-gold-800 ml-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                            @if(request('category'))
                                <span class="inline-flex items-center gap-1 bg-maroon-100 text-maroon-800 text-sm px-3 py-1.5 rounded-full font-medium">
                                    {{ ucfirst(request('category')) }}
                                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-maroon-600 hover:text-maroon-800 ml-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                            @if(request('material'))
                                <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 text-sm px-3 py-1.5 rounded-full font-medium">
                                    {{ request('material') }}
                                    <a href="{{ request()->fullUrlWithQuery(['material' => null]) }}" class="text-purple-600 hover:text-purple-800 ml-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-maroon-950 underline">
                                Clear all
                            </a>
                        </div>
                        @else
                        <div class="text-sm text-gray-500">
                            Showing all products
                        </div>
                        @endif
                        
                        <!-- Sort -->
                        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-3">
                            @foreach(request()->except(['sort']) as $key => $value)
                                @if($value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label for="sort" class="text-sm font-medium text-gray-700 whitespace-nowrap">Sort:</label>
                            <select name="sort" id="sort" onchange="this.form.submit()" 
                                    class="border-2 border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500 bg-white font-medium">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if($products->count() > 0)
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 sm:gap-8">
                        @foreach($products as $product)
                            <x-product-card 
                                :product="$product"
                                :showRating="false"
                                :badgeText="$product->is_featured ? 'Featured' : ($product->stock == 0 ? 'Out of Stock' : null)"
                                :badgeColor="$product->is_featured ? 'gold' : 'red'"
                                imageHeight="h-72"
                            />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 py-16 px-8 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            @if(request('search'))
                                We couldn't find any products matching "<span class="font-semibold">{{ request('search') }}</span>". Try adjusting your search or filters.
                            @else
                                Try adjusting your filter criteria to find what you're looking for.
                            @endif
                        </p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-maroon-950 text-white font-semibold rounded-lg hover:bg-maroon-900 transition-colors border-2 border-gold-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            View All Products
                        </a>
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
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 50;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        #filters-panel.show {
            transform: translateX(0);
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
    
    .grid > * {
        animation: fadeInUp 0.4s ease-out forwards;
    }
    
    .grid > *:nth-child(1) { animation-delay: 0.05s; }
    .grid > *:nth-child(2) { animation-delay: 0.1s; }
    .grid > *:nth-child(3) { animation-delay: 0.15s; }
    .grid > *:nth-child(4) { animation-delay: 0.2s; }
    .grid > *:nth-child(5) { animation-delay: 0.25s; }
    .grid > *:nth-child(6) { animation-delay: 0.3s; }
    .grid > *:nth-child(n+7) { animation-delay: 0.35s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
    const filtersPanel = document.getElementById('filters-panel');
    const mobileFilterClose = document.getElementById('mobile-filter-close');
    
    // Toggle mobile filters
    mobileFilterToggle?.addEventListener('click', function() {
        filtersPanel.classList.toggle('show');
        filtersPanel.classList.toggle('hidden');
        document.body.style.overflow = filtersPanel.classList.contains('show') ? 'hidden' : '';
    });
    
    // Close mobile filters
    mobileFilterClose?.addEventListener('click', function() {
        filtersPanel.classList.remove('show');
        filtersPanel.classList.add('hidden');
        document.body.style.overflow = '';
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            filtersPanel.classList.remove('show', 'hidden');
            document.body.style.overflow = '';
        } else if (!filtersPanel.classList.contains('show')) {
            filtersPanel.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection
