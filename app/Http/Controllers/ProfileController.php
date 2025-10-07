<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function showProfile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'company_logo' => 'nullable|image|max:2048', // optional, max 2MB
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Handle company logo upload
        if ($request->hasFile('company_logo')) {
        
            $user->company_logo = $request->file('company_logo')->store('company_logos', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}