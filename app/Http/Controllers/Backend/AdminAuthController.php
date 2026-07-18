<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\View\View;

class AdminAuthController extends AuthenticatedSessionController
{
    /**
     * Display the admin login view.
     */
    public function create(): View
    {
        return view('backend.auth.login');
    }
}
