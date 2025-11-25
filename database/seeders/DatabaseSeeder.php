<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            PageSeeder::class,
            CustomerSeeder::class,
            PlaceholderImageSeeder::class,
            OrderSeeder::class,
        ]);

        // Create test customer user
        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'role' => 'customer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
