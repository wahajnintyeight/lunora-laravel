<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'stock' => 10,
            'price_pkr' => 150000, // PKR 1500
        ]);
    }

    public function test_products_index_displays_successfully()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas(['products', 'categories', 'materials', 'brands', 'priceRange']);
    }

    public function test_products_index_shows_active_products_only()
    {
        $activeProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => false,
            'stock' => 10,
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($activeProduct));
        $this->assertFalse($products->contains($inactiveProduct));
    }

    public function test_products_index_filters_by_category()
    {
        $category2 = Category::factory()->create();
        
        $productInCategory1 = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $productInCategory2 = Product::factory()->create([
            'category_id' => $category2->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/products?category=' . $this->category->slug);

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($productInCategory1));
        $this->assertFalse($products->contains($productInCategory2));
    }

    public function test_products_index_filters_by_search_term()
    {
        $searchableProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Gold Ring',
            'is_active' => true,
            'stock' => 10,
        ]);

        $otherProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Silver Necklace',
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/products?search=gold');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($searchableProduct));
        $this->assertFalse($products->contains($otherProduct));
    }

    public function test_products_index_filters_by_price_range()
    {
        $cheapProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 50000, // PKR 500
            'is_active' => true,
            'stock' => 10,
        ]);

        $expensiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 300000, // PKR 3000
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/products?min_price=1000&max_price=2000');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($this->product)); // PKR 1500
        $this->assertFalse($products->contains($cheapProduct));
        $this->assertFalse($products->contains($expensiveProduct));
    }

    public function test_products_index_sorts_by_price()
    {
        $cheapProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 50000,
            'is_active' => true,
            'stock' => 10,
        ]);

        $expensiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price_pkr' => 300000,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/products?sort=price&direction=asc');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertEquals($cheapProduct->id, $products->first()->id);
    }

    public function test_product_show_displays_successfully()
    {
        $response = $this->get('/products/' . $this->product->slug);

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas(['product', 'relatedProducts', 'productOptions', 'hasVariants', 'availableStock']);
    }

    public function test_product_show_returns_404_for_inactive_product()
    {
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => false,
            'stock' => 10,
        ]);

        $response = $this->get('/products/' . $inactiveProduct->slug);

        $response->assertStatus(404);
    }

    public function test_product_show_returns_404_for_out_of_stock_product()
    {
        $outOfStockProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'stock' => 0,
        ]);

        $response = $this->get('/products/' . $outOfStockProduct->slug);

        $response->assertStatus(404);
    }

    public function test_product_show_displays_related_products()
    {
        $relatedProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $unrelatedProduct = Product::factory()->create([
            'category_id' => Category::factory()->create()->id,
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/products/' . $this->product->slug);

        $response->assertStatus(200);
        
        $relatedProducts = $response->viewData('relatedProducts');
        $this->assertTrue($relatedProducts->contains($relatedProduct));
        $this->assertFalse($relatedProducts->contains($unrelatedProduct));
        $this->assertFalse($relatedProducts->contains($this->product)); // Should not include itself
    }

    public function test_search_displays_successfully()
    {
        $response = $this->get('/search?q=ring');

        $response->assertStatus(200);
        $response->assertViewIs('products.search');
        $response->assertViewHas(['products', 'searchTerm', 'suggestions']);
    }

    public function test_search_redirects_to_products_index_when_empty()
    {
        $response = $this->get('/search');

        $response->assertRedirect('/products');
    }

    public function test_search_finds_products_by_name()
    {
        $searchableProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Diamond Ring',
            'is_active' => true,
            'stock' => 10,
        ]);

        $otherProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Gold Necklace',
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/search?q=diamond');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $this->assertTrue($products->contains($searchableProduct));
        $this->assertFalse($products->contains($otherProduct));
    }

    public function test_search_provides_suggestions_when_no_results()
    {
        Product::factory()->create([
            'category_id' => $this->category->id,
            'name' => 'Gold Ring',
            'is_active' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/search?q=nonexistentproduct');

        $response->assertStatus(200);
        
        $products = $response->viewData('products');
        $suggestions = $response->viewData('suggestions');
        
        $this->assertEquals(0, $products->count());
        $this->assertIsArray($suggestions);
    }

    public function test_get_variants_returns_json_response()
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'is_active' => true,
            'stock' => 5,
            'options_json' => ['Size' => 'Medium', 'Color' => 'Gold'],
        ]);

        $response = $this->getJson('/products/' . $this->product->id . '/variants?options[Size]=Medium&options[Color]=Gold');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'variants' => [
                '*' => ['id', 'sku', 'price_pkr', 'formatted_price', 'stock', 'options']
            ]
        ]);
        
        $variants = $response->json('variants');
        $this->assertCount(1, $variants);
        $this->assertEquals($variant->id, $variants[0]['id']);
    }

    public function test_get_variants_filters_by_selected_options()
    {
        $matchingVariant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'is_active' => true,
            'stock' => 5,
            'options_json' => ['Size' => 'Medium', 'Color' => 'Gold'],
        ]);

        $nonMatchingVariant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'is_active' => true,
            'stock' => 5,
            'options_json' => ['Size' => 'Large', 'Color' => 'Silver'],
        ]);

        $response = $this->getJson('/products/' . $this->product->id . '/variants?options[Size]=Medium');

        $response->assertStatus(200);
        
        $variants = $response->json('variants');
        $this->assertCount(1, $variants);
        $this->assertEquals($matchingVariant->id, $variants[0]['id']);
    }

    public function test_get_variants_excludes_inactive_variants()
    {
        $activeVariant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'is_active' => true,
            'stock' => 5,
            'options_json' => ['Size' => 'Medium'],
        ]);

        $inactiveVariant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'is_active' => false,
            'stock' => 5,
            'options_json' => ['Size' => 'Large'],
        ]);

        $response = $this->getJson('/products/' . $this->product->id . '/variants');

        $response->assertStatus(200);
        
        $variants = $response->json('variants');
        $variantIds = array_column($variants, 'id');
        
        $this->assertContains($activeVariant->id, $variantIds);
        $this->assertNotContains($inactiveVariant->id, $variantIds);
    }
}