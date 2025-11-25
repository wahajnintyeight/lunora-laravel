<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add only essential indexes that don't already exist
        $this->addIndexIfNotExists('products', 'products_search_idx', ['name']);
        $this->addIndexIfNotExists('products', 'products_featured_idx', ['is_featured', 'is_active']);
        $this->addIndexIfNotExists('orders', 'orders_status_idx', ['status']);
        $this->addIndexIfNotExists('users', 'users_role_idx', ['role']);
        $this->addIndexIfNotExists('coupons', 'coupons_code_idx', ['code']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropIndexIfExists('products', 'products_search_idx');
        $this->dropIndexIfExists('products', 'products_featured_idx');
        $this->dropIndexIfExists('orders', 'orders_status_idx');
        $this->dropIndexIfExists('users', 'users_role_idx');
        $this->dropIndexIfExists('coupons', 'coupons_code_idx');
    }

    /**
     * Add index if it doesn't exist.
     */
    private function addIndexIfNotExists(string $table, string $indexName, array $columns): void
    {
        if (!$this->indexExists($table, $indexName)) {
            try {
                Schema::table($table, function (Blueprint $table) use ($indexName, $columns) {
                    $table->index($columns, $indexName);
                });
            } catch (\Exception $e) {
                // Silently ignore if index creation fails
            }
        }
    }

    /**
     * Drop index if it exists.
     */
    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if ($this->indexExists($table, $indexName)) {
            try {
                Schema::table($table, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            } catch (\Exception $e) {
                // Silently ignore if index drop fails
            }
        }
    }

    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table}");
            
            foreach ($indexes as $index) {
                if ($index->Key_name === $indexName) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            // Return false if we can't check
        }
        
        return false;
    }
};