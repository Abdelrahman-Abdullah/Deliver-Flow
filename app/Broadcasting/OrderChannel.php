<?php

namespace App\Broadcasting;

use App\Models\Order;
use App\Models\User;

class OrderChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, Order $order): array|bool
    {
        // Only Super Admins can listen to order updates for real-time monitoring
        if ($user->isSuperAdmin()) {
            return ['id' => $user->id, 'name' => $user->name];
        }

        if ($user->isDriver() && $user->id === $order->driver_id) {
            return ['id' => $user->id, 'name' => $user->name];
        }

        if ($user->isCustomer() && $user->id === $order->customer_id) {
            return ['id' => $user->id, 'name' => $user->name];
        }

        if ($user->isVendor() && $user->id === $order->vendor->owner_id) {
            return ['id' => $user->id, 'name' => $user->name];
        }


        return false;
    }
}
