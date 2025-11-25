/**
 * Mobile Cart and Checkout Optimization
 * Enhanced mobile experience for shopping cart and checkout flow
 */

class MobileCartOptimizer {
    constructor() {
        this.isTouch = 'ontouchstart' in window;
        this.init();
    }

    init() {
        this.setupTouchFriendlyQuantityControls();
        this.setupSwipeGestures();
        this.setupMobileCartInteractions();
        this.setupCartItemAnimations();
        this.setupMobileCustomizationForms();
        this.setupMobileCheckoutFlow();
        this.setupProgressIndicators();
        this.optimizePaymentForms();
        this.setupMobileValidation();
    }

    /**
     * Enhanced touch-friendly quantity controls
     */
    setupTouchFriendlyQuantityControls() {
        const quantityControls = document.querySelectorAll('.quantity-control');

        quantityControls.forEach(control => {
            const decreaseBtn = control.querySelector('[data-quantity-decrease]');
            const increaseBtn = control.querySelector('[data-quantity-increase]');
            const input = control.querySelector('input[type="number"]');

            if (decreaseBtn && increaseBtn && input) {
                // Enhanced touch feedback
                [decreaseBtn, increaseBtn].forEach(btn => {
                    this.addTouchFeedback(btn);

                    // Prevent double-tap zoom
                    btn.addEventListener('touchend', (e) => {
                        e.preventDefault();
                    });

                    // Long press for rapid increment/decrement
                    this.setupLongPress(btn, () => {
                        const isDecrease = btn.hasAttribute('data-quantity-decrease');
                        const currentValue = parseInt(input.value) || 1;
                        const newValue = isDecrease ? Math.max(1, currentValue - 1) : currentValue + 1;

                        input.value = newValue;
                        this.triggerQuantityUpdate(input);
                    });
                });

                // Enhanced input handling for mobile
                input.addEventListener('focus', () => {
                    // Select all text for easy editing
                    input.select();
                });

                input.addEventListener('blur', () => {
                    // Validate and update quantity
                    const value = parseInt(input.value) || 1;
                    input.value = Math.max(1, value);
                    this.triggerQuantityUpdate(input);
                });
            }
        });
    }

