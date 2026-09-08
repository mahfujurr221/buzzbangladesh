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
                'image_source' => '0.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image_source' => '1.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image_source' => '2.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image_source' => '3.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image_source' => '4.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
            [
                'image_source' => '5.png',
                'link' => 'https://www.instagram.com/',
                'status' => 1,
            ],
        ];

        InstagramFeed::truncate();
        foreach ($feeds as $feed) {
            unset($feed['image_source']);
            $feed['image'] = 'instagram_feeds/placeholder.png';

            InstagramFeed::create($feed);
        }
    }
}
