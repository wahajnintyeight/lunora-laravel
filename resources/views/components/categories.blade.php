@php
    $categories = $categories ?? \App\Models\Category::with(['products' => function($query) {
        $query->where('is_active', true)->where('stock', '>', 0);
    }])
    ->whereNull('parent_id')
    ->where('is_active', true)
    ->withCount(['products' => function($query) {
        $query->where('is_active', true)->where('stock', '>', 0);
    }])
    ->having('products_count', '>', 0)
    ->orderBy('sort_order')
    ->limit(6)
    ->get();

    $categoryIcons = [
        'rings' => 'fas fa-ring',
        'necklaces' => 'fas fa-gem',
        'bracelets' => 'fas fa-bracelet',
        'earrings' => 'fas fa-water',
        'watches' => 'fas fa-clock',
        'pendants' => 'fas fa-star',
        'chains' => 'fas fa-link',
        'sets' => 'fas fa-layer-group',
    ];
@endphp

<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <p class="section-subtitle">Explore our finest jewelry collections</p>
        
        <div class="categories-grid">
            @forelse($categories as $category)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-gold-500 hover:border-gold-400 group">
                    <div class="category-image">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="category-img">
                        @else
                            <div class="placeholder-image">
                                <i class="{{ $categoryIcons[strtolower($category->slug)] ?? 'fas fa-gem' }}"></i>
                            </div>
                        @endif
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">{{ $category->name }}</h3>
                        <p class="category-count">{{ $category->products_count }}+ Items</p>
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="inline-block bg-maroon-950 text-white px-6 py-2 rounded-lg font-semibold hover:bg-maroon-900 border-2 border-gold-500 hover:border-gold-400 transition-all duration-200">
                            Browse <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Fallback categories if no data -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-gold-500 hover:border-gold-400 group">
                    <div class="category-image">
                        <div class="placeholder-image">
                            <i class="fas fa-ring"></i>
                        </div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Rings</h3>
                        <p class="category-count">Coming Soon</p>
                        <a href="{{ route('shop.index') }}" class="inline-block bg-maroon-950 text-white px-6 py-2 rounded-lg font-semibold hover:bg-maroon-900 border-2 border-gold-500 hover:border-gold-400 transition-all duration-200">
                            Browse <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-gold-500 hover:border-gold-400 group">
                    <div class="category-image">
                        <div class="placeholder-image">
                            <i class="fas fa-gem"></i>
                        </div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Necklaces</h3>
                        <p class="category-count">Coming Soon</p>
                        <a href="{{ route('shop.index') }}" class="inline-block bg-maroon-950 text-white px-6 py-2 rounded-lg font-semibold hover:bg-maroon-900 border-2 border-gold-500 hover:border-gold-400 transition-all duration-200">
                            Browse <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endforelse
            
            <!-- Custom Orders - Always show -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-gold-500 hover:border-gold-400 group">
                <div class="category-image">
                    <div class="placeholder-image">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                </div>
                <div class="category-info">
                    <h3 class="category-name">Custom Orders</h3>
                    <p class="category-count">Bespoke Design</p>
                    <a href="{{ route('custom.index') }}" class="inline-block bg-maroon-950 text-white px-6 py-2 rounded-lg font-semibold hover:bg-maroon-900 border-2 border-gold-500 hover:border-gold-400 transition-all duration-200">
                        Get Started <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>