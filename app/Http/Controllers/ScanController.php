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

        // Find user by PIN
        $user = User::where('pin', $request->pin)->first();

        if (! $user) {
            return redirect()->back()->with('error', __('PIN tidak valid atau tidak ditemukan.'));
        }

        // Log the user in so they can access the inspection form
        Auth::login($user);

        if ($request->input('action') === 'update') {
            return redirect()->route('scan.apar', ['kode' => $apar->kode, 'edit' => 1]);
        }

        // Redirect to start inspection
        return redirect()->route('inspeksi.pedoman', $apar->id);
    }
}
