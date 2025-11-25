<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileCartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 150000, // PKR 1,500
            'stock' => 10
        ]);
        $this->user = User::factory()->create();
    }

    public function test_mobile_cart_page_loads_with_touch_friendly_controls()
    {
        // Create cart with item
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        $response = $this->actingAs($this->user)->get('/cart');

        $response->assertStatus(200);
        
        // Check for mobile-optimized elements
        $response->assertSee('data-cart-item');
        $response->assertSee('data-quantity-control');
        $response->assertSee('touch-target');
        $response->assertSee('quantity-control');
    }

    public function test_mobile_checkout_page_has_progress_indicators()
    {
        // Create cart with item
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($this->user)->get('/checkout');

        $response->assertStatus(200);
        
        // Check for mobile checkout elements
        $response->assertSee('data-checkout-progress');
        $response->assertSee('data-checkout-container');
        $response->assertSee('data-form-section');
        $response->assertSee('data-mobile-validation');
        $response->assertSee('data-checkout-submit');
    }

    public function test_mobile_cart_quantity_controls_work()
    {
        // Create cart with item
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        // Test quantity update via AJAX
        $response = $this->actingAs($this->user)
            ->postJson("/cart/update/{$cartItem->id}", [
                'quantity' => 3
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Verify quantity was updated
        $this->assertEquals(3, $cartItem->fresh()->quantity);
    }

    public function test_mobile_checkout_form_validation()
    {
        // Create cart with item
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        // Submit incomplete checkout form
        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'email' => 'test@example.com',
                // Missing required fields
            ]);

        $response->assertSessionHasErrors([
            'phone',
            'shipping_first_name',
            'shipping_last_name',
            'shipping_address_line_1',
            'shipping_city',
            'shipping_state',
            'shipping_postal_code',
            'terms_accepted'
        ]);
    }

    public function test_mobile_responsive_elements_present()
    {
        // Create cart with item to see mobile elements
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($this->user)->get('/cart');

        $response->assertStatus(200);
        
        // Check for mobile-specific CSS classes and attributes
        $response->assertSee('touch-target');
        $response->assertSee('data-cart-item');
        $response->assertSee('data-quantity-control');
        $response->assertSee('responsive-text-');
        
        // Check for mobile-optimized quantity controls
        $response->assertSee('w-12 h-12 sm:w-10 sm:h-10');
    }
}