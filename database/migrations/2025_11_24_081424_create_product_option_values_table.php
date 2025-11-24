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
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->string('value'); // e.g., "Small", "Medium", "Large", "Gold", "Silver"
            $table->string('display_value')->nullable(); // For display purposes
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('price_adjustment_pkr')->default(0); // Additional cost in paisa
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('product_option_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_values');
    }
};
