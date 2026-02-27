<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = Vendor::first();

        if (!$vendor) {
            $this->command->error('No vendor found. Run VendorSeeder first.');
            return;
        }

        // Get categories by name for clean reference
        $fastFood = Category::where('name_en', 'Fast Food')->first();
        $pizza    = Category::where('name_en', 'Pizza')->first();
        $drinks   = Category::where('name_en', 'Drinks')->first();
        $desserts = Category::where('name_en', 'Desserts')->first();

        $products = [

            // -----------------------------------------------
            // Fast Food
            // -----------------------------------------------
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $fastFood->id,
                'name_en'         => 'Classic Burger',
                'name_ar'         => 'برجر كلاسيك',
                'description_en'  => 'Juicy beef patty with lettuce, tomato and pickles',
                'description_ar'  => 'برجر لحم بقري مع خس وطماطم ومخلل',
                'price'           => 45.00,
                'is_active'       => true,
                'sort_order'      => 1,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $fastFood->id,
                'name_en'         => 'Crispy Chicken Burger',
                'name_ar'         => 'برجر دجاج مقرمش',
                'description_en'  => 'Crispy fried chicken fillet with coleslaw sauce',
                'description_ar'  => 'فيليه دجاج مقلي مقرمش مع صوص كول سلو',
                'price'           => 50.00,
                'is_active'       => true,
                'sort_order'      => 2,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $fastFood->id,
                'name_en'         => 'French Fries',
                'name_ar'         => 'بطاطس مقلية',
                'description_en'  => 'Golden crispy french fries with ketchup',
                'description_ar'  => 'بطاطس مقلية ذهبية مقرمشة مع كاتشب',
                'price'           => 20.00,
                'is_active'       => true,
                'sort_order'      => 3,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $fastFood->id,
                'name_en'         => 'Hot Dog',
                'name_ar'         => 'هوت دوج',
                'description_en'  => 'Classic hot dog with mustard and ketchup',
                'description_ar'  => 'هوت دوج كلاسيك مع مسطردة وكاتشب',
                'price'           => 30.00,
                'is_active'       => true,
                'sort_order'      => 4,
            ],

            // -----------------------------------------------
            // Pizza
            // -----------------------------------------------
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $pizza->id,
                'name_en'         => 'Margherita Pizza',
                'name_ar'         => 'بيتزا مارغريتا',
                'description_en'  => 'Classic pizza with tomato sauce and mozzarella',
                'description_ar'  => 'بيتزا كلاسيك بصلصة الطماطم والموزاريلا',
                'price'           => 75.00,
                'is_active'       => true,
                'sort_order'      => 1,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $pizza->id,
                'name_en'         => 'Pepperoni Pizza',
                'name_ar'         => 'بيتزا بيبروني',
                'description_en'  => 'Loaded with pepperoni and melted cheese',
                'description_ar'  => 'محملة بالبيبروني والجبنة المذابة',
                'price'           => 90.00,
                'is_active'       => true,
                'sort_order'      => 2,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $pizza->id,
                'name_en'         => 'BBQ Chicken Pizza',
                'name_ar'         => 'بيتزا دجاج باربيكيو',
                'description_en'  => 'Grilled chicken with BBQ sauce and onions',
                'description_ar'  => 'دجاج مشوي مع صوص باربيكيو وبصل',
                'price'           => 95.00,
                'is_active'       => true,
                'sort_order'      => 3,
            ],

            // -----------------------------------------------
            // Drinks
            // -----------------------------------------------
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $drinks->id,
                'name_en'         => 'Pepsi',
                'name_ar'         => 'بيبسي',
                'description_en'  => 'Chilled Pepsi 330ml can',
                'description_ar'  => 'علبة بيبسي مبردة 330 مل',
                'price'           => 15.00,
                'is_active'       => true,
                'sort_order'      => 1,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $drinks->id,
                'name_en'         => 'Fresh Orange Juice',
                'name_ar'         => 'عصير برتقال طازج',
                'description_en'  => 'Freshly squeezed orange juice',
                'description_ar'  => 'عصير برتقال طازج معصور',
                'price'           => 25.00,
                'is_active'       => true,
                'sort_order'      => 2,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $drinks->id,
                'name_en'         => 'Water',
                'name_ar'         => 'مياه',
                'description_en'  => 'Mineral water 500ml',
                'description_ar'  => 'مياه معدنية 500 مل',
                'price'           => 8.00,
                'is_active'       => true,
                'sort_order'      => 3,
            ],

            // -----------------------------------------------
            // Desserts
            // -----------------------------------------------
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $desserts->id,
                'name_en'         => 'Chocolate Lava Cake',
                'name_ar'         => 'كيكة لافا بالشوكولاتة',
                'description_en'  => 'Warm chocolate cake with a molten center',
                'description_ar'  => 'كيكة شوكولاتة دافئة بمركز سائل',
                'price'           => 35.00,
                'is_active'       => true,
                'sort_order'      => 1,
            ],
            [
                'vendor_id'       => $vendor->id,
                'category_id'     => $desserts->id,
                'name_en'         => 'Cheesecake',
                'name_ar'         => 'تشيز كيك',
                'description_en'  => 'Creamy New York style cheesecake',
                'description_ar'  => 'تشيز كيك كريمي على الطريقة النيويوركية',
                'price'           => 40.00,
                'is_active'       => true,
                'sort_order'      => 2,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                [
                    'vendor_id' => $product['vendor_id'],
                    'name_en'   => $product['name_en'],
                ],
                $product
            );
        }

        $this->command->info('✅ Products seeded successfully — ' . count($products) . ' products added.');
    }
}
