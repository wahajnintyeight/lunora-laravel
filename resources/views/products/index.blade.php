@extends('layouts.shop')

@section('title', 'Products - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Mobile Filter Toggle -->
    <div class="lg:hidden mb-4">
        <button type="button" 
                id="mobile-filter-toggle"
                class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filters & Sort
            <span class="ml-auto">
                <svg class="w-5 h-5 transform transition-transform" id="filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:w-1/4">
            <div id="filters-panel" class="hidden lg:block bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 lg:mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
                    <button type="button" 
                            id="mobile-filter-close"
                            class="lg:hidden p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                    <!-- Search -->
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search products..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <!-- Categories -->
                    @if($categories->count() > 0)
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
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
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (PKR)</label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   name="min_price" 
                                   value="{{ request('min_price') }}"
                                   placeholder="Min: {{ number_format($priceRange->min_price / 100, 0) }}"
                                   class="w-1/2 border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <input type="number" 
                                   name="max_price" 
                                   value="{{ request('max_price') }}"
                                   placeholder="Max: {{ number_format($priceRange->max_price / 100, 0) }}"
                                   class="w-1/2 border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    @endif

                    <!-- Material -->
                    @if($materials->count() > 0)
                    <div class="mb-6">
                        <label for="material" class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                        <select id="material" name="material" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
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
                    <div class="mb-6">
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select id="brand" name="brand" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="submit" class="flex-1 bg-[#f59e0b] text-white py-3 px-4 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 font-medium">
                            Apply Filters
                        </button>
                        <a href="{{ route('products.index') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-md text-center hover:bg-gray-300 font-medium">
                            Clear All
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products -->
        <div class="lg:w-3/4">
            <!-- Header with sorting -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Products</h1>
                    <p class="text-gray-600 mt-1">
                        {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found
                        @if(request()->hasAny(['search', 'category', 'material', 'brand', 'min_price', 'max_price']))
                            <span class="text-sm">
                                • <a href="{{ route('products.index') }}" class="text-[#f59e0b] hover:text-emerald-700">Clear filters</a>
                            </span>
                        @endif
                    </p>
                </div>
                
                <div class="w-full sm:w-auto">
                    <form method="GET" action="{{ route('products.index') }}" class="flex gap-2">
                        @foreach(request()->except(['sort', 'direction']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="sort" class="text-sm text-gray-600 whitespace-nowrap">Sort by:</label>
                            <select name="sort" id="sort" onchange="this.form.submit()" 
                                    class="flex-1 sm:flex-none border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'category', 'material', 'brand', 'min_price', 'max_price']))
                <div class="mb-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm text-gray-600">Active filters:</span>
                        
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-sm px-3 py-1 rounded-full">
                                Search: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-[#f59e0b] hover:text-emerald-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('category'))
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                Category: {{ request('category') }}
                                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('material'))
                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">
                                Material: {{ request('material') }}
                                <a href="{{ request()->fullUrlWithQuery(['material' => null]) }}" class="text-purple-600 hover:text-purple-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('brand'))
                            <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-800 text-sm px-3 py-1 rounded-full">
                                Brand: {{ request('brand') }}
                                <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="text-orange-600 hover:text-orange-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('min_price') || request('max_price'))
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                                Price: 
                                @if(request('min_price'))PKR {{ number_format(request('min_price')) }}@endif
                                @if(request('min_price') && request('max_price')) - @endif
                                @if(request('max_price'))PKR {{ number_format(request('max_price')) }}@endif
                                <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="text-gray-600 hover:text-gray-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            @if($products->count() > 0)
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 group">
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <!-- Product Image -->
                                <div class="aspect-square overflow-hidden bg-gray-100">
                                    @if($product->images->count() > 0)
                                        <img src="{{ $product->images->first()->image_path }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                             loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 group-hover:text-[#f59e0b] transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-3">{{ $product->category->name }}</p>
                                    
                                    <!-- Price and Badges -->
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            @if($product->compare_at_price_pkr && $product->compare_at_price_pkr > $product->price_pkr)
                                                <p class="text-sm text-gray-500 line-through">PKR {{ number_format($product->compare_at_price_pkr / 100, 2) }}</p>
                                            @endif
                                            <p class="text-[#f59e0b] font-bold text-lg">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
                                        </div>
                                        
                                        <!-- Badges -->
                                        <div class="flex flex-col gap-1 items-end">
                                            @if($product->is_featured)
                                                <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-medium">Featured</span>
                                            @endif
                                            @if($product->stock <= 5 && $product->stock > 0)
                                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-medium">Low Stock</span>
                                            @elseif($product->stock == 0)
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Stock Status -->
                                    @if($product->stock <= 5 && $product->stock > 0)
                                        <p class="text-orange-600 text-xs">Only {{ $product->stock }} left in stock</p>
                                    @elseif($product->stock == 0)
                                        <p class="text-red-600 text-xs">Currently out of stock</p>
                                    @else
                                        <p class="text-green-600 text-xs">In stock ({{ $product->stock }} available)</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.5-.816-6.207-2.175.277-.079.556-.175.831-.29a6 6 0 1110.752 0c.275.115.554.211.831.29A7.962 7.962 0 0112 15z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#f59e0b] hover:bg-emerald-700">
                                View All Products
                            </a>
                        </div>
                    </div>
                </div>
            @endif
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
            transform: translateY(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        #filters-panel.show {
            transform: translateY(0);
        }
        
        #filters-panel .container {
            padding: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
    const filtersPanel = document.getElementById('filters-panel');
    const mobileFilterClose = document.getElementById('mobile-filter-close');
    const filterChevron = document.getElementById('filter-chevron');
    
    // Toggle mobile filters
    mobileFilterToggle?.addEventListener('click', function() {
        filtersPanel.classList.toggle('show');
        filtersPanel.classList.toggle('hidden');
        filterChevron.classList.toggle('rotate-180');
        
        // Prevent body scroll when filters are open
        if (filtersPanel.classList.contains('show')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });
    
    // Close mobile filters
    mobileFilterClose?.addEventListener('click', function() {
        filtersPanel.classList.remove('show');
        filtersPanel.classList.add('hidden');
        filterChevron.classList.remove('rotate-180');
        document.body.style.overflow = '';
    });
    
    // Close filters when clicking outside (mobile only)
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1024) {
            if (!filtersPanel.contains(e.target) && !mobileFilterToggle.contains(e.target)) {
                if (filtersPanel.classList.contains('show')) {
                    filtersPanel.classList.remove('show');
                    filtersPanel.classList.add('hidden');
                    filterChevron.classList.remove('rotate-180');
                    document.body.style.overflow = '';
                }
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            filtersPanel.classList.remove('show', 'hidden');
            document.body.style.overflow = '';
            filterChevron.classList.remove('rotate-180');
        } else if (!filtersPanel.classList.contains('show')) {
            filtersPanel.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection