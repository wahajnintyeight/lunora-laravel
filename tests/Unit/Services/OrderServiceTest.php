<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\InventoryService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $orderService;
    private CartService $cartService;
    private InventoryService $inventoryService;
    private User $user;
    private Product $product;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->inventoryService = new InventoryService();
        $this->orderService = new OrderService($this->inventoryService);
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

    public function test_create_order_from_cart()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);

        $shippingAddress = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'city' => 'Karachi',
            'state_province' => 'Sindh',
            'postal_code' => '75500',
            'phone' => '+92300123456',
            'email' => 'john@example.com',
        ];

        $order = $this->orderService->createFromCart($cart, $shippingAddress);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($this->user->id, $order->user_id);
        $this->assertEquals(Order::STATUS_PENDING, $order->status);
        $this->assertEquals(200000, $order->subtotal_pkr); // 2 * 100000
        $this->assertEquals(200000, $order->total_pkr);
        $this->assertEquals(1, $order->items()->count());
        $this->assertEquals(2, $order->addresses()->count()); // shipping + billing
        
        // Check stock was decremented
        $this->assertEquals(8, $this->product->fresh()->stock);
        
        // Check cart was cleared
        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_create_order_with_coupon()
    {
        $coupon = Coupon::factory()->create([
            'code' => 'TEST10',
            'type' => Coupon::TYPE_PERCENTAGE,
            'percentage_value' => 10.00,
            'is_active' => true,
        ]);

        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);
        $this->cartService->applyCoupon($cart, 'TEST10');

        $shippingAddress = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'city' => 'Karachi',
            'state_province' => 'Sindh',
            'postal_code' => '75500',
            'phone' => '+92300123456',
            'email' => 'john@example.com',
        ];

        $order = $this->orderService->createFromCart($cart, $shippingAddress);

        $this->assertEquals(200000, $order->subtotal_pkr);
        $this->assertEquals(20000, $order->discount_pkr); // 10% of 200000
        $this->assertEquals(180000, $order->total_pkr);
        $this->assertEquals('TEST10', $order->coupon_code);
        
        // Check coupon usage was incremented
        $this->assertEquals(1, $coupon->fresh()->used_count);
    }

    public function test_create_order_from_empty_cart_throws_exception()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        
        $shippingAddress = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'city' => 'Karachi',
            'state_province' => 'Sindh',
            'postal_code' => '75500',
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create order from empty cart');
        
        $this->orderService->createFromCart($cart, $shippingAddress);
    }

    public function test_update_order_status()
    {
        $order = Order::factory()->create([
            'status' => Order::STATUS_PENDING,
        ]);

        $updatedOrder = $this->orderService->updateStatus($order, Order::STATUS_CONFIRMED);

        $this->assertEquals(Order::STATUS_CONFIRMED, $updatedOrder->status);
    }

    public function test_update_order_status_with_invalid_transition()
    {
        $order = Order::factory()->create([
            'status' => Order::STATUS_DELIVERED,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status transition');
        
        $this->orderService->updateStatus($order, Order::STATUS_PENDING);
    }

    public function test_cancel_order()
    {
        // Create order first
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);

        $shippingAddress = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'city' => 'Karachi',
            'state_province' => 'Sindh',
            'postal_code' => '75500',
        ];

        $order = $this->orderService->createFromCart($cart, $shippingAddress);
        $this->assertEquals(8, $this->product->fresh()->stock); // Stock decremented

        // Cancel the order
        $cancelledOrder = $this->orderService->cancelOrder($order, true);

        $this->assertEquals(Order::STATUS_CANCELLED, $cancelledOrder->status);
        
        // Check stock was restored
        $this->assertEquals(10, $this->product->fresh()->stock);
    }

    public function test_cancel_order_without_stock_restoration()
    {
        // Create order first
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 2);

        $shippingAddress = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'city' => 'Karachi',
            'state_province' => 'Sindh',
            'postal_code' => '75500',
        ];

        $order = $this->orderService->createFromCart($cart, $shippingAddress);
        $this->assertEquals(8, $this->product->fresh()->stock);

        // Cancel without stock restoration
        $cancelledOrder = $this->orderService->cancelOrder($order, false);

        $this->assertEquals(Order::STATUS_CANCELLED, $cancelledOrder->status);
        
        // Stock should not be restored
        $this->assertEquals(8, $this->product->fresh()->stock);
    }

    public function test_cancel_non_cancellable_order()
    {
        $order = Order::factory()->create([
            'status' => Order::STATUS_DELIVERED,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order cannot be cancelled');
        
        $this->orderService->cancelOrder($order);
    }

    public function test_process_refund()
    {
        $order = Order::factory()->create([
            'status' => Order::STATUS_DELIVERED,
        ]);

        $refundedOrder = $this->orderService->processRefund($order);

        $this->assertEquals(Order::STATUS_REFUNDED, $refundedOrder->status);
    }

    public function test_process_refund_non_refundable_order()
    {
        $order = Order::factory()->create([
            'status' => Order::STATUS_PENDING,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order cannot be refunded');
        
        $this->orderService->processRefund($order);
    }

    public function test_generate_order_number()
    {
        $orderNumber = $this->orderService->generateOrderNumber();
        
        $this->assertStringStartsWith('LUN-' . date('Y') . '-', $orderNumber);
        $this->assertEquals(15, strlen($orderNumber)); // LUN-YYYY-XXXXXX
    }

    public function test_calculate_shipping()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 1); // PKR 1000 subtotal

        $address = ['city' => 'Karachi'];
        $shipping = $this->orderService->calculateShipping($cart, $address);
        
        $this->assertEquals(50000, $shipping); // PKR 500 base shipping

        // Test free shipping threshold
        $expensiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 600000, // PKR 6000
            'stock' => 10,
            'is_active' => true,
        ]);
        $cart2 = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart2, $expensiveProduct, 1);
        
        $shipping = $this->orderService->calculateShipping($cart2, $address);
        $this->assertEquals(0, $shipping); // Free shipping
    }

    public function test_calculate_shipping_with_additional_charges()
    {
        $cart = $this->cartService->getOrCreateCart($this->user);
        $this->cartService->addItem($cart, $this->product, 1);

        $address = ['city' => 'Multan']; // Non-major city
        $shipping = $this->orderService->calculateShipping($cart, $address);
        
        $this->assertEquals(75000, $shipping); // PKR 500 + PKR 250 additional
    }

    public function test_get_order_statistics()
    {
        // Create some test orders
        Order::factory()->create(['status' => Order::STATUS_PENDING, 'total_pkr' => 100000]);
        Order::factory()->create(['status' => Order::STATUS_DELIVERED, 'total_pkr' => 200000]);
        Order::factory()->create(['status' => Order::STATUS_CANCELLED, 'total_pkr' => 150000]);

        $stats = $this->orderService->getOrderStatistics();

        $this->assertEquals(3, $stats['total_orders']);
        $this->assertEquals(1, $stats['pending_orders']);
        $this->assertEquals(1, $stats['delivered_orders']);
        $this->assertEquals(1, $stats['cancelled_orders']);
        $this->assertEquals(300000, $stats['total_revenue']); // Pending + Delivered
        $this->assertEquals(150000, $stats['average_order_value']); // (100000 + 200000) / 2
    }
}