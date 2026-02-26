<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_en' => 'Fast Food',  'name_ar' => 'وجبات سريعة', 'sort_order' => 1],
            ['name_en' => 'Pizza',      'name_ar' => 'بيتزا',        'sort_order' => 2],
            ['name_en' => 'Sushi',      'name_ar' => 'سوشي',         'sort_order' => 3],
            ['name_en' => 'Desserts',   'name_ar' => 'حلويات',       'sort_order' => 4],
            ['name_en' => 'Drinks',     'name_ar' => 'مشروبات',      'sort_order' => 5],
            ['name_en' => 'Groceries',  'name_ar' => 'بقالة',        'sort_order' => 6],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name_en' => $category['name_en']],
                $category
            );
        }

        $this->command->info('✅ Categories seeded successfully.');
    }
}
