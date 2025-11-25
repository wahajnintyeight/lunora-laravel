@props([
    'product',
    'variant' => 'default',
    'showPrice' => true,
    'showRating' => false,
    'showBadge' => true,
    'badgeText' => null,
    'badgeColor' => 'gold',
    'hoverEffect' => true,
    'imageHeight' => 'h-64',
    'class' => '',
])

@php
    $badgeColors = [
        'gold' => 'bg-gold-500 text-maroon-950',
        'maroon' => 'bg-maroon-950 text-white',
        'red' => 'bg-red-500 text-white',
        'green' => 'bg-green-500 text-white',
    ];
    
    $badgeClass = $badgeColors[$badgeColor] ?? $badgeColors['gold'];
    
    // Determine badge text
    $displayBadge = $badgeText;
    if (!$displayBadge && $showBadge) {
        if ($product->is_featured) {
            $displayBadge = 'Featured';
        } elseif (!$product->isInStock()) {
            $displayBadge = 'Out of Stock';
        }
    }
@endphp

<div {{ $attributes->merge(['class' => "group relative bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 border-2 border-gold-500 hover:border-gold-400 $class" . ($hoverEffect ? ' hover:shadow-xl hover:scale-105' : '')]) }}>
    
    <!-- Image Container -->
    <div class="relative {{ $imageHeight }} bg-gray-100 overflow-hidden">
        <!-- Product Image -->
        <img 
            src="{{ $product->featured_image ?? asset('images/placeholder.jpg') }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover transition-transform duration-300 {{ $hoverEffect ? 'group-hover:scale-110' : '' }}"
            loading="lazy"
        >
        
        <!-- Badge -->
        @if ($displayBadge)
            <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                {{ $displayBadge }}
            </div>
        @endif
        
        <!-- Overlay Actions (appears on hover) -->
        @if ($hoverEffect)
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                <a 
                    href="{{ route('products.show', $product) }}"
                    class="bg-white text-maroon-950 px-4 py-2 rounded-lg font-semibold hover:bg-gold-500 transition-colors duration-200 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                </a>
                <button 
                    type="button"
                    onclick="addToCart({{ $product->id }})"
                    class="bg-gold-500 text-maroon-950 px-4 py-2 rounded-lg font-semibold hover:bg-gold-600 transition-colors duration-200 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add
                </button>
            </div>
        @endif
        
        <!-- Stock Status Indicator -->
        @if (!$product->isInStock())
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="text-white font-bold text-lg">Out of Stock</span>
            </div>
        @endif
    </div>
    
    <!-- Content -->
    <div class="p-4">
        <!-- Category -->
        <p class="text-xs font-semibold text-gold-500 uppercase tracking-wider mb-1">
            {{ $product->category->name ?? 'Jewelry' }}
        </p>
        
        <!-- Product Name -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-gold-500 transition-colors">
            <a href="{{ route('products.show', $product) }}" class="hover:underline">
                {{ $product->name }}
            </a>
        </h3>
        
        <!-- Rating (if enabled) -->
        @if ($showRating)
            <div class="flex items-center gap-1 mb-2">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 {{ $i < 4 ? 'text-gold-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
                <span class="text-xs text-gray-500 ml-1">(24 reviews)</span>
            </div>
        @endif
        
        <!-- Description (truncated) -->
        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
            {{ Str::limit($product->description, 80) }}
        </p>
        
        <!-- Price Section -->
        @if ($showPrice)
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-maroon-950">
                        PKR {{ number_format($product->price_pkr / 100, 0) }}
                    </span>
                    @if ($product->compare_at_price_pkr && $product->compare_at_price_pkr > $product->price_pkr)
                        <span class="text-sm text-gray-500 line-through">
                            PKR {{ number_format($product->compare_at_price_pkr / 100, 0) }}
                        </span>
                        @php
                            $discount = round((1 - $product->price_pkr / $product->compare_at_price_pkr) * 100);
                        @endphp
                        <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-1 rounded">
                            -{{ $discount }}%
                        </span>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Stock Status -->
        <div class="flex items-center justify-between text-xs mb-3">
            @if ($product->isInStock())
                <span class="text-green-600 font-semibold flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    In Stock
                </span>
            @else
                <span class="text-red-600 font-semibold">Out of Stock</span>
            @endif
            <span class="text-gray-500">SKU: {{ $product->sku }}</span>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2">
            <a 
                href="{{ route('products.show', $product) }}"
                class="flex-1 bg-maroon-950 text-white py-2 rounded-lg font-semibold hover:bg-maroon-900 transition-colors duration-200 text-center text-sm"
            >
                View Details
            </a>
            <button 
                type="button"
                onclick="addToCart({{ $product->id }})"
                {{ !$product->isInStock() ? 'disabled' : '' }}
                class="flex-1 bg-gold-500 text-maroon-950 py-2 rounded-lg font-semibold hover:bg-gold-600 transition-colors duration-200 text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
                Add to Cart
            </button>
        </div>
    </div>
</div>
