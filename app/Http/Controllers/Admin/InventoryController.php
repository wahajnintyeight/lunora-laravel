<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    /**
     * Get low stock alerts for admin dashboard.
     */
    public function getLowStockAlerts(): JsonResponse
    {
        $alerts = $this->inventoryService->getInventoryAlerts();
        
        return response()->json([
            'alerts' => $alerts,
            'count' => count($alerts)
        ]);
    }

    /**
     * Get low stock products with pagination.
     */
    public function getLowStockProducts(Request $request): JsonResponse
    {
        $threshold = $request->get('threshold', 10);
        $lowStockProducts = $this->inventoryService->getLowStockProducts($threshold);
        
        return response()->json([
            'products' => $lowStockProducts,
            'threshold' => $threshold
        ]);
    }

    /**
     * Update stock for a product.
     */
    public function updateProductStock(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        try {
            $this->inventoryService->updateStock($product, $request->stock);
            
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'new_stock' => $product->fresh()->stock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update stock for a product variant.
     */
    public function updateVariantStock(Request $request, ProductVariant $variant): JsonResponse
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        try {
            $this->inventoryService->updateStock($variant->product, $request->stock, $variant);
            
            return response()->json([
                'success' => true,
                'message' => 'Variant stock updated successfully',
                'new_stock' => $variant->fresh()->stock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update variant stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update stock levels.
     */
    public function bulkUpdateStock(Request $request): JsonResponse
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.product_id' => 'required|exists:products,id',
            'updates.*.variant_id' => 'nullable|exists:product_variants,id',
            'updates.*.stock' => 'required|integer|min:0'
        ]);

        try {
            $results = $this->inventoryService->bulkUpdateStock($request->updates);
            
            $successCount = collect($results)->where('success', true)->count();
            $failureCount = collect($results)->where('success', false)->count();
            
            return response()->json([
                'success' => true,
                'message' => "Updated {$successCount} items successfully" . 
                           ($failureCount > 0 ? ", {$failureCount} failed" : ""),
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check availability for a specific product/variant.
     */
    public function checkAvailability(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $variant = null;
        if ($request->variant_id) {
            $variant = ProductVariant::find($request->variant_id);
        }

        $available = $this->inventoryService->checkAvailability(
            $product, 
            $request->quantity, 
            $variant
        );

        $currentStock = $this->inventoryService->getStockLevel($product, $variant);

        return response()->json([
            'available' => $available,
            'requested_quantity' => $request->quantity,
            'current_stock' => $currentStock,
            'product_id' => $product->id,
            'variant_id' => $variant?->id
        ]);
    }

    /**
     * Get total available stock for a product (including all variants).
     */
    public function getTotalStock(Product $product): JsonResponse
    {
        $totalStock = $this->inventoryService->getTotalAvailableStock($product);
        
        return response()->json([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'total_available_stock' => $totalStock,
            'has_variants' => $product->hasVariants()
        ]);
    }
}