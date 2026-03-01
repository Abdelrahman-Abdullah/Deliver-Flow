<?php

namespace App\Notifications\Order;

use App\Models\Order;

class OrderStatusChangedNotification extends BaseOrderNotification
{
    public function __construct(public Order $order) {}

    public function toArray(object $notifiable): array
    {
        [$title, $titleAr, $body, $bodyAr] = $this->getMessageForStatus();

        return $this->buildPayLoad(
            title:   $title,
            titleAr: $titleAr,
            body:    $body,
            bodyAr:  $bodyAr,
            type:    'order_status_changed',
            data:    [
                'order_id' => $this->order->id,
                'status'   => $this->order->status,
            ]
        );
    }

    // Each status has its own message
    private function getMessageForStatus(): array
    {
        return match($this->order->status) {
            Order::STATUS_ACCEPTED => [
                'Order Accepted ✅',
                'تم قبول طلبك ✅',
                "Your order #{$this->order->id} has been accepted by the restaurant.",
                "تم قبول طلبك رقم #{$this->order->id} من المطعم.",
            ],
            Order::STATUS_PREPARING => [
                'Order Being Prepared 👨‍🍳',
                'جاري تحضير طلبك 👨‍🍳',
                "The restaurant is now preparing your order #{$this->order->id}.",
                "المطعم يقوم الآن بتحضير طلبك رقم #{$this->order->id}.",
            ],
            Order::STATUS_READY => [
                'Order Ready 📦',
                'طلبك جاهز 📦',
                "Your order #{$this->order->id} is ready and waiting for a driver.",
                "طلبك رقم #{$this->order->id} جاهز وينتظر سائقاً.",
            ],
            Order::STATUS_PICKED_UP => [
                'Driver On The Way 🚗',
                'السائق في الطريق إليك 🚗',
                "Your order #{$this->order->id} has been picked up. Track your driver live!",
                "تم استلام طلبك رقم #{$this->order->id}. تابع السائق مباشرة!",
            ],
            Order::STATUS_DELIVERED => [
                'Order Delivered 🎉',
                'تم توصيل طلبك 🎉',
                "Your order #{$this->order->id} has been delivered. Enjoy your meal!",
                "تم توصيل طلبك رقم #{$this->order->id}. بالهناء والشفاء!",
            ],
            Order::STATUS_CANCELLED => [
                'Order Cancelled ❌',
                'تم إلغاء الطلب ❌',
                "Your order #{$this->order->id} has been cancelled.",
                "تم إلغاء طلبك رقم #{$this->order->id}.",
            ],
            default => [
                'Order Updated',
                'تم تحديث الطلب',
                "Your order #{$this->order->id} has been updated.",
                "تم تحديث طلبك رقم #{$this->order->id}.",
            ],
        };
    }
}