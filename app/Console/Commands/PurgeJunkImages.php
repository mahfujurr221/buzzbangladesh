<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\InstagramFeed;
use App\Models\FlashModal;
use App\Models\Setting;
use App\Models\SettingWebsite;

class PurgeJunkImages extends Command
{
    protected $signature = 'images:purge-junk';
    protected $description = 'Purge all junk images and reset products, banners, categories to standardized default placeholders';

    public function handle()
    {
        $this->info('Starting junk images purge & default placeholder standardization...');

        $storagePath = storage_path('app/public');
        $backendPath = public_path('backend/images');

        // Ensure directories exist
        $dirs = ['products', 'banners', 'categories', 'brands', 'subcategories', 'instagram_feeds', 'flash_modals', 'settings', 'defaults', 'users'];
        foreach ($dirs as $dir) {
            File::ensureDirectoryExists($storagePath . '/' . $dir);
            File::ensureDirectoryExists($backendPath . '/' . $dir);
        }

        // 1. Prepare Standard Placeholders
        $this->info('1. Creating clean, standardized placeholders...');
        $this->generateProductPlaceholder($storagePath . '/products/placeholder.png');
        $this->generateBannerPlaceholder($storagePath . '/banners/placeholder.png');
        $this->generateCategoryPlaceholder($storagePath . '/categories/placeholder.png');
        $this->generateCategoryPlaceholder($storagePath . '/brands/placeholder.png');
        $this->generateCategoryPlaceholder($storagePath . '/instagram_feeds/placeholder.png');
        $this->generateCategoryPlaceholder($storagePath . '/flash_modals/placeholder.png');

        // Copy to defaults directory
        File::copy($storagePath . '/products/placeholder.png', $storagePath . '/defaults/placeholder.png');
        File::copy($storagePath . '/banners/placeholder.png', $storagePath . '/defaults/banner_placeholder.png');

        // Copy placeholders to backend directory for fallbacks
        File::copy($storagePath . '/products/placeholder.png', $backendPath . '/placeholder.png');
        File::copy($storagePath . '/products/placeholder.png', $backendPath . '/products/placeholder.png');
        File::copy($storagePath . '/banners/placeholder.png', $backendPath . '/banners/placeholder.png');

        // Ensure default user avatar exists
        if (File::exists($backendPath . '/users/avatar-1.jpg')) {
            File::copy($backendPath . '/users/avatar-1.jpg', $storagePath . '/users/avatar-1.jpg');
            File::copy($backendPath . '/users/avatar-1.jpg', $storagePath . '/defaults/avatar-1.jpg');
        }
        if (File::exists($backendPath . '/users/default.png')) {
            File::copy($backendPath . '/users/default.png', $storagePath . '/users/default.png');
            File::copy($backendPath . '/users/default.png', $storagePath . '/defaults/default.png');
        }

        // Ensure setting logo & favicon exist in settings/
        if (!File::exists($storagePath . '/settings/logo.png') && File::exists(public_path('frontend/assets/images/logo.png'))) {
            File::copy(public_path('frontend/assets/images/logo.png'), $storagePath . '/settings/logo.png');
        }
        if (!File::exists($storagePath . '/settings/favicon.png') && File::exists(public_path('frontend/assets/images/favicon.png'))) {
            File::copy(public_path('frontend/assets/images/favicon.png'), $storagePath . '/settings/favicon.png');
        }

        // 2. Update Database Records to Standard Placeholders
        $this->info('2. Updating Database Records to use default placeholders...');
        
        // Products: keep 1 clean primary ProductImage per product pointing to products/placeholder.png
        // Delete redundant duplicate color/gallery image records
        ProductImage::where('is_main', 0)->delete();
        ProductImage::where('is_main', 1)->update([
            'image_path' => 'products/placeholder.png',
            'product_color_id' => null,
            'sort_order' => 0,
        ]);
        // For any product without a ProductImage, create one
        $products = Product::all();
        foreach ($products as $p) {
            if ($p->images()->count() === 0) {
                ProductImage::create([
                    'product_id' => $p->id,
                    'image_path' => 'products/placeholder.png',
                    'is_main' => 1,
                    'sort_order' => 0,
                ]);
            }
        }
        $this->line("   - Standardized " . $products->count() . " products with primary placeholder image.");

        // Banners
        Banner::query()->update(['image' => 'banners/placeholder.png']);
        $this->line("   - Standardized Banners to banners/placeholder.png");

        // Categories
        Category::query()->update(['logo' => 'categories/placeholder.png']);
        $this->line("   - Standardized Categories to categories/placeholder.png");

        // Brands
        Brand::query()->update(['logo' => 'brands/placeholder.png']);
        $this->line("   - Standardized Brands to brands/placeholder.png");

        // Instagram Feeds
        InstagramFeed::query()->update(['image' => 'instagram_feeds/placeholder.png']);
        $this->line("   - Standardized Instagram Feeds to instagram_feeds/placeholder.png");

        // Flash Modals
        FlashModal::query()->update(['image' => 'flash_modals/placeholder.png']);
        $this->line("   - Standardized Flash Modals to flash_modals/placeholder.png");

        // Website Settings
        $settingWebsite = SettingWebsite::first();
        if ($settingWebsite) {
            $settingWebsite->update([
                'logo' => 'settings/logo.png',
                'favicon' => 'settings/favicon.png',
                'promo_banner_1' => 'banners/placeholder.png',
                'promo_banner_2' => 'banners/placeholder.png',
                'shop_bg' => null,
                'about_bg' => null,
                'contact_bg' => null,
            ]);
            $this->line("   - Standardized Website Setting logo, promo banners, and backgrounds.");
        }

        // Backend Settings
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'logo' => 'settings/logo.png',
                'favicon' => 'settings/favicon.png',
            ]);
            $this->line("   - Standardized Backend Setting logo and favicon.");
        }

        // 3. Purge Junk Files from storage/app/public
        $this->info('3. Purging junk images from storage/app/public...');
        $this->cleanFolder($storagePath . '/products', ['placeholder.png']);
        $this->cleanFolder($storagePath . '/banners', ['placeholder.png', 'promo_banner_1.png', 'promo_banner_2.png']);
        $this->cleanFolder($storagePath . '/categories', ['placeholder.png']);
        $this->cleanFolder($storagePath . '/brands', ['placeholder.png']);
        $this->cleanFolder($storagePath . '/instagram_feeds', ['placeholder.png']);
        $this->cleanFolder($storagePath . '/flash_modals', ['placeholder.png']);
        $this->cleanFolder($storagePath . '/subcategories', ['placeholder.png']);
        $this->cleanFolder($storagePath . '/users', ['avatar-1.jpg', 'default.png']);
        $this->cleanFolder($storagePath . '/settings', ['logo.png', 'favicon.png']);
        $this->cleanFolder($storagePath . '/defaults', ['placeholder.png', 'banner_placeholder.png', 'avatar-1.jpg', 'default.png']);

        // 4. Purge Junk Files from public/backend/images
        $this->info('4. Purging junk images from public/backend/images...');
        // Root files to retain in backend/images:
        $retainedBackendRoot = [
            'auth-bg.jpg', 'bg-2.jpg', 'error-img.png', 'favicon.png', 'logo.png', 'placeholder.png', 'default_favicon.png'
        ];
        $this->cleanFolderFiles($backendPath, $retainedBackendRoot);

        // Subfolders in backend/images:
        $this->cleanFolder($backendPath . '/products', ['placeholder.png']);
        $this->cleanFolder($backendPath . '/banners', ['placeholder.png']);
        $this->cleanFolder($backendPath . '/categories', ['placeholder.png']);
        $this->cleanFolder($backendPath . '/brands', ['placeholder.png']);
        $this->cleanFolder($backendPath . '/instagram_feeds', ['placeholder.png']);
        $this->cleanFolder($backendPath . '/flash_modals', []);
        $this->cleanFolder($backendPath . '/users', ['avatar-1.jpg', 'default.png']);

        $this->info('Junk image purge and default placeholder setup completed successfully!');
        return 0;
    }

    private function cleanFolder(string $folder, array $keepFiles)
    {
        if (!File::isDirectory($folder)) return;
        
        $files = File::files($folder);
        $deleted = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            if (!in_array($filename, $keepFiles)) {
                File::delete($file->getPathname());
                $deleted++;
            }
        }
        $this->line("   Cleaned " . basename($folder) . ": deleted {$deleted} files, kept " . count($keepFiles) . ".");
    }

    private function cleanFolderFiles(string $folder, array $keepFiles)
    {
        if (!File::isDirectory($folder)) return;
        
        $files = File::files($folder);
        $deleted = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            if (!in_array($filename, $keepFiles)) {
                File::delete($file->getPathname());
                $deleted++;
            }
        }
        $this->line("   Cleaned root " . basename($folder) . ": deleted {$deleted} files.");
    }

    private function generateProductPlaceholder(string $path)
    {
        $w = 800; $h = 800;
        $img = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($img, 243, 244, 246); // #f3f4f6
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        // Border
        $border = imagecolorallocate($img, 229, 231, 235); // #e5e7eb
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $border);

        // Inner card
        $cardBg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 80, 80, $w - 80, $h - 80, $cardBg);
        imagerectangle($img, 80, 80, $w - 80, $h - 80, $border);

        // Camera / Image outline
        $iconColor = imagecolorallocate($img, 154, 0, 2); // Buzz brand crimson #9A0002
        $grayText = imagecolorallocate($img, 107, 114, 128); // #6b7280

        // Draw camera icon
        $cx = $w / 2; $cy = ($h / 2) - 40;
        imagefilledrectangle($img, $cx - 80, $cy - 50, $cx + 80, $cy + 50, $border);
        imagefilledellipse($img, $cx, $cy, 60, 60, $cardBg);
        imagefilledellipse($img, $cx, $cy, 44, 44, $iconColor);

        // Text
        $text = "BUZZ BANGLADESH";
        $sub = "No Image Available";
        imagestring($img, 5, $cx - (strlen($text) * 4.5), $cy + 80, $text, $iconColor);
        imagestring($img, 4, $cx - (strlen($sub) * 4), $cy + 110, $sub, $grayText);

        imagepng($img, $path);
        imagedestroy($img);
    }

    private function generateBannerPlaceholder(string $path)
    {
        $w = 1920; $h = 650;
        $img = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($img, 248, 249, 250); // soft luxury warm white #f8f9fa
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        $border = imagecolorallocate($img, 229, 231, 235);
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $border);

        // Subtle decorative gradient bars
        $brandRed = imagecolorallocate($img, 154, 0, 2); // #9A0002
        $grayText = imagecolorallocate($img, 107, 114, 128);

        imagefilledrectangle($img, 0, 0, $w, 8, $brandRed);
        imagefilledrectangle($img, 0, $h - 8, $w, $h, $brandRed);

        // Center badge
        $cx = $w / 2; $cy = $h / 2;
        $cardBg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, $cx - 350, $cy - 120, $cx + 350, $cy + 120, $cardBg);
        imagerectangle($img, $cx - 350, $cy - 120, $cx + 350, $cy + 120, $border);

        // Title text
        $title = "BUZZ BANGLADESH";
        $sub = "Promotional Banner - Ready for Upload";
        imagestring($img, 5, $cx - (strlen($title) * 4.5), $cy - 30, $title, $brandRed);
        imagestring($img, 4, $cx - (strlen($sub) * 4), $cy + 15, $sub, $grayText);

        imagepng($img, $path);
        imagedestroy($img);
    }

    private function generateCategoryPlaceholder(string $path)
    {
        $w = 600; $h = 600;
        $img = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($img, 248, 250, 252);
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        $border = imagecolorallocate($img, 226, 232, 240);
        imagerectangle($img, 0, 0, $w - 1, $h - 1, $border);

        $brandRed = imagecolorallocate($img, 154, 0, 2);
        $grayText = imagecolorallocate($img, 100, 116, 139);

        $cx = $w / 2; $cy = ($h / 2) - 20;
        imagefilledellipse($img, $cx, $cy, 120, 120, $border);
        imagefilledellipse($img, $cx, $cy, 90, 90, $brandRed);

        $title = "BUZZ";
        $sub = "Default Category";
        imagestring($img, 5, $cx - (strlen($title) * 4.5), $cy + 80, $title, $brandRed);
        imagestring($img, 3, $cx - (strlen($sub) * 3.5), $cy + 110, $sub, $grayText);

        imagepng($img, $path);
        imagedestroy($img);
    }
}
