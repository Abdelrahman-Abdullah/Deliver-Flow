<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;
    public function __construct(protected OrderService $orderService) {}


    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        $user = $request->user();

        if (!$user->hasAnyRole(['super_admin', 'vendor', 'driver', 'customer'])) {
            return $this->forbiddenResponse('You do not have permission to view orders');
        }

        $query = Order::with(['customer', 'vendor', 'items.product']);
        match (true) {
            $user->isSuperAdmin() => $query, // No additional filtering
            $user->isVendor()     => $query->where('vendor_id', $user->vendor_id),
            $user->isDriver()     => $query->where('driver_id', $user->id),
            $user->isCustomer()   => $query->where('customer_id', $user->id),
        };

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->latest()->paginate(10);

        return $this->successResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

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

        public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
        {
            $this->authorize('update', $order);

            try {

                $order = $this->orderService->updateOrderStatus($order, auth()->user(), $request->validated('status'));
                return $this->successResponse(new OrderResource($order), 'Order status updated successfully');
                
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 400);
            }

        }

}
