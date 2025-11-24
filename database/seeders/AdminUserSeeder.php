<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@lunora.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create additional admin user for testing
        User::firstOrCreate(
            ['email' => 'manager@lunora.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('manager123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@lunora.com / admin123');
        $this->command->info('Manager: manager@lunora.com / manager123');
    }
}