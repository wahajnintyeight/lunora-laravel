<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement([Coupon::TYPE_FIXED, Coupon::TYPE_PERCENTAGE]);
        
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??????')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $type,
            'value_pkr' => $type === Coupon::TYPE_FIXED ? $this->faker->numberBetween(10000, 100000) : null,
            'percentage_value' => $type === Coupon::TYPE_PERCENTAGE ? $this->faker->numberBetween(5, 25) : null,
            'minimum_order_pkr' => 0,
            'maximum_discount_pkr' => null,
            'usage_limit_total' => null,
            'usage_limit_per_user' => null,
            'used_count' => 0,
            'is_active' => true,
            'starts_at' => now()->subDays(7),
            'expires_at' => now()->addDays(30),
            'applicable_categories' => null,
            'applicable_products' => null,
        ];
    }



    /**
     * Indicate that the coupon is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the coupon is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDays(1),
        ]);
    }

    /**
     * Create a fixed amount coupon.
     */
    public function fixed(int $amountInPaisa): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Coupon::TYPE_FIXED,
            'value_pkr' => $amountInPaisa,
            'percentage_value' => null,
        ]);
    }

    /**
     * Create a percentage coupon.
     */
    public function percentage(float $percentage): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Coupon::TYPE_PERCENTAGE,
            'value_pkr' => null,
            'percentage_value' => $percentage,
        ]);
    }

    /**
     * Set minimum order amount.
     */
    public function minimumOrder(int $amountInPaisa): static
    {
        return $this->state(fn (array $attributes) => [
            'minimum_order_pkr' => $amountInPaisa,
        ]);
    }

    /**
     * Set usage limits.
     */
    public function usageLimit(int $totalLimit, ?int $perUserLimit = null): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_limit_total' => $totalLimit,
            'usage_limit_per_user' => $perUserLimit,
        ]);
    }
}