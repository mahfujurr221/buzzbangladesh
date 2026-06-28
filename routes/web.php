<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Admin / Backend Routes
|--------------------------------------------------------------------------
*/

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
});
