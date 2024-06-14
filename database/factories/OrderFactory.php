<?php

namespace Database\Factories;

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
    public function definition()
    {
        // Generate random date and time for each order within the last year
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'address_id' => function () {
                return \App\Models\Address::factory()->create()->id;
            },
            'subtotal' => $this->faker->numberBetween(1000, 5000),
            'shipping_cost' => $this->faker->numberBetween(100, 500),
            'total_cost' => function (array $attributes) {
                return $attributes['subtotal'] + $attributes['shipping_cost'];
            },
            'status' => $this->faker->randomElement(['pending', 'paid', 'on_delivery', 'delivered', 'expired', 'canceled']),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'ewallet']),
            'shipping_service' => $this->faker->randomElement(['JNE', 'TIKI', 'POS']),
            'transaction_number' => $this->faker->uuid,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
