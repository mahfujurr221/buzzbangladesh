<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $setting = \Illuminate\Support\Facades\Cache::rememberForever('setting_website', function () {
                return \App\Models\SettingWebsite::first();
            });
            $view->with('setting', $setting);
            
            // Share global footer variables
            $footerPages = \Illuminate\Support\Facades\Cache::rememberForever('footer_pages', function () {
                return \App\Models\Page::where('status', 1)->take(5)->get();
            });
            $view->with('footerPages', $footerPages);

            $footerCategories = \Illuminate\Support\Facades\Cache::rememberForever('footer_categories', function () {
                return \App\Models\Category::where('active_status', 1)->take(5)->get();
            });
            $view->with('footerCategories', $footerCategories);

            $categories = \Illuminate\Support\Facades\Cache::rememberForever('global_categories', function () {
                return \App\Models\Category::where('active_status', 1)->get();
            });
            $view->with('categories', $categories);
            
            $cartRecommendedProducts = collect();
            $cart = session()->get('cart', []);
            
            if (!empty($cart)) {
                $productIds = collect($cart)->pluck('id')->unique();
                $categoryIds = \App\Models\Product::whereIn('id', $productIds)->pluck('category_id')->unique();
                
                if ($categoryIds->isNotEmpty()) {
                    $cartRecommendedProducts = \App\Models\Product::where('active_status', 1)
                        ->whereIn('category_id', $categoryIds)
                        ->whereNotIn('id', $productIds)
                        ->with('images')
                        ->inRandomOrder()
                        ->take(4)
                        ->get();
                }
            }

            if ($cartRecommendedProducts->isEmpty()) {
                $cartRecommendedProducts = \Illuminate\Support\Facades\Cache::remember('fallback_recommended_products', 3600, function () {
                    $newArrivals = \App\Models\Product::where('active_status', 1)->where('is_new_arrival', 1)->with('images')->take(2)->get();
                    $bestSellers = \App\Models\Product::where('active_status', 1)->where('is_best_seller', 1)->with('images')->take(2)->get();
                    return $newArrivals->merge($bestSellers)->unique('id')->take(4);
                });
            }
            
            $view->with('cartRecommendedProducts', $cartRecommendedProducts);

            // Hot Deals: check if any discount is currently running (cached 5 min)
            $hasActiveDeals = \Illuminate\Support\Facades\Cache::remember('has_active_deals', 300, function () {
                return \App\Models\Discount::active()->exists();
            });
            $view->with('hasActiveDeals', $hasActiveDeals);
        });
    }
}
