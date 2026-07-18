<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\ProductSizeController;
use App\Http\Controllers\Backend\ProductColorController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Admin / Backend Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('backend/login', [App\Http\Controllers\Backend\AdminAuthController::class, 'create'])->name('login');
    Route::post('backend/login', [App\Http\Controllers\Backend\AdminAuthController::class, 'store']);
});

Route::prefix('back')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /////////////// Roles & Permissions ///////////////
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);
    Route::get('roles/permissions/{id}', [RoleController::class, 'addPermissionToRole'])->name('role.permissions');
    Route::put('roles/permissions/{id}', [RoleController::class, 'addPermissionToRoleUpdate'])->name('role-permissions.update');

    /////////////// Users & Profile ///////////////
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile-reset', [ProfileController::class, 'reset'])->name('profile.reset');
    Route::put('/profile-update', [ProfileController::class, 'update'])->name('profile.update');


    /////////////// Settings ///////////////
    Route::resource('settings', SettingController::class)->except(['show', 'edit', 'create', 'destroy']);

    /////////////// Brands ///////////////
    Route::resource('brands', BrandController::class)->except(['create', 'show', 'edit']);

    /////////////// Categories ///////////////
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('subcategories', SubCategoryController::class)->except(['create', 'show', 'edit']);

    /////////////// Banners ///////////////
    Route::post('banners/toggle-status', [\App\Http\Controllers\Backend\BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    Route::resource('banners', \App\Http\Controllers\Backend\BannerController::class)->except(['show']);

    /////////////// Pages ///////////////
    Route::post('pages/toggle-status', [\App\Http\Controllers\Backend\PageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::resource('pages', \App\Http\Controllers\Backend\PageController::class)->except(['show']);

    /////////////// Attributes ///////////////
    Route::resource('sizes', ProductSizeController::class)->except(['create', 'show', 'edit']);
    Route::resource('colors', App\Http\Controllers\Backend\ProductColorController::class)->except(['create', 'show', 'edit']);
    Route::resource('products', App\Http\Controllers\Backend\ProductController::class);
    Route::get('get-subcategories/{category_id}', [App\Http\Controllers\Backend\ProductController::class, 'getSubcategories']);

    /////////////// Stock Management ///////////////
    Route::get('stocks', [App\Http\Controllers\Backend\StockController::class, 'index'])->name('stocks.index');
    Route::get('stocks/ledger', [App\Http\Controllers\Backend\StockController::class, 'ledger'])->name('stocks.ledger');
    Route::get('stocks/{product}/manage', [App\Http\Controllers\Backend\StockController::class, 'manage'])->name('stocks.manage');
    Route::post('stocks/{product}/store', [App\Http\Controllers\Backend\StockController::class, 'store'])->name('stocks.store');

    /////////////// Order Statuses ///////////////
    Route::resource('order-statuses', App\Http\Controllers\Backend\OrderStatusController::class);

    /////////////// Orders ///////////////
    Route::controller(App\Http\Controllers\Backend\OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('online', 'onlineOrders')->name('online');
        Route::get('sales', 'sales')->name('sales');
        Route::get('canceled', 'canceledOrders')->name('canceled');
        Route::get('returned', 'returnedOrders')->name('returned');
        Route::get('{order}', 'show')->name('show');
        Route::post('{order}/change-status', 'changeStatus')->name('change-status');
    });

    /////////////// Customers ///////////////
    Route::resource('customers', App\Http\Controllers\Backend\CustomerController::class)->except(['show']);
});
