@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8">My Wishlist</h1>
        
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-heart text-6xl text-gray-300 mb-6"></i>
            <h2 class="text-2xl font-semibold mb-4">Your wishlist is empty</h2>
            <p class="text-gray-600 mb-8">Start adding items you love to keep track of them here.</p>
            
            <a href="{{ route('shop.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300 inline-block">
                Browse Products
            </a>
        </div>
    </div>
</div>
@endsection