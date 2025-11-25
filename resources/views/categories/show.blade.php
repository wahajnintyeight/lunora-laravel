@extends('layouts.app')

@section('title', $category->name . ' - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Breadcrumbs -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-1 sm:space-x-2 text-sm overflow-x-auto">
            @foreach($breadcrumbs as $breadcrumb)
                @if($breadcrumb['is_current'])
                    <li class="text-gray-500 font-medium whitespace-nowrap" aria-current="page">{{ $breadcrumb['name'] }}</li>
                @else
                    <li class="whitespace-nowrap">
                        <a href="{{ $breadcrumb['url'] }}" class="text-[#f59e0b] hover:text-emerald-700 transition-colors">
                            {{ $breadcrumb['name'] }}
                        </a>
                    </li>
                @endif
                @if(!$loop->last)
                    <li class="text-gray-400 flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="mb-8 sm:mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-600 text-base sm:text-lg max-w-3xl">{{ $category->description }}</p>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found
            </div>
        </div>
    </div>

    <!-- Subcategories -->
    @if($category->children->count() > 0)
        <div class="mb-8 sm:mb-12">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Shop by Subcategory</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4">
                @foreach($category->children as $child)
                    <a href="{{ route('category.show', $child->slug) }}" 
                       class="group bg-white hover:bg-emerald-50 border border-gray-200 hover:border-emerald-200 rounded-lg overflow-hidden transition-all duration-200">
                        @if($child->image_url)
                            <div class="aspect-square overflow-hidden bg-gray-100">
                                <img src="{{ $child->image_url }}" alt="{{ $child->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="aspect-square bg-gray-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-3 text-center">
                            <div class="text-sm sm:text-base font-medium text-gray-900 group-hover:text-emerald-700 mb-1">
                                {{ $child->name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $child->products_count }} {{ Str::plural('item', $child->products_count) }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
        <div class="mb-8 sm:mb-12">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-6">Featured in {{ $category->name }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card 
                        :product="$product"
                        :showRating="false"
                        badgeText="Featured"
                        badgeColor="gold"
                        imageHeight="h-64"
                    />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters and Sorting -->
    @if($products->count() > 0)
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Filter Options -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    @if($materials->count() > 0 || $brands->count() > 0 || $priceRange)
                        <button type="button" 
                                id="category-filter-toggle"
                                class="sm:hidden flex items-center gap-2 bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </button>
                        
                        <!-- Desktop Filters -->
                        <div class="hidden sm:flex items-center gap-4">
                            @if($materials->count() > 0)
                                <form method="GET" class="inline">
                                    @foreach(request()->except('material') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="material" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="">All Materials</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                                                {{ $material }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                            
                            @if($brands->count() > 0)
                                <form method="GET" class="inline">
                                    @foreach(request()->except('brand') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <select name="brand" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                                {{ $brand }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm text-gray-600 whitespace-nowrap">Sort by:</label>
                    <form method="GET" class="inline">
                        @foreach(request()->except(['sort', 'direction']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" id="sort" onchange="this.form.submit()" 
                                class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                <x-product-card 
                    :product="$product"
                    :showRating="false"
                    :badgeText="$product->is_featured ? 'Featured' : ($product->stock == 0 ? 'Out of Stock' : null)"
                    :badgeColor="$product->is_featured ? 'gold' : 'red'"
                    imageHeight="h-64"
                />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 sm:mt-12">
            {{ $products->withQueryString()->links() }}
        </div>
    @else
        <!-- No Products -->
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-600 mb-6">
                    There are currently no products available in this category.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center justify-center bg-[#f59e0b] hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        Browse All Products
                    </a>
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg border border-gray-300 transition-colors">
                        View All Categories
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection