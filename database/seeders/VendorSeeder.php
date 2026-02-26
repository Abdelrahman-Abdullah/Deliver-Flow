<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendorUser = User::where('email', 'vendor@deliverflow.com')->first();

        Vendor::firstOrCreate(
            ['owner_id' => $vendorUser->id],
            [
                'name_en'         => "Ahmed's Restaurant",
                'name_ar'         => 'مطعم أحمد',
                'description_en'  => 'The best fast food in town',
                'description_ar'  => 'أفضل وجبات سريعة في المدينة',
                'address'         => '123 Main Street, Cairo',
                'latitude'        => 30.0444,
                'longitude'       => 31.2357,
                'is_active'       => true,
                'is_open'         => true,
            ]
        );

        $this->command->info('✅ Vendor seeded successfully.');
    }
}
