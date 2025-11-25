<?php

namespace App\Services;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    protected ImageManager $imageManager;
    
    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload and process a product image
     */
    public function uploadProductImage(UploadedFile $file, int $productId, ?string $altText = null, int $sortOrder = 0, bool $isPrimary = false): ProductImage
    {
        // Validate the uploaded file
        $this->validateImage($file);
        
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);
        
        // Store original image
        $originalPath = "products/original/{$filename}";
        Storage::disk('public')->put($originalPath, file_get_contents($file->getRealPath()));
        
        // Generate optimized versions
        $this->generateThumbnail($originalPath, $filename);
        $this->generateMediumImage($originalPath, $filename);
        $this->optimizeOriginalImage($originalPath);
        
        // Create database record
        return ProductImage::create([
            'product_id' => $productId,
            'file_path' => $originalPath,
            'alt_text' => $altText ?: "Product image",
            'sort_order' => $sortOrder,
            'is_primary' => $isPrimary,
        ]);
    }

    /**
     * Upload multiple product images
     */
    public function uploadMultipleProductImages(array $files, int $productId, array $altTexts = [], int $startingSortOrder = 0): array
    {
        $uploadedImages = [];
        
        foreach ($files as $index => $file) {
            $altText = $altTexts[$index] ?? null;
            $sortOrder = $startingSortOrder + $index;
            $isPrimary = $index === 0 && ProductImage::where('product_id', $productId)->count() === 0;
            
            $uploadedImages[] = $this->uploadProductImage($file, $productId, $altText, $sortOrder, $isPrimary);
        }
        
        return $uploadedImages;
    }

    /**
     * Delete a product image and its variants
     */
    public function deleteProductImage(ProductImage $image): bool
    {
        $pathInfo = pathinfo($image->file_path);
        $filename = $pathInfo['basename'];
        
        // Delete all image variants
        $paths = [
            $image->file_path,
            "products/thumbnails/" . str_replace('.' . $pathInfo['extension'], '-thumb.' . $pathInfo['extension'], $filename),
            "products/medium/" . str_replace('.' . $pathInfo['extension'], '-medium.' . $pathInfo['extension'], $filename),
        ];
        
        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        return $image->delete();
    }

    /**
     * Bulk delete product images
     */
    public function bulkDeleteProductImages(array $imageIds): int
    {
        $deletedCount = 0;
        $images = ProductImage::whereIn('id', $imageIds)->get();
        
        foreach ($images as $image) {
            if ($this->deleteProductImage($image)) {
                $deletedCount++;
            }
        }
        
        return $deletedCount;
    }

    /**
     * Reorder product images
     */
    public function reorderProductImages(array $imageOrders): bool
    {
        foreach ($imageOrders as $imageId => $sortOrder) {
            ProductImage::where('id', $imageId)->update(['sort_order' => $sortOrder]);
        }
        
        return true;
    }

    /**
     * Set primary image for a product
     */
    public function setPrimaryImage(int $imageId, int $productId): bool
    {
        // Remove primary status from all images of this product
        ProductImage::where('product_id', $productId)->update(['is_primary' => false]);
        
        // Set the specified image as primary
        return ProductImage::where('id', $imageId)
            ->where('product_id', $productId)
            ->update(['is_primary' => true]) > 0;
    }

    /**
     * Validate uploaded image file
     */
    protected function validateImage(UploadedFile $file): void
    {
        $maxSize = 2048 * 1024; // 2MB in bytes
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('Image file size must not exceed 2MB.');
        }
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Invalid image type. Only JPEG, PNG, and WebP are allowed.');
        }
        
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw new \InvalidArgumentException('Invalid file extension. Only jpg, jpeg, png, and webp are allowed.');
        }
        
        // Validate image dimensions
        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            throw new \InvalidArgumentException('Invalid image file.');
        }
        
        [$width, $height] = $imageInfo;
        
        if ($width < 300 || $height < 300) {
            throw new \InvalidArgumentException('Image dimensions must be at least 300x300 pixels.');
        }
        
        if ($width > 2000 || $height > 2000) {
            throw new \InvalidArgumentException('Image dimensions must not exceed 2000x2000 pixels.');
        }
    }

    /**
     * Generate unique filename for uploaded image
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d-H-i-s');
        $random = Str::random(8);
        
        return "product-{$timestamp}-{$random}.{$extension}";
    }

    /**
     * Generate thumbnail image (400x400)
     */
    protected function generateThumbnail(string $originalPath, string $filename): void
    {
        $originalFullPath = Storage::disk('public')->path($originalPath);
        $pathInfo = pathinfo($filename);
        $thumbnailFilename = $pathInfo['filename'] . '-thumb.' . $pathInfo['extension'];
        $thumbnailPath = "products/thumbnails/{$thumbnailFilename}";
        
        // Ensure thumbnails directory exists
        Storage::disk('public')->makeDirectory('products/thumbnails');
        
        $image = $this->imageManager->read($originalFullPath);
        $image->cover(400, 400);
        
        // Optimize for web
        if ($pathInfo['extension'] === 'jpg' || $pathInfo['extension'] === 'jpeg') {
            $image->toJpeg(85);
        } elseif ($pathInfo['extension'] === 'png') {
            $image->toPng();
        } elseif ($pathInfo['extension'] === 'webp') {
            $image->toWebp(85);
        }
        
        $image->save(Storage::disk('public')->path($thumbnailPath));
    }

    /**
     * Generate medium image (800x800)
     */
    protected function generateMediumImage(string $originalPath, string $filename): void
    {
        $originalFullPath = Storage::disk('public')->path($originalPath);
        $pathInfo = pathinfo($filename);
        $mediumFilename = $pathInfo['filename'] . '-medium.' . $pathInfo['extension'];
        $mediumPath = "products/medium/{$mediumFilename}";
        
        // Ensure medium directory exists
        Storage::disk('public')->makeDirectory('products/medium');
        
        $image = $this->imageManager->read($originalFullPath);
        $image->cover(800, 800);
        
        // Optimize for web
        if ($pathInfo['extension'] === 'jpg' || $pathInfo['extension'] === 'jpeg') {
            $image->toJpeg(90);
        } elseif ($pathInfo['extension'] === 'png') {
            $image->toPng();
        } elseif ($pathInfo['extension'] === 'webp') {
            $image->toWebp(90);
        }
        
        $image->save(Storage::disk('public')->path($mediumPath));
    }

    /**
     * Optimize original image for web delivery
     */
    protected function optimizeOriginalImage(string $originalPath): void
    {
        $originalFullPath = Storage::disk('public')->path($originalPath);
        $pathInfo = pathinfo($originalPath);
        
        $image = $this->imageManager->read($originalFullPath);
        
        // Resize if too large, maintaining aspect ratio
        $image->scaleDown(1200, 1200);
        
        // Optimize compression
        if ($pathInfo['extension'] === 'jpg' || $pathInfo['extension'] === 'jpeg') {
            $image->toJpeg(92);
        } elseif ($pathInfo['extension'] === 'png') {
            $image->toPng();
        } elseif ($pathInfo['extension'] === 'webp') {
            $image->toWebp(92);
        }
        
        $image->save($originalFullPath);
    }

    /**
     * Get image storage statistics
     */
    public function getStorageStats(): array
    {
        $totalImages = ProductImage::count();
        $totalSize = 0;
        
        $images = ProductImage::all();
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image->file_path)) {
                $totalSize += Storage::disk('public')->size($image->file_path);
            }
        }
        
        return [
            'total_images' => $totalImages,
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
        ];
    }

    /**
     * Clean up orphaned image files
     */
    public function cleanupOrphanedFiles(): array
    {
        $deletedFiles = [];
        $directories = ['products/original', 'products/thumbnails', 'products/medium'];
        
        foreach ($directories as $directory) {
            if (!Storage::disk('public')->exists($directory)) {
                continue;
            }
            
            $files = Storage::disk('public')->files($directory);
            
            foreach ($files as $file) {
                $filename = basename($file);
                
                // Check if this file is referenced in the database
                $exists = ProductImage::where('file_path', 'LIKE', "%{$filename}%")->exists();
                
                if (!$exists) {
                    Storage::disk('public')->delete($file);
                    $deletedFiles[] = $file;
                }
            }
        }
        
        return $deletedFiles;
    }
}