<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('front.login');
    }
     public function userlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('front.home')->with('success', 'Login successful!');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }
     public function showRegistrationForm()
    {
        return view('front.register');
    }
     public function userregister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        // dd($user->id);
        return redirect()->route('front.home')->with('success', 'Registration successful!');
    }
     public function UserLogout()
    {
        Auth::logout();
        return redirect()->route('user.login')->with('success', 'Logged out successfully!');
    }

}
