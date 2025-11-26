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

<section class="modern-categories-section">
    <div class="modern-categories-container">
        <div class="modern-categories-header">
            <h2 class="modern-categories-title">Shop by Category</h2>
            <p class="modern-categories-subtitle">Discover our exquisite collections, curated for every occasion</p>
        </div>
        
        <div class="modern-categories-grid">
            @forelse($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="modern-category-card group">
                    <div class="modern-category-image-wrapper">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="modern-category-image">
                            <div class="modern-category-overlay"></div>
                        @else
                            <div class="modern-category-placeholder">
                                <div class="modern-category-icon-wrapper">
                                    <i class="{{ $categoryIcons[strtolower($category->slug)] ?? 'fas fa-gem' }} modern-category-icon"></i>
                                </div>
                            </div>
                            <div class="modern-category-overlay"></div>
                        @endif
                        <div class="modern-category-badge">
                            <span>{{ $category->products_count }}+</span>
                        </div>
                    </div>
                    <div class="modern-category-content">
                        <h3 class="modern-category-name">{{ $category->name }}</h3>
                        <p class="modern-category-count">{{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}</p>
                        <div class="modern-category-cta">
                            <span>Shop Now</span>
                            <i class="fas fa-arrow-right modern-category-arrow"></i>
                        </div>
                    </div>
                </a>
            @empty
                <!-- Fallback categories if no data -->
                <a href="{{ route('shop.index') }}" class="modern-category-card group">
                    <div class="modern-category-image-wrapper">
                        <div class="modern-category-placeholder">
                            <div class="modern-category-icon-wrapper">
                                <i class="fas fa-ring modern-category-icon"></i>
                            </div>
                        </div>
                        <div class="modern-category-overlay"></div>
                        <div class="modern-category-badge">
                            <span>New</span>
                        </div>
                    </div>
                    <div class="modern-category-content">
                        <h3 class="modern-category-name">Rings</h3>
                        <p class="modern-category-count">Coming Soon</p>
                        <div class="modern-category-cta">
                            <span>Explore</span>
                            <i class="fas fa-arrow-right modern-category-arrow"></i>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('shop.index') }}" class="modern-category-card group">
                    <div class="modern-category-image-wrapper">
                        <div class="modern-category-placeholder">
                            <div class="modern-category-icon-wrapper">
                                <i class="fas fa-gem modern-category-icon"></i>
                            </div>
                        </div>
                        <div class="modern-category-overlay"></div>
                        <div class="modern-category-badge">
                            <span>New</span>
                        </div>
                    </div>
                    <div class="modern-category-content">
                        <h3 class="modern-category-name">Necklaces</h3>
                        <p class="modern-category-count">Coming Soon</p>
                        <div class="modern-category-cta">
                            <span>Explore</span>
                            <i class="fas fa-arrow-right modern-category-arrow"></i>
                        </div>
                    </div>
                </a>
            @endforelse
            
            <!-- Custom Orders - Always show -->
            <a href="{{ route('custom.index') }}" class="modern-category-card group modern-category-card-special">
                <div class="modern-category-image-wrapper">
                    <div class="modern-category-placeholder modern-category-placeholder-special">
                        <div class="modern-category-icon-wrapper">
                            <i class="fas fa-wand-magic-sparkles modern-category-icon"></i>
                        </div>
                    </div>
                    <div class="modern-category-overlay"></div>
                    <div class="modern-category-badge modern-category-badge-special">
                        <span>Custom</span>
                    </div>
                </div>
                <div class="modern-category-content">
                    <h3 class="modern-category-name">Custom Orders</h3>
                    <p class="modern-category-count">Bespoke Design</p>
                    <div class="modern-category-cta">
                        <span>Get Started</span>
                        <i class="fas fa-arrow-right modern-category-arrow"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>