<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingWebsite;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $destinationPath = public_path('backend/images');
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
                'site_title' => 'Buzz Bangladesh - Best E-commerce Platform',
                'top_bar_text' => 'Welcome to Buzz Bangladesh! Enjoy free shipping on orders over ৳1000.',
                'email' => 'info@buzzbangladesh.com',
                'phone' => '+880 1234 567890',
                'address' => 'House: 12, Road: 5, Dhanmondi, Dhaka-1209, Bangladesh',
                'currency_name' => 'Taka',
                'currency_symbol' => '৳',
                'currency_code' => 'BDT',
                'logo' => 'logo.png',
                'favicon' => 'favicon.png',
            ]
        );

        SettingWebsite::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Buzz Bangladesh',
                'site_title' => 'Buzz Bangladesh - Best E-commerce Platform',
                'top_bar_text' => 'Welcome to Buzz Bangladesh! Enjoy free shipping on orders over ৳1000.',
                'email' => 'info@buzzbangladesh.com',
                'phone' => '+880 1234 567890',
                'whatsapp_number' => '+8801234567890',
                'address' => 'House: 12, Road: 5, Dhanmondi, Dhaka-1209, Bangladesh',
                'facebook' => 'https://facebook.com/buzzbangladesh',
                'twitter' => 'https://twitter.com/buzzbangladesh',
                'instagram' => 'https://instagram.com/buzzbangladesh',
                'youtube' => 'https://youtube.com/buzzbangladesh',
                'linkedin' => 'https://linkedin.com/company/buzzbangladesh',
                'footer_text' => 'Buzz Bangladesh is your premier online shopping destination, offering a wide range of quality products at affordable prices. Shop with us for the best deals and excellent customer service.',
                'newsletter_text' => 'Subscribe to our newsletter to receive the latest updates, exclusive offers, and exciting promotions straight to your inbox.',
                'headline' => 'Discover the best products for you!',
                'meta_title' => 'Buzz Bangladesh - Online Shopping in BD',
                'meta_description' => 'Shop the latest trends and best deals at Buzz Bangladesh. Fast delivery, secure payments, and 24/7 customer support.',
                'meta_keywords' => json_encode(['ecommerce', 'online shopping', 'bangladesh', 'buzz', 'fashion', 'electronics']),
                'logo' => 'logo.png',
                'favicon' => 'favicon.png',
            ]
        );
    }
}
