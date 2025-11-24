<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => Order::generateOrderNumber(),
            'user_id' => User::factory(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement([
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
            ]),
            'subtotal_pkr' => $this->faker->numberBetween(50000, 500000),
            'discount_pkr' => 0,
            'shipping_pkr' => $this->faker->numberBetween(0, 50000),
            'total_pkr' => function (array $attributes) {
                return $attributes['subtotal_pkr'] - $attributes['discount_pkr'] + $attributes['shipping_pkr'];
            },
            'coupon_code' => null,
            'notes' => $this->faker->optional()->sentence(),
            'placed_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PENDING,
        ]);
    }

    /**
     * Indicate that the order is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_CONFIRMED,
        ]);
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_DELIVERED,
            'delivered_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_CANCELLED,
            'cancelled_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Indicate that the order has a coupon applied.
     */
    public function withCoupon(string $couponCode, int $discountAmount): static
    {
        return $this->state(fn (array $attributes) => [
            'coupon_code' => $couponCode,
            'discount_pkr' => $discountAmount,
            'total_pkr' => $attributes['subtotal_pkr'] - $discountAmount + $attributes['shipping_pkr'],
        ]);
    }

    /**
     * Set specific total amount.
     */
    public function total(int $totalInPaisa): static
    {
        return $this->state(fn (array $attributes) => [
            'total_pkr' => $totalInPaisa,
        ]);
    }
}