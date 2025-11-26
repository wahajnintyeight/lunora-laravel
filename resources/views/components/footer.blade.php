<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4 class="footer-title">About Lunora</h4>
            <p class="footer-text">Crafting timeless jewelry with premium materials and exceptional craftsmanship since 2020.</p>
        </div>
        
        <div class="footer-section">
            <h4 class="footer-title">Quick Links</h4>
            <ul class="footer-links">
                <li><a href="{{ route('shop.index') }}">Shop</a></li>
                @if($hasPage('about'))
                    <li><a href="{{ route('page.show', $getPage('about')->slug) }}">{{ $getPage('about')->title }}</a></li>
                @else
                    <li><a href="{{ route('about') }}">About Us</a></li>
                @endif
                @if($hasPage('blog'))
                    <li><a href="{{ route('page.show', $getPage('blog')->slug) }}">{{ $getPage('blog')->title }}</a></li>
                @else
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                @endif
                @if($hasPage('contact'))
                    <li><a href="{{ route('page.show', $getPage('contact')->slug) }}">{{ $getPage('contact')->title }}</a></li>
                @else
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                @endif
            </ul>
        </div>
        
        <div class="footer-section">
            <h4 class="footer-title">Customer Service</h4>
            <ul class="footer-links">
                @if($hasPage('faq'))
                    <li><a href="{{ route('page.show', $getPage('faq')->slug) }}">{{ $getPage('faq')->title }}</a></li>
                @else
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                @endif
                @if($hasPage('shipping'))
                    <li><a href="{{ route('page.show', $getPage('shipping')->slug) }}">{{ $getPage('shipping')->title }}</a></li>
                @else
                    <li><a href="{{ route('shipping') }}">Shipping Info</a></li>
                @endif
                @if($hasPage('returns'))
                    <li><a href="{{ route('page.show', $getPage('returns')->slug) }}">{{ $getPage('returns')->title }}</a></li>
                @else
                    <li><a href="{{ route('returns') }}">Returns Policy</a></li>
                @endif
                @if($hasPage('warranty'))
                    <li><a href="{{ route('page.show', $getPage('warranty')->slug) }}">{{ $getPage('warranty')->title }}</a></li>
                @else
                    <li><a href="{{ route('warranty') }}">Warranty</a></li>
                @endif
            </ul>
        </div>
        
        <div class="footer-section">
            <h4 class="footer-title">Connect With Us</h4>
            <div class="social-links">
                <a href="#" class="social-icon" aria-label="Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="social-icon" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-icon" aria-label="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon" aria-label="Pinterest">
                    <i class="fab fa-pinterest"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Lunora. All rights reserved.</p>
        <div class="footer-payment">
            <i class="fab fa-cc-visa" title="Visa"></i>
            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
            <i class="fab fa-cc-paypal" title="PayPal"></i>
        </div>
    </div>
</footer>