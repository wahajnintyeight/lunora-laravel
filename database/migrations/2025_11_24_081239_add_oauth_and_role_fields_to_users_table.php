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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('google_id');
            $table->enum('role', ['customer', 'admin'])->default('customer')->after('avatar_url');
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('password')->nullable()->change();
            
            // Add indexes
            $table->index('email');
            $table->index('google_id');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['google_id']);
            $table->dropIndex(['role']);
            $table->dropColumn(['google_id', 'avatar_url', 'role', 'is_active', 'last_login_at']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
