<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::name('frontend.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/shop', 'shop')->name('shop');
        Route::get('/product/{slug}', 'productDetails')->name('product.details');
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/about', 'about')->name('about');
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/page/{slug}', 'page')->name('page');
    });
});
