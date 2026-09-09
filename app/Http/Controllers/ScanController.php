<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function index($kode)
    {
        $apar = Apar::where('kode', $kode)->firstOrFail();

        return view('scan.index', compact('apar'));
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
            return redirect()->back()->with('error', 'PIN tidak valid atau tidak ditemukan.');
        }

        // Log the user in so they can access the inspection form
        Auth::login($user);

        // Redirect to start inspection
        return redirect()->route('inspeksi.mulai', $apar->id);
    }
}
