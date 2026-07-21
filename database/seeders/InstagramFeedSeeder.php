<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstagramFeed;

class InstagramFeedSeeder extends Seeder
{
    public function run(): void
    {
        $feeds = [
            [
                'image' => 'frontend/images/instagram/0.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image' => 'frontend/images/instagram/1.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image' => 'frontend/images/instagram/2.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image' => 'frontend/images/instagram/3.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image' => 'frontend/images/instagram/4.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image' => 'frontend/images/instagram/5.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
        ];

        foreach ($feeds as $feed) {
            InstagramFeed::create($feed);
        }
    }
}
