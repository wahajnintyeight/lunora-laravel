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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['fixed', 'percentage']);
            $table->unsignedBigInteger('value_pkr')->nullable(); // For fixed amount coupons (in paisa)
            $table->decimal('percentage_value', 5, 2)->nullable(); // For percentage coupons (0.00-100.00)
            $table->unsignedBigInteger('minimum_order_pkr')->default(0); // Minimum order amount
            $table->unsignedBigInteger('maximum_discount_pkr')->nullable(); // Max discount for percentage coupons
            $table->integer('usage_limit_total')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_user')->nullable(); // Per user usage limit
            $table->integer('used_count')->default(0); // Track total usage
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('applicable_categories')->nullable(); // Category IDs where coupon applies
            $table->json('applicable_products')->nullable(); // Product IDs where coupon applies
            $table->timestamps();
            
            // Indexes
            $table->index('code');
            $table->index('is_active');
            $table->index('starts_at');
            $table->index('expires_at');
            $table->index(['is_active', 'starts_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
