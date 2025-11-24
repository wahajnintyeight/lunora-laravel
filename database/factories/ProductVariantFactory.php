<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{4}-[A-Z]{2}'),
            'options_json' => [
                'Size' => $this->faker->randomElement(['Small', 'Medium', 'Large']),
                'Color' => $this->faker->randomElement(['Gold', 'Silver', 'Rose Gold']),
            ],
            'price_pkr' => null, // Will use product price by default
            'stock' => $this->faker->numberBetween(0, 50),
            'weight_grams' => $this->faker->numberBetween(10, 500),
            'is_active' => true,
            'barcode' => null,
        ];
    }

    /**
     * Indicate that the variant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the variant is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Set a specific price for the variant.
     */
    public function price(int $priceInPaisa): static
    {
        return $this->state(fn (array $attributes) => [
            'price_pkr' => $priceInPaisa,
        ]);
    }

    /**
     * Set a specific stock quantity.
     */
    public function stock(int $quantity): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $quantity,
        ]);
    }

    /**
     * Set specific options for the variant.
     */
    public function options(array $options): static
    {
        return $this->state(fn (array $attributes) => [
            'options_json' => $options,
        ]);
    }
}