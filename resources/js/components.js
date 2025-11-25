// Navigation and UI Components JavaScript
document.addEventListener("DOMContentLoaded", () => {
    // Toggle search bar visibility
    const searchToggle = document.querySelector(".search-toggle");
    const searchBarContainer = document.querySelector(".search-bar-container");
    
    if (searchToggle && searchBarContainer) {
        searchToggle.addEventListener("click", (e) => {
            e.preventDefault();
            searchBarContainer.classList.toggle("active");
            
            // Focus on search input when opened
            if (searchBarContainer.classList.contains("active")) {
                const searchInput = searchBarContainer.querySelector(".search-input");
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        });

        // Close search bar when clicking outside
        document.addEventListener("click", (event) => {
            if (!event.target.closest(".search-toggle") && 
                !event.target.closest(".search-bar-container")) {
                searchBarContainer.classList.remove("active");
            }
        });

        // Close search bar on escape key
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                searchBarContainer.classList.remove("active");
            }
        });
    }

    // Handle mobile menu toggle
    const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener("click", () => {
            const navLinks = document.querySelector(".nav-links");
            if (navLinks) {
                navLinks.classList.toggle("mobile-active");
                mobileMenuToggle.classList.toggle("active");
            }
        });
    }

    // User menu dropdown functionality
    const userMenuToggle = document.querySelector(".user-menu-toggle");
    const userMenuDropdown = document.querySelector(".user-menu-dropdown");
    
    if (userMenuToggle && userMenuDropdown) {
        // Close dropdown when clicking outside
        document.addEventListener("click", (event) => {
            if (!event.target.closest(".user-menu-container")) {
                userMenuDropdown.style.opacity = "0";
                userMenuDropdown.style.visibility = "hidden";
                userMenuDropdown.style.transform = "translateY(-10px)";
            }
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Update cart count dynamically
    function updateCartCount() {
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.count || 0;
                    cartBadge.style.display = data.count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
    }

    // Update cart count on page load
    updateCartCount();

    // Listen for cart updates
    window.addEventListener('cart-updated', updateCartCount);

    // Add loading states to buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                submitBtn.disabled = true;
                
                // Reset button after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });

    // Add hover effects to category cards
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Add animation classes when elements come into view
    const animateOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.category-card, .hero-content, .footer-section').forEach(el => {
        animateOnScroll.observe(el);
    });
});

// Utility functions
window.showNotification = function(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="notification-close">×</button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
};

// Add to cart functionality
window.addToCart = function(productId, quantity = 1) {
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Product added to cart!');
            window.dispatchEvent(new Event('cart-updated'));
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
};