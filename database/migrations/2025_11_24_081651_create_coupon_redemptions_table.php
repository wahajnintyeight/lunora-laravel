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
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('email'); // For guest orders
            $table->unsignedBigInteger('discount_amount_pkr'); // Actual discount applied
            $table->timestamps();
            
            // Indexes
            $table->index('coupon_id');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
