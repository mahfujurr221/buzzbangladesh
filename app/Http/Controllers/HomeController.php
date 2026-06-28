<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class HomeController extends Controller
{
    /**
     * Frontend landing page
     */
    public function index()
    {
        return view('frontend.home');

    }
}
