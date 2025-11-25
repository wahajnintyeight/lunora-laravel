@extends('layouts.app')

@section('title', 'Checkout - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-4 sm:py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Progress Indicator - Mobile Optimized -->
        <div class="mb-6 sm:mb-8" data-checkout-progress>
            <div class="flex items-center justify-center overflow-x-auto">
                <div class="flex items-center min-w-max px-4">
                    <div class="flex items-center text-[#f59e0b]">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#f59e0b] text-white rounded-full flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm sm:text-base font-medium">Cart</span>
                    </div>
                    <div class="w-8 sm:w-16 h-0.5 bg-[#f59e0b] mx-2 sm:mx-4"></div>
                    <div class="flex items-center text-[#f59e0b]">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#f59e0b] text-white rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm sm:text-base font-medium">Checkout</span>
                    </div>
                    <div class="w-8 sm:w-16 h-0.5 bg-gray-300 mx-2 sm:mx-4"></div>
                    <div class="flex items-center text-gray-400">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm sm:text-base font-medium">Confirmation</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" data-checkout-container>
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="lg:col-span-2 space-y-4 sm:space-y-6 lg:space-y-8 order-2 lg:order-1" data-checkout-form data-mobile-validation>
            @csrf
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6" data-form-section data-section-title="Customer Information">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6 responsive-text-xl">Customer Information</h2>
                    <div data-section-content>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="sm:col-span-2 sm:col-span-1">
                            <label for="email" class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   required
                                   class="form-input w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="sm:col-span-2 sm:col-span-1">
                            <label for="phone" class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   required
                                   placeholder="+92 300 1234567"
                                   class="form-input w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md p-6" data-form-section data-section-title="Shipping Address">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Shipping Address</h2>
                    <div data-section-content>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="shipping_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="shipping_first_name" 
                                   name="shipping_first_name" 
                                   value="{{ old('shipping_first_name') }}"
                                   required
                                   class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_first_name') border-red-500 @enderror">
                            @error('shipping_first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="shipping_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="shipping_last_name" 
                                   name="shipping_last_name" 
                                   value="{{ old('shipping_last_name') }}"
                                   required
                                   class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_last_name') border-red-500 @enderror">
                            @error('shipping_last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="shipping_address_line_1" class="block text-sm font-medium text-gray-700 mb-2">
                            Address Line 1 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="shipping_address_line_1" 
                               name="shipping_address_line_1" 
                               value="{{ old('shipping_address_line_1') }}"
                               required
                               placeholder="Street address, P.O. box, company name, c/o"
                               class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_address_line_1') border-red-500 @enderror">
                        @error('shipping_address_line_1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-6">
                        <label for="shipping_address_line_2" class="block text-sm font-medium text-gray-700 mb-2">
                            Address Line 2 (Optional)
                        </label>
                        <input type="text" 
                               id="shipping_address_line_2" 
                               name="shipping_address_line_2" 
                               value="{{ old('shipping_address_line_2') }}"
                               placeholder="Apartment, suite, unit, building, floor, etc."
                               class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_address_line_2') border-red-500 @enderror">
                        @error('shipping_address_line_2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="shipping_city" 
                                   name="shipping_city" 
                                   value="{{ old('shipping_city') }}"
                                   required
                                   class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-2">
                                State/Province <span class="text-red-500">*</span>
                            </label>
                            <select id="shipping_state" 
                                    name="shipping_state" 
                                    required
                                    class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_state') border-red-500 @enderror">
                                <option value="">Select State</option>
                                <option value="Punjab" {{ old('shipping_state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                                <option value="Sindh" {{ old('shipping_state') == 'Sindh' ? 'selected' : '' }}>Sindh</option>
                                <option value="Khyber Pakhtunkhwa" {{ old('shipping_state') == 'Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
                                <option value="Balochistan" {{ old('shipping_state') == 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
                                <option value="Gilgit-Baltistan" {{ old('shipping_state') == 'Gilgit-Baltistan' ? 'selected' : '' }}>Gilgit-Baltistan</option>
                                <option value="Azad Kashmir" {{ old('shipping_state') == 'Azad Kashmir' ? 'selected' : '' }}>Azad Kashmir</option>
                                <option value="Islamabad Capital Territory" {{ old('shipping_state') == 'Islamabad Capital Territory' ? 'selected' : '' }}>Islamabad Capital Territory</option>
                            </select>
                            @error('shipping_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Postal Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="shipping_postal_code" 
                                   name="shipping_postal_code" 
                                   value="{{ old('shipping_postal_code') }}"
                                   required
                                   class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_postal_code') border-red-500 @enderror">
                            @error('shipping_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <select id="shipping_country" 
                                name="shipping_country" 
                                required
                                class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('shipping_country') border-red-500 @enderror">
                            <option value="Pakistan" {{ old('shipping_country', 'Pakistan') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                        </select>
                        @error('shipping_country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-lg shadow-md p-6" data-form-section data-section-title="Billing Address">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Billing Address</h2>
                    <div data-section-content>
                    
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   id="billing_same_as_shipping" 
                                   name="billing_same_as_shipping" 
                                   value="1"
                                   {{ old('billing_same_as_shipping', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#f59e0b] border-gray-300 rounded focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700">Same as shipping address</span>
                        </label>
                    </div>
                    
                    <div id="billing-address-fields" class="space-y-6" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="billing_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="billing_first_name" 
                                       name="billing_first_name" 
                                       value="{{ old('billing_first_name') }}"
                                       class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_first_name') border-red-500 @enderror">
                                @error('billing_first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="billing_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="billing_last_name" 
                                       name="billing_last_name" 
                                       value="{{ old('billing_last_name') }}"
                                       class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_last_name') border-red-500 @enderror">
                                @error('billing_last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="billing_address_line_1" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 1 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="billing_address_line_1" 
                                   name="billing_address_line_1" 
                                   value="{{ old('billing_address_line_1') }}"
                                   placeholder="Street address, P.O. box, company name, c/o"
                                   class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_address_line_1') border-red-500 @enderror">
                            @error('billing_address_line_1')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_address_line_2" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 2 (Optional)
                            </label>
                            <input type="text" 
                                   id="billing_address_line_2" 
                                   name="billing_address_line_2" 
                                   value="{{ old('billing_address_line_2') }}"
                                   placeholder="Apartment, suite, unit, building, floor, etc."
                                   class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_address_line_2') border-red-500 @enderror">
                            @error('billing_address_line_2')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="billing_city" 
                                       name="billing_city" 
                                       value="{{ old('billing_city') }}"
                                       class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_city') border-red-500 @enderror">
                                @error('billing_city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State/Province <span class="text-red-500">*</span>
                                </label>
                                <select id="billing_state" 
                                        name="billing_state" 
                                        class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_state') border-red-500 @enderror">
                                    <option value="">Select State</option>
                                    <option value="Punjab" {{ old('billing_state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                                    <option value="Sindh" {{ old('billing_state') == 'Sindh' ? 'selected' : '' }}>Sindh</option>
                                    <option value="Khyber Pakhtunkhwa" {{ old('billing_state') == 'Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option>
                                    <option value="Balochistan" {{ old('billing_state') == 'Balochistan' ? 'selected' : '' }}>Balochistan</option>
                                    <option value="Gilgit-Baltistan" {{ old('billing_state') == 'Gilgit-Baltistan' ? 'selected' : '' }}>Gilgit-Baltistan</option>
                                    <option value="Azad Kashmir" {{ old('billing_state') == 'Azad Kashmir' ? 'selected' : '' }}>Azad Kashmir</option>
                                    <option value="Islamabad Capital Territory" {{ old('billing_state') == 'Islamabad Capital Territory' ? 'selected' : '' }}>Islamabad Capital Territory</option>
                                </select>
                                @error('billing_state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Postal Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="billing_postal_code" 
                                       name="billing_postal_code" 
                                       value="{{ old('billing_postal_code') }}"
                                       class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_postal_code') border-red-500 @enderror">
                                @error('billing_postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select id="billing_country" 
                                    name="billing_country" 
                                    class="w-full min-h-[44px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('billing_country') border-red-500 @enderror">
                                <option value="Pakistan" {{ old('billing_country', 'Pakistan') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                            </select>
                            @error('billing_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white rounded-lg shadow-md p-6" data-form-section data-section-title="Order Notes">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Notes (Optional)</h2>
                    <div data-section-content>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Special instructions for your order
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4"
                                  placeholder="Any special instructions for delivery, gift wrapping, or customization..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="bg-white rounded-lg shadow-md p-6" data-form-section data-section-title="Terms & Conditions">
                    <div data-section-content>
                    <div class="flex items-start">
                        <input type="checkbox" 
                               id="terms_accepted" 
                               name="terms_accepted" 
                               value="1"
                               required
                               class="w-4 h-4 text-[#f59e0b] border-gray-300 rounded focus:ring-emerald-500 mt-1 @error('terms_accepted') border-red-500 @enderror">
                        <label for="terms_accepted" class="ml-3 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-[#f59e0b] hover:text-emerald-700 underline">Terms and Conditions</a> 
                            and <a href="#" class="text-[#f59e0b] hover:text-emerald-700 underline">Privacy Policy</a> <span class="text-red-500">*</span>
                        </label>
                    </div>
                    @error('terms_accepted')
                        <p class="mt-1 text-sm text-red-600 ml-7">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" 
                            class="w-full bg-[#f59e0b] text-white py-4 px-6 rounded-lg font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-base transition-colors duration-200"
                            data-checkout-submit>
                        Complete Order
                    </button>
                </div>
            </form>

            <!-- Order Summary -->
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 lg:sticky lg:top-4" data-order-summary>
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h2>
                    <div data-summary-content>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cart->items as $item)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 relative">
                                    @if($item->product->images->count() > 0)
                                        <img src="{{ $item->product->images->first()->image_path }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <span class="absolute -top-2 -right-2 bg-[#f59e0b] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</h4>
                                    
                                    @if($item->variant)
                                        <div class="mt-1">
                                            @foreach($item->variant->options_json ?? [] as $optionName => $optionValue)
                                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded mr-1">
                                                    {{ $optionName }}: {{ $optionValue }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($item->customizations)
                                        <div class="mt-1 text-xs text-gray-600">
                                            @if(isset($item->customizations['engraving']) && $item->customizations['engraving'])
                                                <p><strong>Engraving:</strong> {{ $item->customizations['engraving'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <p class="text-sm font-medium text-gray-900 mt-1">
                                        PKR {{ number_format(($item->unit_price_pkr * $item->quantity) / 100, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Coupon Display -->
                    @if($cart->coupon_code && $cart->discount_pkr > 0)
                        <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-green-800">{{ $cart->coupon_code }}</span>
                                <span class="text-sm font-medium text-green-800">-{{ $totals['formatted']['discount'] }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Totals -->
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>{{ $totals['formatted']['subtotal'] }}</span>
                        </div>
                        
                        @if($cart->coupon_code && $cart->discount_pkr > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>-{{ $totals['formatted']['discount'] }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span id="shipping-cost">{{ $totals['formatted']['shipping'] }}</span>
                        </div>
                        
                        <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-3">
                            <span>Total</span>
                            <span id="final-total">{{ $totals['formatted']['total'] }}</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="mt-6">
                        <button type="submit" 
                                id="place-order-btn"
                                class="w-full bg-[#f59e0b] text-white py-3 px-6 rounded-lg font-semibold hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="btn-text">Place Order</span>
                            <span id="btn-loading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-4 text-center">
                        <div class="flex items-center justify-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Secure Checkout
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle billing address toggle
    const billingCheckbox = document.getElementById('billing_same_as_shipping');
    const billingFields = document.getElementById('billing-address-fields');
    
    function toggleBillingFields() {
        if (billingCheckbox.checked) {
            billingFields.style.display = 'none';
            // Clear billing field requirements
            billingFields.querySelectorAll('input, select').forEach(field => {
                field.removeAttribute('required');
            });
        } else {
            billingFields.style.display = 'block';
            // Add billing field requirements
            billingFields.querySelectorAll('input[name*="billing_first_name"], input[name*="billing_last_name"], input[name*="billing_address_line_1"], input[name*="billing_city"], select[name*="billing_state"], input[name*="billing_postal_code"], select[name*="billing_country"]').forEach(field => {
                field.setAttribute('required', 'required');
            });
        }
    }
    
    billingCheckbox.addEventListener('change', toggleBillingFields);
    toggleBillingFields(); // Initialize on page load
    
    // Handle form submission
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('place-order-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');
    
    // Mark form as handled to prevent duplicate handlers from app.js
    form.setAttribute('data-loading-handled', 'true');
    
    form.addEventListener('submit', function(e) {
        // Prevent duplicate submissions
        if (submitBtn.hasAttribute('data-loading')) return;
        
        // Mark button as loading
        submitBtn.setAttribute('data-loading', 'true');
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        
        // Re-enable button after 10 seconds as fallback
        setTimeout(() => {
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
            submitBtn.removeAttribute('data-loading');
        }, 10000);
    });
    
    // Auto-fill billing address from shipping if checkbox is checked
    function copyShippingToBilling() {
        if (billingCheckbox.checked) return;
        
        const shippingFields = [
            'first_name', 'last_name', 'address_line_1', 'address_line_2',
            'city', 'state', 'postal_code', 'country'
        ];
        
        shippingFields.forEach(field => {
            const shippingField = document.getElementById(`shipping_${field}`);
            const billingField = document.getElementById(`billing_${field}`);
            
            if (shippingField && billingField && shippingField.value) {
                billingField.value = shippingField.value;
            }
        });
    }
    
    // Add copy button for convenience
    const billingHeader = document.querySelector('#billing-address-fields').previousElementSibling;
    const copyButton = document.createElement('button');
    copyButton.type = 'button';
    copyButton.className = 'text-sm text-[#f59e0b] hover:text-emerald-700 underline ml-4';
    copyButton.textContent = 'Copy from shipping';
    copyButton.onclick = copyShippingToBilling;
    
    // Insert copy button after the checkbox
    billingCheckbox.parentElement.parentElement.appendChild(copyButton);
});
</script>
@endsection