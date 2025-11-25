@extends('layouts.app')

@section('title', 'Warranty Information')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8">Warranty Information</h1>
        
        <div class="prose prose-lg mx-auto">
            <h2>Lifetime Warranty</h2>
            <p>All Lunora jewelry comes with a lifetime warranty against manufacturing defects. We stand behind the quality of our craftsmanship.</p>
            
            <h2>What's Covered</h2>
            <ul>
                <li>Manufacturing defects in materials or workmanship</li>
                <li>Prong re-tipping and stone tightening</li>
                <li>Clasp and chain repairs</li>
                <li>Rhodium re-plating (white gold items)</li>
            </ul>
            
            <h2>What's Not Covered</h2>
            <ul>
                <li>Normal wear and tear</li>
                <li>Damage from accidents or misuse</li>
                <li>Lost or stolen items</li>
                <li>Damage from improper care or cleaning</li>
            </ul>
            
            <h2>Care Instructions</h2>
            <p>To maintain your jewelry's beauty and ensure warranty coverage:</p>
            <ul>
                <li>Store pieces separately in soft pouches</li>
                <li>Avoid exposure to chemicals and perfumes</li>
                <li>Clean gently with appropriate jewelry cleaners</li>
                <li>Have pieces professionally inspected annually</li>
            </ul>
            
            <h2>Warranty Claims</h2>
            <p>To make a warranty claim, contact our customer service team with your order number and photos of the issue. We'll guide you through the process.</p>
        </div>
    </div>
</div>
@endsection