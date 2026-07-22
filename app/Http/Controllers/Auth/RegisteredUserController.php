<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate a placeholder email since it's required by the users table
        $placeholderEmail = $request->phone . '@buzz.local';

        // Split name into fname and lname for users table
        $nameParts = explode(' ', $request->name, 2);
        $fname = $nameParts[0];
        $lname = isset($nameParts[1]) ? $nameParts[1] : null;

        $user = User::create([
            'fname' => $fname,
            'lname' => $lname,
            'phone' => $request->phone,
            'email' => $placeholderEmail,
            'password' => Hash::make($request->password),
        ]);

        // Create a matching customer record
        \App\Models\Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $placeholderEmail,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('frontend.home', absolute: false));
    }
}
