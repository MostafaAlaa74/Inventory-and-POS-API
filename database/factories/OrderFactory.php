<?php

namespace Database\Factories;

use App\Models\Customer;
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
            'customer_id' => Customer::factory(),
            'total_amount' => fake()->numberBetween(100, 10000),   
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']), 
            'payment_method' => fake()->randomElement(['cash', 'card']),       
        ];
    }
}
