<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use App\Services\PerformanceOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OptimizePerformance extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:optimize-performance 
                            {--cache : Warm up application caches}
                            {--database : Run database optimizations}
                            {--images : Optimize image storage}
                            {--cleanup : Clean up old cache entries}
                            {--all : Run all optimizations}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize application performance through various methods';

    /**
     * Execute the console command.
     */
    public function handle(CacheService $cacheService, PerformanceOptimizationService $performanceService): int
    {
        $this->info('Starting performance optimization...');

        $runAll = $this->option('all');

        if ($runAll || $this->option('cache')) {
            $this->optimizeCaches($cacheService);
        }

        if ($runAll || $this->option('database')) {
            $this->optimizeDatabase($performanceService);
        }

        if ($runAll || $this->option('images')) {
            $this->optimizeImages();
        }

        if ($runAll || $this->option('cleanup')) {
            $this->cleanupStorage($performanceService);
        }

        if (!$runAll && !$this->option('cache') && !$this->option('database') && !$this->option('images') && !$this->option('cleanup')) {
            $this->warn('No optimization options specified. Use --all or specific options.');
            return self::FAILURE;
        }

        $this->info('Performance optimization completed successfully!');
        return self::SUCCESS;
    }

    /**
     * Optimize application caches.
     */
    protected function optimizeCaches(CacheService $cacheService): void
    {
        $this->info('Optimizing caches...');

        // Clear existing caches
        $this->call('cache:clear');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        // Warm up application caches
        $this->info('Warming up application caches...');
        $cacheService->warmUpCaches();

        $this->info('✓ Cache optimization completed');
    }

    /**
     * Optimize database performance.
     */
    protected function optimizeDatabase(PerformanceOptimizationService $performanceService): void
    {
        $this->info('Optimizing database...');

        try {
            // Run database migrations to ensure indexes are in place
            $this->call('migrate', ['--force' => true]);

            // Analyze tables for optimization
            $this->info('Analyzing database tables...');
            $tables = ['products', 'categories', 'orders', 'users', 'carts', 'product_images'];

            foreach ($tables as $table) {
                DB::statement("ANALYZE TABLE {$table}");
            }

            // Get performance metrics
            $metrics = $performanceService->getPerformanceMetrics();

            if (isset($metrics['database']['slow_queries'])) {
                $this->info("Database slow queries: {$metrics['database']['slow_queries']}");
            }

            $this->info('✓ Database optimization completed');
        } catch (\Exception $e) {
            $this->error('Database optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Optimize image storage.
     */
    protected function optimizeImages(): void
    {
        $this->info('Optimizing images...');

        try {
            // Create symbolic link for storage
            $this->call('storage:link');

            // Clean up orphaned images
            $this->cleanupOrphanedImages();

            $this->info('✓ Image optimization completed');
        } catch (\Exception $e) {
            $this->error('Image optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up storage and optimize cache storage.
     */
    protected function cleanupStorage(PerformanceOptimizationService $performanceService): void
    {
        $this->info('Cleaning up storage...');

        try {
            // Optimize cache storage
            $results = $performanceService->optimizeCacheStorage();

            if ($results['cleaned_entries'] > 0) {
                $this->info("Cleaned up {$results['cleaned_entries']} cache entries");
            }

            // Clean up old log files (keep last 30 days)
            $this->cleanupLogFiles();

            // Clean up temporary files
            $this->cleanupTempFiles();

            $this->info('✓ Storage cleanup completed');
        } catch (\Exception $e) {
            $this->error('Storage cleanup failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up orphaned images.
     */
    protected function cleanupOrphanedImages(): void
    {
        $this->info('Cleaning up orphaned images...');

        $storagePath = storage_path('app/public/products');

        if (!is_dir($storagePath)) {
            return;
        }

        $imageFiles = glob($storagePath . '/original/*');
        $validImages = DB::table('product_images')->pluck('image_path')->toArray();

        $cleanedCount = 0;
        foreach ($imageFiles as $imageFile) {
            $relativePath = 'products/original/' . basename($imageFile);

            if (!in_array($relativePath, $validImages)) {
                if (unlink($imageFile)) {
                    $cleanedCount++;

                    // Also remove thumbnails and medium sizes
                    $filename = pathinfo($imageFile, PATHINFO_FILENAME);
                    $extension = pathinfo($imageFile, PATHINFO_EXTENSION);

                    $thumbnailPath = $storagePath . '/thumbnails/' . $filename . '.' . $extension;
                    $mediumPath = $storagePath . '/medium/' . $filename . '.' . $extension;

                    if (file_exists($thumbnailPath)) {
                        unlink($thumbnailPath);
                    }

                    if (file_exists($mediumPath)) {
                        unlink($mediumPath);
                    }
                }
            }
        }

        if ($cleanedCount > 0) {
            $this->info("Cleaned up {$cleanedCount} orphaned images");
        }
    }

    /**
     * Clean up old log files.
     */
    protected function cleanupLogFiles(): void
    {
        $logPath = storage_path('logs');
        $cutoffDate = now()->subDays(30);

        $logFiles = glob($logPath . '/*.log');
        $cleanedCount = 0;

        foreach ($logFiles as $logFile) {
            if (filemtime($logFile) < $cutoffDate->timestamp) {
                if (unlink($logFile)) {
                    $cleanedCount++;
                }
            }
        }

        if ($cleanedCount > 0) {
            $this->info("Cleaned up {$cleanedCount} old log files");
        }
    }

    /**
     * Clean up temporary files.
     */
    protected function cleanupTempFiles(): void
    {
        $tempPaths = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        $cleanedCount = 0;
        foreach ($tempPaths as $tempPath) {
            if (is_dir($tempPath)) {
                $files = glob($tempPath . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < now()->subHours(24)->timestamp) {
                        if (unlink($file)) {
                            $cleanedCount++;
                        }
                    }
                }
            }
        }

        if ($cleanedCount > 0) {
            $this->info("Cleaned up {$cleanedCount} temporary files");
        }
    }
}
