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
            ['name' => 'Classic Cotton T-Shirt', 'price' => 500, 'category_id' => $categories->first()->id],
            ['name' => 'Slim Fit Denim Jeans', 'price' => 1200, 'category_id' => $categories->first()->id],
            ['name' => 'Elegant Silk Dress', 'price' => 2500, 'category_id' => $categories->last()->id],
        ];

        foreach ($dummyProducts as $prod) {
            $product = Product::updateOrCreate(
                ['name' => $prod['name']],
                [
                    'slug' => Str::slug($prod['name']),
                    'category_id' => $prod['category_id'],
                    'brand_id' => $brand->id,
                    'short_description' => 'A wonderful addition to your wardrobe.',
                    'description' => '<p>High quality material and excellent finish.</p>',
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
