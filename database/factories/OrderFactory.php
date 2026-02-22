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
    public function definition(): array
    {
        return [
            'total_amount' => 0,
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'payment_method' => fake()->randomElement(['cash', 'visa']),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
