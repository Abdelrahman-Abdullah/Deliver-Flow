<?php

namespace App\Notifications\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

abstract class BaseOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    abstract public function toArray(object $notifiable): array;

    public function buildPayLoad(
        string $title,
        string $titleAr,
        string $body,
        string $bodyAr,
        string $type,
        array $data = []
    ) {
        return [
            'title' => app()->getLocale() === 'ar' ? $titleAr : $title,
            'body'  => app()->getLocale() === 'ar' ? $bodyAr : $body,
            'type'  => $type,
            'data'  => $data,
            'sent_at' => now(),
        ];
    }


}
