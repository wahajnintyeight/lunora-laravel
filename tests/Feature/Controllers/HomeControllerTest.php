<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_page_shows_featured_products()
    {
        $category = Category::factory()->create();
        
        // Create featured products
        $featuredProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_featured' => true,
            'is_active' => true,
            'stock' => 10,
        ]);

        // Create non-featured product
        $regularProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_featured' => false,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('featuredProducts');
        
        $featuredProducts = $response->viewData('featuredProducts');
        $this->assertTrue($featuredProducts->contains($featuredProduct));
        $this->assertFalse($featuredProducts->contains($regularProduct));
    }

    public function test_home_page_shows_main_categories()
    {
        // Create parent category with products
        $parentCategory = Category::factory()->create([
            'parent_id' => null,
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $parentCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        // Create child category (should not appear in main categories)
        $childCategory = Category::factory()->create([
            'parent_id' => $parentCategory->id,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('mainCategories');
        
        $mainCategories = $response->viewData('mainCategories');
        $this->assertTrue($mainCategories->contains($parentCategory));
        $this->assertFalse($mainCategories->contains($childCategory));
    }

    public function test_home_page_shows_new_arrivals()
    {
        $category = Category::factory()->create();
        
        $newProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock' => 10,
            'created_at' => now(),
        ]);

        $oldProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock' => 10,
            'created_at' => now()->subDays(30),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('newArrivals');
        
        $newArrivals = $response->viewData('newArrivals');
        $this->assertTrue($newArrivals->contains($newProduct));
        
        // Should be ordered by newest first
        $this->assertEquals($newProduct->id, $newArrivals->first()->id);
    }

    public function test_home_page_excludes_inactive_products()
    {
        $category = Category::factory()->create();
        
        $activeProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => true,
            'stock' => 10,
        ]);

        $inactiveProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => false,
            'is_featured' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        
        $featuredProducts = $response->viewData('featuredProducts');
        $this->assertTrue($featuredProducts->contains($activeProduct));
        $this->assertFalse($featuredProducts->contains($inactiveProduct));
    }

    public function test_home_page_excludes_out_of_stock_products()
    {
        $category = Category::factory()->create();
        
        $inStockProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => true,
            'stock' => 10,
        ]);

        $outOfStockProduct = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => true,
            'stock' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        
        $featuredProducts = $response->viewData('featuredProducts');
        $this->assertTrue($featuredProducts->contains($inStockProduct));
        $this->assertFalse($featuredProducts->contains($outOfStockProduct));
    }

    public function test_home_page_shows_category_showcase()
    {
        $category = Category::factory()->create([
            'parent_id' => null,
            'is_active' => true,
        ]);

        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('categoryShowcase');
        
        $categoryShowcase = $response->viewData('categoryShowcase');
        $this->assertTrue($categoryShowcase->contains($category));
        $this->assertGreaterThan(0, $categoryShowcase->first()->products->count());
    }
}