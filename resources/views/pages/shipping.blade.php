@extends('layouts.app')

@section('title', 'Shipping Information')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16 lg:pt-20 lg:pb-24">
        <div class="max-w-4xl mx-auto">
            <div class="mb-16 sm:mb-20">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 text-center mb-4">Shipping Information</h1>
                <div class="w-16 h-1 bg-gradient-to-r from-gold-500 to-gold-400 rounded-full mx-auto"></div>
            </div>
            
            <div class="space-y-12 sm:space-y-16">
                <!-- Shipping Options Section -->
                <section>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8 pb-4 border-b-2 border-gold-200">Shipping Options</h2>
                    <div class="grid md:grid-cols-2 gap-6 sm:gap-8">
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-8">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Standard Shipping</h3>
                            <div class="space-y-3">
                                <p class="text-lg text-gold-600 font-semibold">3-5 business days</p>
                                <p class="text-gray-600">Free on orders over $100</p>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-8">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Express Shipping</h3>
                            <div class="space-y-3">
                                <p class="text-lg text-gold-600 font-semibold">1-2 business days</p>
                                <p class="text-gray-600">$15.99</p>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- International Shipping Section -->
                <section>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-5 pb-4 border-b-2 border-gold-200">International Shipping</h2>
                    <div class="bg-white rounded-xl shadow-md p-8 border-l-4 border-gold-500">
                        <p class="text-gray-700 text-lg leading-relaxed">We ship worldwide! International shipping times vary by destination (5-14 business days). Customs duties and taxes may apply.</p>
                    </div>
                </section>
                
                <!-- Order Processing Section -->
                <section>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-5 pb-4 border-b-2 border-gold-200">Order Processing</h2>
                    <div class="bg-white rounded-xl shadow-md p-8 border-l-4 border-gold-500">
                        <p class="text-gray-700 text-lg leading-relaxed">Orders are processed within 1-2 business days. You'll receive a tracking number once your order ships.</p>
                    </div>
                </section>
                
                <!-- Packaging Section -->
                <section>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-5 pb-4 border-b-2 border-gold-200">Packaging</h2>
                    <div class="bg-white rounded-xl shadow-md p-8 border-l-4 border-gold-500">
                        <p class="text-gray-700 text-lg leading-relaxed">All jewelry is carefully packaged in our signature boxes with protective materials to ensure safe delivery.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection