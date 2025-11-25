<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageUploadRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductImageController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageUploadService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display images for a specific product
     */
    public function index(Product $product): View
    {
        $images = $product->images()->orderBy('sort_order')->get();
        
        return view('admin.products.images.index', compact('product', 'images'));
    }

    /**
     * Upload new images for a product
     */
    public function store(ProductImageUploadRequest $request): JsonResponse
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $files = $request->file('images');
            $altTexts = $request->input('alt_texts', []);
            
            // Get the current highest sort order
            $maxSortOrder = $product->images()->max('sort_order') ?? -1;
            
            $uploadedImages = $this->imageUploadService->uploadMultipleProductImages(
                $files,
                $product->id,
                $altTexts,
                $maxSortOrder + 1
            );
            
            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' image(s) uploaded successfully.',
                'images' => $uploadedImages->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->url,
                        'thumbnail_url' => $image->thumbnail_url,
                        'alt_text' => $image->alt_text,
                        'sort_order' => $image->sort_order,
                        'is_primary' => $image->is_primary,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update image details
     */
    public function update(Request $request, ProductImage $image): JsonResponse
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $image->update($request->only(['alt_text', 'sort_order']));
            
            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully.',
                'image' => [
                    'id' => $image->id,
                    'alt_text' => $image->alt_text,
                    'sort_order' => $image->sort_order,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Delete a product image
     */
    public function destroy(ProductImage $image): JsonResponse
    {
        try {
            $this->imageUploadService->deleteProductImage($image);
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Bulk delete images
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'integer|exists:product_images,id',
        ]);

        try {
            $deletedCount = $this->imageUploadService->bulkDeleteProductImages($request->image_ids);
            
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} image(s) deleted successfully.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete images: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Reorder product images
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'integer|min:0',
        ]);

        try {
            $this->imageUploadService->reorderProductImages($request->orders);
            
            return response()->json([
                'success' => true,
                'message' => 'Images reordered successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder images: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Set primary image
     */
    public function setPrimary(Request $request, ProductImage $image): JsonResponse
    {
        try {
            $this->imageUploadService->setPrimaryImage($image->id, $image->product_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Primary image set successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary image: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get storage statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->imageUploadService->getStorageStats();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get storage stats: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Clean up orphaned files
     */
    public function cleanup(): JsonResponse
    {
        try {
            $deletedFiles = $this->imageUploadService->cleanupOrphanedFiles();
            
            return response()->json([
                'success' => true,
                'message' => count($deletedFiles) . ' orphaned file(s) cleaned up.',
                'deleted_files' => $deletedFiles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cleanup files: ' . $e->getMessage(),
            ], 422);
        }
    }
}