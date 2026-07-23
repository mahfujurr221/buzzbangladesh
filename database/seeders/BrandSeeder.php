<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Buzz' => '1.png',
            'Zara' => '2.png',
            'H&M' => '3.png',
            'Gucci' => '4.png',
            'Nike' => '5.png'
        ];

        $destinationPath = public_path('backend/images');
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        foreach ($brands as $name => $imageName) {
            $sourcePath = public_path('frontend/images/brand/' . $imageName);
            $newImageName = 'demo_brand_' . $imageName;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath . '/' . $newImageName);
            } else {
                $newImageName = null;
            }

            Brand::updateOrCreate(
                ['name' => $name],
                [
                    'active_status' => 1,
                    'logo' => $newImageName
                ]
            );
        }
    }
}
