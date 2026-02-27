<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            RolesAndPermissionsSeeder::class, // 1. Create roles & permissions
            UserSeeder::class,                // 2. Create users & assign roles
            CategorySeeder::class,            // 3. Create categories
            VendorSeeder::class,              // 4. Create vendor store
            ProductSeeder::class,             // 5. Create products 

        ]);
    }
}