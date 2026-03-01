<?php

namespace App\Notifications\Order;

use App\Models\Order;
use App\Notifications\Order\BaseOrderNotification;


class DriverAssignedNotification extends BaseOrderNotification
{
    public function __construct(public Order $order){}

    public function toArray(object $notifiable): array
    {
        return $this->buildPayload(
            title:   'New Delivery Assignment 🚗',
            titleAr: 'مهمة توصيل جديدة 🚗',
            body:    "You have been assigned to order #{$this->order->id}. Head to {$this->order->vendor->name_en} to pick it up.",
            bodyAr:  "تم تعيينك للطلب رقم #{$this->order->id}. توجه إلى {$this->order->vendor->name_ar} لاستلامه.",
            type:    'driver_assigned',
            data:    [
                'order_id'         => $this->order->id,
                'vendor_name'      => $this->order->vendor->name_en,
                'vendor_latitude'  => $this->order->vendor->latitude,
                'vendor_longitude' => $this->order->vendor->longitude,
                'delivery_address' => $this->order->delivery_address,
            ]
        );
    }
}
