<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        
        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => str($name)->slug(),
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{4}'),
            'description' => $this->faker->paragraph(),
            'short_description' => $this->faker->sentence(),
            'material' => $this->faker->randomElement(['Gold', 'Silver', 'Platinum', 'Diamond']),
            'brand' => $this->faker->company(),
            'price_pkr' => $this->faker->numberBetween(50000, 1000000), // PKR 500 - 10,000
            'compare_at_price_pkr' => null,
            'stock' => $this->faker->numberBetween(0, 100),
            'weight_grams' => $this->faker->numberBetween(10, 500),
            'dimensions' => null,
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'track_stock' => true,
            'allow_backorder' => false,
            'meta_data' => null,
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Set a specific price for the product.
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
}