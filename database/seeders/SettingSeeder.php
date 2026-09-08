<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingWebsite;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $destinationPath = storage_path('app/public/settings');
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        $logoPath = public_path('frontend/assets/images/logo.png');
        $faviconPath = public_path('frontend/assets/images/favicon.png');

        if (file_exists($logoPath)) {
            copy($logoPath, $destinationPath . '/logo.png');
        }
        if (file_exists($faviconPath)) {
            copy($faviconPath, $destinationPath . '/favicon.png');
        }

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Buzz Bangladesh',
                'site_title' => 'Buzz Bangladesh - Contemporary Fashion & Lifestyle',
                'top_bar_text' => '✨ Free nationwide delivery on orders over ৳1,999! Cash on Delivery available across Bangladesh.',
                'email' => 'info@buzzbangladesh.com',
                'phone' => '+880 1958-227060',
                'address' => 'House: 12, Road: 5, Dhanmondi, Dhaka-1209, Bangladesh',
                'currency_name' => 'Taka',
                'currency_symbol' => '৳',
                'currency_code' => 'BDT',
                'logo' => 'settings/logo.png',
                'favicon' => 'settings/favicon.png',
            ]
        );

        SettingWebsite::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Buzz Bangladesh',
                'site_title' => 'Buzz Bangladesh - Contemporary Fashion & Lifestyle',
                'top_bar_text' => '✨ Free nationwide delivery on orders over ৳1,999! Cash on Delivery available across Bangladesh.',
                'email' => 'info@buzzbangladesh.com',
                'phone' => '+880 1958-227060',
                'whatsapp_number' => '+8801958227060',
                'address' => 'House: 12, Road: 5, Dhanmondi, Dhaka-1209, Bangladesh',
                'facebook' => 'https://facebook.com/buzzbangladesh',
                'twitter' => 'https://twitter.com/buzzbangladesh',
                'instagram' => 'https://instagram.com/buzzbangladesh',
                'youtube' => 'https://youtube.com/buzzbangladesh',
                'linkedin' => 'https://linkedin.com/company/buzzbangladesh',
                'footer_text' => 'Buzz Bangladesh is your premier fashion destination, curating modern menswear, contemporary womenswear, and premium kidswear crafted with supreme comfort and timeless style.',
                'newsletter_text' => 'Subscribe for early access to private festive sales, seasonal lookbooks, and exclusive member discounts.',
                'headline' => 'Elevate Your Everyday Style',
                'promo_banner_1' => 'banners/promo_banner_1.png',
                'promo_banner_1_title' => 'Curated Essentials',
                'promo_banner_1_link' => '/shop',
                'promo_banner_2' => 'banners/promo_banner_2.png',
                'promo_banner_2_title' => 'New Season Drops',
                'promo_banner_2_link' => '/shop?sort=newest',
                'meta_title' => 'Buzz Bangladesh | Contemporary Fashion & Lifestyle Store',
                'meta_description' => 'Discover Bangladesh\'s premier fashion collections at Buzz Bangladesh. Premium Panjabis, Oxford shirts, sarees, polo tees, and denim with fast nationwide delivery.',
                'meta_keywords' => json_encode(['buzz bangladesh', 'contemporary fashion', 'panjabi bd', 'online shopping dhaka', 'menswear bangladesh', 'womenswear bd']),
                'logo' => 'settings/logo.png',
                'favicon' => 'settings/favicon.png',
            ]
        );
    }
}
