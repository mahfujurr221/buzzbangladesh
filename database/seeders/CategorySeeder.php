<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'T-Shirts & Polos' => 't-shirt.png',
            'Shirts & Tunics' => 'top.png',
            'Jeans & Trousers' => 'outfit.png',
            'Dresses & Skirts' => 'outfit.png',
            'Jackets & Winterwear' => 'outerwear.png',
            'Activewear' => 'swimwear.png',
            'Traditional Wear' => 'outfit.png',
            'Innerwear & Sleepwear' => 'underwear.png'
        ];

        $destinationPath = public_path('backend/images');
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        foreach ($categories as $catName => $imageName) {
            $sourcePath = public_path('frontend/images/collection/' . $imageName);
            $newImageName = 'demo_category_' . $imageName;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath . '/' . $newImageName);
            } else {
                $newImageName = null;
            }

            Category::updateOrCreate(
                ['name' => $catName],
                [
                    'active_status' => 1,
                    'logo' => $newImageName
                ]
            );
        }
    }
}
