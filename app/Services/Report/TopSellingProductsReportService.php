<?php

namespace App\Services\Report;

use Illuminate\Support\Facades\DB;

class TopSellingProductsReportService
{

    /** 
     * @param int|null $limit
     */

    public function topSellingProducts($limit = 10)
    {
        $topProducts = $topProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->where('orders.status', 'completed')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        return $topProducts;
    }
}
