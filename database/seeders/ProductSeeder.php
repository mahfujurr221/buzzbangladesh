<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductVariation;
use App\Models\StockLedger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::where('name', 'Buzz')->first();
        $categories = Category::all();
        $colors = ProductColor::take(2)->get();
        $sizes = ProductSize::take(3)->get();

        if (!$brand || $categories->isEmpty() || $colors->isEmpty() || $sizes->isEmpty()) {
            return; // Prerequisites not met
        }

        $dummyProducts = [
            ['name' => 'Classic Cotton Basic T-Shirt', 'price' => 500],
            ['name' => 'Premium Polo Shirt', 'price' => 850],
            ['name' => 'Slim Fit Denim Jeans', 'price' => 1200],
            ['name' => 'Relaxed Fit Cargo Pants', 'price' => 1400],
            ['name' => 'Elegant Silk Maxi Dress', 'price' => 2500],
            ['name' => 'Floral Summer Sundress', 'price' => 1800],
            ['name' => 'Waterproof Windbreaker Jacket', 'price' => 2200],
            ['name' => 'Fleece Lined Winter Hoodie', 'price' => 1600],
            ['name' => 'Breathable Running Shorts', 'price' => 600],
            ['name' => 'High-Waist Yoga Leggings', 'price' => 900],
            ['name' => 'Traditional Embroidered Panjabi', 'price' => 2100],
            ['name' => 'Casual Checkered Button-Up', 'price' => 950],
            ['name' => 'Formal Oxford Shirt', 'price' => 1350],
            ['name' => 'Vintage Distressed Jacket', 'price' => 2800],
            ['name' => 'Cozy Knit Sweater', 'price' => 1500],
            ['name' => 'Comfortable Cotton Lounge Pants', 'price' => 750],
            ['name' => 'Sleeveless Gym Tank Top', 'price' => 450],
            ['name' => 'Pleated Midi Skirt', 'price' => 1100],
            ['name' => 'Classic Trench Coat', 'price' => 3500],
            ['name' => 'Heavyweight Winter Parka', 'price' => 4200],
        ];

        foreach ($dummyProducts as $prod) {
            // Randomly assign a category
            $randomCategory = $categories->random();

            $product = Product::updateOrCreate(
                ['name' => $prod['name']],
                [
                    'slug' => Str::slug($prod['name']),
                    'category_id' => $randomCategory->id,
                    'brand_id' => $brand->id,
                    'short_description' => 'A wonderful addition to your wardrobe. Premium quality materials.',
                    'description' => '<p>High quality material and excellent finish. Designed for maximum comfort and style. Available in multiple sizes and colors.</p>',
                    'purchase_price' => $prod['price'] * 0.7,
                    'sale_price' => $prod['price'],
                    'active_status' => 1,
                ]
            );

            // Create Variations and Stock
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    $sku = 'BUZZ-' . strtoupper(Str::random(4)) . '-' . strtoupper(substr($color->name, 0, 3)) . '-' . $size->name;
                    
                    $variation = ProductVariation::updateOrCreate(
                        ['product_id' => $product->id, 'product_color_id' => $color->id, 'product_size_id' => $size->id],
                        [
                            'sku' => $sku,
                            'purchase_price' => $product->purchase_price,
                            'sale_price' => $product->sale_price,
                            'stock_quantity' => 100 // Seed with 100 stock
                        ]
                    );

                    // Add to StockLedger
                    StockLedger::create([
                        'product_id' => $product->id,
                        'product_variation_id' => $variation->id,
                        'quantity_added' => 100,
                        'purchase_price' => $product->purchase_price,
                        'note' => 'Demo stock generation',
                        'created_by' => 1 // Assuming Super Admin is ID 1
                    ]);
                }
            }
        }
    }
}
