<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------
        // SUPER ADMIN
        // -----------------------------------------------
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@deliverflow.com'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('password'),
                'phone'     => '01000000001',
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // -----------------------------------------------
        // VENDOR
        // -----------------------------------------------
        $vendor = User::firstOrCreate(
            ['email' => 'vendor@deliverflow.com'],
            [
                'name'      => 'Ahmed Vendor',
                'password'  => Hash::make('password'),
                'phone'     => '01000000002',
                'is_active' => true,
            ]
        );
        $vendor->assignRole('vendor');

        // -----------------------------------------------
        // DRIVER
        // -----------------------------------------------
        $driver = User::firstOrCreate(
            ['email' => 'driver@deliverflow.com'],
            [
                'name'      => 'Mohamed Driver',
                'password'  => Hash::make('password'),
                'phone'     => '01000000003',
                'is_active' => true,
            ]
        );
        $driver->assignRole('driver');

        // -----------------------------------------------
        // CUSTOMER
        // -----------------------------------------------
        $customer = User::firstOrCreate(
            ['email' => 'customer@deliverflow.com'],
            [
                'name'      => 'Sara Customer',
                'password'  => Hash::make('password'),
                'phone'     => '01000000004',
                'is_active' => true,
            ]
        );
        $customer->assignRole('customer');

        $this->command->info('✅ Users seeded successfully.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['super_admin', 'admin@deliverflow.com',    'password'],
                ['vendor',      'vendor@deliverflow.com',   'password'],
                ['driver',      'driver@deliverflow.com',   'password'],
                ['customer',    'customer@deliverflow.com', 'password'],
            ]
        );
    }
}
