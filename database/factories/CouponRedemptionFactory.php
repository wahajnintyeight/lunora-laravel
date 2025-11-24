<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CouponRedemption>
 */
class CouponRedemptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coupon_id' => Coupon::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'discount_amount_pkr' => $this->faker->numberBetween(5000, 50000), // PKR 50 - 500
            'redeemed_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Set a specific discount amount.
     */
    public function discount(int $amountInPaisa): static
    {
        return $this->state(fn(array $attributes) => [
            'discount_amount_pkr' => $amountInPaisa,
        ]);
    }

    /**
     * Set redemption for a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Set redemption for a specific coupon.
     */
    public function forCoupon(Coupon $coupon): static
    {
        return $this->state(fn(array $attributes) => [
            'coupon_id' => $coupon->id,
        ]);
    }

    /**
     * Set redemption for a specific order.
     */
    public function forOrder(Order $order): static
    {
        return $this->state(fn(array $attributes) => [
            'order_id' => $order->id,
        ]);
    }
}
