<?php

namespace App\Http\Controllers;

use App\Models\InvoiceTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Register user
   public function register(Request $request)
{
    // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    // Create user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Automatically create default InvoiceTitle for this user
    InvoiceTitle::create([
        'user_id' => $user->id,
        'invoice_number_title' => 'Invoice #',
        'invoice_date_title' => 'Date',
        'payment_terms_title' => 'Payment Terms',
        'due_date_title' => 'Due Date',
        'po_number_title' => 'PO Number',
        'bill_to_title' => 'Bill To',
        'ship_to_title' => 'Ship To',
    ]);

    // Login user
    Auth::login($user);

    return redirect('/');
}

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login user
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}