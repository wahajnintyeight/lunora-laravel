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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('status', [
                'pending',
                'confirmed', 
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->unsignedBigInteger('subtotal_pkr');
            $table->unsignedBigInteger('discount_pkr')->default(0);
            $table->unsignedBigInteger('shipping_pkr')->default(0);
            $table->unsignedBigInteger('tax_pkr')->default(0);
            $table->unsignedBigInteger('total_pkr');
            $table->string('coupon_code')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('payment_details')->nullable(); // For future payment integration
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('placed_at');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
