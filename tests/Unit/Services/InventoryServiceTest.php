<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private InventoryService $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = new InventoryService();
    }

    public function test_check_availability_for_product_without_variants()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);

        $this->assertTrue($this->inventoryService->checkAvailability($product, 5));
        $this->assertTrue($this->inventoryService->checkAvailability($product, 10));
        $this->assertFalse($this->inventoryService->checkAvailability($product, 15));
    }

    public function test_check_availability_for_inactive_product()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => false]);

        $this->assertFalse($this->inventoryService->checkAvailability($product, 5));
    }

    public function test_check_availability_for_product_with_variants()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => true
        ]);

        $this->assertTrue($this->inventoryService->checkAvailability($product, 3, $variant));
        $this->assertTrue($this->inventoryService->checkAvailability($product, 5, $variant));
        $this->assertFalse($this->inventoryService->checkAvailability($product, 8, $variant));
    }

    public function test_check_availability_for_inactive_variant()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => false
        ]);

        $this->assertFalse($this->inventoryService->checkAvailability($product, 3, $variant));
    }

    public function test_reserve_stock_for_product()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);

        Log::shouldReceive('channel')->with('inventory')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $result = $this->inventoryService->reserveStock($product, 3);

        $this->assertTrue($result);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_reserve_stock_for_variant()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => true
        ]);

        Log::shouldReceive('channel')->with('inventory')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $result = $this->inventoryService->reserveStock($product, 2, $variant);

        $this->assertTrue($result);
        $this->assertEquals(3, $variant->fresh()->stock);
        $this->assertEquals(10, $product->fresh()->stock); // Product stock unchanged
    }

    public function test_reserve_stock_insufficient_stock_throws_exception()
    {
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Insufficient stock for product: {$product->name}");

        $this->inventoryService->reserveStock($product, 10);
    }

    public function test_release_stock_for_product()
    {
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);

        Log::shouldReceive('channel')->with('inventory')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $result = $this->inventoryService->releaseStock($product, 3);

        $this->assertTrue($result);
        $this->assertEquals(8, $product->fresh()->stock);
    }

    public function test_release_stock_for_variant()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 3,
            'is_active' => true
        ]);

        Log::shouldReceive('channel')->with('inventory')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $result = $this->inventoryService->releaseStock($product, 2, $variant);

        $this->assertTrue($result);
        $this->assertEquals(5, $variant->fresh()->stock);
        $this->assertEquals(10, $product->fresh()->stock); // Product stock unchanged
    }

    public function test_update_stock_for_product()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);

        Log::shouldReceive('channel')->with('inventory')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $result = $this->inventoryService->updateStock($product, 15);

        $this->assertTrue($result);
        $this->assertEquals(15, $product->fresh()->stock);
    }

    public function test_update_stock_for_variant()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => true
        ]);

        Log::shouldReceive('channel')->with('inventory')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $result = $this->inventoryService->updateStock($product, 8, $variant);

        $this->assertTrue($result);
        $this->assertEquals(8, $variant->fresh()->stock);
        $this->assertEquals(10, $product->fresh()->stock); // Product stock unchanged
    }

    public function test_get_low_stock_products()
    {
        // Create products with different stock levels
        $lowStockProduct = Product::factory()->create(['stock' => 3, 'is_active' => true]);
        $normalStockProduct = Product::factory()->create(['stock' => 20, 'is_active' => true]);
        $outOfStockProduct = Product::factory()->create(['stock' => 0, 'is_active' => true]);

        $lowStockProducts = $this->inventoryService->getLowStockProducts(5);

        $this->assertCount(2, $lowStockProducts);
        $this->assertTrue($lowStockProducts->contains($lowStockProduct));
        $this->assertTrue($lowStockProducts->contains($outOfStockProduct));
        $this->assertFalse($lowStockProducts->contains($normalStockProduct));
    }

    public function test_get_low_stock_products_with_variants()
    {
        $product = Product::factory()->create(['stock' => 20, 'is_active' => true]);
        
        // Create variants with different stock levels
        $lowStockVariant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 2,
            'is_active' => true
        ]);
        
        $normalStockVariant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 15,
            'is_active' => true
        ]);

        $lowStockProducts = $this->inventoryService->getLowStockProducts(5);

        // Product should not be included because it has at least one variant with normal stock
        $this->assertCount(0, $lowStockProducts);

        // Now make all variants low stock
        $normalStockVariant->update(['stock' => 3]);

        $lowStockProducts = $this->inventoryService->getLowStockProducts(5);
        $this->assertCount(1, $lowStockProducts);
        $this->assertTrue($lowStockProducts->contains($product));
    }

    public function test_validate_cart_stock()
    {
        $product1 = Product::factory()->create(['stock' => 5, 'is_active' => true]);
        $product2 = Product::factory()->create(['stock' => 0, 'is_active' => true]);
        $product3 = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product3->id,
            'stock' => 2,
            'is_active' => true
        ]);

        $cartItems = [
            [
                'product' => $product1,
                'variant' => null,
                'quantity' => 3
            ],
            [
                'product' => $product2,
                'variant' => null,
                'quantity' => 1
            ],
            [
                'product' => $product3,
                'variant' => $variant,
                'quantity' => 5
            ]
        ];

        $errors = $this->inventoryService->validateCartStock($cartItems);

        $this->assertCount(2, $errors);
        $this->assertStringContainsString('out of stock', $errors[0]);
        $this->assertStringContainsString('Only 2 units available', $errors[1]);
    }

    public function test_get_stock_level()
    {
        $product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => true
        ]);

        $this->assertEquals(10, $this->inventoryService->getStockLevel($product));
        $this->assertEquals(5, $this->inventoryService->getStockLevel($product, $variant));
    }

    public function test_is_in_stock()
    {
        $inStockProduct = Product::factory()->create(['stock' => 5, 'is_active' => true]);
        $outOfStockProduct = Product::factory()->create(['stock' => 0, 'is_active' => true]);

        $this->assertTrue($this->inventoryService->isInStock($inStockProduct));
        $this->assertFalse($this->inventoryService->isInStock($outOfStockProduct));
    }

    public function test_get_total_available_stock()
    {
        // Product without variants
        $product1 = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        $this->assertEquals(10, $this->inventoryService->getTotalAvailableStock($product1));

        // Product with variants
        $product2 = Product::factory()->create(['stock' => 10, 'is_active' => true]);
        ProductVariant::factory()->create([
            'product_id' => $product2->id,
            'stock' => 5,
            'is_active' => true
        ]);
        ProductVariant::factory()->create([
            'product_id' => $product2->id,
            'stock' => 3,
            'is_active' => true
        ]);
        ProductVariant::factory()->create([
            'product_id' => $product2->id,
            'stock' => 2,
            'is_active' => false // Inactive variant should not be counted
        ]);

        $this->assertEquals(8, $this->inventoryService->getTotalAvailableStock($product2));
    }

    public function test_get_inventory_alerts()
    {
        // Create products with different stock levels
        $lowStockProduct = Product::factory()->create([
            'name' => 'Low Stock Ring',
            'stock' => 3,
            'is_active' => true
        ]);
        
        $outOfStockProduct = Product::factory()->create([
            'name' => 'Out of Stock Necklace',
            'stock' => 0,
            'is_active' => true
        ]);
        
        $normalStockProduct = Product::factory()->create([
            'name' => 'Normal Stock Bracelet',
            'stock' => 20,
            'is_active' => true
        ]);

        $alerts = $this->inventoryService->getInventoryAlerts();

        $this->assertCount(2, $alerts);
        
        // Check low stock alert
        $lowStockAlert = collect($alerts)->firstWhere('product_id', $lowStockProduct->id);
        $this->assertEquals('low_stock', $lowStockAlert['type']);
        $this->assertEquals('warning', $lowStockAlert['severity']);
        $this->assertEquals(3, $lowStockAlert['current_stock']);

        // Check out of stock alert
        $outOfStockAlert = collect($alerts)->firstWhere('product_id', $outOfStockProduct->id);
        $this->assertEquals('low_stock', $outOfStockAlert['type']);
        $this->assertEquals('critical', $outOfStockAlert['severity']);
        $this->assertEquals(0, $outOfStockAlert['current_stock']);
    }
}