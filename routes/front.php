<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\OrderController;

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

    // Cart Routes
    Route::controller(CartController::class)->group(function () {
        Route::post('/cart/add',    'add')->name('cart.add');
        Route::post('/cart/update', 'update')->name('cart.update');
        Route::post('/cart/remove', 'remove')->name('cart.remove');
        Route::get('/cart/render',  'render')->name('cart.render');
        Route::get('/cart/count',   'getCount')->name('cart.count');
        Route::get('/cart/data',    'getCartData')->name('cart.data');
    });

    // Order Routes
    Route::controller(OrderController::class)->group(function () {
        Route::post('/order/place',              'placeOrder')->name('order.place');
        Route::get('/order/success/{orderNumber}','success')->name('order.success');
    });
});
