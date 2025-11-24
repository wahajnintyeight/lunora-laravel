<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private InventoryService $inventoryService;
    private CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = app(InventoryService::class);
        $this->cartService = app(CartService::class);
    }

    public function test_inventory_service_integrates_with_cart_validation()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);
        
        // Create cart and add item
        $cart = $this->cartService->getOrCreateCart($user);
        $this->cartService->addItem($cart, $product, 3);

        // Validate cart stock using inventory service
        $cart->load('items.product', 'items.variant');
        $cartItems = $cart->items->map(function ($item) {
            return [
                'product' => $item->product,
                'variant' => $item->variant,
                'quantity' => $item->quantity
            ];
        })->toArray();

        $errors = $this->inventoryService->validateCartStock($cartItems);
        $this->assertEmpty($errors);

        // Now reduce stock and validate again
        $this->inventoryService->updateStock($product, 2);
        
        // Refresh the cart items with updated product data
        $cart->load('items.product', 'items.variant');
        $cartItems = $cart->items->map(function ($item) {
            return [
                'product' => $item->product->fresh(),
                'variant' => $item->variant,
                'quantity' => $item->quantity
            ];
        })->toArray();
        
        $errors = $this->inventoryService->validateCartStock($cartItems);
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Only 2 units available', $errors[0]);
    }

    public function test_stock_reservation_during_order_processing()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => true
        ]);

        // Reserve stock for product
        $this->inventoryService->reserveStock($product, 3);
        $this->assertEquals(7, $product->fresh()->stock);

        // Reserve stock for variant
        $this->inventoryService->reserveStock($product, 2, $variant);
        $this->assertEquals(3, $variant->fresh()->stock);
        $this->assertEquals(7, $product->fresh()->stock); // Product stock unchanged

        // Release stock (simulate order cancellation)
        $this->inventoryService->releaseStock($product, 3);
        $this->inventoryService->releaseStock($product, 2, $variant);

        $this->assertEquals(10, $product->fresh()->stock);
        $this->assertEquals(5, $variant->fresh()->stock);
    }

    public function test_low_stock_alerts_for_admin_dashboard()
    {
        // Create products with various stock levels
        $criticalProduct = Product::factory()->create([
            'name' => 'Critical Stock Ring',
            'stock' => 0,
            'is_active' => true
        ]);

        $lowProduct = Product::factory()->create([
            'name' => 'Low Stock Necklace',
            'stock' => 3,
            'is_active' => true
        ]);

        $normalProduct = Product::factory()->create([
            'name' => 'Normal Stock Bracelet',
            'stock' => 50,
            'is_active' => true
        ]);

        // Get low stock products
        $lowStockProducts = $this->inventoryService->getLowStockProducts(10);
        $this->assertCount(2, $lowStockProducts);

        // Get inventory alerts
        $alerts = $this->inventoryService->getInventoryAlerts();
        $this->assertCount(2, $alerts);

        $criticalAlert = collect($alerts)->firstWhere('severity', 'critical');
        $warningAlert = collect($alerts)->firstWhere('severity', 'warning');

        $this->assertNotNull($criticalAlert);
        $this->assertNotNull($warningAlert);
        $this->assertEquals($criticalProduct->id, $criticalAlert['product_id']);
        $this->assertEquals($lowProduct->id, $warningAlert['product_id']);
    }

    public function test_bulk_stock_updates()
    {
        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 5]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product2->id,
            'stock' => 3
        ]);

        $updates = [
            ['product_id' => $product1->id, 'stock' => 20],
            ['product_id' => $product2->id, 'variant_id' => $variant->id, 'stock' => 8],
        ];

        $results = $this->inventoryService->bulkUpdateStock($updates);

        $this->assertCount(2, $results);
        $this->assertTrue($results[0]['success']);
        $this->assertTrue($results[1]['success']);

        $this->assertEquals(20, $product1->fresh()->stock);
        $this->assertEquals(8, $variant->fresh()->stock);
    }

    public function test_total_available_stock_calculation()
    {
        // Product without variants
        $simpleProduct = Product::factory()->create(['stock' => 15]);
        $this->assertEquals(15, $this->inventoryService->getTotalAvailableStock($simpleProduct));

        // Product with variants
        $variantProduct = Product::factory()->create(['stock' => 10]);
        ProductVariant::factory()->create([
            'product_id' => $variantProduct->id,
            'stock' => 5,
            'is_active' => true
        ]);
        ProductVariant::factory()->create([
            'product_id' => $variantProduct->id,
            'stock' => 3,
            'is_active' => true
        ]);
        ProductVariant::factory()->create([
            'product_id' => $variantProduct->id,
            'stock' => 2,
            'is_active' => false // Should not be counted
        ]);

        $this->assertEquals(8, $this->inventoryService->getTotalAvailableStock($variantProduct));
    }
}