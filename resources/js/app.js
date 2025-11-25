import './bootstrap';
import { initRealtimeProductSearch } from './shop/search';
import { initCatalogFilter } from './shop/catalog';

// Mobile Menu Controller
class MobileMenuController {
    constructor() {
        this.menuButton = document.querySelector('#mobile-menu-toggle');
        this.menu = document.querySelector('#mobile-menu');
        this.overlay = null;
        this.isOpen = false;

        this.init();
    }

    init() {
        if (!this.menuButton || !this.menu) return;

        this.createOverlay();
        this.bindEvents();
        this.setupAccessibility();
    }

    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden transition-opacity duration-300';
        this.overlay.id = 'mobile-menu-overlay';
        document.body.appendChild(this.overlay);
    }

    bindEvents() {
        // Menu toggle button
        this.menuButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleMenu();
        });

        // Overlay click to close
        this.overlay.addEventListener('click', () => {
            this.closeMenu();
        });

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeMenu();
            }
        });

        // Close menu when clicking on menu links
        this.menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                this.closeMenu();
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024 && this.isOpen) {
                this.closeMenu();
            }
        });
    }

    setupAccessibility() {
        this.menuButton.setAttribute('aria-expanded', 'false');
        this.menu.setAttribute('aria-hidden', 'true');
    }

    toggleMenu() {
        this.isOpen ? this.closeMenu() : this.openMenu();
    }

    openMenu() {
        this.isOpen = true;

        // Show menu and overlay
        this.menu.classList.remove('hidden');
        this.overlay.classList.remove('hidden');

        // Update button state
        this.menuButton.classList.add('mobile-menu-open');
        this.menuButton.setAttribute('aria-expanded', 'true');
        this.menu.setAttribute('aria-hidden', 'false');

        // Prevent body scroll
        document.body.style.overflow = 'hidden';

        // Focus first menu item
        const firstMenuItem = this.menu.querySelector('a, button');
        if (firstMenuItem) {
            setTimeout(() => firstMenuItem.focus(), 100);
        }
    }

    closeMenu() {
        this.isOpen = false;

        // Hide menu and overlay
        this.menu.classList.add('hidden');
        this.overlay.classList.add('hidden');

        // Update button state
        this.menuButton.classList.remove('mobile-menu-open');
        this.menuButton.setAttribute('aria-expanded', 'false');
        this.menu.setAttribute('aria-hidden', 'true');

        // Restore body scroll
        document.body.style.overflow = '';

        // Return focus to menu button
        this.menuButton.focus();
    }
}

// Touch Gesture Handler
class TouchGestureHandler {
    constructor() {
        this.startX = 0;
        this.startY = 0;
        this.threshold = 50; // Minimum distance for swipe

        this.init();
    }

    init() {
        // Add touch event listeners to image galleries
        document.querySelectorAll('[data-touch-gallery]').forEach(gallery => {
            this.setupGalleryGestures(gallery);
        });
    }

    setupGalleryGestures(gallery) {
        let startX = 0;
        let currentIndex = 0;
        const images = gallery.querySelectorAll('img');
        const totalImages = images.length;

        if (totalImages <= 1) return;

        gallery.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });

        gallery.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;

            if (Math.abs(diffX) > this.threshold) {
                if (diffX > 0 && currentIndex < totalImages - 1) {
                    // Swipe left - next image
                    currentIndex++;
                } else if (diffX < 0 && currentIndex > 0) {
                    // Swipe right - previous image
                    currentIndex--;
                }

                this.updateGalleryDisplay(gallery, currentIndex);
            }
        }, { passive: true });
    }

    updateGalleryDisplay(gallery, index) {
        const images = gallery.querySelectorAll('img');
        const indicators = gallery.querySelectorAll('[data-gallery-indicator]');

        images.forEach((img, i) => {
            img.style.display = i === index ? 'block' : 'none';
        });

        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }
}

// Cart Count Updater
class CartCountUpdater {
    constructor() {
        this.init();
    }

    init() {
        // Make updateCartCount globally available
        window.updateCartCount = (count) => {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = count;
                element.style.display = count > 0 ? 'flex' : 'none';
            });
        };
    }
}

// Form Enhancement for Mobile
class MobileFormEnhancer {
    constructor() {
        this.init();
    }

    init() {
        // Prevent zoom on iOS when input is focused
        this.preventIOSZoom();

        // Enhance form validation display
        this.enhanceValidation();

        // Add loading states
        this.addLoadingStates();
    }

    preventIOSZoom() {
        // Add viewport meta tag if not present
        if (!document.querySelector('meta[name="viewport"]')) {
            const viewport = document.createElement('meta');
            viewport.name = 'viewport';
            viewport.content = 'width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no';
            document.head.appendChild(viewport);
        }
    }

