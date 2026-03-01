<?php

namespace App\Services;

use App\Models\{
    Order,
    User,
    Vendor
};

class OrderService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function placeOrder(User $customer, array $data)
    {
       $vendor = Vendor::findOrFail($data['vendor_id']);

       if (!$vendor->is_active) {
           throw new \Exception('Vendor is currently unavailable');
       }


       // Fetch all products in the order and ensure they belong to the vendor and are active
       $vendorProducts = $vendor->activeProducts()
                                ->whereIn('id', array_column($data['items'], 'product_id'))
                                ->get()
                                ->keyBy('id');
        $totalPrice = 0;        
        $orderItems = [];
        foreach ($data['items'] as $item) {
             // What About order product not belonging to the vendor?
            if (!$vendorProducts->has($item['product_id'])) {
                throw new \Exception('Product ID ' . $item['product_id'] . ' is not available from this vendor', 400);

            }

            $product = $vendorProducts[$item['product_id']];
            $productSubtotal = $product->price * $item['quantity'];
            $totalPrice += $productSubtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price, // Store the price at the time of order
                'subtotal'   => $productSubtotal, // Store the subtotal at the time of order according to the quantity
            ];
        }


        $order = Order::create([
            'customer_id'         => $customer->id,
            'vendor_id'           => $data['vendor_id'],
            'status'              => Order::STATUS_PENDING,
            'total_amount'        => $totalPrice,
            'delivery_address'    => $data['delivery_address'],
            'delivery_latitude'   => $data['delivery_latitude'],
            'delivery_longitude'  => $data['delivery_longitude'],
            'notes'               => $data['notes'] ?? null,
        ]);

        //Create all order items at once (one DB query)

        $order->items()->createMany($orderItems);
        $this->notificationService->notifyOrderPlaced($order);

        return $order->load('items.product', 'customer', 'vendor'); // Eager load items and their associated products for the response


    }

    public function updateOrderStatus(Order $order, User $user, string $status)
    {

        $allowedStatuses = $this->getAllowedTransitions($order, $user);

        if (!in_array($status, $allowedStatuses)) {
            throw new \Exception("Cannot transition order from '{$order->status}' to '{$status}'.");
        }

        $order->status = $status;
        if ($status === Order::STATUS_DELIVERED) {
            $order->delivered_at = now();
        }
        $order->save();

        $this->notificationService->notifyOrderStatusChanged($order);

        return $order->fresh(); // Return the updated order with all relationships loaded
    }

    public function assignDriver(Order $order, int $driverId)
    {
        $user = User::findOrFail($driverId);
        if ($user && !$user->isDriver() ) {
            throw new \Exception('Only drivers can be assigned to orders');
        }
        if (!$user->isActive()) {
            throw new \Exception('Driver is currently unavailable');
        }
        if ($order->status !== Order::STATUS_READY) {
            throw new \Exception('Only orders that are ready can be assigned to drivers');
        }
        $order->update([
            'driver_id' => $user->id,
        ]);

        $this->notificationService->notifyDriverAssigned($order);
        return $order->fresh(); // Return the updated order with all relationships loaded

    }

    private function getAllowedTransitions(Order $order, User $user): array
    {
        if ($user->isVendor()) {
            return match ($order->status) {
                Order::STATUS_PENDING   => [Order::STATUS_ACCEPTED, Order::STATUS_CANCELLED],
                Order::STATUS_ACCEPTED  => [Order::STATUS_PREPARING, Order::STATUS_CANCELLED],
                Order::STATUS_PREPARING => [Order::STATUS_READY],
                default                 => [],
    
            };
        }

        if ($user->isDriver()) {
            return match ($order->status) {
                Order::STATUS_READY => [Order::STATUS_PICKED_UP],
                Order::STATUS_PICKED_UP => [Order::STATUS_DELIVERED],
                default => [],
            };
        }

        if ($user->isCustomer()) {
            return match ($order->status) {
                Order::STATUS_PENDING => [Order::STATUS_CANCELLED],
                default => [],
            };
        }

        if ($user->isSuperAdmin()) {
            return [
                Order::STATUS_PENDING,
                Order::STATUS_ACCEPTED,
                Order::STATUS_PREPARING,
                Order::STATUS_READY,
                Order::STATUS_PICKED_UP,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ];
  
        }

        return [];


    }

}