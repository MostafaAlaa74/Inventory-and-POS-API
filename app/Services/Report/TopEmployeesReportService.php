<?php

namespace App\Services\Report;

use Illuminate\Support\Facades\DB;

class TopEmployeesReportService 
{

public function getTopEmployees($limit = 3)
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id', 
                'users.name', 
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_amount) as total_revenue') 
            )
            ->where('orders.status', 'completed')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_orders')
            ->limit($limit)
            ->get();
    }

}
