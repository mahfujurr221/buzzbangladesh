<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::name('frontend.')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/about', 'about')->name('about');
        Route::get('/service', 'service')->name('service');
        Route::get('/service-details', 'serviceDetails')->name('service.details');
        Route::get('/career', 'career')->name('career');
        Route::get('/career-details', 'careerDetails')->name('career.details');
        Route::get('/integrations', 'integrations')->name('integrations');
        Route::get('/price', 'price')->name('price');
        Route::get('/projects', 'projects')->name('projects');
        Route::get('/projects-details', 'projectsDetails')->name('projects.details');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/blog-details', 'blogDetails')->name('blog.details');
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/team', 'team')->name('team');
        Route::get('/team/member/{id}', 'show')->name('member.show');
    });
});
