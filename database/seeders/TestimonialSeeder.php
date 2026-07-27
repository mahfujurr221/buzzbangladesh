<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Testimonial::insert([
            [
                'name' => 'Rakib Hasan',
                'title' => 'Regular Customer',
                'comment' => 'Their collections are truly amazing! The fabric quality is great, and the price is very reasonable. I am extremely satisfied.',
                'rating' => 5,
                'active_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sarah Mim',
                'title' => 'Fashion Enthusiast',
                'comment' => 'Absolutely love the quality of the fabrics! The delivery was super fast and the fit is perfect. Highly recommend.',
                'rating' => 5,
                'active_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sakib Ahmed',
                'title' => 'Software Engineer',
                'comment' => 'The customer service is very good. I received the delivery quickly and the product looks exactly as seen in the pictures.',
                'rating' => 4,
                'active_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mayesha Siddiqua',
                'title' => 'Student',
                'comment' => 'Great value for money. The colors did not fade after washing, and the stitching is very durable. Will buy again!',
                'rating' => 5,
                'active_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
