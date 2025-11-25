@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
            <p class="text-xl text-gray-600">Find answers to common questions about our jewelry and services.</p>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md">
                <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 flex justify-between items-center" 
                        onclick="toggleFaq(this)">
                    <span>What materials do you use in your jewelry?</span>
                    <i class="fas fa-chevron-down transform transition-transform"></i>
                </button>
                <div class="px-6 pb-4 text-gray-600 hidden">
                    <p>We use only premium materials including 14k and 18k gold, sterling silver, platinum, and genuine gemstones. All our pieces are crafted with the highest quality standards to ensure durability and lasting beauty.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 flex justify-between items-center" 
                        onclick="toggleFaq(this)">
                    <span>Do you offer custom jewelry design?</span>
                    <i class="fas fa-chevron-down transform transition-transform"></i>
                </button>
                <div class="px-6 pb-4 text-gray-600 hidden">
                    <p>Yes! We offer custom jewelry design services. Our skilled artisans can work with you to create a unique piece that reflects your personal style and preferences. Contact us to discuss your custom jewelry needs.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 flex justify-between items-center" 
                        onclick="toggleFaq(this)">
                    <span>What is your return policy?</span>
                    <i class="fas fa-chevron-down transform transition-transform"></i>
                </button>
                <div class="px-6 pb-4 text-gray-600 hidden">
                    <p>We offer a 30-day return policy for all jewelry purchases. Items must be in original condition with all packaging. Custom and personalized items are not eligible for return unless there is a manufacturing defect.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 flex justify-between items-center" 
                        onclick="toggleFaq(this)">
                    <span>Do you provide certificates of authenticity?</span>
                    <i class="fas fa-chevron-down transform transition-transform"></i>
                </button>
                <div class="px-6 pb-4 text-gray-600 hidden">
                    <p>Yes, all our jewelry comes with a certificate of authenticity that details the materials, gemstones, and craftsmanship. For pieces with diamonds or precious gemstones, we provide additional certification from recognized gemological institutes.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 flex justify-between items-center" 
                        onclick="toggleFaq(this)">
                    <span>How should I care for my jewelry?</span>
                    <i class="fas fa-chevron-down transform transition-transform"></i>
                </button>
                <div class="px-6 pb-4 text-gray-600 hidden">
                    <p>To maintain your jewelry's beauty, store pieces separately in soft pouches, avoid exposure to chemicals and perfumes, clean regularly with appropriate jewelry cleaners, and have professional cleaning done annually.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <button class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 flex justify-between items-center" 
                        onclick="toggleFaq(this)">
                    <span>Do you offer warranty on your jewelry?</span>
                    <i class="fas fa-chevron-down transform transition-transform"></i>
                </button>
                <div class="px-6 pb-4 text-gray-600 hidden">
                    <p>Yes, we provide a lifetime warranty against manufacturing defects. This covers issues with craftsmanship but does not include damage from normal wear, accidents, or improper care. Warranty details are provided with each purchase.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <p class="text-gray-600 mb-4">Still have questions?</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-gradient-luxury text-white font-semibold rounded-lg hover:opacity-90 transition-opacity">
                <i class="fas fa-envelope mr-2"></i>
                Contact Us
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFaq(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
@endpush
@endsection