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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->json('meta_data')->nullable(); // SEO meta title, description, keywords
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('template')->default('default'); // For different page layouts
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_published');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
