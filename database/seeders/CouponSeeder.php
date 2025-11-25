<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => 'Get 10% off on your first purchase',
                'type' => 'percentage',
                'percentage_value' => 10,
                'minimum_order_pkr' => 1000000, // PKR 10,000
                'usage_limit_per_user' => 1,
                'usage_limit_total' => 100,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE5000',
                'name' => 'Fixed Discount',
                'description' => 'Save PKR 5,000 on orders above PKR 50,000',
                'type' => 'fixed',
                'value_pkr' => 500000, // PKR 5,000 in paisa
                'minimum_order_pkr' => 5000000, // PKR 50,000
                'usage_limit_per_user' => 2,
                'usage_limit_total' => 50,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'BRIDAL20',
                'name' => 'Bridal Special',
                'description' => '20% off on all bridal jewelry sets',
                'type' => 'percentage',
                'percentage_value' => 20,
                'minimum_order_pkr' => 2000000, // PKR 20,000
                'usage_limit_per_user' => 1,
                'usage_limit_total' => 25,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(4),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER15',
                'name' => 'Summer Sale',
                'description' => '15% off on selected items',
                'type' => 'percentage',
                'percentage_value' => 15,
                'minimum_order_pkr' => 1500000, // PKR 15,000
                'usage_limit_per_user' => 3,
                'usage_limit_total' => 200,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'LOYALTY25',
                'name' => 'Loyalty Reward',
                'description' => '25% off for loyal customers',
                'type' => 'percentage',
                'percentage_value' => 25,
                'minimum_order_pkr' => 3000000, // PKR 30,000
                'usage_limit_per_user' => 1,
                'usage_limit_total' => 15,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Free shipping on orders above PKR 25,000',
                'type' => 'fixed',
                'value_pkr' => 150000, // PKR 1,500 shipping cost in paisa
                'minimum_order_pkr' => 2500000, // PKR 25,000
                'usage_limit_per_user' => 5,
                'usage_limit_total' => 500,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(12),
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED10',
                'name' => 'Expired Coupon',
                'description' => 'This coupon has expired (for testing)',
                'type' => 'percentage',
                'percentage_value' => 10,
                'minimum_order_pkr' => 1000000, // PKR 10,000
                'usage_limit_per_user' => 1,
                'usage_limit_total' => 100,
                'starts_at' => now()->subMonths(2),
                'expires_at' => now()->subMonth(),
                'is_active' => false,
            ],
            [
                'code' => 'FUTURE20',
                'name' => 'Future Promotion',
                'description' => 'Future promotion (not yet active)',
                'type' => 'percentage',
                'percentage_value' => 20,
                'minimum_order_pkr' => 2000000, // PKR 20,000
                'usage_limit_per_user' => 1,
                'usage_limit_total' => 50,
                'starts_at' => now()->addWeek(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::firstOrCreate(
                ['code' => $couponData['code']],
                $couponData
            );
        }

        $this->command->info('Coupons created successfully!');
        $this->command->info('Active coupons: WELCOME10, SAVE5000, BRIDAL20, SUMMER15, LOYALTY25, FREESHIP');
    }
}