<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(5)->create();

        Category::factory(10)
            ->has(Product::factory()->count(50))
            ->create();
            
        $products = Product::all();

        
        $customers = Customer::factory(200)->create();

        Order::factory(2000)->make()->each(function ($order) use ($users, $customers, $products) {
            $order->customer_id = $customers->random()->id;
            $order->user_id = $users->random()->id;
            $order->save();

            $orderProducts = $products->random(rand(1, 5));
            $totalAmount = 0;

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 5);
                $unitPrice = $product->price;

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ]);

                $totalAmount += ($quantity * $unitPrice);
            }

            $order->update(['total_amount' => $totalAmount]);
        });
    }
}
