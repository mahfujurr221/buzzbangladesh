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
        $categories = [
            'Men' => [
                'image' => 'outfit.png',
                'subcategories' => ['T-Shirts', 'Shirts', 'Jeans & Trousers', 'Activewear']
            ],
            'Women' => [
                'image' => 'top.png',
                'subcategories' => ['Dresses', 'Tops', 'Skirts & Bottoms', 'Ethnic Wear']
            ],
            'Kids' => [
                'image' => 't-shirt.png',
                'subcategories' => ['Boys Clothing', 'Girls Clothing', 'Toys', 'School Gear']
            ],
        ];

        $destinationPath = public_path('backend/images');
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        foreach ($categories as $catName => $data) {
            $imageName = $data['image'];
            $sourcePath = public_path('frontend/images/collection/' . $imageName);
            $newImageName = 'demo_category_' . $imageName;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath . '/' . $newImageName);
            } elseif (!file_exists($destinationPath . '/' . $newImageName)) {
                $newImageName = null;
            }

            $category = Category::updateOrCreate(
                ['name' => $catName],
                [
                    'active_status' => 1,
                    'logo' => $newImageName
                ]
            );

            foreach ($data['subcategories'] as $subName) {
                SubCategory::updateOrCreate(
                    ['name' => $subName, 'category_id' => $category->id],
                    [
                        'slug' => Str::slug($subName),
                        'active_status' => 1
                    ]
                );
            }
        }
    }
}
