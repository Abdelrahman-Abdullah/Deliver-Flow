<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    // View any vendor — everyone including guests
    public function viewAny(?User $user): bool
    {
        return true;
    }

    // View single vendor — everyone including guests
    public function view(?User $user, Vendor $vendor): bool
    {
        return true;
    }

    // Create vendor — super_admin only
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    // Update vendor:
    // super_admin → can update ANY vendor
    // vendor      → can only update THEIR OWN store
    public function update(User $user, Vendor $vendor): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isVendor() && $vendor->owner_id === $user->id;
    }

    // Delete vendor — super_admin only
    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->isSuperAdmin();
    }
}