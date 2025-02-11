<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'], // Email valid
            'password' => ['required'],//, 'min:8'], // Password minimal 8 karakter
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'password.required' => 'Password tidak boleh kosong.',
            //'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Cek kredensial
        // if (Auth::attempt($credentials)) {
        //     $user = Auth::user();

        //     // Periksa role user
        //     if ($user->hasRole(['Customer', 'Reseller'])) {
        //         return redirect()->route('home')->with('success', 'Login berhasil!');
        //     }

        //     Auth::logout();
        //     return back()->with('error', 'Anda tidak memiliki akses.');

        //      // Redirect ke halaman profile dengan first_name
        //     $firstName = strtolower(auth()->user()->first_name);
        //     return redirect()->route('profile', ['first_name' => $firstName]);
        // }
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Periksa apakah pengguna memiliki role yang diizinkan
            if ($user->hasRole(['Customer', 'Reseller'])) {
                // Redirect ke halaman profile dengan first_name
                $firstName = strtolower($user->first_name);
                return redirect("/home")->with('success', 'Login berhasil!');
            }

            // Logout jika pengguna tidak memiliki akses
            Auth::logout();
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Error login
        return back()->withErrors(['error' => 'Email atau password salah.'])->withInput();
    }
    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

        // return redirect('/')->withCookie(cookie()->forget('your_cookie_name'));
    }
}
