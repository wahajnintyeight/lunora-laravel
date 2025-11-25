<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Sarah Ahmed',
                'email' => 'sarah.ahmed@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(2),
            ],
            [
                'name' => 'Ali Hassan',
                'email' => 'ali.hassan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subWeek(),
            ],
            [
                'name' => 'Fatima Khan',
                'email' => 'fatima.khan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(5),
            ],
            [
                'name' => 'Muhammad Usman',
                'email' => 'muhammad.usman@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(1),
            ],
            [
                'name' => 'Ayesha Malik',
                'email' => 'ayesha.malik@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(3),
            ],
            [
                'name' => 'Hassan Ali',
                'email' => 'hassan.ali@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(7),
            ],
            [
                'name' => 'Zara Sheikh',
                'email' => 'zara.sheikh@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(4),
            ],
            [
                'name' => 'Omar Farooq',
                'email' => 'omar.farooq@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(6),
            ],
            [
                'name' => 'Mariam Siddique',
                'email' => 'mariam.siddique@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(8),
            ],
            [
                'name' => 'Ahmed Raza',
                'email' => 'ahmed.raza@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(10),
            ]
        ];

        foreach ($customers as $customerData) {
            User::firstOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
        }

        $this->command->info('Sample customer accounts created successfully!');
        $this->command->info('All customers use password: password123');
    }
}