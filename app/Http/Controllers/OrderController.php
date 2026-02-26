<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\CreateOrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    protected $createOrderService;
    public function __construct(CreateOrderService $createOrderService)
    {
        $this->createOrderService = $createOrderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::with('products')
        ->filterByStatus($request->status)
        ->filterBySpecificDate($request->date)
        ->filterByCustomer($request->user_id)
        ->latest()
        ->paginate(20);

        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        $orderData = $request->validated();

        try {
            $order = $this->createOrderService->checkOut($orderData);
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
