<?php

namespace App\Models;

use App\Jobs\SendLowStockAlertJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock_quantity',
        'SKU',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class)
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public static function decreaseStock(array $items)
    {
        if (empty($items)) {
            return;
        }


        $caseSql = '';
        $ids = array_keys($items);

        foreach ($items as $id => $reduceBy) {
            $id = (int) $id;
            $reduceBy = (int) $reduceBy;

            $caseSql .= " WHEN id = {$id} THEN stock_quantity - {$reduceBy} ";
        }

        // $lowStockProducts = self::whereIn('id', $ids)
        //     ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
        //     ->get();

        // foreach ($lowStockProducts as $product) {
        //     SendLowStockAlertJob::dispatch($product);
        // }

        self::whereIn('id', $ids)->update([
            'stock_quantity' => DB::raw("CASE {$caseSql} ELSE stock_quantity END"),
            'updated_at' => now(),
        ]);
    }

    public static function addStock(array $items)
    {
        if (empty($items)) {
            return;
        }


        $caseSql = '';
        $ids = array_keys($items);

        foreach ($items as $id => $increaseBy) {
            $id = (int) $id;
            $increaseBy = (int) $increaseBy;

            $caseSql .= " WHEN id = {$id} THEN stock_quantity + {$increaseBy} ";
        }

        self::whereIn('id', $ids)->update([
            'stock_quantity' => DB::raw("CASE {$caseSql} ELSE stock_quantity END"),
            'updated_at' => now(),
        ]);
    }
}
