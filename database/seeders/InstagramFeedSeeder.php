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

        $destinationPath = public_path('backend/images/instagram_feeds');
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        foreach ($feeds as $feed) {
            $imageName = $feed['image_source'];
            $sourcePath = public_path('frontend/images/instagram/' . $imageName);
            $newImageName = 'demo_insta_' . $imageName;
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath . '/' . $newImageName);
                $finalImagePath = 'backend/images/instagram_feeds/' . $newImageName;
            } else {
                $finalImagePath = null;
            }

            unset($feed['image_source']);
            $feed['image'] = $finalImagePath;

            InstagramFeed::create($feed);
        }
    }
}
