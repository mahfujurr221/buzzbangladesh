<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1], // Usually settings table only has one row for global settings
            [
                'site_name' => 'Buzz',
                'email' => 'info@buzzbangladesh.com',
                'phone' => '+880 1234 567890',
                'address' => 'Dhanmondi, dhaka',
            ]
        );
    }
}
