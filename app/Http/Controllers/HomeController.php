<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FlashModal;

class HomeController extends Controller
{
    public function index()
    {
        $flashModal = FlashModal::active()->latest()->first();
        
        $banners = \App\Models\Banner::where('status', 1)->latest()->get();
        
        $categories = \App\Models\Category::where('active_status', 1)
            ->with(['products' => function($query) {
                $query->where('active_status', 1)
                      ->with(['images', 'category', 'variations.color']) // eager load relations for the frontend
                      ->latest()
                      ->take(8);
            }])
            ->whereHas('products', function($query) {
                $query->where('active_status', 1);
            })
            ->take(5)
            ->get();
            
        $allCategories = \App\Models\Category::where('active_status', 1)->get();
        
        $bestSellers = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->where('is_best_seller', 1)->latest()->take(8)->get();
        $onSale = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->where('is_on_sale', 1)->latest()->take(8)->get();
        $newArrivals = \App\Models\Product::with(['images', 'category', 'variations.color'])->where('active_status', 1)->where('is_new_arrival', 1)->latest()->take(8)->get();

        return view('frontend.home', compact('flashModal', 'banners', 'categories', 'allCategories', 'bestSellers', 'onSale', 'newArrivals'));
    }

    public function shop()
    {
        return view('frontend.shop');
    }

    public function productDetails()
    {
        return view('frontend.product-details');
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }

    public function blog()
    {
        return view('frontend.blog');
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }
}
