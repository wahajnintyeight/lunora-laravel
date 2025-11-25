<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class CacheService
{
    /**
     * Cache duration constants (in seconds).
     */
    const CACHE_DURATION_SHORT = 300; // 5 minutes
    const CACHE_DURATION_MEDIUM = 1800; // 30 minutes
    const CACHE_DURATION_LONG = 3600; // 1 hour
    const CACHE_DURATION_VERY_LONG = 86400; // 24 hours

    /**
     * Get cached categories with hierarchy.
     */
    public function getCategoriesWithHierarchy(): Collection
    {
        return Cache::remember('categories.hierarchy', self::CACHE_DURATION_LONG, function () {
            return Category::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        });
    }

    /**
     * Get cached featured products.
     */
    public function getFeaturedProducts(int $limit = 8): Collection
    {
        $cacheKey = "products.featured.{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION_MEDIUM, function () use ($limit) {
            return Product::with(['category', 'images'])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get cached products by category.
     */
    public function getProductsByCategory(int $categoryId, int $page = 1, int $perPage = 12): array
    {
        $cacheKey = "products.category.{$categoryId}.page.{$page}.per_page.{$perPage}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION_MEDIUM, function () use ($categoryId, $page, $perPage) {
            $offset = ($page - 1) * $perPage;
            
            $products = Product::with(['category', 'images'])
                ->where('category_id', $categoryId)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $total = Product::where('category_id', $categoryId)
                ->where('is_active', true)
                ->count();

            return [
                'products' => $products,
                'total' => $total,
                'has_more' => $total > ($page * $perPage),
            ];
        });
    }

    /**
     * Get cached product with related data.
     */
    public function getProductWithRelations(string $slug): ?Product
    {
        $cacheKey = "product.{$slug}.with_relations";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION_LONG, function () use ($slug) {
            return Product::with([
                'category',
                'images' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'options.values',
                'variants' => function ($query) {
                    $query->where('stock', '>', 0);
                }
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        });
    }

    /**
     * Get cached search results.
     */
    public function getSearchResults(string $query, int $page = 1, int $perPage = 12): array
    {
        $cacheKey = "search." . md5($query) . ".page.{$page}.per_page.{$perPage}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION_SHORT, function () use ($query, $page, $perPage) {
            $offset = ($page - 1) * $perPage;
            
            $products = Product::with(['category', 'images'])
                ->where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('sku', 'LIKE', "%{$query}%");
                })
                ->orderByRaw("
                    CASE 
                        WHEN name LIKE ? THEN 1
                        WHEN sku LIKE ? THEN 2
                        WHEN description LIKE ? THEN 3
                        ELSE 4
                    END
                ", ["%{$query}%", "%{$query}%", "%{$query}%"])
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $total = Product::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('sku', 'LIKE', "%{$query}%");
                })
                ->count();

            return [
                'products' => $products,
                'total' => $total,
                'has_more' => $total > ($page * $perPage),
                'query' => $query,
            ];
        });
    }

    /**
     * Clear product-related caches.
     */
    public function clearProductCaches(?int $productId = null, ?int $categoryId = null): void
    {
        // Clear general product caches
        Cache::forget('products.featured.8');
        Cache::forget('products.featured.12');
        Cache::forget('products.featured.16');

        // Clear category-specific caches if category is provided
        if ($categoryId) {
            $this->clearCategoryProductCaches($categoryId);
        }

        // Clear specific product cache if product ID is provided
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                Cache::forget("product.{$product->slug}.with_relations");
                $this->clearCategoryProductCaches($product->category_id);
            }
        }

        // Clear search caches (this is expensive, so only do it when necessary)
        $this->clearSearchCaches();
    }

    /**
     * Clear category-related caches.
     */
    public function clearCategoryCaches(): void
    {
        Cache::forget('categories.hierarchy');
        
        // Clear all category product caches
        $categories = Category::pluck('id');
        foreach ($categories as $categoryId) {
            $this->clearCategoryProductCaches($categoryId);
        }
    }

    /**
     * Clear category-specific product caches.
     */
    protected function clearCategoryProductCaches(int $categoryId): void
    {
        // Clear paginated category product caches (clear first 10 pages)
        for ($page = 1; $page <= 10; $page++) {
            Cache::forget("products.category.{$categoryId}.page.{$page}.per_page.12");
            Cache::forget("products.category.{$categoryId}.page.{$page}.per_page.24");
        }
    }

    /**
     * Clear search caches.
     */
    protected function clearSearchCaches(): void
    {
        // This is a simplified approach - in production you might want to use cache tags
        // or a more sophisticated cache invalidation strategy
        $cacheKeys = Cache::getRedis()->keys('*search.*');
        if (!empty($cacheKeys)) {
            Cache::getRedis()->del($cacheKeys);
        }
    }

    /**
     * Warm up essential caches.
     */
    public function warmUpCaches(): void
    {
        // Warm up categories
        $this->getCategoriesWithHierarchy();
        
        // Warm up featured products
        $this->getFeaturedProducts(8);
        $this->getFeaturedProducts(12);
        
        // Warm up first page of each category
        $categories = Category::where('is_active', true)->pluck('id');
        foreach ($categories as $categoryId) {
            $this->getProductsByCategory($categoryId, 1, 12);
        }
    }

    /**
     * Get cache statistics.
     */
    public function getCacheStats(): array
    {
        $redis = Cache::getRedis();
        
        return [
            'total_keys' => $redis->dbsize(),
            'memory_usage' => $redis->info('memory')['used_memory_human'] ?? 'N/A',
            'hit_rate' => $this->calculateHitRate(),
            'categories_cached' => Cache::has('categories.hierarchy'),
            'featured_products_cached' => Cache::has('products.featured.8'),
        ];
    }

    /**
     * Calculate cache hit rate (simplified).
     */
    protected function calculateHitRate(): string
    {
        $redis = Cache::getRedis();
        $info = $redis->info('stats');
        
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;
        
        if ($total === 0) {
            return '0%';
        }
        
        return round(($hits / $total) * 100, 2) . '%';
    }
}