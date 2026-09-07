<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\Banner;
use App\Models\FlashModal;
use App\Models\InstagramFeed;
use App\Models\User;
use App\Models\Setting;
use App\Models\SettingWebsite;

class MigrateImagesToStorage extends Command
{
    protected $signature = 'images:migrate-to-storage';
    protected $description = 'Migrate all images from public/backend and seed sources to storage/app/public and update DB paths';

    public function handle()
    {
        $this->info('Starting Image Migration to Storage...');

        // 1. Create target directories
        $folders = [
            'products',
            'categories',
            'subcategories',
            'brands',
            'banners',
            'flash_modals',
            'instagram_feeds',
            'users',
            'settings',
            'defaults',
        ];

        foreach ($folders as $folder) {
            $path = storage_path('app/public/' . $folder);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $this->line("Created directory: storage/app/public/{$folder}");
            }
        }

        // 2. Copy Products images
        $this->copyFiles(public_path('backend/images/products'), storage_path('app/public/products'));
        $this->copyFiles(public_path('frontend/images/product/fashion'), storage_path('app/public/products'));

        // 3. Copy Banners images
        $this->copyFiles(public_path('backend/images/banners'), storage_path('app/public/banners'));
        $this->copyFiles(public_path('frontend/images/slider'), storage_path('app/public/banners'));

        // 4. Copy Instagram Feeds images
        $this->copyFiles(public_path('backend/images/instagram_feeds'), storage_path('app/public/instagram_feeds'));
        $this->copyFiles(public_path('frontend/images/instagram'), storage_path('app/public/instagram_feeds'));

        // 5. Copy Users images
        $this->copyFiles(public_path('backend/images/users'), storage_path('app/public/users'));

        // 6. Copy Flash Modals images
        $this->copyFiles(public_path('backend/images/flash_modals'), storage_path('app/public/flash_modals'));

        // 7. Copy Categories images
        $this->copyFiles(public_path('frontend/images/collection'), storage_path('app/public/categories'));
        // Copy demo_category_* and loose images from backend/images
        if (File::isDirectory(public_path('backend/images'))) {
            foreach (File::files(public_path('backend/images')) as $file) {
                $filename = $file->getFilename();
                if (str_starts_with($filename, 'demo_category_') || preg_match('/^\d+\.(png|jpg|jpeg|webp)$/i', $filename)) {
                    File::copy($file->getRealPath(), storage_path('app/public/categories/' . $filename));
                    // Also copy to subcategories
                    File::copy($file->getRealPath(), storage_path('app/public/subcategories/' . $filename));
                }
                if (str_starts_with($filename, 'demo_brand_')) {
                    File::copy($file->getRealPath(), storage_path('app/public/brands/' . $filename));
                }
            }
        }
        $this->copyFiles(public_path('frontend/images/brand'), storage_path('app/public/brands'));

        // 8. Copy Settings images (logo, favicon)
        if (File::exists(public_path('backend/images/logo.png'))) {
            File::copy(public_path('backend/images/logo.png'), storage_path('app/public/settings/logo.png'));
        } elseif (File::exists(public_path('frontend/assets/images/logo.png'))) {
            File::copy(public_path('frontend/assets/images/logo.png'), storage_path('app/public/settings/logo.png'));
        }

        if (File::exists(public_path('backend/images/favicon.png'))) {
            File::copy(public_path('backend/images/favicon.png'), storage_path('app/public/settings/favicon.png'));
        } elseif (File::exists(public_path('frontend/assets/images/favicon.png'))) {
            File::copy(public_path('frontend/assets/images/favicon.png'), storage_path('app/public/settings/favicon.png'));
        }

        // Copy promo banners from frontend if any
        $this->copyFiles(public_path('frontend/images/banner'), storage_path('app/public/settings'));

        // 9. Copy Defaults
        if (File::exists(public_path('backend/images/products/placeholder.png'))) {
            File::copy(public_path('backend/images/products/placeholder.png'), storage_path('app/public/defaults/placeholder.png'));
            File::copy(public_path('backend/images/products/placeholder.png'), storage_path('app/public/products/placeholder.png'));
        }
        if (File::exists(public_path('backend/images/users/avatar-1.jpg'))) {
            File::copy(public_path('backend/images/users/avatar-1.jpg'), storage_path('app/public/defaults/avatar-1.jpg'));
        }
        if (File::exists(public_path('backend/images/users/default.png'))) {
            File::copy(public_path('backend/images/users/default.png'), storage_path('app/public/defaults/default.png'));
        }

        $this->info('All files copied to storage/app/public successfully.');

        // 10. Update Database Records
        $this->info('Updating database records...');

        // ProductImage
        $productImages = ProductImage::all();
        foreach ($productImages as $pi) {
            $cleaned = str_replace('backend/images/products/', 'products/', $pi->image_path);
            if (!str_starts_with($cleaned, 'products/')) {
                $cleaned = 'products/' . ltrim($cleaned, '/');
            }
            $pi->image_path = $cleaned;
            $pi->save();
        }
        $this->line("Updated {$productImages->count()} ProductImage records.");

