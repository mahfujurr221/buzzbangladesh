<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Illuminate\Support\Str;

class BasicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Brands (Only Brand Buzz)
        $brands = ['Brand Buzz'];
        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand], ['active_status' => 1]);
        }

        // Seed Categories (Gen-Z T-Shirt Vibe)
        $categories = ['Graphic Tees', 'Oversized Tees', 'Anime Collection', 'Streetwear', 'Basics'];
        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[$cat] = Category::firstOrCreate(['name' => $cat], ['active_status' => 1]);
        }

        // Seed SubCategories
        $subCategories = [
            'Graphic Tees' => ['Typography Prints', 'Vintage Aesthetic', 'Pop Culture', 'Abstract Art'],
            'Oversized Tees' => ['Drop Shoulder', 'Baggy Fit', 'Heavyweight Cotton', 'Boxy Fit'],
            'Anime Collection' => ['Shonen Heroes', 'Minimalist Anime', 'Mecha Prints', 'Manga Panels'],
            'Streetwear' => ['Y2K Vibes', 'Acid Wash', 'Gothic Core', 'Cyberpunk'],
            'Basics' => ['Solid Core Colors', 'Essential Ribbed', 'Premium Blends'],
        ];

        foreach ($subCategories as $parentName => $subs) {
            if (isset($createdCategories[$parentName])) {
                $parentId = $createdCategories[$parentName]->id;
                foreach ($subs as $sub) {
                    SubCategory::firstOrCreate([
                        'name' => $sub,
                        'category_id' => $parentId
                    ], [
                        'slug' => Str::slug($sub),
                        'active_status' => 1
                    ]);
                }
            }
        }

        // Seed Sizes (Unisex/Streetwear focus)
        $sizes = [
            ['name' => 'S', 'body_size' => 'Chest: 38", Length: 27"', 'height' => '5\'2" - 5\'5"'],
            ['name' => 'M', 'body_size' => 'Chest: 40", Length: 28"', 'height' => '5\'5" - 5\'8"'],
            ['name' => 'L', 'body_size' => 'Chest: 42", Length: 29"', 'height' => '5\'8" - 5\'11"'],
            ['name' => 'XL', 'body_size' => 'Chest: 44", Length: 30"', 'height' => '5\'11" - 6\'1"'],
            ['name' => 'XXL', 'body_size' => 'Chest: 46", Length: 31"', 'height' => '6\'1" +'],
            ['name' => 'Oversized Free', 'body_size' => 'Chest: 48", Drop Shoulder', 'height' => 'Universal Fit'],
        ];

        foreach ($sizes as $size) {
            ProductSize::firstOrCreate(['name' => $size['name']], [
                'body_size' => $size['body_size'],
                'height' => $size['height'],
                'active_status' => 1
            ]);
        }

        // Seed Colors (Gen-Z Trendy Palette)
        $colors = [
            ['name' => 'Washed Black', 'code' => '#2b2b2b'],
            ['name' => 'Off-White', 'code' => '#f8f9fa'],
            ['name' => 'Matcha Green', 'code' => '#a9c0a6'],
            ['name' => 'Lavender', 'code' => '#e6e6fa'],
            ['name' => 'Charcoal', 'code' => '#36454f'],
            ['name' => 'Mocha Brown', 'code' => '#4b3621'],
            ['name' => 'Baby Pink', 'code' => '#f4c2c2'],
            ['name' => 'Core Black', 'code' => '#000000'],
            ['name' => 'Pure White', 'code' => '#ffffff'],
        ];

        foreach ($colors as $color) {
            ProductColor::firstOrCreate(['name' => $color['name']], [
                'code' => $color['code'],
                'active_status' => 1
            ]);
        }
    }
}
