@extends('layouts.app')

@section('title', 'Custom Jewelry')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center mb-8">Custom Jewelry Design</h1>
        <p class="text-xl text-gray-600 text-center mb-12">Create a unique piece that tells your story</p>
        
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-semibold mb-6">Bring Your Vision to Life</h2>
                <p class="text-gray-600 mb-6">
                    Our master craftsmen work with you to create one-of-a-kind jewelry pieces. From engagement rings to family heirlooms, we'll help you design something truly special.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Personal Consultation</h3>
                            <p class="text-gray-600">One-on-one design sessions with our experts</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">3D Rendering</h3>
                            <p class="text-gray-600">See your design before it's made</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Premium Materials</h3>
                            <p class="text-gray-600">Choose from the finest metals and gemstones</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-100 h-96 rounded-lg flex items-center justify-center">
                <i class="fas fa-wand-magic-sparkles text-8xl text-gray-400"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-center mb-8">Start Your Custom Design</h2>
            
            <form class="max-w-2xl mx-auto space-y-6">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">First Name</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Last Name</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Phone</label>
                    <input type="tel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Type of Jewelry</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select a type</option>
                        <option value="ring">Ring</option>
                        <option value="necklace">Necklace</option>
                        <option value="bracelet">Bracelet</option>
                        <option value="earrings">Earrings</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Budget Range</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select budget range</option>
                        <option value="500-1000">$500 - $1,000</option>
                        <option value="1000-2500">$1,000 - $2,500</option>
                        <option value="2500-5000">$2,500 - $5,000</option>
                        <option value="5000+">$5,000+</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2">Describe Your Vision</label>
                    <textarea rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Tell us about your dream piece..." required></textarea>
                </div>
                
                <x-button type="submit" variant="primary" size="md" fullWidth>
                    Submit Design Request
                </x-button>
            </form>
        </div>
    </div>
</div>
@endsection