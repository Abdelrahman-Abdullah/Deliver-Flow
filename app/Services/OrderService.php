<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

class OrderService
{
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

        return $order->load('items.product', 'customer', 'vendor'); // Eager load items and their associated products for the response


    }

}