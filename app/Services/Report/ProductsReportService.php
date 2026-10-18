<?php

namespace App\Services\Report;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ProductsReportService
{
    private Builder $query;

    public function __construct()
    {
        $this->query = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.id', 'products.name', 
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'))
            ->groupBy('products.id', 'products.name');
    }       

    public function withStatus(string $status = 'completed'): self
    {
        $this->query->where('orders.status', $status);
        return $this;
    }

    public function betweenDates(?string $startDate, ?string $endDate): self
    {
        if ($startDate && $endDate) {
            $this->query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        return $this;
    }

    public function get(int $limit = 10, string $orderBy = 'total_sold')
    {
        return $this->query
            ->orderByDesc($orderBy) 
            ->limit($limit)
            ->get();
    }
    /** 
     * @param int|null $limit
     */

    // public function topSellingProducts($limit = 10)
    // {
    //     $topProducts = DB::table('products')
    //         ->join('order_items', 'products.id', '=', 'order_items.product_id')
    //         ->join('orders', 'order_items.order_id', '=', 'orders.id')
    //         ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
    //         ->where('orders.status', 'completed')
    //         ->groupBy('products.id', 'products.name')
    //         ->orderByDesc('total_sold')
    //         ->limit($limit)
    //         ->get();

    //     return $topProducts;
    // }
}
