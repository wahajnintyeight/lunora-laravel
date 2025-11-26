@extends('layouts.app')

@section('title', $page->title)

@if($page->meta_description)
    @section('meta_description', $page->meta_description)
@endif

@section('content')
@php
    $slug = $page->slug;
    $isAbout = $slug === 'about';
    $isContact = $slug === 'contact';
    $isFaq = $slug === 'faq';
    $isShipping = $slug === 'shipping' || $slug === 'returns' || $slug === 'warranty';
@endphp

@if($isAbout)
    <!-- About Page Design -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-[#450a0a] to-[#5a0f0f] text-white py-16 sm:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">{{ $page->title }}</h1>
                    @if($page->meta_description)
                        <p class="text-xl sm:text-2xl text-gold-200 max-w-3xl mx-auto">{{ $page->meta_description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="prose prose-lg prose-headings:text-[#450a0a] prose-headings:font-bold prose-p:text-gray-700 prose-a:text-[#f59e0b] prose-a:no-underline hover:prose-a:underline prose-strong:text-[#450a0a] max-w-none dark:prose-invert">
                {!! $page->content !!}
            </div>

            <!-- Mission/Vision Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16">
                <div class="bg-gradient-to-br from-[#450a0a] to-[#5a0f0f] rounded-2xl p-8 text-white shadow-xl">
                    <div class="w-16 h-16 bg-[#f59e0b] rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-bullseye text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Our Mission</h3>
                    <p class="text-gold-200">To create exceptional jewelry that celebrates individuality and marks life's special moments with elegance and style.</p>
                </div>
                <div class="bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-2xl p-8 text-white shadow-xl">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-eye text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Our Vision</h3>
                    <p class="text-white/90">To be the premier destination for timeless jewelry that tells your unique story and becomes a cherished heirloom.</p>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center text-[#450a0a] mb-12">Why Choose Lunora?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-gem text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#450a0a]">Premium Quality</h3>
                        <p class="text-gray-600">Only the finest materials and gemstones make it into our collection.</p>
                    </div>
                    <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tools text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#450a0a]">Expert Craftsmanship</h3>
                        <p class="text-gray-600">Our skilled artisans bring decades of experience to every piece.</p>
                    </div>
                    <div class="text-center p-6 rounded-xl hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-heart text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-[#450a0a]">Personal Touch</h3>
                        <p class="text-gray-600">Custom engraving and personalization options available.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($isContact)
    <!-- Contact Page Design -->
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16 lg:pt-20 lg:pb-24">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16 sm:mb-20">
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                    @if($page->meta_description)
                        <p class="text-lg sm:text-xl text-gray-600 mb-6">{{ $page->meta_description }}</p>
                    @endif
                    <div class="w-16 h-1 bg-gradient-to-r from-[#f59e0b] to-[#d97706] rounded-full mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                    <!-- Contact Form -->
                    <div class="bg-white rounded-xl shadow-lg p-8 sm:p-10">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Send us a Message</h2>
                        <div class="prose prose-lg max-w-none">
                            {!! $page->content !!}
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-8">
                        <div class="bg-white rounded-xl shadow-lg p-8 sm:p-10">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Get in Touch</h2>
                            <div class="space-y-7">
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-[#f59e0b] rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">Address</h3>
                                        <p class="text-gray-600 mt-2">123 Jewelry Street<br>Luxury District<br>City, State 12345</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-[#f59e0b] rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                        <i class="fas fa-phone text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">Phone</h3>
                                        <p class="text-gray-600 mt-2">+1 (555) 123-4567</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-[#f59e0b] rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                        <i class="fas fa-envelope text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">Email</h3>
                                        <p class="text-gray-600 mt-2">info@lunora.com</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-[#f59e0b] rounded-full flex items-center justify-center flex-shrink-0 shadow-md">
                                        <i class="fas fa-clock text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">Business Hours</h3>
                                        <p class="text-gray-600 mt-2">
                                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                                            Saturday: 10:00 AM - 4:00 PM<br>
                                            Sunday: Closed
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($isFaq)
    <!-- FAQ Page Design -->
    <div class="min-h-screen bg-gray-50 py-12 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                @if($page->meta_description)
                    <p class="text-lg text-gray-600">{{ $page->meta_description }}</p>
                @endif
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 sm:p-10">
                <div class="prose prose-lg max-w-none prose-headings:text-[#450a0a] prose-headings:font-bold prose-p:text-gray-700 prose-a:text-[#f59e0b] dark:prose-invert">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

@elseif($isShipping)
    <!-- Shipping/Returns/Warranty Page Design -->
    <div class="min-h-screen bg-gray-50 py-12 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-full flex items-center justify-center mx-auto mb-6">
                    @if($slug === 'shipping')
                        <i class="fas fa-shipping-fast text-white text-3xl"></i>
                    @elseif($slug === 'returns')
                        <i class="fas fa-undo text-white text-3xl"></i>
                    @else
                        <i class="fas fa-shield-alt text-white text-3xl"></i>
                    @endif
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                @if($page->meta_description)
                    <p class="text-lg text-gray-600">{{ $page->meta_description }}</p>
                @endif
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 sm:p-10">
                <div class="prose prose-lg max-w-none prose-headings:text-[#450a0a] prose-headings:font-bold prose-p:text-gray-700 prose-a:text-[#f59e0b] prose-ul:text-gray-700 prose-li:text-gray-700 dark:prose-invert">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

@else
    <!-- Default Page Design -->
    <div class="min-h-screen bg-gray-50 py-12 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                @if($page->meta_description)
                    <p class="text-lg text-gray-600">{{ $page->meta_description }}</p>
                @endif
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-8 sm:p-10">
                <div class="prose prose-lg max-w-none prose-headings:text-[#450a0a] prose-headings:font-bold prose-p:text-gray-700 prose-a:text-[#f59e0b] dark:prose-invert">
                    {!! $page->content !!}
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Last updated: {{ $page->updated_at->format('F j, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
