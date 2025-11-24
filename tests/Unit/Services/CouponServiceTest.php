<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CouponService $couponService;
    protected CartService $cartService;
    protected User $user;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->couponService = app(CouponService::class);
        $this->cartService = app(CartService::class);

        // Create test data
        $this->user = User::factory()->create();
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'price_pkr' => 100000, // PKR 1000
        ]);
    }

    public function test_validate_coupon_success()
    {
        $coupon = Coupon::factory()->create([
            'code' => 'VALID10',
            'type' => Coupon::TYPE_PERCENTAGE,
            'percentage_value' => 10.00,
            'is_active' => true,
        ]);

        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 1);

        $result = $this->couponService->validateCoupon('VALID10', $cart, $this->user);

        $this->assertTrue($result['valid']);
        $this->assertEquals('Coupon is valid', $result['message']);
        $this->assertEquals($coupon->id, $result['coupon']->id);
    }

    public function test_validate_coupon_invalid_code()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 1);

        $result = $this->couponService->validateCoupon('INVALID', $cart, $this->user);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Invalid coupon code', $result['message']);
        $this->assertNull($result['coupon']);
    }

    public function test_calculate_discount_percentage()
    {
        $coupon = Coupon::factory()->create([
            'type' => Coupon::TYPE_PERCENTAGE,
            'percentage_value' => 15.00,
        ]);

        $discount = $this->couponService->calculateDiscount($coupon, 200000); // PKR 2000

        $this->assertEquals(30000, $discount); // 15% of PKR 2000 = PKR 300
    }

    public function test_calculate_discount_fixed()
    {
        $coupon = Coupon::factory()->create([
            'type' => Coupon::TYPE_FIXED,
            'value_pkr' => 50000, // PKR 500
        ]);

        $discount = $this->couponService->calculateDiscount($coupon, 200000); // PKR 2000

        $this->assertEquals(50000, $discount); // Fixed PKR 500
    }

    public function test_calculate_discount_fixed_exceeds_subtotal()
    {
        $coupon = Coupon::factory()->create([
            'type' => Coupon::TYPE_FIXED,
            'value_pkr' => 150000, // PKR 1500
        ]);

        $discount = $this->couponService->calculateDiscount($coupon, 100000); // PKR 1000

        $this->assertEquals(100000, $discount); // Should not exceed subtotal
    }

    public function test_check_usage_limits()
    {
        $coupon = Coupon::factory()->create([
            'usage_limit_total' => 10,
            'usage_limit_per_user' => 2,
            'used_count' => 5,
        ]);

        // Create an order first
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
        ]);

        // Create some redemptions for the user
        $coupon->redemptions()->create([
            'user_id' => $this->user->id,
            'order_id' => $order->id,
            'email' => $this->user->email,
            'discount_amount_pkr' => 10000,
        ]);

        $result = $this->couponService->checkUsageLimits($coupon, $this->user);

        $this->assertTrue($result['can_use']);
        $this->assertEquals(5, $result['total_usage']);
        $this->assertEquals(10, $result['total_limit']);
        $this->assertEquals(1, $result['user_usage']);
        $this->assertEquals(2, $result['user_limit']);
        $this->assertEquals(1, $result['remaining_uses']); // Limited by user limit
    }

    public function test_redeem_coupon()
    {
        $coupon = Coupon::factory()->create([
            'code' => 'REDEEM10',
            'used_count' => 0,
        ]);

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'discount_pkr' => 10000,
        ]);

        $redemption = $this->couponService->redeemCoupon($coupon, $order, $this->user);

        $this->assertEquals($coupon->id, $redemption->coupon_id);
        $this->assertEquals($this->user->id, $redemption->user_id);
        $this->assertEquals($order->id, $redemption->order_id);
        $this->assertEquals($this->user->email, $redemption->email);
        $this->assertEquals(10000, $redemption->discount_amount_pkr);
        $this->assertNotNull($redemption->redeemed_at);

        // Check that coupon usage was incremented
        $this->assertEquals(1, $coupon->fresh()->used_count);
    }
}