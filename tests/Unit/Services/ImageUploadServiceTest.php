<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ImageUploadService $imageUploadService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageUploadService = new ImageUploadService();
        Storage::fake('public');
    }

    public function test_can_upload_single_product_image(): void
    {
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('test-image.jpg', 600, 600);

        $productImage = $this->imageUploadService->uploadProductImage(
            $file,
            $product->id,
            'Test alt text',
            0,
            true
        );

        $this->assertInstanceOf(ProductImage::class, $productImage);
        $this->assertEquals($product->id, $productImage->product_id);
        $this->assertEquals('Test alt text', $productImage->alt_text);
        $this->assertTrue($productImage->is_primary);
        $this->assertEquals(0, $productImage->sort_order);

        // Check that files were created
        Storage::disk('public')->assertExists($productImage->file_path);
        
        // Check thumbnail and medium images
        $pathInfo = pathinfo($productImage->file_path);
        $filename = $pathInfo['basename'];
        $thumbnailPath = "products/thumbnails/" . str_replace('.jpg', '-thumb.jpg', $filename);
        $mediumPath = "products/medium/" . str_replace('.jpg', '-medium.jpg', $filename);
        
        Storage::disk('public')->assertExists($thumbnailPath);
        Storage::disk('public')->assertExists($mediumPath);
    }

    public function test_can_upload_multiple_product_images(): void
    {
        $product = Product::factory()->create();
        $files = [
            UploadedFile::fake()->image('test-image-1.jpg', 600, 600),
            UploadedFile::fake()->image('test-image-2.jpg', 600, 600),
            UploadedFile::fake()->image('test-image-3.jpg', 600, 600),
        ];
        $altTexts = ['Alt text 1', 'Alt text 2', 'Alt text 3'];

        $productImages = $this->imageUploadService->uploadMultipleProductImages(
            $files,
            $product->id,
            $altTexts,
            0
        );

        $this->assertCount(3, $productImages);
        
        foreach ($productImages as $index => $productImage) {
            $this->assertEquals($product->id, $productImage->product_id);
            $this->assertEquals($altTexts[$index], $productImage->alt_text);
            $this->assertEquals($index, $productImage->sort_order);
            $this->assertEquals($index === 0, $productImage->is_primary); // First image should be primary
            
            Storage::disk('public')->assertExists($productImage->file_path);
        }
    }

    public function test_validates_image_file_size(): void
    {
        $product = Product::factory()->create();
        
        // Create a file that's too large (over 2MB)
        $file = UploadedFile::fake()->create('large-image.jpg', 3000); // 3MB

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Image file size must not exceed 2MB.');

        $this->imageUploadService->uploadProductImage($file, $product->id);
    }

    public function test_validates_image_dimensions(): void
    {
        $product = Product::factory()->create();
        
        // Create an image that's too small
        $file = UploadedFile::fake()->image('small-image.jpg', 200, 200);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Image dimensions must be at least 300x300 pixels.');

        $this->imageUploadService->uploadProductImage($file, $product->id);
    }

    public function test_validates_image_mime_type(): void
    {
        $product = Product::factory()->create();
        
        // Create a non-image file
        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid image type. Only JPEG, PNG, and WebP are allowed.');

        $this->imageUploadService->uploadProductImage($file, $product->id);
    }

    public function test_can_delete_product_image(): void
    {
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('test-image.jpg', 600, 600);

        $productImage = $this->imageUploadService->uploadProductImage($file, $product->id);
        
        // Verify files exist
        Storage::disk('public')->assertExists($productImage->file_path);
        
        // Delete the image
        $result = $this->imageUploadService->deleteProductImage($productImage);
        
        $this->assertTrue($result);
        $this->assertDatabaseMissing('product_images', ['id' => $productImage->id]);
        Storage::disk('public')->assertMissing($productImage->file_path);
    }

    public function test_can_bulk_delete_product_images(): void
    {
        $product = Product::factory()->create();
        $files = [
            UploadedFile::fake()->image('test-image-1.jpg', 600, 600),
            UploadedFile::fake()->image('test-image-2.jpg', 600, 600),
        ];

        $productImages = $this->imageUploadService->uploadMultipleProductImages($files, $product->id);
        $imageIds = collect($productImages)->pluck('id')->toArray();
        
        // Bulk delete
        $deletedCount = $this->imageUploadService->bulkDeleteProductImages($imageIds);
        
        $this->assertEquals(2, $deletedCount);
        
        foreach ($productImages as $productImage) {
            $this->assertDatabaseMissing('product_images', ['id' => $productImage->id]);
            Storage::disk('public')->assertMissing($productImage->file_path);
        }
    }

    public function test_can_reorder_product_images(): void
    {
        $product = Product::factory()->create();
        $files = [
            UploadedFile::fake()->image('test-image-1.jpg', 600, 600),
            UploadedFile::fake()->image('test-image-2.jpg', 600, 600),
            UploadedFile::fake()->image('test-image-3.jpg', 600, 600),
        ];

        $productImages = $this->imageUploadService->uploadMultipleProductImages($files, $product->id);
        
        // Reorder: reverse the order
        $newOrders = [
            $productImages[0]->id => 2,
            $productImages[1]->id => 1,
            $productImages[2]->id => 0,
        ];
        
        $result = $this->imageUploadService->reorderProductImages($newOrders);
        
        $this->assertTrue($result);
        
        // Verify new order
        $this->assertDatabaseHas('product_images', ['id' => $productImages[0]->id, 'sort_order' => 2]);
        $this->assertDatabaseHas('product_images', ['id' => $productImages[1]->id, 'sort_order' => 1]);
        $this->assertDatabaseHas('product_images', ['id' => $productImages[2]->id, 'sort_order' => 0]);
    }

    public function test_can_set_primary_image(): void
    {
        $product = Product::factory()->create();
        $files = [
            UploadedFile::fake()->image('test-image-1.jpg', 600, 600),
            UploadedFile::fake()->image('test-image-2.jpg', 600, 600),
        ];

        $productImages = $this->imageUploadService->uploadMultipleProductImages($files, $product->id);
        
        // First image should be primary initially
        $this->assertTrue($productImages[0]->fresh()->is_primary);
        $this->assertFalse($productImages[1]->fresh()->is_primary);
        
        // Set second image as primary
        $result = $this->imageUploadService->setPrimaryImage($productImages[1]->id, $product->id);
        
        $this->assertTrue($result);
        $this->assertFalse($productImages[0]->fresh()->is_primary);
        $this->assertTrue($productImages[1]->fresh()->is_primary);
    }

    public function test_can_get_storage_stats(): void
    {
        $product = Product::factory()->create();
        $file = UploadedFile::fake()->image('test-image.jpg', 600, 600);

        $this->imageUploadService->uploadProductImage($file, $product->id);
        
        $stats = $this->imageUploadService->getStorageStats();
        
        $this->assertArrayHasKey('total_images', $stats);
        $this->assertArrayHasKey('total_size_bytes', $stats);
        $this->assertArrayHasKey('total_size_mb', $stats);
        $this->assertEquals(1, $stats['total_images']);
        $this->assertGreaterThan(0, $stats['total_size_bytes']);
    }

    public function test_generates_unique_filenames(): void
    {
        $product = Product::factory()->create();
        $file1 = UploadedFile::fake()->image('same-name.jpg', 600, 600);
        $file2 = UploadedFile::fake()->image('same-name.jpg', 600, 600);

        $image1 = $this->imageUploadService->uploadProductImage($file1, $product->id);
        $image2 = $this->imageUploadService->uploadProductImage($file2, $product->id);
        
        $this->assertNotEquals($image1->file_path, $image2->file_path);
    }
}