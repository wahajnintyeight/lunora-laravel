<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_id' => $this->faker->uuid,
            'coupon_code' => null,
            'discount_pkr' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withCoupon(string $couponCode = null, int $discountPkr = 0): static
    {
        return $this->state(fn (array $attributes) => [
            'coupon_code' => $couponCode ?? $this->faker->word,
            'discount_pkr' => $discountPkr,
        ]);
    }

    public function forGuest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'session_id' => $this->faker->uuid,
        ]);
    }
}