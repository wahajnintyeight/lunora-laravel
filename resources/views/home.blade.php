@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    @include('components.hero-search')

    <!-- Categories Section -->
    @include('components.categories', ['categories' => $mainCategories ?? null])

    <!-- Featured Products Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Products</h2>
                <p class="text-lg text-gray-600">Handpicked pieces from our premium collection</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12 items-stretch">
                @forelse($featuredProducts ?? [] as $product)
                    <x-product-card 
                        :product="$product"
                        :showRating="false"
                        badgeText="Featured"
                        badgeColor="gold"
                        imageHeight="h-64"
                    />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No featured products available</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center">
                <a href="{{ route('shop.index') }}" class="inline-block bg-maroon-950 text-white px-8 py-3 rounded-lg font-semibold hover:bg-maroon-900 transition-colors duration-200">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    @if(isset($newArrivals) && $newArrivals->count() > 0)
    <!-- New Arrivals Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">New Arrivals</h2>
                <p class="text-lg text-gray-600">Latest additions to our collection</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                @foreach($newArrivals as $product)
                    <x-product-card 
                        :product="$product"
                        :showRating="false"
                        badgeText="New Arrival"
                        badgeColor="green"
                        imageHeight="h-64"
                    />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h2 class="newsletter-title">Stay Updated</h2>
                <p class="newsletter-subtitle">Get the latest updates on new arrivals and exclusive offers</p>
                
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                    @csrf
                    @honeypot
                    <div class="newsletter-input-group">
                        <input type="email" name="email" class="newsletter-input" 
                               placeholder="Enter your email address" required>
                        <button type="submit" class="newsletter-submit">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
/* Featured Products Section */
.featured-products-section {
    padding: 80px 0;
    background: white;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.product-image {
    height: 250px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.product-image .placeholder-image {
    font-size: 48px;
    color: #666;
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    background: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: #1a1a1a;
    color: white;
    transform: scale(1.1);
}

.product-info {
    padding: 20px;
    text-align: center;
}

.product-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #1a1a1a;
}

.product-price {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 15px;
}

.add-to-cart-btn {
    background: #1a1a1a;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
}

.add-to-cart-btn:hover {
    background: #333;
    transform: translateY(-2px);
}

/* Newsletter Section */
.newsletter-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
}

.newsletter-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.newsletter-title {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
}

.newsletter-subtitle {
    font-size: 18px;
    margin-bottom: 40px;
    opacity: 0.9;
}

.newsletter-form {
    max-width: 500px;
    margin: 0 auto;
}

.newsletter-input-group {
    display: flex;
    background: white;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.newsletter-input {
    flex: 1;
    padding: 18px 25px;
    border: none;
    font-size: 16px;
    color: #333;
}

.newsletter-input::placeholder {
    color: #999;
}

.newsletter-submit {
    background: #1a1a1a;
    color: white;
    border: none;
    padding: 18px 30px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease;
}

.newsletter-submit:hover {
    background: #333;
}

/* Responsive Design */
@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .newsletter-input-group {
        flex-direction: column;
        border-radius: 12px;
    }
    
    .newsletter-submit {
        border-radius: 0 0 12px 12px;
    }
    
    .newsletter-title {
        font-size: 28px;
    }
    
    .newsletter-subtitle {
        font-size: 16px;
    }
}
</style>
@endpush

@push('styles')
<style>
/* Featured Products Section */
.featured-products-section,
.new-arrivals-section {
    padding: 80px 0;
    background: white;
}

.new-arrivals-section {
    background: #f8f9fa;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.product-image {
    height: 250px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-img {
    transform: scale(1.05);
}

.product-image .placeholder-image {
    font-size: 48px;
    color: #666;
}

.product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #f59e0b 0%, #dc2626 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.new-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    background: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: #666;
}

.action-btn:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #dc2626 100%);
    color: white;
    transform: scale(1.1);
}

.product-info {
    padding: 20px;
    text-align: center;
}

.product-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #1a1a1a;
}

.product-category {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-price {
    font-size: 20px;
    font-weight: 700;
    color: #dc2626;
    margin-bottom: 15px;
}

.add-to-cart-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #dc2626 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
}

.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #d97706 0%, #b91c1c 100%);
    transform: translateY(-2px);
}

/* Newsletter Section */
.newsletter-section {
    background: linear-gradient(135deg, #f59e0b 0%, #dc2626 50%, #991b1b 100%);
    color: white;
    padding: 80px 0;
}

.newsletter-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.newsletter-title {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
}

.newsletter-subtitle {
    font-size: 18px;
    margin-bottom: 40px;
    opacity: 0.9;
}

.newsletter-form {
    max-width: 500px;
    margin: 0 auto;
}

.newsletter-input-group {
    display: flex;
    background: white;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.newsletter-input {
    flex: 1;
    padding: 18px 25px;
    border: none;
    font-size: 16px;
    color: #333;
}

.newsletter-input::placeholder {
    color: #999;
}

.newsletter-submit {
    background: #1a1a1a;
    color: white;
    border: none;
    padding: 18px 30px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.newsletter-submit:hover {
    background: #333;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .newsletter-input-group {
        flex-direction: column;
        border-radius: 12px;
    }
    
    .newsletter-submit {
        border-radius: 0 0 12px 12px;
    }
    
    .newsletter-title {
        font-size: 28px;
    }
    
    .newsletter-subtitle {
        font-size: 16px;
    }
    
    .featured-products-section,
    .new-arrivals-section {
        padding: 60px 0;
    }
}
</style>
@endpush