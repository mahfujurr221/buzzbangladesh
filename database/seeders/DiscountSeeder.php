<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Category level discount
        $category = Category::first();
        if ($category) {
            Discount::updateOrCreate(
                ['name' => 'Eid Mega Sale - ' . $category->name],
                [
                    'level' => 'category',
                    'category_id' => $category->id,
                    'discount_percentage' => 15.00,
                    'start_date' => Carbon::now()->subDays(2),
                    'end_date' => Carbon::now()->addDays(10),
                    'active_status' => 1,
                    'created_by' => 1,
                ]
            );
        }

        // 2. Product level discount
        $products = Product::where('is_on_sale', 1)->take(3)->get();
        foreach ($products as $key => $product) {
            Discount::updateOrCreate(
                ['name' => 'Flash Deal on ' . $product->name],
                [
                    'level' => 'product',
                    'product_id' => $product->id,
                    'discount_percentage' => 20.00 + ($key * 5), // 20%, 25%, 30%
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(5),
                    'active_status' => 1,
                    'created_by' => 1,
                ]
            );
        }

        // 3. Storewide generic product discount
        $product2 = Product::where('is_on_sale', 0)->first();
        if ($product2) {
            Discount::updateOrCreate(
                ['name' => 'Weekend Special'],
                [
                    'level' => 'product',
                    'product_id' => $product2->id,
                    'discount_percentage' => 10.00,
                    'start_date' => Carbon::now()->subDay(),
                    'end_date' => Carbon::now()->addDays(3),
                    'active_status' => 1,
                    'created_by' => 1,
                ]
            );
        }
    }
}
