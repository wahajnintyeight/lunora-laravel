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
        'gold' => 'bg-amber-50 text-amber-900 border border-amber-200',
        'maroon' => 'bg-rose-50 text-rose-900 border border-rose-200',
        'red' => 'bg-red-50 text-red-900 border border-red-200',
        'green' => 'bg-emerald-50 text-emerald-900 border border-emerald-200',
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

<div
    {{ $attributes->merge(['class' => "group relative bg-white border-1 border-gray-200 rounded-2xl overflow-hidden transition-all duration-500 flex flex-col h-full $class" . ($hoverEffect ? ' hover:shadow-2xl' : '')]) }}>

    <!-- Image Container with Refined Styling -->
    <div
        class="relative {{ $imageHeight }} bg-gradient-to-br from-slate-50 to-slate-100 overflow-hidden flex items-center justify-center flex-shrink-0">
        <!-- <CHANGE> Improved image styling with better aspect ratio and subtle shadow -->
        <div class="relative w-full h-full">
            <img src="{{ $product->featured_image ?? asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-700 {{ $hoverEffect ? 'group-hover:scale-105' : '' }}"
                loading="lazy">
        </div>

        <!-- <CHANGE> Refined badge positioning with better contrast -->
        @if ($displayBadge)
            <div
                class="absolute top-4 right-4 px-3 py-2 rounded-full text-xs font-semibold tracking-wide {{ $badgeClass }} backdrop-blur-sm">
                {{ $displayBadge }}
            </div>
        @endif

        <!-- <CHANGE> Enhanced overlay with smooth reveal animation - Hidden on mobile -->
        @if ($hoverEffect)
            <div
                class="hidden md:flex absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex-col items-center justify-center gap-3">
                <a href="{{ route('products.show', $product) }}"
                    class="bg-gradient-to-r from-[#881337] to-[#78350f] text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:from-[#9f1239] hover:to-[#92400e] transition-all duration-200 flex items-center gap-2 backdrop-blur-sm shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    View
                </a>
                <button type="button" onclick="addToCart({{ $product->id }})"
                    class="bg-[#f59e0b] text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-[#d97706] transition-all duration-200 flex items-center gap-2 backdrop-blur-sm shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        @endif

        <!-- <CHANGE> Refined out of stock overlay -->
        @if (!$product->isInStock())
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center">
                <span class="text-white font-semibold text-lg tracking-wide">Out of Stock</span>
            </div>
        @endif
    </div>

    <!-- Content Section with Enhanced Spacing -->
    <div class="p-5 sm:p-6 flex flex-col flex-grow min-h-0">
        <!-- <CHANGE> Improved category styling with better visual hierarchy -->
        <p class="text-xs font-semibold text-amber-700 uppercase tracking-widest mb-2 opacity-80">
            {{ $product->category->name ?? 'Jewelry' }}
        </p>

        <!-- <CHANGE> Enhanced product name with refined typography -->
        <h3
            class="text-lg sm:text-xl font-semibold text-slate-900 mb-3 line-clamp-2 group-hover:text-amber-700 transition-colors duration-300 leading-tight">
            <a href="{{ route('products.show', $product) }}" class="hover:opacity-80 transition-opacity">
                {{ $product->name }}
            </a>
        </h3>

        <!-- <CHANGE> Enhanced rating display with refined styling -->
        @if ($showRating)
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center gap-0.5">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i < 4 ? 'text-amber-500' : 'text-slate-300' }}" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs text-slate-500 font-medium">(24)</span>
            </div>
        @endif

        <!-- <CHANGE> Improved description with better readability -->
        <p class="text-sm text-slate-600 mb-4 line-clamp-2 leading-relaxed">
            {{ Str::limit($product->description, 80) }}
        </p>

        <!-- <CHANGE> Enhanced price section with refined layout -->
        @if ($showPrice)
            <div class="mb-4">
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-2xl sm:text-3xl font-bold text-slate-900">
                        PKR {{ number_format($product->price_pkr / 100, 0) }}
                    </span>
                    @if ($product->compare_at_price_pkr && $product->compare_at_price_pkr > $product->price_pkr)
                        @php
                            $discount = round((1 - $product->price_pkr / $product->compare_at_price_pkr) * 100);
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500 line-through opacity-75">
                                PKR {{ number_format($product->compare_at_price_pkr / 100, 0) }}
                            </span>
                            <span class="text-xs font-bold text-white bg-red-500 px-2.5 py-1 rounded-lg">
                                -{{ $discount }}%
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- <CHANGE> Refined stock status with better visual treatment -->
        <div class="flex items-center gap-2 text-xs mb-5 pb-5 border-b border-slate-200">
            @if ($product->isInStock())
                <span
                    class="text-emerald-700 font-semibold flex items-center gap-1 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    In Stock
                </span>
            @else
                <span class="text-red-700 font-semibold bg-red-50 px-2.5 py-1 rounded-lg">Out of Stock</span>
            @endif
         
        </div>

        <!-- <CHANGE> Enhanced action buttons with better mobile responsiveness -->
        <div class="flex gap-3 mt-auto flex-shrink-0">
            <a href="{{ route('products.show', $product) }}"
                class="flex-1 px-4 py-2.5 bg-gradient-to-r from-[#881337] to-[#78350f] text-white rounded-lg font-semibold text-sm hover:from-[#9f1239] hover:to-[#92400e] transition-all duration-200 text-center shadow-md hover:shadow-lg">
                View Details
            </a>
            <button type="button" onclick="addToCart({{ $product->id }})" :disabled="!$product->isInStock()"
                class="flex-1 px-4 py-2.5 bg-[#f59e0b] text-white rounded-lg font-semibold text-sm hover:bg-[#d97706] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                Add to Cart
            </button>
        </div>
    </div>
</div>
