@extends('layouts.shop')

@section('title', "Search Results for \"{$searchTerm}\" - Lunora Jewelry")

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Search Results</h1>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-gray-600">
                Showing {{ $products->count() }} of {{ $products->total() }} results for 
                <span class="font-semibold text-gray-900">"{{ $searchTerm }}"</span>
            </p>
            
            @if($products->count() > 0)
                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm text-gray-600">Sort by:</label>
                    <form method="GET" action="{{ route('search') }}" class="inline">
                        <input type="hidden" name="q" value="{{ $searchTerm }}">
                        <select name="sort" id="sort" onchange="this.form.submit()" 
                                class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </form>
                </div>
            @endif
        </div>
    </div>
    
    @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <!-- Product Image -->
                        @if($product->images->count() > 0)
                            <div class="aspect-square overflow-hidden">
                                <img src="{{ $product->images->first()->image_path }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="aspect-square bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Product Name with Highlighting -->
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                {!! highlightSearchTerm($product->name, $searchTerm) !!}
                            </h3>
                            
                            <!-- Category -->
                            <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                            
                            <!-- Price and Stock -->
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($product->compare_at_price_pkr && $product->compare_at_price_pkr > $product->price_pkr)
                                        <p class="text-sm text-gray-500 line-through">PKR {{ number_format($product->compare_at_price_pkr / 100, 2) }}</p>
                                    @endif
                                    <p class="text-emerald-600 font-bold">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
                                </div>
                                
                                <!-- Stock Status -->
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded-full">
                                        Only {{ $product->stock }} left
                                    </span>
                                @elseif($product->stock == 0)
                                    <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">
                                        Out of stock
                                    </span>
                                @elseif($product->is_featured)
                                    <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                                        Featured
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Description with Highlighting (if matches search) -->
                            @if($product->description && str_contains(strtolower($product->description), strtolower($searchTerm)))
                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                    {!! highlightSearchTerm(Str::limit($product->description, 100), $searchTerm) !!}
                                </p>
                            @endif
                            
                            <!-- Material/Brand with Highlighting (if matches search) -->
                            @if($product->material && str_contains(strtolower($product->material), strtolower($searchTerm)))
                                <p class="text-xs text-gray-500 mt-1">
                                    Material: {!! highlightSearchTerm($product->material, $searchTerm) !!}
                                </p>
                            @endif
                            
                            @if($product->brand && str_contains(strtolower($product->brand), strtolower($searchTerm)))
                                <p class="text-xs text-gray-500 mt-1">
                                    Brand: {!! highlightSearchTerm($product->brand, $searchTerm) !!}
                                </p>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->appends(['q' => $searchTerm, 'sort' => request('sort')])->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-600 mb-6">
                    We couldn't find any products matching <span class="font-semibold">"{{ $searchTerm }}"</span>
                </p>
                
                <!-- Search Suggestions -->
                @if(isset($suggestions) && count($suggestions) > 0)
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Did you mean:</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach($suggestions as $suggestion)
                                <a href="{{ route('search', ['q' => $suggestion]) }}" 
                                   class="bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-full text-sm transition-colors">
                                    {{ $suggestion }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Search Tips -->
                <div class="text-left bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-gray-900 mb-2">Search Tips:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Check your spelling</li>
                        <li>• Try more general keywords</li>
                        <li>• Use fewer keywords</li>
                        <li>• Try searching by category or material</li>
                    </ul>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        Browse All Products
                    </a>
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        View Categories
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
    
    .search-highlight {
        background-color: #fef3c7;
        color: #92400e;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-weight: 600;
    }
</style>
@endpush

@php
if (!function_exists('highlightSearchTerm')) {
    function highlightSearchTerm($text, $searchTerm) {
        if (empty($searchTerm) || empty($text)) {
            return $text;
        }
        
        // Escape special regex characters in search term
        $escapedTerm = preg_quote($searchTerm, '/');
        
        // Highlight the search term (case insensitive)
        return preg_replace(
            '/(' . $escapedTerm . ')/i',
            '<span class="search-highlight">$1</span>',
            $text
        );
    }
}
@endphp
@endsection