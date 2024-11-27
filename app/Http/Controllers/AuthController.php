<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function verify(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            return redirect()->intended('/admin/dashboard');
        } else if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'staff'])) {
            return redirect()->intended('/staff/dashboard');
        } else if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'user'])) {
            return redirect()->intended('/user/home');
        } else {
            return redirect('/')->with('msg', 'Email and Password are incorrect.');
        }
    }

    public function logout(Request $request) {
        Auth::logout(); // Logout user
        
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/'); // Redirect to home or login page
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'no_identitas' => 'required|string|max:20|unique:users,no_identitas',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Simpan data pengguna ke database
        User::create([
            'name' => $validatedData['name'],
            'no_identitas' => $validatedData['no_identitas'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->intended('/register')->with('msg', 'Registration successful. Please log in.');
    }
}