    /**
     * Setup swipe gestures for product image galleries
     */
    setupSwipeGestures() {
        const galleries = document.querySelectorAll('[data-product-gallery]');

        galleries.forEach(gallery => {
            let startX = 0;
            let startY = 0;
            let currentX = 0;
            let currentY = 0;
            let isDragging = false;

            gallery.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isDragging = true;
            });

            gallery.addEventListener('touchmove', (e) => {
                if (!isDragging) return;

                currentX = e.touches[0].clientX;
                currentY = e.touches[0].clientY;

                const diffX = startX - currentX;
                const diffY = startY - currentY;

                // Prevent vertical scrolling during horizontal swipe
                if (Math.abs(diffX) > Math.abs(diffY)) {
                    e.preventDefault();
                }
            });

            gallery.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;

                const diffX = startX - currentX;
                const threshold = 50;

                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        this.nextImage(gallery);
                    } else {
                        this.prevImage(gallery);
                    }
                }
            });
        });
    }

    /**
     * Setup mobile cart interactions
     */
    setupMobileCartInteractions() {
        // Swipe to remove cart items
        const cartItems = document.querySelectorAll('[data-cart-item]');

        cartItems.forEach(item => {
            this.setupSwipeToRemove(item);
        });

        // Pull to refresh cart
        this.setupPullToRefresh();

        // Sticky cart summary on mobile
        this.setupStickyCartSummary();
    }

    /**
     * Setup cart item animations
     */
    setupCartItemAnimations() {
        // Animate quantity changes
        document.addEventListener('cart:quantity-updated', (e) => {
            const item = e.target.closest('[data-cart-item]');
            if (item) {
                item.classList.add('updating');
                setTimeout(() => {
                    item.classList.remove('updating');
                }, 300);
            }
        });

        // Animate item removal
        document.addEventListener('cart:item-removed', (e) => {
            const item = e.target.closest('[data-cart-item]');
            if (item) {
                item.style.transform = 'translateX(-100%)';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                }, 300);
            }
        });
    }

    /**
     * Setup mobile customization forms
     */
    setupMobileCustomizationForms() {
        const customizationForms = document.querySelectorAll('[data-customization-form]');

        customizationForms.forEach(form => {
            // Collapsible sections for better mobile UX
            this.makeFormCollapsible(form);

            // Enhanced file upload for mobile
            this.enhanceMobileFileUpload(form);

            // Auto-save form data
            this.setupAutoSave(form);
        });
    }

    /**
     * Add touch feedback to buttons
     */
    addTouchFeedback(element) {
        element.addEventListener('touchstart', () => {
            element.classList.add('touch-active');
        });

        element.addEventListener('touchend', () => {
            setTimeout(() => {
                element.classList.remove('touch-active');
            }, 150);
        });

        element.addEventListener('touchcancel', () => {
            element.classList.remove('touch-active');
        });
    }

    /**
     * Setup long press functionality
     */
    setupLongPress(element, callback, delay = 500) {
        let pressTimer;

        element.addEventListener('touchstart', (e) => {
            pressTimer = setTimeout(() => {
                callback();
                // Haptic feedback if available
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }, delay);
        });

        element.addEventListener('touchend', () => {
            clearTimeout(pressTimer);
        });

        element.addEventListener('touchmove', () => {
            clearTimeout(pressTimer);
        });
    }

    /**
     * Trigger quantity update event
     */
    triggerQuantityUpdate(input) {
        const event = new CustomEvent('cart:quantity-updated', {
            bubbles: true,
            detail: { value: input.value }
        });
        input.dispatchEvent(event);
    }

    /**
     * Navigate to next image in gallery
     */
    nextImage(gallery) {
        const images = gallery.querySelectorAll('img');
        const activeIndex = Array.from(images).findIndex(img => img.classList.contains('active'));
        const nextIndex = (activeIndex + 1) % images.length;

        this.showImage(gallery, nextIndex);
    }

    /**
     * Navigate to previous image in gallery
     */
    prevImage(gallery) {
        const images = gallery.querySelectorAll('img');
        const activeIndex = Array.from(images).findIndex(img => img.classList.contains('active'));
        const prevIndex = activeIndex === 0 ? images.length - 1 : activeIndex - 1;

        this.showImage(gallery, prevIndex);
    }

    /**
     * Show specific image in gallery
     */
    showImage(gallery, index) {
        const images = gallery.querySelectorAll('img');
        images.forEach((img, i) => {
            img.classList.toggle('active', i === index);
        });

        // Update indicators if present
        const indicators = gallery.querySelectorAll('[data-gallery-indicator]');
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }

    /**
     * Setup swipe to remove functionality
     */
    setupSwipeToRemove(item) {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        let threshold = 100;

        item.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            item.style.transition = 'none';
        });

        item.addEventListener('touchmove', (e) => {
            if (!isDragging) return;

            currentX = e.touches[0].clientX;
            const diffX = currentX - startX;

            // Only allow left swipe
            if (diffX < 0) {
                item.style.transform = `translateX(${diffX}px)`;

                // Show remove button when swiped enough
                if (Math.abs(diffX) > threshold / 2) {
                    item.classList.add('swipe-active');
                } else {
                    item.classList.remove('swipe-active');
                }
            }
        });

        item.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;

            item.style.transition = 'transform 0.3s ease';

            const diffX = currentX - startX;

            if (Math.abs(diffX) > threshold) {
                // Remove item
                this.removeCartItem(item);
            } else {
                // Snap back
                item.style.transform = 'translateX(0)';
                item.classList.remove('swipe-active');
            }
        });
    }

    /**
     * Setup pull to refresh
     */
    setupPullToRefresh() {
        const cartContainer = document.querySelector('[data-cart-container]');
        if (!cartContainer) return;

        let startY = 0;
        let currentY = 0;
        let isDragging = false;
        let threshold = 80;

        cartContainer.addEventListener('touchstart', (e) => {
            if (cartContainer.scrollTop === 0) {
                startY = e.touches[0].clientY;
                isDragging = true;
            }
        });

        cartContainer.addEventListener('touchmove', (e) => {
            if (!isDragging) return;

            currentY = e.touches[0].clientY;
            const diffY = currentY - startY;

            if (diffY > 0 && cartContainer.scrollTop === 0) {
                e.preventDefault();
                const pullDistance = Math.min(diffY, threshold * 1.5);
                cartContainer.style.transform = `translateY(${pullDistance}px)`;

                if (pullDistance > threshold) {
                    cartContainer.classList.add('pull-refresh-active');
                } else {
                    cartContainer.classList.remove('pull-refresh-active');
                }
            }
        });

        cartContainer.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;

            const diffY = currentY - startY;

            if (diffY > threshold) {
                this.refreshCart();
            }

            cartContainer.style.transform = 'translateY(0)';
            cartContainer.classList.remove('pull-refresh-active');
        });
    }

    /**
     * Setup sticky cart summary
     */
    setupStickyCartSummary() {
        const summary = document.querySelector('[data-cart-summary]');
        if (!summary) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    summary.classList.remove('sticky');
                } else {
                    summary.classList.add('sticky');
                }
            });
        });

        const trigger = document.querySelector('[data-cart-summary-trigger]');
        if (trigger) {
            observer.observe(trigger);
        }
    }

    /**
     * Make form collapsible for mobile
     */
    makeFormCollapsible(form) {
        const sections = form.querySelectorAll('[data-form-section]');

        sections.forEach(section => {
            const header = section.querySelector('[data-section-header]');
            const content = section.querySelector('[data-section-content]');

            if (header && content) {
                header.addEventListener('click', () => {
                    const isExpanded = section.classList.contains('expanded');
                    section.classList.toggle('expanded', !isExpanded);

                    if (!isExpanded) {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    } else {
                        content.style.maxHeight = '0';
                    }
                });
            }
        });
    }

    /**
     * Enhance file upload for mobile
     */
    enhanceMobileFileUpload(form) {
        const fileInputs = form.querySelectorAll('input[type="file"]');

        fileInputs.forEach(input => {
            const wrapper = document.createElement('div');
            wrapper.className = 'mobile-file-upload';

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'file-upload-btn';
            button.textContent = 'Choose File';

            const preview = document.createElement('div');
            preview.className = 'file-preview';

            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(button);
            wrapper.appendChild(preview);
            wrapper.appendChild(input);

            button.addEventListener('click', () => {
                input.click();
            });

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = `<span class="file-name">${file.name}</span>`;
                    }
                    button.textContent = 'Change File';
                }
            });
        });
    }

    /**
     * Setup auto-save functionality
     */
    setupAutoSave(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        const formId = form.dataset.formId || 'customization-form';

        // Load saved data
        this.loadFormData(form, formId);

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.saveFormData(form, formId);
            });
        });
    }

    /**
     * Save form data to localStorage
     */
    saveFormData(form, formId) {
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        localStorage.setItem(`form-${formId}`, JSON.stringify(data));
    }

    /**
     * Load form data from localStorage
     */
    loadFormData(form, formId) {
        const savedData = localStorage.getItem(`form-${formId}`);
        if (!savedData) return;

        try {
            const data = JSON.parse(savedData);

            Object.keys(data).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input && input.type !== 'file') {
                    input.value = data[key];
                }
            });
        } catch (e) {
            console.warn('Failed to load saved form data:', e);
        }
    }

    /**
     * Remove cart item
     */
    removeCartItem(item) {
        const event = new CustomEvent('cart:item-removed', {
            bubbles: true,
            detail: { item: item }
        });
        item.dispatchEvent(event);
    }

    /**
     * Refresh cart data
     */
    refreshCart() {
        const event = new CustomEvent('cart:refresh', {
            bubbles: true
        });
        document.dispatchEvent(event);
    }

    /**
     * Setup mobile checkout flow optimization
     */
    setupMobileCheckoutFlow() {
        // Single-column mobile layout optimization
        this.optimizeCheckoutLayout();
        
        // Mobile-friendly form navigation
        this.setupFormNavigation();
        
        // Auto-advance to next field
        this.setupAutoAdvance();
        
        // Sticky checkout button
        this.setupStickyCheckoutButton();
    }

    /**
     * Setup mobile-specific progress indicators
     */
    setupProgressIndicators() {
        const progressContainer = document.querySelector('[data-checkout-progress]');
        if (!progressContainer) return;

        // Create mobile-optimized progress indicator
        const mobileProgress = document.createElement('div');
        mobileProgress.className = 'mobile-progress-indicator';
        mobileProgress.innerHTML = `
            <div class="progress-bar">
                <div class="progress-fill" data-progress-fill></div>
            </div>
            <div class="progress-steps">
                <span class="step active" data-step="1">Cart</span>
                <span class="step active" data-step="2">Checkout</span>
                <span class="step" data-step="3">Complete</span>
            </div>
        `;

        // Insert mobile progress on small screens
        if (window.innerWidth < 768) {
            progressContainer.parentNode.insertBefore(mobileProgress, progressContainer);
            progressContainer.style.display = 'none';
        }

        // Update progress based on form completion
        this.updateCheckoutProgress();
    }

    /**
     * Optimize payment forms for mobile
     */
    optimizePaymentForms() {
        const paymentForms = document.querySelectorAll('[data-payment-form]');
        
        paymentForms.forEach(form => {
            // Optimize input types for mobile keyboards
            this.optimizeInputTypes(form);
            
            // Add input formatting
            this.setupInputFormatting(form);
            
            // Enhanced validation feedback
            this.setupMobileValidationFeedback(form);
            
            // Auto-complete optimization
            this.setupAutoComplete(form);
        });
    }

    /**
     * Setup mobile validation with better UX
     */
    setupMobileValidation() {
        const forms = document.querySelectorAll('form[data-mobile-validation]');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                // Real-time validation with debouncing
                let validationTimeout;
                
                input.addEventListener('input', () => {
                    clearTimeout(validationTimeout);
                    validationTimeout = setTimeout(() => {
                        this.validateField(input);
                    }, 300);
                });

                // Immediate validation on blur
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });

                // Clear validation on focus
                input.addEventListener('focus', () => {
                    this.clearFieldValidation(input);
                });
            });
        });
    }

    /**
     * Optimize checkout layout for mobile
     */
    optimizeCheckoutLayout() {
        const checkoutContainer = document.querySelector('[data-checkout-container]');
        if (!checkoutContainer) return;

        // Reorder elements for mobile
        if (window.innerWidth < 1024) {
            const orderSummary = checkoutContainer.querySelector('[data-order-summary]');
            const checkoutForm = checkoutContainer.querySelector('[data-checkout-form]');
            
            if (orderSummary && checkoutForm) {
                // Move order summary to top on mobile
                checkoutContainer.insertBefore(orderSummary, checkoutForm);
                
                // Make order summary collapsible on mobile
                this.makeOrderSummaryCollapsible(orderSummary);
            }
        }
    }

    /**
     * Setup form navigation for mobile
     */
    setupFormNavigation() {
        const formSections = document.querySelectorAll('[data-form-section]');
        
        formSections.forEach((section, index) => {
            // Add section navigation
            const navButton = document.createElement('button');
            navButton.type = 'button';
            navButton.className = 'section-nav-btn';
            navButton.innerHTML = `
                <span class="section-number">${index + 1}</span>
                <span class="section-title">${section.dataset.sectionTitle || 'Section'}</span>
                <svg class="chevron" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            `;

            section.insertBefore(navButton, section.firstChild);

            // Toggle section visibility
            navButton.addEventListener('click', () => {
                const isExpanded = section.classList.contains('expanded');
                
                // Collapse all sections
                formSections.forEach(s => s.classList.remove('expanded'));
                
                // Expand current section if it wasn't expanded
                if (!isExpanded) {
                    section.classList.add('expanded');
                    
                    // Scroll to section
                    section.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            });
        });

        // Expand first section by default
        if (formSections.length > 0) {
            formSections[0].classList.add('expanded');
        }
    }

    /**
     * Setup auto-advance to next field
     */
    setupAutoAdvance() {
        const autoAdvanceInputs = document.querySelectorAll('[data-auto-advance]');
        
        autoAdvanceInputs.forEach(input => {
            input.addEventListener('input', () => {
                const maxLength = input.getAttribute('maxlength');
                
                if (maxLength && input.value.length >= parseInt(maxLength)) {
                    const nextInput = this.getNextInput(input);
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });
        });
    }

    /**
     * Setup sticky checkout button
     */
    setupStickyCheckoutButton() {
        const checkoutButton = document.querySelector('[data-checkout-submit]');
        if (!checkoutButton) return;

        const stickyContainer = document.createElement('div');
        stickyContainer.className = 'sticky-checkout-container';
        stickyContainer.appendChild(checkoutButton.cloneNode(true));

        document.body.appendChild(stickyContainer);

        // Show/hide based on scroll position
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                stickyContainer.classList.toggle('visible', !entry.isIntersecting);
            });
        });

        observer.observe(checkoutButton);
    }

    /**
     * Update checkout progress
     */
    updateCheckoutProgress() {
        const progressFill = document.querySelector('[data-progress-fill]');
        if (!progressFill) return;

        const forms = document.querySelectorAll('[data-checkout-form] [data-form-section]');
        let completedSections = 0;

        forms.forEach(section => {
            const inputs = section.querySelectorAll('input[required], select[required]');
            const filledInputs = Array.from(inputs).filter(input => input.value.trim() !== '');
            
            if (filledInputs.length === inputs.length) {
                completedSections++;
            }
        });

        const progress = (completedSections / forms.length) * 100;
        progressFill.style.width = `${progress}%`;
    }

    /**
     * Optimize input types for mobile keyboards
     */
    optimizeInputTypes(form) {
        const inputs = form.querySelectorAll('input');
        
        inputs.forEach(input => {
            const name = input.name || input.id;
            
            // Set appropriate input types and attributes
            if (name.includes('email')) {
                input.type = 'email';
                input.autocomplete = 'email';
            } else if (name.includes('phone') || name.includes('tel')) {
                input.type = 'tel';
                input.autocomplete = 'tel';
            } else if (name.includes('postal') || name.includes('zip')) {
                input.inputMode = 'numeric';
                input.autocomplete = 'postal-code';
            } else if (name.includes('address')) {
                input.autocomplete = 'street-address';
            } else if (name.includes('city')) {
                input.autocomplete = 'address-level2';
            } else if (name.includes('state') || name.includes('province')) {
                input.autocomplete = 'address-level1';
            } else if (name.includes('country')) {
                input.autocomplete = 'country';
            } else if (name.includes('name')) {
                if (name.includes('first')) {
                    input.autocomplete = 'given-name';
                } else if (name.includes('last')) {
                    input.autocomplete = 'family-name';
                } else {
                    input.autocomplete = 'name';
                }
            }
        });
    }

    /**
     * Setup input formatting
     */
    setupInputFormatting(form) {
        const phoneInputs = form.querySelectorAll('input[type="tel"]');
        const postalInputs = form.querySelectorAll('input[inputmode="numeric"]');
        
        phoneInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                
                // Format Pakistani phone numbers
                if (value.startsWith('92')) {
                    value = value.replace(/^92(\d{3})(\d{7})/, '+92 $1 $2');
                } else if (value.startsWith('0')) {
                    value = value.replace(/^0(\d{3})(\d{7})/, '0$1 $2');
                }
                
                e.target.value = value;
            });
        });

        postalInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                // Only allow numbers for postal codes
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        });
    }

    /**
     * Setup mobile validation feedback
     */
    setupMobileValidationFeedback(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Create validation message container
            const messageContainer = document.createElement('div');
            messageContainer.className = 'mobile-validation-message';
            input.parentNode.appendChild(messageContainer);
        });
    }

    /**
     * Setup auto-complete optimization
     */
    setupAutoComplete(form) {
        // Enable auto-complete for the entire form
        form.setAttribute('autocomplete', 'on');
        
        // Add specific autocomplete attributes based on field names
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            if (!input.autocomplete) {
                const name = input.name || input.id;
                
                // Map common field names to autocomplete values
                const autocompleteMap = {
                    'email': 'email',
                    'phone': 'tel',
                    'first_name': 'given-name',
                    'last_name': 'family-name',
                    'address_line_1': 'street-address',
                    'address_line_2': 'address-line2',
                    'city': 'address-level2',
                    'state': 'address-level1',
                    'postal_code': 'postal-code',
                    'country': 'country'
                };
                
                Object.keys(autocompleteMap).forEach(key => {
                    if (name.includes(key)) {
                        input.autocomplete = autocompleteMap[key];
                    }
                });
            }
        });
    }

    /**
     * Validate individual field
     */
    validateField(input) {
        const messageContainer = input.parentNode.querySelector('.mobile-validation-message');
        if (!messageContainer) return;

        let isValid = true;
        let message = '';

        // Required field validation
        if (input.hasAttribute('required') && !input.value.trim()) {
            isValid = false;
            message = 'This field is required';
        }
        
        // Email validation
        else if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                isValid = false;
                message = 'Please enter a valid email address';
            }
        }
        
        // Phone validation
        else if (input.type === 'tel' && input.value) {
            const phoneRegex = /^(\+92|0)\d{10}$/;
            if (!phoneRegex.test(input.value.replace(/\s/g, ''))) {
                isValid = false;
                message = 'Please enter a valid phone number';
            }
        }

        // Update UI
        input.classList.toggle('invalid', !isValid);
        input.classList.toggle('valid', isValid && input.value.trim());
        
        messageContainer.textContent = message;
        messageContainer.classList.toggle('visible', !isValid);

        return isValid;
    }

    /**
     * Clear field validation
     */
    clearFieldValidation(input) {
        const messageContainer = input.parentNode.querySelector('.mobile-validation-message');
        if (messageContainer) {
            messageContainer.classList.remove('visible');
        }
        
        input.classList.remove('invalid', 'valid');
    }

    /**
     * Make order summary collapsible
     */
    makeOrderSummaryCollapsible(summary) {
        const header = summary.querySelector('h2');
        if (!header) return;

        const content = summary.querySelector('[data-summary-content]');
        if (!content) return;

        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'summary-toggle';
        toggleButton.innerHTML = `
            <span>Show Order Summary</span>
            <svg class="chevron" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        `;

        header.appendChild(toggleButton);

        // Initially collapsed on mobile
        content.style.display = 'none';
        summary.classList.add('collapsed');

        toggleButton.addEventListener('click', () => {
            const isCollapsed = summary.classList.contains('collapsed');
            
            summary.classList.toggle('collapsed', !isCollapsed);
            content.style.display = isCollapsed ? 'block' : 'none';
            
            toggleButton.querySelector('span').textContent = 
                isCollapsed ? 'Hide Order Summary' : 'Show Order Summary';
        });
    }

    /**
     * Get next input element
     */
    getNextInput(currentInput) {
        const form = currentInput.closest('form');
        if (!form) return null;

        const inputs = Array.from(form.querySelectorAll('input, select, textarea'));
        const currentIndex = inputs.indexOf(currentInput);
        
        return inputs[currentIndex + 1] || null;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new MobileCartOptimizer();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MobileCartOptimizer;
}