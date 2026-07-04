<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Essential / Production Seeders
        $this->call([
            RolePermissionSeeder::class,
            SettingSeeder::class,
            BrandSeeder::class,
            ProductColorSeeder::class,
            ProductSizeSeeder::class,
            OrderStatusSeeder::class,
            PageSeeder::class,
        ]);

        // 2. Demo Seeders (Only in local environment)
        if (app()->environment('local', 'development', 'testing')) {
            $this->call([
                CategorySeeder::class,
                BannerSeeder::class,
                ProductSeeder::class,
            ]);
        }
    }
}
