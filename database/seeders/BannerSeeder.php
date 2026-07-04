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
                'image' => 'https://via.placeholder.com/1200x400?text=Summer+Sale',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'status' => 1
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'Discover the latest trends',
                'image' => 'https://via.placeholder.com/1200x400?text=New+Arrivals',
                'button_text' => 'Discover',
                'button_link' => '#',
                'status' => 1
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
