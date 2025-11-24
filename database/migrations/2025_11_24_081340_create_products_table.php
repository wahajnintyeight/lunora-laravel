<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('material')->nullable();
            $table->string('brand')->nullable();
            $table->unsignedBigInteger('price_pkr'); // Price in paisa (PKR * 100)
            $table->unsignedBigInteger('compare_at_price_pkr')->nullable(); // Original price for discounts
            $table->integer('stock')->default(0);
            $table->integer('weight_grams')->nullable();
            $table->json('dimensions')->nullable(); // Length, width, height
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('track_stock')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->json('meta_data')->nullable(); // SEO and additional data
            $table->timestamps();
            
            // Indexes
            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('stock');
            $table->index(['is_active', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
