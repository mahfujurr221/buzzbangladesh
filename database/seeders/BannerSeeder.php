<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Summer Sale',
                'subtitle' => 'Up to 50% Off',
                'image_source' => 'bg1-2.png',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'status' => 1
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'Discover the latest trends',
                'image_source' => 'bg1-3.png',
                'button_text' => 'Discover',
                'button_link' => '#',
                'status' => 1
            ],
        ];

        $destinationPath = public_path('backend/images/banners');
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        foreach ($banners as $bannerData) {
            $imageName = $bannerData['image_source'];
            $sourcePath = public_path('frontend/images/slider/' . $imageName);
            $newImageName = 'demo_banner_' . $imageName;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath . '/' . $newImageName);
                $finalImagePath = 'backend/images/banners/' . $newImageName;
            } elseif (file_exists($destinationPath . '/' . $newImageName)) {
                $finalImagePath = 'backend/images/banners/' . $newImageName;
            } else {
                // Use a valid placeholder path that actually exists
                $finalImagePath = 'backend/images/products/placeholder.png';
            }

            unset($bannerData['image_source']);
            $bannerData['image'] = $finalImagePath;

            Banner::updateOrCreate(
                ['title' => $bannerData['title']],
                $bannerData
            );
        }
    }
}
