<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['title' => 'About Us', 'content' => '<p>Welcome to Buzz Bangladesh. We are the leading e-commerce platform...</p>'],
            ['title' => 'Terms and Conditions', 'content' => '<p>These terms and conditions outline the rules and regulations for the use of our website...</p>'],
            ['title' => 'Refund and Return Policies', 'content' => '<p>We offer a full money-back guarantee for all purchases made on our website if returned within 30 days...</p>'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => Str::slug($page['title'])],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'status' => 1
                ]
            );
        }
    }
}
