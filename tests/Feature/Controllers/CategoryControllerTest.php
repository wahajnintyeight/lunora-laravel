<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private Category $parentCategory;
    private Category $childCategory;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->parentCategory = Category::factory()->create([
            'parent_id' => null,
            'is_active' => true,
        ]);

        $this->childCategory = Category::factory()->create([
            'parent_id' => $this->parentCategory->id,
            'is_active' => true,
        ]);

        $this->product = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);
    }

    public function test_categories_index_displays_successfully()
    {
        $response = $this->get('/categories');

        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
        $response->assertViewHas('categories');
    }

    public function test_categories_index_returns_json_when_requested()
    {
        $response = $this->getJson('/categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'categories' => [
                '*' => ['id', 'name', 'slug', 'products_count', 'children']
            ]
        ]);
    }

    public function test_categories_index_shows_only_parent_categories()
    {
        $response = $this->get('/categories');

        $response->assertStatus(200);
        
        $categories = $response->viewData('categories');
        $this->assertTrue($categories->contains($this->parentCategory));
        $this->assertFalse($categories->contains($this->childCategory));
    }

    public function test_categories_index_excludes_categories_without_products()
    {
        $emptyCategory = Category::factory()->create([
            'parent_id' => null,
            'is_active' => true,
        ]);

        $response = $this->get('/categories');

        $response->assertStatus(200);
        
        $categories = $response->viewData('categories');
        $this->assertTrue($categories->contains($this->parentCategory));
        $this->assertFalse($categories->contains($emptyCategory));
    }

    public function test_category_show_displays_successfully()
    {
        $response = $this->get('/categories/' . $this->parentCategory->slug);

        $response->assertStatus(200);
        $response->assertViewIs('categories.show');
        $response->assertViewHas([
            'category',
            'products',
            'materials',
            'brands',
            'priceRange',
            'breadcrumbs',
            'featuredProducts'
        ]);
    }

    public function test_category_show_returns_404_for_inactive_category()
    {
        $inactiveCategory = Category::factory()->create([
            'is_active' => false,
        ]);

        $response = $this->get('/categories/' . $inactiveCategory->slug);

        $response->assertStatus(404);
    }

    public function test_category_show_displays_products_from_category_and_subcategories()
    {
        $productInParent = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $productInChild = Product::factory()->create([
            'category_id' => $this->childCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $productInOtherCategory = Product::factory()->create([
            'category_id' => Category::factory()->create()->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/categories/' . $this->parentCategory->slug);

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($productInParent));
        $this->assertTrue($products->contains($productInChild));
        $this->assertFalse($products->contains($productInOtherCategory));
    }

    public function test_category_show_filters_by_subcategory()
    {
        $productInParent = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $productInChild = Product::factory()->create([
            'category_id' => $this->childCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/categories/' . $this->parentCategory->slug . '?subcategory=' . $this->childCategory->slug);

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertFalse($products->contains($productInParent));
        $this->assertTrue($products->contains($productInChild));
    }

    public function test_category_show_filters_by_search_term()
    {
        $searchableProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'name' => 'Gold Ring',
            'is_active' => true,
            'stock' => 10,
        ]);

        $otherProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'name' => 'Silver Necklace',
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/categories/' . $this->parentCategory->slug . '?search=gold');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($searchableProduct));
        $this->assertFalse($products->contains($otherProduct));
    }

    public function test_category_show_filters_by_price_range()
    {
        $cheapProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'price_pkr' => 50000, // PKR 500
            'is_active' => true,
            'stock' => 10,
        ]);

        $expensiveProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'price_pkr' => 300000, // PKR 3000
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/categories/' . $this->parentCategory->slug . '?min_price=1000&max_price=2000');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertFalse($products->contains($cheapProduct));
        $this->assertFalse($products->contains($expensiveProduct));
    }

    public function test_category_show_sorts_products()
    {
        $cheapProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'price_pkr' => 50000,
            'is_active' => true,
            'stock' => 10,
        ]);

        $expensiveProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'price_pkr' => 300000,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/categories/' . $this->parentCategory->slug . '?sort=price&direction=asc');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertEquals($cheapProduct->id, $products->first()->id);
    }

    public function test_category_show_generates_breadcrumbs()
    {
        $response = $this->get('/categories/' . $this->childCategory->slug);

        $response->assertStatus(200);
        
        $breadcrumbs = $response->viewData('breadcrumbs');
        $this->assertIsArray($breadcrumbs);
        $this->assertGreaterThan(0, count($breadcrumbs));
        
        // Should include Home, parent category, and current category
        $breadcrumbNames = array_column($breadcrumbs, 'name');
        $this->assertContains('Home', $breadcrumbNames);
        $this->assertContains($this->parentCategory->name, $breadcrumbNames);
        $this->assertContains($this->childCategory->name, $breadcrumbNames);
    }

    public function test_category_show_displays_featured_products()
    {
        $featuredProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'is_featured' => true,
            'is_active' => true,
            'stock' => 10,
        ]);

        $regularProduct = Product::factory()->create([
            'category_id' => $this->parentCategory->id,
            'is_featured' => false,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/categories/' . $this->parentCategory->slug);

        $response->assertStatus(200);
        
        $featuredProducts = $response->viewData('featuredProducts');
        $this->assertTrue($featuredProducts->contains($featuredProduct));
        $this->assertFalse($featuredProducts->contains($regularProduct));
    }

    public function test_category_suggestions_returns_json()
    {
        $response = $this->getJson('/api/categories/suggestions?q=test');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'suggestions' => [
                '*' => ['id', 'name', 'slug', 'url']
            ]
        ]);
    }

    public function test_category_suggestions_filters_by_query()
    {
        $matchingCategory = Category::factory()->create([
            'name' => 'Gold Jewelry',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $matchingCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $nonMatchingCategory = Category::factory()->create([
            'name' => 'Silver Items',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $nonMatchingCategory->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->getJson('/api/categories/suggestions?q=gold');

        $response->assertStatus(200);
        
        $suggestions = $response->json('suggestions');
        $suggestionNames = array_column($suggestions, 'name');
        
        $this->assertContains($matchingCategory->name, $suggestionNames);
        $this->assertNotContains($nonMatchingCategory->name, $suggestionNames);
    }

    public function test_category_suggestions_returns_empty_for_short_query()
    {
        $response = $this->getJson('/api/categories/suggestions?q=a');

        $response->assertStatus(200);
        $response->assertJson(['suggestions' => []]);
    }

    public function test_category_suggestions_excludes_categories_without_products()
    {
        $categoryWithProducts = Category::factory()->create([
            'name' => 'Gold Jewelry',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'category_id' => $categoryWithProducts->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $emptyCategory = Category::factory()->create([
            'name' => 'Gold Items',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/categories/suggestions?q=gold');

        $response->assertStatus(200);
        
        $suggestions = $response->json('suggestions');
        $suggestionNames = array_column($suggestions, 'name');
        
        $this->assertContains($categoryWithProducts->name, $suggestionNames);
        $this->assertNotContains($emptyCategory->name, $suggestionNames);
    }
}