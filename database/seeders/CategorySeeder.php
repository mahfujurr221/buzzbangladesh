<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Men\'s Fashion',
            'Women\'s Fashion',
            'Electronics',
            'Home & Lifestyle',
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat],
                ['active_status' => 1]
            );
        }
    }
}
