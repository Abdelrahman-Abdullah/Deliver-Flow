<?php 

namespace App\Services;

use App\Models\Order;
use App\Notifications\Order\DriverAssignedNotification;
use App\Notifications\Order\OrderPlacedNotification;
use App\Notifications\Order\OrderStatusChangedNotification;

class NotificationService
{

    public function notifyOrderPlaced(Order $order)
    {
        //When an order is placed, we want to notify the restaurant (vendor) about the new order. 

        $order->load(['customer', 'items', 'vendor']);
        $order->vendor->owner->notify(new OrderPlacedNotification($order));
    }

    public function notifyOrderStatusChanged(Order $order)
    {
        //When the order status changes, we want to notify the customer about the new status of their order. 

        $order->load(['customer', 'items', 'vendor']);

      return match ($order->status) {
            Order::STATUS_CANCELLED,
            Order::STATUS_ACCEPTED,
            Order::STATUS_PREPARING,
            Order::STATUS_READY => $order->customer->notify(
                new OrderStatusChangedNotification($order)
                ),
            default => null,
        };
    }

    public function notifyDriverAssigned(Order $order)
    {
        //When a driver is assigned to an order, we want to notify the driver about the new assignment. 

        $order->load([ 'vendor' , 'driver']);
        if ($order->driver) {
            $order->driver->notify(new DriverAssignedNotification($order));
        }
    }

   
}