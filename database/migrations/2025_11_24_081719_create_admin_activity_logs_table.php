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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // e.g., 'created', 'updated', 'deleted'
            $table->string('model_type')->nullable(); // e.g., 'App\Models\Product'
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected model
            $table->json('old_values')->nullable(); // Previous values before change
            $table->json('new_values')->nullable(); // New values after change
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable(); // Human-readable description
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('model_type');
            $table->index('model_id');
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
