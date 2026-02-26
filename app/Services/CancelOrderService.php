<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CancelOrderService
{

    public function cancel($orderID)
    {
        return DB::transaction(function () use ($orderID) {
            $order = Order::with('products')->findOrFail($orderID);

            foreach ($order->products as $product) {
                $product->addStock($product->pivot->quantity);
            }

            $order->update(['status' => 'cancelled']);
            return $order;
        });
    }
}
