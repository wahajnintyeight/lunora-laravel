@extends('layouts.app')

@section('title', "Search Results for \"{$searchTerm}\" - Lunora Jewelry")

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white p-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16"></div>
        <!-- Search Header Section -->
        <div class="mb-12">
            <div class="mb-6">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-3">Search Results</h1>
                <div class="h-1 w-20 bg-gradient-to-r from-gold-500 to-maroon-950 rounded-full"></div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mt-8">
                <div class="flex-1">
                    <p class="text-lg text-gray-600">
                        <span class="font-semibold text-gray-900">{{ $products->count() }}</span> 
                        {{ Str::plural('result', $products->count()) }} found for
                    </p>
                    <p class="text-xl sm:text-2xl font-bold text-maroon-950 mt-1">
                        "{{ $searchTerm }}"
                    </p>
                </div>
                
                @if($products->count() > 0)
                    <!-- Sort Options -->
                    <div class="flex items-center gap-3 bg-white px-4 py-3 rounded-lg border border-gray-200 shadow-sm">
                        <label for="sort" class="text-sm font-medium text-gray-700 whitespace-nowrap">Sort by:</label>
                        <form method="GET" action="{{ route('search') }}" class="inline">
                            <input type="hidden" name="q" value="{{ $searchTerm }}">
                            <select name="sort" id="sort" onchange="this.form.submit()" 
                                    class="text-sm border-0 rounded-md px-3 py-1.5 focus:ring-2 focus:ring-gold-500 focus:border-gold-500 bg-gray-50 text-gray-900 font-medium">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8 items-stretch">
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

    @else
        <!-- No Results Section -->
        <div class="py-16 sm:py-24">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Icon -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Heading -->
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">No products found</h2>
                <p class="text-lg text-gray-600 mb-8">
                    We couldn't find any products matching 
                    <span class="font-semibold text-maroon-950">"{{ $searchTerm }}"</span>
                </p>
                
                <!-- Search Suggestions -->
                @if(isset($suggestions) && count($suggestions) > 0)
                    <div class="mb-12 p-6 bg-gold-50 rounded-xl border border-gold-200">
                        <p class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Try searching for:</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            @foreach($suggestions as $suggestion)
                                <a href="{{ route('search', ['q' => $suggestion]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white border-2 border-gold-500 text-gold-600 rounded-full text-sm font-medium hover:bg-gold-500 hover:text-white transition-all duration-200">
                                    {{ $suggestion }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Search Tips -->
                <div class="mb-12 text-left bg-white rounded-xl border border-gray-200 p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                        </svg>
                        Search Tips
                    </h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start gap-3">
                            <span class="text-gold-500 font-bold mt-0.5">•</span>
                            <span>Check your spelling and try again</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-gold-500 font-bold mt-0.5">•</span>
                            <span>Try using more general keywords</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-gold-500 font-bold mt-0.5">•</span>
                            <span>Use fewer words for broader results</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-gold-500 font-bold mt-0.5">•</span>
                            <span>Browse by category or material instead</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('shop.index') }}" 
                       class="inline-flex items-center justify-center px-8 py-3 bg-maroon-950 text-white font-semibold rounded-lg hover:bg-maroon-900 transition-colors duration-200 border-2 border-gold-500 hover:border-gold-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Browse All Products
                    </a>
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center justify-center px-8 py-3 bg-white text-maroon-950 font-semibold rounded-lg border-2 border-gray-300 hover:border-gold-500 hover:bg-gold-50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        View Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

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
    
    /* Smooth animations */
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
        animation: fadeInUp 0.5s ease-out forwards;
    }
    
    .grid > *:nth-child(1) { animation-delay: 0.05s; }
    .grid > *:nth-child(2) { animation-delay: 0.1s; }
    .grid > *:nth-child(3) { animation-delay: 0.15s; }
    .grid > *:nth-child(4) { animation-delay: 0.2s; }
    .grid > *:nth-child(n+5) { animation-delay: 0.25s; }
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