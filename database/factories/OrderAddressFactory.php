<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderAddress>
 */
class OrderAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'type' => OrderAddress::TYPE_SHIPPING,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'company' => $this->faker->optional()->company,
            'address_line_1' => $this->faker->streetAddress,
            'address_line_2' => $this->faker->optional()->secondaryAddress,
            'city' => $this->faker->city,
            'state_province' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => 'Pakistan',
            'phone' => $this->faker->optional()->phoneNumber,
        ];
    }

    /**
     * Indicate that the address is a shipping address.
     */
    public function shipping(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => OrderAddress::TYPE_SHIPPING,
        ]);
    }

    /**
     * Indicate that the address is a billing address.
     */
    public function billing(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => OrderAddress::TYPE_BILLING,
        ]);
    }

    /**
     * Indicate that the address is in a major Pakistani city.
     */
    public function pakistaniCity(): static
    {
        return $this->state(fn (array $attributes) => [
            'city' => $this->faker->randomElement([
                'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad',
                'Multan', 'Peshawar', 'Quetta', 'Sialkot', 'Gujranwala'
            ]),
            'state_province' => $this->faker->randomElement([
                'Sindh', 'Punjab', 'Khyber Pakhtunkhwa', 'Balochistan', 'Islamabad Capital Territory'
            ]),
            'country' => 'Pakistan',
        ]);
    }
}