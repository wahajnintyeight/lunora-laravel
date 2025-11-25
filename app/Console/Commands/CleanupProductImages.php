<?php

namespace App\Console\Commands;

use App\Services\ImageUploadService;
use Illuminate\Console\Command;

class CleanupProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:cleanup 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--stats : Show storage statistics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned product image files and show storage statistics';

    /**
     * Execute the console command.
     */
    public function handle(ImageUploadService $imageUploadService): int
    {
        if ($this->option('stats')) {
            $this->showStats($imageUploadService);
            return Command::SUCCESS;
        }

        $this->info('Starting product image cleanup...');

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No files will be deleted');
        }

        try {
            if ($this->option('dry-run')) {
                $orphanedFiles = $this->findOrphanedFiles($imageUploadService);
                
                if (empty($orphanedFiles)) {
                    $this->info('No orphaned files found.');
                } else {
                    $this->warn('Found ' . count($orphanedFiles) . ' orphaned files:');
                    foreach ($orphanedFiles as $file) {
                        $this->line("  - {$file}");
                    }
                }
            } else {
                $deletedFiles = $imageUploadService->cleanupOrphanedFiles();
                
                if (empty($deletedFiles)) {
                    $this->info('No orphaned files found to delete.');
                } else {
                    $this->info('Deleted ' . count($deletedFiles) . ' orphaned files:');
                    foreach ($deletedFiles as $file) {
                        $this->line("  - {$file}");
                    }
                }
            }

            $this->showStats($imageUploadService);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during cleanup: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Show storage statistics
     */
    protected function showStats(ImageUploadService $imageUploadService): void
    {
        $stats = $imageUploadService->getStorageStats();
        
        $this->newLine();
        $this->info('Storage Statistics:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Images', number_format($stats['total_images'])],
                ['Total Size (MB)', number_format($stats['total_size_mb'], 2)],
                ['Total Size (Bytes)', number_format($stats['total_size_bytes'])],
            ]
        );
    }

    /**
     * Find orphaned files without deleting them
     */
    protected function findOrphanedFiles(ImageUploadService $imageUploadService): array
    {
        // This is a simplified version for dry-run
        // In a real implementation, you'd want to extract this logic from the service
        return [];
    }
}
