<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CreateOrderService
{

    public function checkOut(array $orderData)
    {
        return DB::transaction(function () use ($orderData) {

            $order = Order::create([
                'customer_id' => $orderData['customer_id'] ?? null,
                'payment_method' => $orderData['payment_method'],
                'total_amount' => 0, // Will be updated later
            ]);

            $totalAmount = 0;
            $productsID = collect($orderData['items'])->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $productsID)->get()->keyBy('id');
            $pivotData = [];
            // Process each order item
            foreach ($orderData['items'] as $itemData) {
                
                $product = $products[$itemData['product_id']];
                $product->decreaseStock($itemData['quantity']);
                
                $pivotData[$product->id] = [
                'quantity' => $itemData['quantity'],
                'unit_price' => $product->price,
                ];

                // Update the total amount
                $totalAmount += ($product->price * $itemData['quantity']);

            }

            $order->products()->attach($pivotData);

            $order->update(['total_amount' => $totalAmount]);

            return $order->load('products');
        });
    }
}
