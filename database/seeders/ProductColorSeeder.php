<?php

namespace Database\Seeders;

use App\Models\ProductColor;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Red', 'code' => '#FF0000'],
            ['name' => 'Blue', 'code' => '#0000FF'],
            ['name' => 'Green', 'code' => '#00FF00'],
            ['name' => 'Black', 'code' => '#000000'],
            ['name' => 'White', 'code' => '#FFFFFF'],
            ['name' => 'Yellow', 'code' => '#FFFF00'],
            ['name' => 'Navy', 'code' => '#000080'],
        ];

        foreach ($colors as $color) {
            ProductColor::updateOrCreate(
                ['name' => $color['name']],
                ['code' => $color['code'], 'active_status' => 1]
            );
        }
    }
}
