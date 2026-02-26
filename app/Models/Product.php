<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function decreaseStock($quantity)
    {
        $updated = $this->where('id', $this->id)
            ->where('stock_quantity', '>=', $quantity)
            ->decrement('stock_quantity', $quantity);

        if($updated === 0) {
            throw new \Exception('Insufficient stock for product: ' . $this->name . $this->id);
        }


    }

    public function addStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);
    }
}
