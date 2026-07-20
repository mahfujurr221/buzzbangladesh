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
        });
    }
}
