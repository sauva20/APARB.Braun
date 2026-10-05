<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function index(Request $request, $kode)
    {
        // Jika pengguna sudah login (via PIN) dan tidak berasal dari dashboard sistem,
        // kita log out sesi mereka agar jika mereka menekan "Kembali", sesi inspeksi benar-benar terhapus.
        // Dengan begitu, jika mereka menekan "Forward" (Maju) di browser, mereka tetap tidak bisa masuk tanpa PIN.
        if (\Illuminate\Support\Facades\Auth::check() && $request->source !== 'system') {
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $apar = Apar::with([
            'lokasi.gedung',
            'jenis',
            'kapasitas',
            'inspeksis' => function ($query) {
                $query->latest()->with('user');
            }
        ])->where('kode', $kode)->firstOrFail();

        $latestInspeksi = $apar->inspeksis->first();
        
        $gedungs = \App\Models\Gedung::with('lokasi')->get();
        $jenisApars = \App\Models\JenisApar::all();
        $kapasitasApars = \App\Models\KapasitasApar::all();

        return view('scan.index', compact('apar', 'latestInspeksi', 'gedungs', 'jenisApars', 'kapasitasApars'));
    }

    public function verifyPin(Request $request, $kode)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        $apar = Apar::where('kode', $kode)->firstOrFail();

        // Rate Limiting: Maksimal 5 percobaan dalam 15 menit per IP dan APAR
        $key = 'verify-pin:' . $apar->id . '|' . $request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            return redirect()->back()->with('error', __('Terlalu banyak percobaan PIN gagal. Silakan coba lagi dalam :seconds detik.', ['seconds' => $seconds]));
        }

        // Find user by PIN (Loop through all users because PIN is hashed)
        $users = User::whereNotNull('pin')->get();
        $user = $users->first(function ($u) use ($request) {
            return \Illuminate\Support\Facades\Hash::check($request->pin, $u->pin) || $u->pin === $request->pin;
        });

        if (! $user) {
            \Illuminate\Support\Facades\RateLimiter::hit($key, 15 * 60); // Blokir 15 menit jika 5x gagal
            return redirect()->back()->with('error', __('PIN tidak valid atau tidak ditemukan.'));
        }

        \Illuminate\Support\Facades\RateLimiter::clear($key);

        // Log the user in so they can access the inspection form
        Auth::login($user);

        if ($request->input('action') === 'update') {
            return redirect()->route('scan.apar', ['kode' => $apar->kode, 'edit' => 1]);
        }

        // Redirect to start inspection
        return redirect()->route('inspeksi.pedoman', $apar->id);
    }
}
