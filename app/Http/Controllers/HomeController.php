<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FlashModal;

class HomeController extends Controller
{
    public function index()
    {
        $flashModal = FlashModal::active()->latest()->first();
        return view('frontend.home', compact('flashModal'));
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
