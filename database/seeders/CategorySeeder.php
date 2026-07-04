<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'T-Shirts & Polos',
            'Shirts & Tunics',
            'Jeans & Trousers',
            'Dresses & Skirts',
            'Jackets & Winterwear',
            'Activewear',
            'Traditional Wear',
            'Innerwear & Sleepwear'
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat],
                ['active_status' => 1]
            );
        }
    }
}
