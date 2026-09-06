<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Area::updateOrCreate(
            ['name' => 'Inside Dhaka'],
            [
                'delivery_charge' => 70.00,
                'default'         => 1,
                'status'          => 1,
            ]
        );

        \App\Models\Area::updateOrCreate(
            ['name' => 'Near Dhaka'],
            [
                'delivery_charge' => 100.00,
                'default'         => 0,
                'status'          => 1,
            ]
        );
        
        \App\Models\Area::updateOrCreate(
            ['name' => 'Outside Dhaka'],
            [
                'delivery_charge' => 120.00,
                'default'         => 0,
                'status'          => 1,
            ]
        );
    }
}
