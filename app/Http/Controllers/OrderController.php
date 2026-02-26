<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\CancelOrderService;
use App\Services\CreateOrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    protected $createOrderService;
    protected $cancelOrderService;
    public function __construct(
        CreateOrderService $createOrderService,
        CancelOrderService $cancelOrderService)
    {
        $this->createOrderService = $createOrderService;
        $this->cancelOrderService = $cancelOrderService;
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
        response()->json($order->load('products'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'sometimes|in:pending,completed,cancelled',
            'payment_method' => 'sometimes|in:cash,card',
        ]);

        if(isset($validatedData['status']) && $validatedData['status'] === 'cancelled' && $order->status !== 'cancelled') {
            
            try {
                $order = $this->cancelOrderService->cancel($order->id);
                return response()->json($order, 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->status === 'completed') {
            return response()->json([
                'error' => 'Completed orders cannot be deleted.'
            ], 403);
        }

        $order->delete();

        return response()->json(null, 204);
    }
}
