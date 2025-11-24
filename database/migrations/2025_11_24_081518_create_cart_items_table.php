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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->unsignedBigInteger('unit_price_pkr'); // Price at time of adding to cart
            $table->json('customizations')->nullable(); // Engraving, special instructions, etc.
            $table->json('product_snapshot')->nullable(); // Product details at time of adding
            $table->timestamps();
            
            // Indexes
            $table->index('cart_id');
            $table->index('product_id');
            $table->index('product_variant_id');
            
            // Unique constraint to prevent duplicate items in same cart
            $table->unique(['cart_id', 'product_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
