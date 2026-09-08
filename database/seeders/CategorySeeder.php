<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('CategorySeeder skipped: cannot seed demo categories in production.');
            return;
        }

        $categories = [
            'Men' => [
                'subcategories' => [
                    'Panjabi & Festive',
                    'T-Shirts & Polos',
                    'Casual & Formal Shirts',
                    'Jeans & Chinos',
                    'Activewear',
                ],
            ],
            'Women' => [
                'subcategories' => [
                    'Ethnic Wear & Sarees',
                    'Dresses & Gowns',
                    'Tops & Kurtis',
                    'Pants & Skirts',
                ],
            ],
            'Kids' => [
                'subcategories' => [
                    'Boys Collection',
                    'Girls Collection',
                    'Infants & Toddlers',
                ],
            ],
        ];

        foreach ($categories as $catName => $data) {
            $category = Category::updateOrCreate(
                ['name' => $catName],
                [
                    'slug' => Str::slug($catName),
                    'active_status' => 1,
                    'logo' => 'categories/placeholder.png',
                ]
            );

            foreach ($data['subcategories'] as $subName) {
                SubCategory::updateOrCreate(
                    ['name' => $subName, 'category_id' => $category->id],
                    [
                        'slug' => Str::slug($subName),
                        'active_status' => 1,
                    ]
                );
            }
        }
    }
}
