<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Exception;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        try {
            // Ambil data user dari Google
            $socialUser = Socialite::driver('google')->user();

            // Validasi data yang diterima
            if (!$socialUser || !$socialUser->email) {
                throw new Exception('Invalid data received from Google.');
            }

            // Pisahkan nama lengkap untuk mendapatkan first_name
            $nameParts = explode(' ', $socialUser->name);
            $firstName = $nameParts[0] ?? ''; // Ambil bagian pertama sebagai first_name

            // Cari atau buat user berdasarkan email atau google_id
            $user = User::updateOrCreate(
                [
                    'email' => $socialUser->email, // Cari berdasarkan email
                ],
                [
                    'google_id' => $socialUser->id,
                    'first_name' => $firstName, // Isi first_name
                    'last_name' => $nameParts[1] ?? '', // Isi last_name jika ada
                    'email' => $socialUser->email,
                    'google_token' => $socialUser->token,
                    'google_refresh_token' => $socialUser->refreshToken,
                    'password' => bcrypt(Str::random(16)), // Generate random password untuk keamanan
                ]
            );

            // Berikan role "Customer" jika belum memiliki role
            if (!$user->hasRole('Customer')) {
                $user->assignRole('Customer');
            }

            // Kirim email verifikasi
            if (!$user->hasVerifiedEmail()) {
                event(new Registered($user)); // Trigger event untuk mengirim email verifikasi
            }
            // Login user
            Auth::login($user);

            // Redirect ke dashboard atau halaman yang diinginkan
            return redirect('/home')->with('success', 'Login successful!');
        } catch (Exception $e) {
            // Handle error
            return redirect('/login')->with('error', 'Failed to login with Google: ' . $e->getMessage());
        }
    }
}