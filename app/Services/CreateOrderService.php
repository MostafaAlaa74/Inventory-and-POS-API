<?php

namespace App\Services;

use App\Jobs\SendLowStockAlertJob;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateOrderService
{

    public function checkOut(array $orderData)
    {
        return DB::transaction(function () use ($orderData) {

            $totalAmount = 0;
            $productsID = collect($orderData['items'])->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $productsID)->lockForUpdate()->get()->keyBy('id');
            $pivotData = [];
            $itemsToReduce = [];
            // Process each order item
            foreach ($orderData['items'] as $itemData) {

                $product = $products[$itemData['product_id']];
                $quantity = $itemData['quantity'];

                $itemsToReduce[$product->id] = $quantity;

                $pivotData[$product->id] = [
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->price,
                ];

                $totalAmount += ($product->price * $itemData['quantity']);
            }
            Product::decreaseStock($itemsToReduce);

            $lowStockProducts = Product::whereIn('id', array_keys($itemsToReduce))
                ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                ->get();
            if ($lowStockProducts->isNotEmpty()) {
                SendLowStockAlertJob::dispatch($lowStockProducts)->afterCommit();
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_id' => $orderData['customer_id'] ?? null,
                'payment_method' => $orderData['payment_method'],
                'total_amount' => $totalAmount,
            ]);

            $order->products()->attach($pivotData);

            return $order->load('products');
        });
    }
}
