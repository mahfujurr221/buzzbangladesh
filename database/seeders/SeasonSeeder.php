<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'Summer '.date('Y');
        $season = Season::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => 'Our exclusive summer collection featuring the best outfits for the hot weather.',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2)->endOfMonth(),
            'active_status' => 1,
        ]);

        // Attach 4 random products to this season
        $products = Product::inRandomOrder()->take(4)->get();
        foreach ($products as $product) {
            $product->update(['season_id' => $season->id]);
        }
    }
}
