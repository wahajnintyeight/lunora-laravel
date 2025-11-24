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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->json('options_json'); // Store selected option values as JSON
            $table->unsignedBigInteger('price_pkr')->nullable(); // Override product price if set
            $table->integer('stock')->default(0);
            $table->integer('weight_grams')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('barcode')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->index('is_active');
            $table->index('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
