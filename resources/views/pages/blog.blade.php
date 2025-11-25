@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8">Lunora Blog</h1>
        <p class="text-xl text-gray-600 text-center mb-12">Discover jewelry trends, care tips, and style inspiration</p>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Sample blog posts -->
            @for($i = 1; $i <= 6; $i++)
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-3">Jewelry Care Tips {{ $i }}</h2>
                    <p class="text-gray-600 mb-4">Learn how to properly care for your precious jewelry to maintain its beauty and longevity...</p>
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>{{ now()->subDays($i)->format('M j, Y') }}</span>
                        <a href="#" class="text-blue-600 hover:text-blue-800">Read More</a>
                    </div>
                </div>
            </article>
            @endfor
        </div>
        
        <div class="text-center mt-12">
            <button class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                Load More Posts
            </button>
        </div>
    </div>
</div>
@endsection