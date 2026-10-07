<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Pengecekan Honeypot
        // Jika field '_contact_number' terisi, maka itu pasti Bot (karena disembunyikan dari manusia)
        if (!empty($request->input('_contact_number'))) {
            return back()->withErrors([
                'email' => __('Email atau Password yang Anda masukkan salah.'),
            ])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Buat key unik berdasarkan email dan IP address
        $throttleKey = \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($request->input('email')) . '|' . $request->ip());

        // Cek apakah sudah melebihi 5 kali percobaan
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'email' => 'Akun Anda diblokir sementara karena terlalu banyak percobaan masuk.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            // Hapus blokir jika berhasil login
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

            $request->session()->regenerate();
            $request->session()->forget('is_pin_login');

            return redirect()->intended('/dashboard')->with('success', __('Login berhasil! Selamat datang.'));
        }

        // Catat percobaan gagal (blokir selama 10 menit = 600 detik)
        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 600);

        return back()->withErrors([
            'email' => __('Email atau Password yang Anda masukkan salah.'),
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