        // Banner
        $banners = Banner::all();
        foreach ($banners as $b) {
            $cleaned = str_replace('backend/images/banners/', 'banners/', $b->image);
            $cleaned = str_replace('backend/images/products/', 'products/', $cleaned);
            if (!str_starts_with($cleaned, 'banners/') && !str_starts_with($cleaned, 'products/')) {
                $cleaned = 'banners/' . ltrim($cleaned, '/');
            }
            $b->image = $cleaned;
            $b->save();
        }
        $this->line("Updated {$banners->count()} Banner records.");

        // InstagramFeed
        $feeds = InstagramFeed::all();
        foreach ($feeds as $feed) {
            $cleaned = str_replace('backend/images/instagram_feeds/', 'instagram_feeds/', $feed->image);
            if (!str_starts_with($cleaned, 'instagram_feeds/')) {
                $cleaned = 'instagram_feeds/' . ltrim($cleaned, '/');
            }
            $feed->image = $cleaned;
            $feed->save();
        }
        $this->line("Updated {$feeds->count()} InstagramFeed records.");

        // FlashModal
        $modals = FlashModal::all();
        foreach ($modals as $m) {
            if ($m->image) {
                $cleaned = str_replace('backend/images/flash_modals/', 'flash_modals/', $m->image);
                if (!str_starts_with($cleaned, 'flash_modals/')) {
                    $cleaned = 'flash_modals/' . ltrim($cleaned, '/');
                }
                $m->image = $cleaned;
                $m->save();
            }
        }

        // Category
        $categories = Category::all();
        foreach ($categories as $cat) {
            if ($cat->logo) {
                $cleaned = str_replace('backend/images/', '', $cat->logo);
                if (!str_starts_with($cleaned, 'categories/')) {
                    $cleaned = 'categories/' . ltrim($cleaned, '/');
                }
                if (!File::exists(storage_path('app/public/' . $cleaned))) {
                    if (File::exists(storage_path('app/public/categories/demo_category_outerwear.png'))) {
                        $cleaned = 'categories/demo_category_outerwear.png';
                    }
                }
                $cat->logo = $cleaned;
                $cat->save();
            }
        }
        $this->line("Updated {$categories->count()} Category records.");

        // SubCategory
        $subCategories = SubCategory::all();
        foreach ($subCategories as $sub) {
            if ($sub->logo) {
                $cleaned = str_replace('backend/images/', '', $sub->logo);
                if (!str_starts_with($cleaned, 'subcategories/')) {
                    $cleaned = 'subcategories/' . ltrim($cleaned, '/');
                }
                $sub->logo = $cleaned;
                $sub->save();
            }
        }

        // Brand
        $brands = Brand::all();
        foreach ($brands as $br) {
            if ($br->logo) {
                $cleaned = str_replace('backend/images/', '', $br->logo);
                if (!str_starts_with($cleaned, 'brands/')) {
                    $cleaned = 'brands/' . ltrim($cleaned, '/');
                }
                $br->logo = $cleaned;
                $br->save();
            }
        }

        // User
        $users = User::all();
        foreach ($users as $u) {
            if ($u->image) {
                $cleaned = str_replace('backend/images/users/', '', $u->image);
                if (!str_starts_with($cleaned, 'users/')) {
                    $cleaned = 'users/' . ltrim($cleaned, '/');
                }
                $u->image = $cleaned;
                $u->save();
            }
        }

        // Setting
        $setting = Setting::first();
        if ($setting) {
            $setting->logo = 'settings/logo.png';
            $setting->favicon = 'settings/favicon.png';
            $setting->save();
        }

        // SettingWebsite
        $webSetting = SettingWebsite::first();
        if ($webSetting) {
            $webSetting->logo = 'settings/logo.png';
            $webSetting->favicon = 'settings/favicon.png';
            if ($webSetting->promo_banner_1) {
                $webSetting->promo_banner_1 = 'settings/' . basename($webSetting->promo_banner_1);
            }
            if ($webSetting->promo_banner_2) {
                $webSetting->promo_banner_2 = 'settings/' . basename($webSetting->promo_banner_2);
            }
            if ($webSetting->shop_bg) {
                $webSetting->shop_bg = 'settings/' . basename($webSetting->shop_bg);
            }
            if ($webSetting->about_bg) {
                $webSetting->about_bg = 'settings/' . basename($webSetting->about_bg);
            }
            if ($webSetting->contact_bg) {
                $webSetting->contact_bg = 'settings/' . basename($webSetting->contact_bg);
            }
            $webSetting->save();
        }

        $this->info('Database records synchronized successfully.');
        return 0;
    }

    private function copyFiles(string $sourceDir, string $destDir)
    {
        if (!File::isDirectory($sourceDir)) {
            return;
        }

        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0777, true, true);
        }

        $files = File::files($sourceDir);
        foreach ($files as $file) {
            $target = $destDir . '/' . $file->getFilename();
            File::copy($file->getRealPath(), $target);
        }
    }
}
