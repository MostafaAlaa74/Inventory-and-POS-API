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

        // 2. إنشاء 10 أقسام، وكل قسم جواه 50 منتج
        Category::factory(10)
            ->has(Product::factory()->count(50))
            ->create();
            
        $products = Product::all();

        // 3. إنشاء 200 عميل
        $customers = Customer::factory(200)->create();

        // 4. إنشاء 2000 فاتورة وربطها بالمنتجات (Pivot Table)
        Order::factory(2000)->make()->each(function ($order) use ($users, $customers, $products) {
            // نربط الفاتورة بموظف وعميل عشوائي
            $order->customer_id = $customers->random()->id;
            $order->save();

            // نختار من منتج لـ 5 منتجات عشوائية تنزل في الفاتورة دي
            $orderProducts = $products->random(rand(1, 5));
            $totalAmount = 0;

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 5);
                $unitPrice = $product->price;

                // إضافة البيانات للجدول الوسيط (order_items)
                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ]);

                // حساب إجمالي الفاتورة
                $totalAmount += ($quantity * $unitPrice);
            }

            // تحديث إجمالي الفاتورة بعد إضافة المنتجات
            $order->update(['total_amount' => $totalAmount]);
        });
    }
}
