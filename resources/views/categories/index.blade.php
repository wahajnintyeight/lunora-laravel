@extends('layouts.shop')

@section('title', 'Shop by Category - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="text-center mb-8 sm:mb-12">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Shop by Category</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Discover our exquisite collection of jewelry organized by category. 
            From elegant rings to stunning necklaces, find the perfect piece for every occasion.
        </p>
    </div>
    
    @if($categories->count() > 0)
        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <!-- Category Header -->
                    <div class="p-6 sm:p-8">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 group-hover:text-[#f59e0b] transition-colors">
                                    {{ $category->name }}
                                </h3>
                                @if($category->description)
                                    <p class="text-gray-600 text-sm sm:text-base mb-3 line-clamp-2">{{ $category->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <svg class="w-6 h-6 text-[#f59e0b] group-hover:text-emerald-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Subcategories -->
                        @if($category->children->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Popular Subcategories:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($category->children->take(4) as $child)
                                        <a href="{{ route('category.show', $child->slug) }}" 
                                           class="inline-flex items-center gap-1 text-xs bg-gray-100 hover:bg-emerald-100 text-gray-700 hover:text-emerald-700 px-3 py-1.5 rounded-full transition-colors">
                                            {{ $child->name }}
                                            <span class="text-gray-500">({{ $child->products_count }})</span>
                                        </a>
                                    @endforeach
                                    @if($category->children->count() > 4)
                                        <span class="text-xs text-gray-500 px-3 py-1.5">
                                            +{{ $category->children->count() - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Action Button -->
                        <a href="{{ route('category.show', $category->slug) }}" 
                           class="inline-flex items-center justify-center w-full bg-[#f59e0b] hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors group-hover:bg-emerald-700">
                            <span>Explore {{ $category->name }}</span>
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Browse All Products CTA -->
        <div class="text-center mt-12 sm:mt-16">
            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-2xl p-8 sm:p-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Can't find what you're looking for?</h2>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Browse our complete collection of jewelry pieces or use our search to find specific items.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center justify-center bg-[#f59e0b] hover:bg-emerald-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Browse All Products
                    </a>
                    <a href="{{ route('search') }}" 
                       class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-8 rounded-lg border border-gray-300 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Products
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- No Categories -->
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No categories available</h3>
                <p class="text-gray-600 mb-6">Categories are being set up. Please check back soon!</p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center bg-[#f59e0b] hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    Return Home
                </a>
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