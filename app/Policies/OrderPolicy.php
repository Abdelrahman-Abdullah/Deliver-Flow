<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // super_admin sees ALL orders
        // vendor sees only THEIR store orders
        // driver sees only THEIR assigned orders
        // customer sees only THEIR own orders
        return true; 
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Admin can update any order
        // Vendor can update orders for their store
        // Driver can update orders assigned to them
        // Customer can update their own orders but only if they are still pending or accepted (not yet preparing)
       return match (true) {
            $user->isSuperAdmin() => true,
            $user->isVendor()     => $order->vendor->owner_id === $user->id,
            $user->isDriver()     => $order->driver_id === $user->id,
            $user->isCustomer()   => $order->customer_id === $user->id, 
            default               => false,
        };
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function cancle(User $user, Order $order): bool
    {
        return $user->isCustomer()
         && $order->customer_id === $user->id 
         && $order->isCancellable();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
