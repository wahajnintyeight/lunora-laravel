<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $product = Product::factory()->create();
        
        return [
            'cart_id' => Cart::factory(),
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price_pkr' => $product->price_pkr,
            'customizations' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withVariant(): static
    {
        return $this->state(function (array $attributes) {
            $product = Product::find($attributes['product_id']) ?? Product::factory()->create();
            $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
            
            return [
                'product_variant_id' => $variant->id,
                'unit_price_pkr' => $variant->price_pkr ?? $product->price_pkr,
            ];
        });
    }

    public function withCustomizations(array $customizations = null): static
    {
        return $this->state(fn (array $attributes) => [
            'customizations' => $customizations ?? [
                'engraving' => $this->faker->words(3, true),
                'ring_size' => $this->faker->randomFloat(1, 4, 12),
                'instructions' => $this->faker->sentence,
            ],
        ]);
    }

    public function withQuantity(int $quantity): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
        ]);
    }
}