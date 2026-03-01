<?php

namespace App\Notifications\Order;

use App\Models\Order;

class OrderPlacedNotification extends BaseOrderNotification
{

    public function __construct(public Order $order){}

    public function toArray(object $notifiable): array
    {
        return $this->buildPayload(
            'New Order Placed',
            'تم تقديم طلب جديد',
            "Order #{$this->order->id} has been placed by {$this->order->customer->name}.",
            "تم تقديم الطلب رقم #{$this->order->id} من قبل {$this->order->customer->name}.",
            'order_placed',
            [
                'order_id'     => $this->order->id,
                'total_amount' => $this->order->total_amount,
                'customer'     => $this->order->customer->name,
                'items_count'  => $this->order->items->count(),
            ]
        );
    }
}