    enhanceValidation() {
        // Add better error display for mobile
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('invalid', (e) => {
                e.preventDefault();
                this.showMobileError(field, field.validationMessage);
            });
        });
    }

    showMobileError(field, message) {
        // Remove existing error
        const existingError = field.parentNode.querySelector('.mobile-error');
        if (existingError) {
            existingError.remove();
        }

        // Create error element
        const error = document.createElement('div');
        error.className = 'mobile-error text-sm text-red-600 mt-1 px-3 py-2 bg-red-50 rounded-md border border-red-200';
        error.textContent = message;

        // Insert after field
        field.parentNode.insertBefore(error, field.nextSibling);

        // Remove after 5 seconds
        setTimeout(() => {
            if (error.parentNode) {
                error.remove();
            }
        }, 5000);
    }

    addLoadingStates() {
        // Add loading states to forms (only if not already handled by other scripts)
        document.querySelectorAll('form:not([data-loading-handled])').forEach(form => {
            // Mark form as handled to prevent duplicate listeners
            form.setAttribute('data-loading-handled', 'true');
            
            form.addEventListener('submit', (e) => {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton && !submitButton.hasAttribute('data-loading')) {
                    // Mark button as loading to prevent duplicate spinners
                    submitButton.setAttribute('data-loading', 'true');
                    submitButton.classList.add('loading');
                    submitButton.disabled = true;

                    // Add spinner if not present
                    if (!submitButton.querySelector('.spinner, .animate-spin')) {
                        const spinner = document.createElement('span');
                        spinner.className = 'spinner inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2';
                        submitButton.insertBefore(spinner, submitButton.firstChild);
                    }
                }
            }, { once: true }); // Use once option to prevent duplicate listeners
        });
    }
}

// Smooth Scroll Handler
class SmoothScrollHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const href = anchor.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
}

// User Dropdown Controller
class UserDropdownController {
    constructor() {
        this.toggle = document.querySelector('#hs-dropdown-account');
        this.dropdown = this.toggle ? this.toggle.closest('.hs-dropdown') : null;
        this.menu = this.dropdown ? this.dropdown.querySelector('.hs-dropdown-menu') : null;
        this.isOpen = false;

        console.log('UserDropdownController initialized', {
            dropdown: this.dropdown,
            toggle: this.toggle,
            menu: this.menu
        });

        this.init();
    }

    init() {
        if (!this.dropdown || !this.toggle || !this.menu) {
            console.warn('UserDropdownController: Missing required elements');
            return;
        }

        this.bindEvents();
        this.setupAccessibility();
        console.log('UserDropdownController: Events bound successfully');
    }

    bindEvents() {
        // Toggle dropdown on click
        this.toggle.addEventListener('click', (e) => {
            console.log('Dropdown toggle clicked');
            e.preventDefault();
            e.stopPropagation();
            this.toggleDropdown();
        });

        // Also handle touch events for mobile
        this.toggle.addEventListener('touchend', (e) => {
            console.log('Dropdown toggle touched');
            e.preventDefault();
            e.stopPropagation();
            this.toggleDropdown();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.dropdown.contains(e.target)) {
                console.log('Clicking outside dropdown, closing');
                this.closeDropdown();
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                console.log('Escape key pressed, closing dropdown');
                this.closeDropdown();
            }
        });

        // Handle keyboard navigation
        this.menu.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                this.handleKeyboardNavigation(e.key);
            }
        });
    }

    setupAccessibility() {
        this.toggle.setAttribute('aria-expanded', 'false');
        this.menu.setAttribute('aria-hidden', 'true');
        this.menu.setAttribute('role', 'menu');

        // Add role to menu items
        this.menu.querySelectorAll('a, button').forEach(item => {
            item.setAttribute('role', 'menuitem');
        });
    }

    toggleDropdown() {
        console.log('Toggle dropdown called, current state:', this.isOpen);
        this.isOpen ? this.closeDropdown() : this.openDropdown();
    }

    openDropdown() {
        console.log('Opening dropdown');
        this.isOpen = true;

        // Show menu
        this.menu.classList.remove('hidden', 'opacity-0');
        this.menu.classList.add('opacity-100');
        this.dropdown.classList.add('hs-dropdown-open');

        // Update accessibility attributes
        this.toggle.setAttribute('aria-expanded', 'true');
        this.menu.setAttribute('aria-hidden', 'false');

        console.log('Dropdown opened, menu classes:', this.menu.className);

        // Focus first menu item
        const firstMenuItem = this.menu.querySelector('a, button');
        if (firstMenuItem) {
            setTimeout(() => firstMenuItem.focus(), 100);
        }
    }

    closeDropdown() {
        console.log('Closing dropdown');
        this.isOpen = false;

        // Hide menu
        this.menu.classList.add('hidden', 'opacity-0');
        this.menu.classList.remove('opacity-100');
        this.dropdown.classList.remove('hs-dropdown-open');

        // Update accessibility attributes
        this.toggle.setAttribute('aria-expanded', 'false');
        this.menu.setAttribute('aria-hidden', 'true');

        console.log('Dropdown closed, menu classes:', this.menu.className);

        // Return focus to toggle button
        this.toggle.focus();
    }

    handleKeyboardNavigation(key) {
        const menuItems = Array.from(this.menu.querySelectorAll('a, button'));
        const currentIndex = menuItems.findIndex(item => item === document.activeElement);

        let nextIndex;
        if (key === 'ArrowDown') {
            nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0;
        } else {
            nextIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1;
        }

        menuItems[nextIndex].focus();
    }
}

// Initialize all mobile enhancements when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new MobileMenuController();
    new TouchGestureHandler();
    new CartCountUpdater();
    new MobileFormEnhancer();
    new SmoothScrollHandler();
    // UserDropdownController removed - Preline UI handles dropdowns automatically
    console.log("TEST")
    initRealtimeProductSearch();
    initCatalogFilter();

    // Add mobile-specific classes to body
    if (window.innerWidth < 768) {
        document.body.classList.add('mobile-device');
    }

    // Handle orientation change
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            // Force viewport recalculation
            const viewport = document.querySelector('meta[name="viewport"]');
            if (viewport) {
                viewport.content = 'width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no';
            }
        }, 100);
    });
});
