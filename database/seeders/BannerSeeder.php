<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('BannerSeeder skipped: cannot seed demo banners in production.');
            return;
        }

        Banner::truncate();

        $banners = [
            [
                'title' => 'Curated Festive Drop \'26',
                'subtitle' => 'Timeless Heritage & Modern Luxury',
                'image' => 'banners/placeholder.png',
                'button_text' => 'Explore Collection',
                'button_link' => '/shop',
                'status' => 1,
            ],
            [
                'title' => 'The Contemporary Summer Edit',
                'subtitle' => 'Pure Cotton Linens & Everyday Minimalist Silhouettes',
                'image' => 'banners/placeholder.png',
                'button_text' => 'Shop New Arrivals',
                'button_link' => '/shop?sort=newest',
                'status' => 1,
            ],
            [
                'title' => 'Urban Streetwear & Denim',
                'subtitle' => 'Precision Cut, Premium Washes & Supreme Comfort',
                'image' => 'banners/placeholder.png',
                'button_text' => 'Discover Trends',
                'button_link' => '/shop',
                'status' => 1,
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::create($bannerData);
        }
    }
}
