<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        return view('backend.pages.profile.profile');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->only(['fname', 'lname', 'email', 'phone']);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image) {
                    $oldPath = public_path('backend/images/users/' . $user->image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('backend/images/users'), $filename);
                $data['image'] = $filename;
            }

            $user->update($data);

            toast('Profile updated successfully!', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Failed to update profile: ' . $e->getMessage(), 'danger');
            return back()->withInput();
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        toast('Password updated successfully!', 'success');
        return redirect()->back();
    }
}
