<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles and permissions
        // Always do this before seeding to avoid stale cache issues
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -----------------------------------------------
        // 1. CREATE ALL PERMISSIONS
        // -----------------------------------------------
        $permissions = [

            // User management (super_admin only)
            'manage users',

            // Vendor management
            'manage vendors',        // super_admin: manage ALL vendors
            'manage own vendor',     // vendor: manage their OWN store only

            // Category management (super_admin only)
            'manage categories',

            // Product management
            'manage own products',   // vendor: CRUD their own products

            // Order management
            'manage orders',         // super_admin: see and manage ALL orders
            'manage own orders',     // vendor: manage orders for their store
            'place orders',          // customer: create new orders
            'view own orders',       // customer: see their order history
            'track order',           // customer: see live driver location

            // Driver actions
            'view assigned orders',  // driver: see orders assigned to them
            'update order status',   // driver: mark as picked_up / delivered
            'update own location',   // driver: send GPS coordinates

            // Reports
            'view reports',          // super_admin: full platform analytics
            'view own reports',      // vendor: their store analytics only
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // -----------------------------------------------
        // 2. CREATE ROLES AND ASSIGN PERMISSIONS
        // -----------------------------------------------

        // SUPER ADMIN — gets everything
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // VENDOR — manages their own store
        $vendor = Role::firstOrCreate(['name' => 'vendor']);
        $vendor->givePermissionTo([
            'manage own vendor',
            'manage own products',
            'manage own orders',
            'view own reports',
        ]);

        // DRIVER — delivery actions only
        $driver = Role::firstOrCreate(['name' => 'driver']);
        $driver->givePermissionTo([
            'view assigned orders',
            'update order status',
            'update own location',
        ]);

        // CUSTOMER — ordering actions only
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->givePermissionTo([
            'place orders',
            'view own orders',
            'track order',
        ]);

        $this->command->info('✅ Roles and permissions seeded successfully.');
    }
}
