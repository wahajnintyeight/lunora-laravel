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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name'); // Snapshot of product name
            $table->string('product_sku'); // Snapshot of SKU
            $table->integer('quantity');
            $table->unsignedBigInteger('unit_price_pkr'); // Price at time of order
            $table->unsignedBigInteger('total_price_pkr'); // quantity * unit_price_pkr
            $table->json('customizations')->nullable(); // Engraving, special instructions, etc.
            $table->json('product_snapshot')->nullable(); // Full product details at time of order
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('product_id');
            $table->index('product_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
