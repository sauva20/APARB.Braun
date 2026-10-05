<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Apar;
use Illuminate\Support\Facades\Hash;

class InspectionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_login_menggunakan_pin_saat_scan_apar()
    {
        // 1. Persiapan Data (Simulasi Database)
        $user = User::factory()->create([
            'pin' => Hash::make('1234'), // PIN sudah di-hash sesuai aturan terbaru
            'role' => 'EHSS'
        ]);

        // Karena belum ada factory, buat manual relasinya
        \App\Models\Gedung::create(['id' => 1, 'nama' => 'Gedung A']);
        \App\Models\Lokasi::create(['id' => 1, 'nama' => 'Lantai 1', 'gedung_id' => 1]);
        \App\Models\JenisApar::create(['id' => 1, 'nama' => 'Powder']);
        \App\Models\KapasitasApar::create(['id' => 1, 'ukuran' => '3 Kg']);

        $apar = Apar::create([
            'kode' => 'PFE-R-A1',
            'gedung_id' => 1,
            'lokasi_id' => 1,
            'jenis_id' => 1,
            'kapasitas_id' => 1
        ]);

        // 2. Aksi: User mensimulasikan hit endpoint Verify PIN dengan PIN 1234
        $response = $this->post(route('scan.verify', ['kode' => 'PFE-R-A1']), [
            'pin' => '1234'
        ]);

        // 3. Pengecekan Hasil:
        // - Harusnya sistem me-login-kan user ini
        $this->assertAuthenticatedAs($user);
        
        // - Harusnya user dialihkan/diperbolehkan masuk ke halaman Form (bukan balik error)
        // Note: verifyPin bisa redirect ke 'inspeksi.pedoman' atau kembali ke halaman scan index tergantung session,
        // tapi pastinya tidak ada session error 'PIN tidak valid'.
        $response->assertSessionHasNoErrors();
    }
}
