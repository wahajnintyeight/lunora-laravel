<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartService $cartService;
    private User $user;
    private Product $product;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cartService = new CartService();
        
        // Create test data
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 100000, // PKR 1000
            'stock' => 10,
            'is_active' => true,
        ]);
        $this->user = User::factory()->create();
    }

    public function test_get_or_create_cart_for_user()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($this->user->id, $cart->user_id);
        $this->assertNull($cart->session_id);
        
        // Should return same cart on subsequent calls
        $sameCart = $this->cartService->getOrCreateCart($this->user);
        $this->assertEquals($cart->id, $sameCart->id);
    }

    public function test_get_or_create_cart_for_guest()
    {
        Session::start();
        $sessionId = Session::getId();
        
        $cart = $this->cartService->getOrCreateCart(null, $sessionId);
        
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertNull($cart->user_id);
        $this->assertEquals($sessionId, $cart->session_id);
    }

    public function test_add_item_to_cart()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        $cartItem = $this->cartService->addItem($cart, $this->product, 2);
        
        $this->assertInstanceOf(CartItem::class, $cartItem);
        $this->assertEquals($cart->id, $cartItem->cart_id);
        $this->assertEquals($this->product->id, $cartItem->product_id);
        $this->assertEquals(2, $cartItem->quantity);
        $this->assertEquals($this->product->price_pkr, $cartItem->unit_price_pkr);
    }

    public function test_add_item_with_variant()
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'price_pkr' => 120000, // PKR 1200
            'stock' => 5,
            'is_active' => true,
        ]);
        
        $cart = $this->cartService->getOrCreateCart($this->user);
        $cartItem = $this->cartService->addItem($cart, $this->product, 1, $variant);
        
        $this->assertEquals($variant->id, $cartItem->product_variant_id);
        $this->assertEquals($variant->price_pkr, $cartItem->unit_price_pkr);
    }

    public function test_add_item_with_customizations()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $customizations = ['engraving' => 'Happy Birthday', 'size' => 'Large'];
        
        $cartItem = $this->cartService->addItem($cart, $this->product, 1, null, $customizations);
        
        $this->assertEquals($customizations, $cartItem->customizations);
    }

    public function test_add_item_insufficient_stock()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Insufficient stock');
        
        $this->cartService->addItem($cart, $this->product, 15); // More than available stock
    }

    public function test_add_existing_item_increases_quantity()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        // Add item first time
        $this->cartService->addItem($cart, $this->product, 2);
        
        // Add same item again
        $cartItem = $this->cartService->addItem($cart, $this->product, 3);
        
        $this->assertEquals(5, $cartItem->quantity);
        $this->assertEquals(1, $cart->items()->count()); // Should still be one item
    }

    public function test_update_item_quantity()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $cartItem = $this->cartService->addItem($cart, $this->product, 2);
        
        $updatedItem = $this->cartService->updateItemQuantity($cartItem, 5);
        
        $this->assertEquals(5, $updatedItem->quantity);
    }

    public function test_update_item_quantity_to_zero_removes_item()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $cartItem = $this->cartService->addItem($cart, $this->product, 2);
        
        $result = $this->cartService->updateItemQuantity($cartItem, 0);
        
        $this->assertTrue($result);
        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_remove_item()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $cartItem = $this->cartService->addItem($cart, $this->product, 2);
        
        $result = $this->cartService->removeItem($cartItem);
        
        $this->assertTrue($result);
        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_apply_valid_coupon()
    {
        $coupon = Coupon::factory()->create([
            'code' => 'TEST10',
            'type' => Coupon::TYPE_PERCENTAGE,
            'percentage_value' => 10.00, // 10%
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
        ]);
        
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2); // PKR 2000 subtotal
        
        $updatedCart = $this->cartService->applyCoupon($cart, 'TEST10');
        
        $this->assertEquals('TEST10', $updatedCart->coupon_code);
        $this->assertEquals(20000, $updatedCart->discount_pkr); // 10% of 200000 paisa
    }

    public function test_apply_invalid_coupon()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid coupon code');
        
        $this->cartService->applyCoupon($cart, 'INVALID');
    }

    public function test_remove_coupon()
    {
        $coupon = Coupon::factory()->create([
            'code' => 'TEST10',
            'type' => Coupon::TYPE_FIXED,
            'value_pkr' => 50000, // PKR 500
            'is_active' => true,
        ]);
        
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);
        $this->cartService->applyCoupon($cart, 'TEST10');
        
        $updatedCart = $this->cartService->removeCoupon($cart);
        
        $this->assertNull($updatedCart->coupon_code);
        $this->assertEquals(0, $updatedCart->discount_pkr);
    }

    public function test_calculate_totals()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2); // PKR 2000 subtotal
        
        $totals = $this->cartService->calculateTotals($cart, 50000); // PKR 500 shipping
        
        $this->assertEquals(200000, $totals['subtotal']); // PKR 2000 in paisa
        $this->assertEquals(0, $totals['discount']);
        $this->assertEquals(50000, $totals['shipping']); // PKR 500 in paisa
        $this->assertEquals(250000, $totals['total']); // PKR 2500 in paisa
        $this->assertEquals('PKR 2,000.00', $totals['formatted']['subtotal']);
        $this->assertEquals('PKR 2,500.00', $totals['formatted']['total']);
    }

    public function test_merge_guest_cart()
    {
        // Create guest cart
        Session::start();
        $sessionId = Session::getId();
        $guestCart = $this->cartService->getOrCreateCart(null, $sessionId);
        $this->cartService->addItem($guestCart, $this->product, 2);
        
        // Create user cart
        $userCart = $this->cartService->getOrCreateCart($this->user);
        
        // Merge carts
        $mergedCart = $this->cartService->mergeGuestCart($guestCart, $userCart);
        
        $this->assertEquals($userCart->id, $mergedCart->id);
        $this->assertEquals(1, $mergedCart->items()->count());
        $this->assertEquals(2, $mergedCart->items()->first()->quantity);
        
        // Guest cart should be deleted
        $this->assertNull(Cart::find($guestCart->id));
    }

    public function test_validate_cart_items_removes_inactive_products()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);
        
        // Deactivate product
        $this->product->update(['is_active' => false]);
        
        $errors = $this->cartService->validateCartItems($cart);
        
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('no longer available', $errors[0]);
        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_validate_cart_items_adjusts_quantity_for_insufficient_stock()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 8);
        
        // Reduce stock
        $this->product->update(['stock' => 5]);
        
        $errors = $this->cartService->validateCartItems($cart);
        
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('quantity has been reduced', $errors[0]);
        $this->assertEquals(5, $cart->fresh()->items()->first()->quantity);
    }

    public function test_clear_cart()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);
        
        $this->cartService->clearCart($cart);
        
        $this->assertEquals(0, $cart->fresh()->items()->count());
        $this->assertNull($cart->fresh()->coupon_code);
    }

    public function test_get_item_count()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 3);
        
        $count = $this->cartService->getItemCount($cart);
        
        $this->assertEquals(3, $count);
    }

    public function test_is_empty()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        $this->assertTrue($this->cartService->isEmpty($cart));
        
        $this->cartService->addItem($cart, $this->product, 1);
        
        $this->assertFalse($this->cartService->isEmpty($cart->fresh()));
    }
}