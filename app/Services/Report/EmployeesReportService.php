<?php

namespace App\Services\Report;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EmployeesReportService 
{
    private Builder $query;

    public function __construct()
    {
        $this->query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id', 
                'users.name', 
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_amount) as total_revenue') 
            )->groupBy('users.id', 'users.name');
    }

    public function withStatus(string $status = 'completed'): self
    {
        $this->query->where('orders.status' , $status);
        return $this;
    }

    public function betweenDates(?string $startDate, ?string $endDate): self
    {
        if ($startDate && $endDate) {
            $this->query->whereBetween('orders.created_at', [$startDate, $endDate]);
        }
        return $this;
    }

    public function get(int $limit = 3, string $orderBy = 'total_orders')
    {
        return $this->query
            ->orderByDesc($orderBy) 
            ->limit($limit)
            ->get();
    }

// public function getTopEmployees($limit = 3)
//     {
//         return DB::table('orders')
//             ->join('users', 'orders.user_id', '=', 'users.id')
//             ->select(
//                 'users.id', 
//                 'users.name', 
//                 DB::raw('COUNT(orders.id) as total_orders'),
//                 DB::raw('SUM(orders.total_amount) as total_revenue') 
//             )
//             ->where('orders.status', 'completed')
//             ->whereMonth('orders.created_at', now()->month)
//             ->whereYear('orders.created_at', now()->year)
//             ->groupBy('users.id', 'users.name')
//             ->orderByDesc('total_orders')
//             ->limit($limit)
//             ->get();
//     }

// }
}