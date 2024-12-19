<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:16',
            'email' => 'required|email|max:250|unique:users',
            'address' => 'required|string|max:250',
            'password' => 'required|min:8|confirmed'
        ]);

        $now = now();

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'state' => 'Active',
            'first_login' => $now,
            'last_login' => now()->addSecond()
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('catalog')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
