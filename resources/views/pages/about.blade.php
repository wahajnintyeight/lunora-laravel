@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About Lunora</h1>
            <p class="text-xl text-gray-600">Crafting timeless jewelry with premium materials and exceptional craftsmanship since 2020.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                <p class="text-gray-600 mb-4">
                    Founded with a passion for creating beautiful, lasting jewelry, Lunora has been dedicated to bringing you the finest pieces that celebrate life's most precious moments.
                </p>
                <p class="text-gray-600 mb-4">
                    Our journey began with a simple belief: that jewelry should be more than just an accessory. It should tell a story, capture a memory, and reflect the unique beauty of the person wearing it.
                </p>
                <p class="text-gray-600">
                    Today, we continue to honor that vision by carefully selecting premium materials and working with skilled artisans to create pieces that stand the test of time.
                </p>
            </div>
            <div class="bg-gradient-luxury rounded-lg p-8 text-white">
                <h3 class="text-2xl font-bold mb-4">Our Mission</h3>
                <p class="mb-4">
                    To create exceptional jewelry that celebrates individuality and marks life's special moments with elegance and style.
                </p>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Premium Quality Materials
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Expert Craftsmanship
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Exceptional Customer Service
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Lifetime Warranty
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Why Choose Lunora?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-luxury rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gem text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Premium Quality</h3>
                    <p class="text-gray-600">Only the finest materials and gemstones make it into our collection.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-luxury rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tools text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Expert Craftsmanship</h3>
                    <p class="text-gray-600">Our skilled artisans bring decades of experience to every piece.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-luxury rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Personal Touch</h3>
                    <p class="text-gray-600">Custom engraving and personalization options available.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection