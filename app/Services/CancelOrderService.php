<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CancelOrderService
{

    public function cancel($orderID)
    {
        return DB::transaction(function () use ($orderID) {
            $order = Order::with('products')->lockForUpdate()->findOrFail($orderID);
            $itemsToAddBack = [];
            foreach ($order->products as $product) {
                $itemsToAddBack[$product->id] = $product->pivot->quantity;
            }
            Product::addStock($itemsToAddBack);

            $order->update(['status' => 'cancelled']);
            return $order;
        });
    }
}
