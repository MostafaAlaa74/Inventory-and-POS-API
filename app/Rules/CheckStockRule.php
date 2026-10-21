<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckStockRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productsID = collect($value)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productsID)->get()->keyBy('id');

        foreach($value as $itemData) {
            $product = $products[$itemData['product_id']];
            if(!$product) {
                $fail("Product with ID {$itemData['product_id']} does not exist.");
                return;
            }

            if ($product->stock_quantity < $itemData['quantity']) {
                $fail("Insufficient stock for product: {$product->name} (ID: {$product->id}). Available stock: {$product->stock_quantity}, requested: {$itemData['quantity']}.");
            }
        }
    }
}
