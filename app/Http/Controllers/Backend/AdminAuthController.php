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

    /**
     * Handle an incoming admin authentication request.
     */
    public function store(\App\Http\Requests\Auth\LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->authenticate('admin');

        $user = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        if (!$user->hasRole('Admin') && !$user->hasRole('Super Admin')) {
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Only admins can log in to this portal.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function destroy(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        \Illuminate\Support\Facades\Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/backend/login');
    }
}
