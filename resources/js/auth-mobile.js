/**
 * Mobile Authentication Enhancements
 * Provides enhanced mobile experience for authentication pages
 */

class AuthMobileEnhancements {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupTouchFeedback();
        this.setupKeyboardHandling();
        this.setupAccessibility();
        this.setupPasswordToggle();
        this.setupFormSubmission();
    }

    /**
     * Enhanced form validation with mobile-friendly error display
     */
    setupFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[required]');
            
            inputs.forEach(input => {
                // Real-time validation feedback
                input.addEventListener('blur', () => {
                    this.validateInput(input);
                });

                input.addEventListener('input', () => {
                    // Clear errors on input
                    this.clearInputError(input);
                });
            });

            // Enhanced form submission
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    this.focusFirstError(form);
                }
            });
        });
    }

    /**
     * Validate individual input
     */
    validateInput(input) {
        const value = input.value.trim();
        const type = input.type;
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (input.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }

        // Email validation
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
        }

        // Password validation
        if (type === 'password' && value && input.name === 'password') {
            if (value.length < 8) {
                isValid = false;
                errorMessage = 'Password must be at least 8 characters long';
            }
        }

        // Password confirmation validation
        if (input.name === 'password_confirmation' && value) {
            const passwordInput = document.querySelector('input[name="password"]');
            if (passwordInput && value !== passwordInput.value) {
                isValid = false;
                errorMessage = 'Passwords do not match';
            }
        }

        this.displayInputValidation(input, isValid, errorMessage);
        return isValid;
    }

    /**
     * Display validation feedback for input
     */
    displayInputValidation(input, isValid, errorMessage) {
        const container = input.closest('div');
        let errorElement = container.querySelector('.input-error');

        if (!isValid) {
            input.classList.add('border-red-300', 'dark:border-red-600');
            input.classList.remove('border-gray-300', 'dark:border-neutral-600');

            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.className = 'input-error mt-2 text-sm text-red-600 dark:text-red-400 mobile-error';
                container.appendChild(errorElement);
            }
            errorElement.textContent = errorMessage;
        } else {
            this.clearInputError(input);
        }
    }

    /**
     * Clear input error state
     */
    clearInputError(input) {
        const container = input.closest('div');
        const errorElement = container.querySelector('.input-error');

        input.classList.remove('border-red-300', 'dark:border-red-600');
        input.classList.add('border-gray-300', 'dark:border-neutral-600');

        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Validate entire form
     */
    validateForm(form) {
        const inputs = form.querySelectorAll('input[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateInput(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    /**
     * Focus first error field
     */
    focusFirstError(form) {
        const firstError = form.querySelector('.border-red-300, .border-red-600');
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    /**
     * Enhanced touch feedback for buttons and links
     */
    setupTouchFeedback() {
        const touchElements = document.querySelectorAll('button, .touch-target, a[href]');

        touchElements.forEach(element => {
            element.addEventListener('touchstart', () => {
                element.style.transform = 'scale(0.98)';
                element.style.opacity = '0.8';
            });

            element.addEventListener('touchend', () => {
                setTimeout(() => {
                    element.style.transform = '';
                    element.style.opacity = '';
                }, 100);
            });

            element.addEventListener('touchcancel', () => {
                element.style.transform = '';
                element.style.opacity = '';
            });
        });
    }

    /**
     * Handle mobile keyboard interactions
     */
    setupKeyboardHandling() {
        const inputs = document.querySelectorAll('input');

        inputs.forEach(input => {
            // Handle virtual keyboard on mobile
            input.addEventListener('focus', () => {
                // Scroll input into view when keyboard appears
                setTimeout(() => {
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            });

            // Handle Enter key navigation
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const form = input.closest('form');
                    const inputs = Array.from(form.querySelectorAll('input:not([type="hidden"])'));
                    const currentIndex = inputs.indexOf(input);
                    const nextInput = inputs[currentIndex + 1];

                    if (nextInput) {
                        e.preventDefault();
                        nextInput.focus();
                    }
                }
            });
        });
    }

    /**
     * Enhanced accessibility features
     */
    setupAccessibility() {
        // Add ARIA labels for better screen reader support
        const inputs = document.querySelectorAll('input');
        
        inputs.forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (label && !input.getAttribute('aria-label')) {
                input.setAttribute('aria-label', label.textContent.trim());
            }
        });

        // Enhanced focus management
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
    }

    /**
     * Password visibility toggle
     */
    setupPasswordToggle() {
        const passwordInputs = document.querySelectorAll('input[type="password"]');

        passwordInputs.forEach(input => {
            const container = input.closest('div');
            const iconContainer = container.querySelector('.absolute.inset-y-0.right-0');

            if (iconContainer) {
                // Create toggle button
                const toggleButton = document.createElement('button');
                toggleButton.type = 'button';
                toggleButton.className = 'absolute inset-y-0 right-0 pr-3 flex items-center touch-target';
                toggleButton.innerHTML = `
                    <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                `;

                toggleButton.addEventListener('click', () => {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    
                    toggleButton.innerHTML = isPassword ? `
                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                        </svg>
                    ` : `
                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    `;
                });

                // Replace existing icon with toggle button
                iconContainer.replaceWith(toggleButton);
            }
        });
    }

    /**
     * Enhanced form submission with loading states
     */
    setupFormSubmission() {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitButton = form.querySelector('button[type="submit"]');
                
                if (submitButton) {
                    // Add loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;

                    // Reset button state if form submission fails
                    setTimeout(() => {
                        if (submitButton.disabled) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = submitButton.dataset.originalText || 'Submit';
                        }
                    }, 10000);
                }
            });
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthMobileEnhancements();
});

// Handle page visibility changes (for mobile app switching)
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        // Re-focus form if needed when returning to page
        const activeElement = document.activeElement;
        if (activeElement && activeElement.tagName === 'INPUT') {
            setTimeout(() => {
                activeElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        }
    }
});