<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PerformanceOptimizationService
{
    /**
     * Optimize product queries with eager loading.
     */
    public function optimizeProductQuery(Builder $query): Builder
    {
        return $query->with([
            'category:id,name,slug',
            'images' => function ($query) {
                $query->select('id', 'product_id', 'file_path', 'alt_text', 'sort_order')
                      ->orderBy('sort_order');
            }
        ])->select([
            'id', 'category_id', 'name', 'slug', 'sku', 
            'price_pkr', 'compare_at_price_pkr', 'stock', 
            'is_active', 'is_featured', 'created_at'
        ]);
    }

    /**
     * Optimize category queries with eager loading.
     */
    public function optimizeCategoryQuery(Builder $query): Builder
    {
        return $query->with([
            'children' => function ($query) {
                $query->select('id', 'parent_id', 'name', 'slug', 'is_active', 'sort_order')
                      ->where('is_active', true)
                      ->orderBy('sort_order');
            }
        ])->select([
            'id', 'parent_id', 'name', 'slug', 'description', 
            'is_active', 'sort_order', 'created_at'
        ]);
    }

    /**
     * Create efficient pagination with cursor-based pagination for large datasets.
     */
    public function createEfficientPagination(
        Builder $query, 
        Request $request, 
        int $perPage = 15,
        string $cursorColumn = 'id'
    ): LengthAwarePaginator {
        // Use cursor pagination for better performance on large datasets
        $cursor = $request->get('cursor');
        
        if ($cursor) {
            $query->where($cursorColumn, '>', $cursor);
        }
        
        // Get one extra item to determine if there are more pages
        $items = $query->limit($perPage + 1)->get();
        $hasMore = $items->count() > $perPage;
        
        if ($hasMore) {
            $items = $items->slice(0, $perPage);
        }
        
        // Create pagination manually
        $currentPage = $request->get('page', 1);
        $path = $request->url();
        
        return new LengthAwarePaginator(
            $items,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => $path,
                'pageName' => 'page',
            ]
        );
    }

    /**
     * Optimize database queries by adding appropriate indexes.
     */
    public function getRecommendedIndexes(): array
    {
        return [
            'products' => [
                ['category_id', 'is_active', 'stock'],
                ['is_featured', 'is_active'],
                ['slug'],
                ['sku'],
                ['created_at'],
                ['name'], // For search
            ],
            'categories' => [
                ['parent_id', 'is_active', 'sort_order'],
                ['slug'],
                ['is_active'],
            ],
            'product_images' => [
                ['product_id', 'sort_order'],
            ],
            'orders' => [
                ['user_id', 'status'],
                ['order_number'],
                ['created_at'],
                ['status'],
            ],
            'order_items' => [
                ['order_id'],
                ['product_id'],
            ],
            'carts' => [
                ['user_id'],
                ['session_id'],
                ['updated_at'],
            ],
            'cart_items' => [
                ['cart_id'],
                ['product_id'],
            ],
            'users' => [
                ['email'],
                ['role'],
                ['is_active'],
            ],
            'admin_activity_logs' => [
                ['user_id', 'performed_at'],
                ['performed_at'],
                ['action'],
            ],
        ];
    }

    /**
     * Analyze slow queries and provide optimization suggestions.
     */
    public function analyzeSlowQueries(): array
    {
        // Enable query logging
        DB::enableQueryLog();
        
        // This would typically be called after running some operations
        $queries = DB::getQueryLog();
        
        $slowQueries = [];
        foreach ($queries as $query) {
            if ($query['time'] > 100) { // Queries taking more than 100ms
                $slowQueries[] = [
                    'sql' => $query['query'],
                    'time' => $query['time'],
                    'bindings' => $query['bindings'],
                    'suggestions' => $this->getSuggestions($query['query']),
                ];
            }
        }
        
        return $slowQueries;
    }

    /**
     * Get optimization suggestions for a query.
     */
    protected function getSuggestions(string $sql): array
    {
        $suggestions = [];
        
        // Check for missing WHERE clauses
        if (stripos($sql, 'select') !== false && stripos($sql, 'where') === false) {
            $suggestions[] = 'Consider adding WHERE clause to limit results';
        }
        
        // Check for SELECT *
        if (stripos($sql, 'select *') !== false) {
            $suggestions[] = 'Avoid SELECT *, specify only needed columns';
        }
        
        // Check for N+1 queries
        if (stripos($sql, 'select') !== false && stripos($sql, 'in (') !== false) {
            $suggestions[] = 'Possible N+1 query - consider using eager loading';
        }
        
        // Check for ORDER BY without LIMIT
        if (stripos($sql, 'order by') !== false && stripos($sql, 'limit') === false) {
            $suggestions[] = 'ORDER BY without LIMIT can be expensive on large datasets';
        }
        
        return $suggestions;
    }

    /**
     * Optimize image loading with lazy loading and responsive images.
     */
    public function optimizeImageLoading(Collection $products): Collection
    {
        return $products->map(function ($product) {
            if ($product->images) {
                $product->images = $product->images->map(function ($image) {
                    // Add responsive image URLs
                    $image->responsive_urls = [
                        'thumbnail' => $image->thumbnail_url ?? $this->getResponsiveImageUrl($image->file_path, 'thumbnail'),
                        'medium' => $image->medium_url ?? $this->getResponsiveImageUrl($image->file_path, 'medium'),
                        'large' => $image->url ?? $this->getResponsiveImageUrl($image->file_path, 'large'),
                    ];
                    
                    return $image;
                });
            }
            
            return $product;
        });
    }

    /**
     * Get responsive image URL.
     */
    protected function getResponsiveImageUrl(string $imagePath, string $size): string
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        return asset("storage/{$directory}/{$size}/{$filename}.{$extension}");
    }

    /**
     * Clean up old cache entries and optimize cache storage.
     */
    public function optimizeCacheStorage(): array
    {
        $results = [
            'cleaned_entries' => 0,
            'freed_memory' => 0,
            'errors' => [],
        ];
        
        try {
            $redis = \Illuminate\Support\Facades\Cache::getRedis();
            
            // Get all keys
            $keys = $redis->keys('*');
            $cleanedCount = 0;
            
            foreach ($keys as $key) {
                $ttl = $redis->ttl($key);
                
                // Remove expired keys that haven't been cleaned up
                if ($ttl === -2) {
                    $redis->del($key);
                    $cleanedCount++;
                }
                
                // Remove very old cache entries (older than 7 days)
                if ($ttl > 604800) {
                    $redis->del($key);
                    $cleanedCount++;
                }
            }
            
            $results['cleaned_entries'] = $cleanedCount;
            
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }
        
        return $results;
    }

    /**
     * Get performance metrics.
     */
    public function getPerformanceMetrics(): array
    {
        return [
            'database' => $this->getDatabaseMetrics(),
            'cache' => $this->getCacheMetrics(),
            'memory' => $this->getMemoryMetrics(),
            'queries' => $this->getQueryMetrics(),
        ];
    }

    /**
     * Get database performance metrics.
     */
    protected function getDatabaseMetrics(): array
    {
        try {
            $result = DB::select('SHOW STATUS LIKE "Slow_queries"');
            $slowQueries = $result[0]->Value ?? 0;
            
            $result = DB::select('SHOW STATUS LIKE "Questions"');
            $totalQueries = $result[0]->Value ?? 0;
            
            return [
                'slow_queries' => $slowQueries,
                'total_queries' => $totalQueries,
                'slow_query_percentage' => $totalQueries > 0 ? round(($slowQueries / $totalQueries) * 100, 2) : 0,
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Unable to fetch database metrics: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get cache performance metrics.
     */
    protected function getCacheMetrics(): array
    {
        try {
            $redis = \Illuminate\Support\Facades\Cache::getRedis();
            $info = $redis->info();
            
            return [
                'memory_usage' => $info['used_memory_human'] ?? 'N/A',
                'total_keys' => $redis->dbsize(),
                'hit_rate' => $this->calculateCacheHitRate($info),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Unable to fetch cache metrics: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get memory usage metrics.
     */
    protected function getMemoryMetrics(): array
    {
        return [
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'memory_limit' => ini_get('memory_limit'),
        ];
    }

    /**
     * Get query performance metrics.
     */
    protected function getQueryMetrics(): array
    {
        $queries = DB::getQueryLog();
        
        if (empty($queries)) {
            return ['message' => 'Query logging is not enabled'];
        }
        
        $totalTime = array_sum(array_column($queries, 'time'));
        $avgTime = count($queries) > 0 ? $totalTime / count($queries) : 0;
        
        return [
            'total_queries' => count($queries),
            'total_time' => $totalTime,
            'average_time' => round($avgTime, 2),
            'slowest_query' => max(array_column($queries, 'time')),
        ];
    }

    /**
     * Calculate cache hit rate.
     */
    protected function calculateCacheHitRate(array $info): string
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;
        
        if ($total === 0) {
            return '0%';
        }
        
        return round(($hits / $total) * 100, 2) . '%';
    }
}