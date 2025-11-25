@extends('layouts.shop')

@section('title', 'Home - Lunora Jewelry')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-emerald-50 to-emerald-100">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-4">Welcome to Lunora</h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Discover exquisite jewelry crafted with precision and passion. From elegant rings to stunning necklaces, find the perfect piece for every occasion.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" 
                   class="bg-[#f59e0b] text-white px-8 py-3 rounded-lg font-semibold hover:bg-emerald-700 transition-colors">
                    Shop All Products
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="bg-white text-[#f59e0b] border-2 border-[#f59e0b] px-8 py-3 rounded-lg font-semibold hover:bg-emerald-50 transition-colors">
                    Browse Categories
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
                <p class="text-gray-600">Handpicked selections from our finest collection</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <div class="relative">
                                @if($product->images->count() > 0)
                                    <img src="{{ $product->images->first()->image_path }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2">
                                    <span class="bg-[#f59e0b] text-white text-xs px-2 py-1 rounded-full">Featured</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-[#f59e0b] font-bold">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
                                    @if($product->stock <= 5)
                                        <span class="text-orange-600 text-xs">Low Stock</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('products.index', ['sort' => 'featured']) }}" 
                   class="inline-flex items-center text-[#f59e0b] hover:text-emerald-700 font-medium">
                    View All Featured Products
                    <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </section>
    @endif

    <!-- Categories Showcase -->
    @if($mainCategories->count() > 0)
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                <p class="text-gray-600">Explore our diverse collection of fine jewelry</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($mainCategories as $category)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <a href="{{ route('category.show', $category->slug) }}" class="block">
                            <div class="p-6 text-center">
                                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-200 transition-colors">
                                    <svg class="w-8 h-8 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                                @if($category->description)
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($category->description, 100) }}</p>
                                @endif
                                <p class="text-[#f59e0b] font-medium">{{ $category->products_count }} Products</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- New Arrivals -->
    @if($newArrivals->count() > 0)
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">New Arrivals</h2>
                <p class="text-gray-600">Latest additions to our collection</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($newArrivals as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <div class="relative">
                                @if($product->images->count() > 0)
                                    <img src="{{ $product->images->first()->image_path }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                <div class="absolute top-2 left-2">
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">New</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                                <p class="text-[#f59e0b] font-bold">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" 
                   class="inline-flex items-center text-[#f59e0b] hover:text-emerald-700 font-medium">
                    View All New Arrivals
                    <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </section>
    @endif

    <!-- Category Products Showcase -->
    @if($categoryShowcase->count() > 0)
        @foreach($categoryShowcase as $category)
            <section class="mb-16">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                        @if($category->description)
                            <p class="text-gray-600 mt-1">{{ $category->description }}</p>
                        @endif
                    </div>
                    <a href="{{ route('category.show', $category->slug) }}" 
                       class="text-[#f59e0b] hover:text-emerald-700 font-medium">
                        View All
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($category->products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                @if($product->images->count() > 0)
                                    <img src="{{ $product->images->first()->image_path }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h4>
                                    <p class="text-[#f59e0b] font-bold">PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif

    <!-- Call to Action -->
    <section class="bg-emerald-50 rounded-lg p-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Can't Find What You're Looking For?</h2>
        <p class="text-gray-600 mb-6">
            Use our search feature to find specific products or browse our complete catalog
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
            <div class="flex-1">
                <form action="{{ route('search') }}" method="GET" class="flex">
                    <input type="text" 
                           name="q" 
                           placeholder="Search for jewelry..."
                           class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <button type="submit" 
                            class="bg-[#f59e0b] text-white px-6 py-2 rounded-r-lg hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection