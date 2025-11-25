<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = $this->faker->numberBetween(50000, 500000); // PKR 500 - 5000
        $quantity = $this->faker->numberBetween(1, 3);
        $totalPrice = $unitPrice * $quantity;

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'product_name' => $this->faker->words(3, true),
            'product_sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'quantity' => $quantity,
            'unit_price_pkr' => $unitPrice,
            'total_price_pkr' => $totalPrice,
            'customizations' => null,
            'product_snapshot' => [
                'product_name' => $this->faker->words(3, true),
                'product_sku' => $this->faker->regexify('[A-Z]{3}[0-9]{3}'),
                'variant_options' => null,
                'product_details' => [
                    'description' => $this->faker->paragraph,
                    'material' => $this->faker->randomElement(['Gold', 'Silver', 'Platinum', 'Diamond']),
                    'brand' => 'Lunora',
                ]
            ],
        ];
    }

    /**
     * Indicate that the order item has customizations.
     */
    public function withCustomizations(): static
    {
        return $this->state(fn (array $attributes) => [
            'customizations' => [
                'engraving' => $this->faker->words(2, true),
                'size' => $this->faker->randomElement(['Small', 'Medium', 'Large']),
            ],
        ]);
    }

    /**
     * Indicate that the order item has a variant.
     */
    public function withVariant(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_variant_id' => ProductVariant::factory(),
            'product_snapshot' => array_merge($attributes['product_snapshot'] ?? [], [
                'variant_options' => [
                    'Size' => $this->faker->randomElement(['Small', 'Medium', 'Large']),
                    'Color' => $this->faker->randomElement(['Gold', 'Silver', 'Rose Gold']),
                ]
            ]),
        ]);
    }
}