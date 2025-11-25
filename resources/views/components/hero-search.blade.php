<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Luxury Jewelry Crafted for You</h1>
        <p class="hero-subtitle">Discover timeless elegance with our curated collection of premium jewelry</p>
        
        <form action="{{ route('search') }}" method="GET" class="hero-search-form">
            <div class="search-wrapper">
                <input type="text" name="q" class="search-input-large" 
                       placeholder="What are you looking for? Rings, necklaces, bracelets..." 
                       value="{{ request('q') }}" required>
                <button type="submit" class="search-submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
        
        <div class="hero-cta">
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse Collection</a>
            <a href="{{ route('collections.index') }}" class="btn btn-secondary">View New Arrivals</a>
        </div>
    </div>
</section>