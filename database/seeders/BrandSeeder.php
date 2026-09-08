<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Buzz' => '1.png',
            'Zara' => '2.png',
            'H&M' => '3.png',
            'Gucci' => '4.png',
            'Nike' => '5.png'
        ];

        foreach ($brands as $name => $imageName) {
            Brand::updateOrCreate(
                ['name' => $name],
                [
                    'active_status' => 1,
                    'logo' => 'brands/placeholder.png'
                ]
            );
        }
    }
}
