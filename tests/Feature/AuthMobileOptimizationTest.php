<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthMobileOptimizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that login page loads with mobile-optimized layout
     */
    public function test_login_page_has_mobile_optimization()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        
        // Check for mobile viewport meta tag
        $response->assertSee('width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover', false);
        
        // Check for mobile-specific CSS classes
        $response->assertSee('form-input', false);
        $response->assertSee('touch-target', false);
        $response->assertSee('min-h-[44px]', false);
        
        // Check for complete header and footer
        $response->assertSee('partials.shop.header', false);
        $response->assertSee('partials.shop.footer', false);
        
        // Check for mobile-specific styles
        $response->assertSee('font-size: 16px !important', false);
        $response->assertSee('min-height: 44px', false);
    }

    /**
     * Test that register page has mobile optimization
     */
    public function test_register_page_has_mobile_optimization()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        
        // Check for mobile-optimized form elements
        $response->assertSee('form-input', false);
        $response->assertSee('touch-target', false);
        
        // Check for proper spacing and layout
        $response->assertSee('space-y-6', false);
        $response->assertSee('auth-card', false);
    }

    /**
     * Test that forgot password page has mobile optimization
     */
    public function test_forgot_password_page_has_mobile_optimization()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        
        // Check for mobile-friendly layout
        $response->assertSee('form-input', false);
        $response->assertSee('touch-target', false);
        
        // Check for proper mobile spacing
        $response->assertSee('space-y-4', false);
    }

    /**
     * Test that all auth pages include complete header and footer
     */
    public function test_auth_pages_include_complete_layout()
    {
        $authPages = ['/login', '/register', '/forgot-password'];

        foreach ($authPages as $page) {
            $response = $this->get($page);
            
            $response->assertStatus(200);
            
            // Check for header inclusion
            $response->assertSee('partials.shop.header', false);
            
            // Check for footer inclusion  
            $response->assertSee('partials.shop.footer', false);
            
            // Check for mobile menu toggle
            $response->assertSee('mobile-menu-toggle', false);
        }
    }

    /**
     * Test mobile-specific JavaScript enhancements
     */
    public function test_auth_pages_include_mobile_javascript()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        
        // Check for mobile-specific JavaScript
        $response->assertSee('Mobile-specific enhancements', false);
        $response->assertSee('preventDefault zoom on iOS', false);
        $response->assertSee('Enhanced mobile menu toggle', false);
        $response->assertSee('Enhanced touch feedback', false);
    }

    /**
     * Test form validation with mobile-friendly error display
     */
    public function test_mobile_friendly_form_validation()
    {
        // Test login form validation
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => ''
        ]);

        // Should redirect back with errors
        $response->assertSessionHasErrors(['email', 'password']);
        
        // Test register form validation
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different'
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /**
     * Test responsive breakpoints in CSS
     */
    public function test_responsive_css_breakpoints()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        
        // Check for mobile-first responsive classes
        $response->assertSee('@media (max-width: 767px)', false);
        $response->assertSee('@media (min-width: 768px) and (max-width: 1023px)', false);
        $response->assertSee('@media (min-width: 1024px)', false);
        
        // Check for touch-friendly sizing
        $response->assertSee('min-height: 44px', false);
        $response->assertSee('min-width: 44px', false);
    }

    /**
     * Test accessibility features for mobile
     */
    public function test_mobile_accessibility_features()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        
        // Check for proper ARIA labels
        $response->assertSee('aria-label', false);
        $response->assertSee('aria-expanded', false);
        
        // Check for focus management
        $response->assertSee('focus:outline-none', false);
        $response->assertSee('focus:ring-2', false);
        
        // Check for keyboard navigation support
        $response->assertSee('keyboard-navigation', false);
    }
}