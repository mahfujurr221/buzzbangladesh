<?php

namespace Database\Seeders;

use App\Models\ProductColor;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Midnight Black', 'code' => '#18181b'],
            ['name' => 'Pure White', 'code' => '#ffffff'],
            ['name' => 'Royal Navy', 'code' => '#1e3a8a'],
            ['name' => 'Forest Olive', 'code' => '#3f6212'],
            ['name' => 'Crimson Wine', 'code' => '#991b1b'],
            ['name' => 'Desert Khaki', 'code' => '#d97706'],
            ['name' => 'Slate Grey', 'code' => '#475569'],
        ];

        foreach ($colors as $color) {
            ProductColor::updateOrCreate(
                ['name' => $color['name']],
                ['code' => $color['code'], 'active_status' => 1]
            );
        }
    }
}
