<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductVariation;
use App\Models\ProductImage;
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

        if (! $brand || $categories->isEmpty() || $colors->isEmpty() || $sizes->isEmpty()) {
            return; // Prerequisites not met
        }

        $dummyProducts = [
            ['name' => 'Classic Cotton Basic T-Shirt', 'price' => 500, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Premium Polo Shirt', 'price' => 850, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Slim Fit Denim Jeans', 'price' => 1200, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Relaxed Fit Cargo Pants', 'price' => 1400, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 0, 'is_trending' => 0],
            ['name' => 'Elegant Silk Maxi Dress', 'price' => 2500, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 1],
            ['name' => 'Floral Summer Sundress', 'price' => 1800, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 0],
            ['name' => 'Waterproof Windbreaker Jacket', 'price' => 2200, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Fleece Lined Winter Hoodie', 'price' => 1600, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Breathable Running Shorts', 'price' => 600, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'High-Waist Yoga Leggings', 'price' => 900, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Traditional Embroidered Panjabi', 'price' => 2100, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 0],
            ['name' => 'Casual Checkered Button-Up', 'price' => 950, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 1],
            ['name' => 'Formal Oxford Shirt', 'price' => 1350, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 0, 'is_trending' => 0],
            ['name' => 'Vintage Distressed Jacket', 'price' => 2800, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 1, 'is_trending' => 1],
            ['name' => 'Cozy Knit Sweater', 'price' => 1500, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 0, 'is_trending' => 0],
            ['name' => 'Comfortable Cotton Lounge Pants', 'price' => 750, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Sleeveless Gym Tank Top', 'price' => 450, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Pleated Midi Skirt', 'price' => 1100, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Classic Trench Coat', 'price' => 3500, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Heavyweight Winter Parka', 'price' => 4200, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Men\'s Leather Belt', 'price' => 800, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Women\'s Stylish Handbag', 'price' => 3000, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 1, 'is_trending' => 1],
            ['name' => 'Aviator Sunglasses', 'price' => 1200, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 0, 'is_trending' => 0],
            ['name' => 'Unisex Beanie Hat', 'price' => 350, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Classic White Sneakers', 'price' => 2500, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Formal Leather Shoes', 'price' => 4500, 'is_new_arrival' => 0, 'is_featured' => 1, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Sports Running Shoes', 'price' => 3200, 'is_new_arrival' => 1, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Casual Canvas Slip-Ons', 'price' => 1500, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 0, 'is_on_sale' => 1, 'is_trending' => 0],
            ['name' => 'Kids Cartoon T-Shirt', 'price' => 400, 'is_new_arrival' => 1, 'is_featured' => 1, 'is_best_seller' => 1, 'is_on_sale' => 0, 'is_trending' => 1],
            ['name' => 'Kids Denim Overalls', 'price' => 1200, 'is_new_arrival' => 0, 'is_featured' => 0, 'is_best_seller' => 1, 'is_on_sale' => 1, 'is_trending' => 0],
        ];

        foreach ($dummyProducts as $prod) {
            // Randomly assign a category
            $randomCategory = $categories->random();

            // Image handle
            $dummyImages = ['1-1.png', '2-1.png', '3-1.png', '4-1.png', '5-1.png', '6-1.png', '7-1.png', '8-2.png', '9-1.png', '10-1.png'];
            $imageName = $dummyImages[array_rand($dummyImages)];
            $sourcePath = public_path('frontend/images/product/fashion/' . $imageName);
            
            $destinationPath = public_path('backend/images/products');
            if (!file_exists($destinationPath)) {
                @mkdir($destinationPath, 0777, true);
            }
            
            $newImageName = 'demo_prod_' . $imageName;
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath . '/' . $newImageName);
            } else {
                $newImageName = null;
            }

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
                    'is_new_arrival' => $prod['is_new_arrival'],
                    'is_featured' => $prod['is_featured'],
                    'is_best_seller' => $prod['is_best_seller'],
                    'is_on_sale' => $prod['is_on_sale'],
                    'is_trending' => $prod['is_trending'],
                ]
            );

            if ($newImageName) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'backend/images/products/' . $newImageName,
                    'is_main' => 1,
                    'sort_order' => 0
                ]);
            }

            // Create Variations and Stock
            foreach ($colors as $color) {
                // Color variation image
                $colorImageName = $dummyImages[array_rand($dummyImages)];
                $colorSourcePath = public_path('frontend/images/product/fashion/' . $colorImageName);
                $newColorImageName = 'demo_prod_color_' . $colorImageName;
                
                if (file_exists($colorSourcePath)) {
                    copy($colorSourcePath, $destinationPath . '/' . $newColorImageName);
                    
                    ProductImage::updateOrCreate(
                        ['product_id' => $product->id, 'product_color_id' => $color->id],
                        [
                            'image_path' => 'backend/images/products/' . $newColorImageName,
                            'is_main' => false,
                        ]
                    );
                }

                foreach ($sizes as $size) {
                    $sku = 'BUZZ-'.strtoupper(Str::random(4)).'-'.strtoupper(substr($color->name, 0, 3)).'-'.$size->name;

                    $variation = ProductVariation::updateOrCreate(
                        ['product_id' => $product->id, 'product_color_id' => $color->id, 'product_size_id' => $size->id],
                        [
                            'sku' => $sku,
                            'purchase_price' => $product->purchase_price,
                            'sale_price' => $product->sale_price,
                            'stock_quantity' => 10, // Seed with 100 stock
                        ]
                    );

                    // Add to StockLedger
                    StockLedger::create([
                        'product_id' => $product->id,
                        'product_variation_id' => $variation->id,
                        'quantity_added' => 10,
                        'purchase_price' => $product->purchase_price,
                        'note' => 'Demo stock generation',
                        'created_by' => 1, // Assuming Super Admin is ID 1
                    ]);
                }
            }
        }
    }
}
