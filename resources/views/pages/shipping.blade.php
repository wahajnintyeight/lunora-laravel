@extends('layouts.app')

@section('title', 'Shipping Information')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8">Shipping Information</h1>
        
        <div class="prose prose-lg mx-auto">
            <h2>Shipping Options</h2>
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">Standard Shipping</h3>
                    <p class="text-gray-600 mb-2">3-5 business days</p>
                    <p class="text-gray-600">Free on orders over $100</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">Express Shipping</h3>
                    <p class="text-gray-600 mb-2">1-2 business days</p>
                    <p class="text-gray-600">$15.99</p>
                </div>
            </div>
            
            <h2>International Shipping</h2>
            <p>We ship worldwide! International shipping times vary by destination (5-14 business days). Customs duties and taxes may apply.</p>
            
            <h2>Order Processing</h2>
            <p>Orders are processed within 1-2 business days. You'll receive a tracking number once your order ships.</p>
            
            <h2>Packaging</h2>
            <p>All jewelry is carefully packaged in our signature boxes with protective materials to ensure safe delivery.</p>
        </div>
    </div>
</div>
@endsection