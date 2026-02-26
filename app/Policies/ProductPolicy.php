<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    // Anyone can browse products
    public function viewAny(?User $user): bool
    {
        return true;
    }

    // Anyone can view a single product
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    // Only vendors can create products — and only for their OWN store
    public function create(User $user): bool
    {
        return $user->isVendor();
    }

    // Only the vendor who OWNS the store this product belongs to
    public function update(User $user, Product $product): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Check: does this vendor own the store this product belongs to?
        return $user->isVendor() &&
               $product->vendor->owner_id === $user->id;
    }

    // Same logic as update
    public function delete(User $user, Product $product): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isVendor() &&
               $product->vendor->owner_id === $user->id;
    }
}