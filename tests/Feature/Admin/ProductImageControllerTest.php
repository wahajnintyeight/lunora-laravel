<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->product = Product::factory()->create();
        
        // Disable middleware for testing
        $this->withoutMiddleware();
    }

    public function test_admin_can_view_product_images_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.images.index', $this->product));
            
        $response->assertOk();
    }

    public function test_non_admin_cannot_access_product_images_page(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        
        $this->actingAs($user)
            ->get(route('admin.products.images.index', $this->product))
            ->assertForbidden();
    }

    public function test_guest_cannot_access_product_images_page(): void
    {
        $this->get(route('admin.products.images.index', $this->product))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_upload_product_images(): void
    {
        $files = [
            UploadedFile::fake()->image('test1.jpg', 600, 600),
            UploadedFile::fake()->image('test2.png', 800, 800),
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => $this->product->id,
                'images' => $files,
                'alt_texts' => ['Test image 1', 'Test image 2'],
            ]);

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'message',
                'images' => [
                    '*' => ['id', 'url', 'thumbnail_url', 'alt_text', 'sort_order', 'is_primary']
                ]
            ]);

        $this->assertDatabaseCount('product_images', 2);
        
        $images = ProductImage::where('product_id', $this->product->id)->get();
        $this->assertEquals('Test image 1', $images->first()->alt_text);
        $this->assertTrue($images->first()->is_primary); // First image should be primary
        $this->assertFalse($images->last()->is_primary);
    }

    public function test_upload_validates_file_types(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => $this->product->id,
                'images' => [$file],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images.0']);
    }

    public function test_upload_validates_file_size(): void
    {
        $file = UploadedFile::fake()->create('large-image.jpg', 3000); // 3MB

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => $this->product->id,
                'images' => [$file],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images.0']);
    }

    public function test_upload_validates_image_dimensions(): void
    {
        $file = UploadedFile::fake()->image('small.jpg', 200, 200); // Too small

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => $this->product->id,
                'images' => [$file],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images.0']);
    }

    public function test_upload_limits_max_files(): void
    {
        $files = [];
        for ($i = 0; $i < 12; $i++) { // More than max of 10
            $files[] = UploadedFile::fake()->image("test{$i}.jpg", 600, 600);
        }

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => $this->product->id,
                'images' => $files,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images']);
    }

    public function test_admin_can_update_image_details(): void
    {
        $image = ProductImage::factory()->create([
            'product_id' => $this->product->id,
            'alt_text' => 'Original alt text',
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson(route('admin.products.images.update', $image), [
                'alt_text' => 'Updated alt text',
                'sort_order' => 5,
            ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $image->refresh();
        $this->assertEquals('Updated alt text', $image->alt_text);
        $this->assertEquals(5, $image->sort_order);
    }

    public function test_admin_can_delete_product_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 600, 600);
        Storage::disk('public')->put('products/original/test.jpg', file_get_contents($file->getRealPath()));
        
        $image = ProductImage::factory()->create([
            'product_id' => $this->product->id,
            'file_path' => 'products/original/test.jpg',
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.products.images.destroy', $image));

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing('products/original/test.jpg');
    }

    public function test_admin_can_bulk_delete_images(): void
    {
        $images = ProductImage::factory(3)->create([
            'product_id' => $this->product->id,
        ]);

        $imageIds = $images->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.products.images.bulk-destroy'), [
                'image_ids' => $imageIds,
            ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        foreach ($imageIds as $imageId) {
            $this->assertDatabaseMissing('product_images', ['id' => $imageId]);
        }
    }

    public function test_admin_can_reorder_images(): void
    {
        $images = ProductImage::factory(3)->create([
            'product_id' => $this->product->id,
            'sort_order' => 0,
        ]);

        $newOrders = [
            $images[0]->id => 2,
            $images[1]->id => 0,
            $images[2]->id => 1,
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.reorder'), [
                'orders' => $newOrders,
            ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        foreach ($newOrders as $imageId => $sortOrder) {
            $this->assertDatabaseHas('product_images', [
                'id' => $imageId,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    public function test_admin_can_set_primary_image(): void
    {
        $images = ProductImage::factory(2)->create([
            'product_id' => $this->product->id,
            'is_primary' => false,
        ]);

        // Set first image as primary initially
        $images[0]->update(['is_primary' => true]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.set-primary', $images[1]));

        $response->assertOk()
            ->assertJson(['success' => true]);

        $images[0]->refresh();
        $images[1]->refresh();

        $this->assertFalse($images[0]->is_primary);
        $this->assertTrue($images[1]->is_primary);
    }

    public function test_admin_can_get_storage_stats(): void
    {
        ProductImage::factory(3)->create([
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.products.images.stats'));

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'stats' => [
                    'total_images',
                    'total_size_bytes',
                    'total_size_mb',
                ]
            ]);
    }

    public function test_admin_can_cleanup_orphaned_files(): void
    {
        // Create some orphaned files
        Storage::disk('public')->put('products/original/orphaned1.jpg', 'fake content');
        Storage::disk('public')->put('products/original/orphaned2.jpg', 'fake content');

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.cleanup'));

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'message',
                'deleted_files'
            ]);
    }

    public function test_non_admin_cannot_upload_images(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $file = UploadedFile::fake()->image('test.jpg', 600, 600);

        $this->actingAs($user)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => $this->product->id,
                'images' => [$file],
            ])
            ->assertForbidden();
    }

    public function test_non_admin_cannot_delete_images(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $image = ProductImage::factory()->create(['product_id' => $this->product->id]);

        $this->actingAs($user)
            ->deleteJson(route('admin.products.images.destroy', $image))
            ->assertForbidden();
    }

    public function test_validates_product_exists_for_upload(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 600, 600);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.images.store'), [
                'product_id' => 99999, // Non-existent product
                'images' => [$file],
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }
}