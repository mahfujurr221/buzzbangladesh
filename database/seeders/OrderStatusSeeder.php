<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'color_code' => '#FFC107', 'is_default' => true],
            ['name' => 'Received', 'color_code' => '#17A2B8', 'is_default' => false],
            ['name' => 'Packed', 'color_code' => '#007BFF', 'is_default' => false],
            ['name' => 'Shipped', 'color_code' => '#6610F2', 'is_default' => false],
            ['name' => 'Delivered', 'color_code' => '#28A745', 'is_default' => false],
            ['name' => 'Canceled', 'color_code' => '#DC3545', 'is_default' => false],
            ['name' => 'Returned', 'color_code' => '#6C757D', 'is_default' => false],
        ];

        foreach ($statuses as $status) {
            OrderStatus::firstOrCreate(
                ['name' => $status['name']],
                [
                    'color_code' => $status['color_code'],
                    'is_default' => $status['is_default'],
                ]
            );
        }
    }
}
