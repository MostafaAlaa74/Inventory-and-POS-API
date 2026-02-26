<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Product::class);
        $products = Product::with('category')->get();

        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        Gate::authorize('create', Product::class);
        $data = $request->validated();
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);
        return response()->json($product->load('category'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);
        $data = $request->validated();
        $product->update($data);
        return response()->json($product->load('category'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        $product->delete();
        return response()->json(null, 204);
    }

    public function addStock(Request $request, Product $product)
    {
        Gate::authorize('addStock', $product);
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        $product->addStock($quantity);
        // Refresh the model instance to get the updated stock quantity
        $product->refresh();
        return response()->json($product, 200);
    }

    public function decreaseStock(Request $request, Product $product)
    {
        Gate::authorize('decreaseStock', $product);
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        try {
            $product->decreaseStock($quantity);
            // Refresh the model instance to get the updated stock quantity
            $product->refresh();
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
