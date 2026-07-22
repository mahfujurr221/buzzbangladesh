<?php

namespace Database\Seeders;

use App\Models\ProductColor;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Red', 'code' => '#f75e5eff'],
            ['name' => 'Blue', 'code' => '#6c6cfcff'],
            ['name' => 'Green', 'code' => '#63f863ff'],
            ['name' => 'Black', 'code' => '#494747ff'],
            ['name' => 'White', 'code' => '#ffffff'],
            ['name' => 'Yellow', 'code' => '#f7b819ff'],
            ['name' => 'Navy', 'code' => '#414477ff'],
        ];

        foreach ($colors as $color) {
            ProductColor::updateOrCreate(
                ['name' => $color['name']],
                ['code' => $color['code'], 'active_status' => 1]
            );
        }
    }
}
