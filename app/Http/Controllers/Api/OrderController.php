<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Traits\ApiResponse;

class OrderController extends Controller
{
    use ApiResponse;
    public function __construct(protected OrderService $orderService) {}

    public function store(StoreOrderRequest $request)
    {
        try {
            $this->authorize('create', Order::class);

            $order = $this->orderService->placeOrder(auth()->user(), $request->validated());

            return $this->createdResponse(new OrderResource($order), 'Order placed successfully', 201);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    
}
