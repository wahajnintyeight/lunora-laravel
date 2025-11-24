<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Check availability for product and variant stock.
     */
    public function checkAvailability(Product $product, int $quantity, ?ProductVariant $variant = null): bool
    {
        if (!$product->is_active) {
            return false;
        }

        if ($variant) {
            if (!$variant->is_active) {
                return false;
            }
            return $variant->stock >= $quantity;
        }

        return $product->stock >= $quantity;
    }

    /**
     * Reserve stock for order processing.
     */
    public function reserveStock(Product $product, int $quantity, ?ProductVariant $variant = null): bool
    {
        if (!$this->checkAvailability($product, $quantity, $variant)) {
            throw new \InvalidArgumentException("Insufficient stock for product: {$product->name}");
        }

        return DB::transaction(function () use ($product, $quantity, $variant) {
            if ($variant) {
                // Update variant stock
                $affected = ProductVariant::where('id', $variant->id)
                    ->where('stock', '>=', $quantity)
                    ->decrement('stock', $quantity);

                if ($affected === 0) {
                    throw new \InvalidArgumentException("Failed to reserve stock for variant: {$variant->sku}");
                }

                $this->logStockChange($product, $variant, -$quantity, 'reserved', "Stock reserved for order");
            } else {
                // Update product stock
                $affected = Product::where('id', $product->id)
                    ->where('stock', '>=', $quantity)
                    ->decrement('stock', $quantity);

                if ($affected === 0) {
                    throw new \InvalidArgumentException("Failed to reserve stock for product: {$product->sku}");
                }

                $this->logStockChange($product, null, -$quantity, 'reserved', "Stock reserved for order");
            }

            return true;
        });
    }

    /**
     * Release stock for order processing (e.g., when order is cancelled).
     */
    public function releaseStock(Product $product, int $quantity, ?ProductVariant $variant = null): bool
    {
        return DB::transaction(function () use ($product, $quantity, $variant) {
            if ($variant) {
                // Update variant stock
                $variant->increment('stock', $quantity);
                $this->logStockChange($product, $variant, $quantity, 'released', "Stock released from cancelled/refunded order");
            } else {
                // Update product stock
                $product->increment('stock', $quantity);
                $this->logStockChange($product, null, $quantity, 'released', "Stock released from cancelled/refunded order");
            }

            return true;
        });
    }

    /**
     * Update stock with audit logging.
     */
    public function updateStock(Product $product, int $newStock, ?ProductVariant $variant = null): bool
    {
        return DB::transaction(function () use ($product, $newStock, $variant) {
            if ($variant) {
                $oldStock = $variant->stock;
                $variant->update(['stock' => $newStock]);
                $change = $newStock - $oldStock;
                $this->logStockChange($product, $variant, $change, 'manual_update', "Manual stock update");
            } else {
                $oldStock = $product->stock;
                $product->update(['stock' => $newStock]);
                $change = $newStock - $oldStock;
                $this->logStockChange($product, null, $change, 'manual_update', "Manual stock update");
            }

            return true;
        });
    }

    /**
     * Get products with low stock for admin alerts.
     */
    public function getLowStockProducts(int $threshold = 5): Collection
    {
        // Get products with low stock (considering variants)
        $lowStockProducts = Product::where('is_active', true)
            ->where(function ($query) use ($threshold) {
                // Products without variants that have low stock
                $query->where(function ($q) use ($threshold) {
                    $q->whereDoesntHave('variants')
                      ->where('stock', '<=', $threshold);
                })
                // OR products with variants where all variants have low stock
                ->orWhere(function ($q) use ($threshold) {
                    $q->whereHas('variants')
                      ->whereDoesntHave('variants', function ($variant) use ($threshold) {
                          $variant->where('stock', '>', $threshold);
                      });
                });
            })
            ->with(['variants' => function ($query) use ($threshold) {
                $query->where('stock', '<=', $threshold);
            }])
            ->get();

        return $lowStockProducts;
    }

    /**
     * Get out of stock products.
     */
    public function getOutOfStockProducts(): Collection
    {
        return $this->getLowStockProducts(0);
    }

    /**
     * Validate stock for cart operations.
     */
    public function validateCartStock(array $cartItems): array
    {
        $errors = [];

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $variant = $item['variant'] ?? null;
            $quantity = $item['quantity'];

            if (!$this->checkAvailability($product, $quantity, $variant)) {
                $availableStock = $variant ? $variant->stock : $product->stock;
                $itemName = $variant ? $variant->title : $product->name;
                
                if ($availableStock > 0) {
                    $errors[] = "Only {$availableStock} units available for '{$itemName}'";
                } else {
                    $errors[] = "'{$itemName}' is out of stock";
                }
            }
        }

        return $errors;
    }

    /**
     * Get stock level for a product/variant.
     */
    public function getStockLevel(Product $product, ?ProductVariant $variant = null): int
    {
        if ($variant) {
            return $variant->stock;
        }
        return $product->stock;
    }

    /**
     * Check if product/variant is in stock.
     */
    public function isInStock(Product $product, ?ProductVariant $variant = null): bool
    {
        return $this->getStockLevel($product, $variant) > 0;
    }

    /**
     * Get total available stock for a product (including all variants).
     */
    public function getTotalAvailableStock(Product $product): int
    {
        if ($product->hasVariants()) {
            return $product->variants()->where('is_active', true)->sum('stock');
        }
        return $product->stock;
    }

    /**
     * Bulk update stock levels.
     */
    public function bulkUpdateStock(array $updates): array
    {
        $results = [];

        DB::transaction(function () use ($updates, &$results) {
            foreach ($updates as $update) {
                try {
                    $product = Product::findOrFail($update['product_id']);
                    $variant = isset($update['variant_id']) 
                        ? ProductVariant::findOrFail($update['variant_id']) 
                        : null;
                    
                    $this->updateStock($product, $update['stock'], $variant);
                    $results[] = [
                        'success' => true,
                        'product_id' => $product->id,
                        'variant_id' => $variant?->id,
                        'new_stock' => $update['stock']
                    ];
                } catch (\Exception $e) {
                    $results[] = [
                        'success' => false,
                        'product_id' => $update['product_id'] ?? null,
                        'variant_id' => $update['variant_id'] ?? null,
                        'error' => $e->getMessage()
                    ];
                }
            }
        });

        return $results;
    }

    /**
     * Get stock movement history for a product.
     */
    public function getStockHistory(Product $product, ?ProductVariant $variant = null, int $limit = 50): array
    {
        // This would typically query a stock_movements table
        // For now, we'll return an empty array as the table doesn't exist yet
        // This can be implemented when the stock movements tracking is added
        return [];
    }

    /**
     * Log stock changes for audit trail.
     */
    private function logStockChange(
        Product $product, 
        ?ProductVariant $variant, 
        int $change, 
        string $type, 
        string $reason
    ): void {
        $logData = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'variant_id' => $variant?->id,
            'variant_sku' => $variant?->sku,
            'stock_change' => $change,
            'change_type' => $type,
            'reason' => $reason,
            'timestamp' => now()->toISOString(),
        ];

        // Log to Laravel's logging system
        Log::channel('inventory')->info('Stock change', $logData);

        // In a production system, you might also want to store this in a database table
        // for better querying and reporting capabilities
    }

    /**
     * Get inventory alerts.
     */
    public function getInventoryAlerts(): array
    {
        $alerts = [];

        // Low stock alerts
        $lowStockProducts = $this->getLowStockProducts(10);
        foreach ($lowStockProducts as $product) {
            if ($product->hasVariants()) {
                foreach ($product->variants as $variant) {
                    if ($variant->stock <= 10) {
                        $alerts[] = [
                            'type' => 'low_stock',
                            'severity' => $variant->stock === 0 ? 'critical' : 'warning',
                            'message' => "Low stock for {$product->name} ({$variant->sku}): {$variant->stock} remaining",
                            'product_id' => $product->id,
                            'variant_id' => $variant->id,
                            'current_stock' => $variant->stock,
                        ];
                    }
                }
            } else {
                $alerts[] = [
                    'type' => 'low_stock',
                    'severity' => $product->stock === 0 ? 'critical' : 'warning',
                    'message' => "Low stock for {$product->name}: {$product->stock} remaining",
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'current_stock' => $product->stock,
                ];
            }
        }

        return $alerts;
    }
}