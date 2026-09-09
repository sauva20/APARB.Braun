<?php

namespace Database\Seeders;

use App\Models\Apar;
use App\Models\Gedung;
use App\Models\JenisApar;
use App\Models\KapasitasApar;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Gedung
        $gedungUtama = Gedung::create(['nama' => 'BU-R (Gedung Utama)']);
        $gedungProduksi = Gedung::create(['nama' => 'BU-T (Gedung Produksi)']);

        // 2. Lokasi
        $lokOpenOffice = Lokasi::create(['gedung_id' => $gedungUtama->id, 'nama' => 'Open Office']);
        $lokArchiveRoom = Lokasi::create(['gedung_id' => $gedungUtama->id, 'nama' => 'Archive Room']);
        $lokPrepArea = Lokasi::create(['gedung_id' => $gedungProduksi->id, 'nama' => 'Preparation Area']);
        $lokCompound = Lokasi::create(['gedung_id' => $gedungProduksi->id, 'nama' => 'Compound Mixing Area']);

        // 3. Jenis
        $jenisPowder = JenisApar::create(['nama' => 'ABC Powder']);
        $jenisCo2 = JenisApar::create(['nama' => 'CO2']);
        $jenisFoam = JenisApar::create(['nama' => 'Foam']);

        // 4. Kapasitas
        $kap3 = KapasitasApar::create(['ukuran' => '3 Kg']);
        $kap5 = KapasitasApar::create(['ukuran' => '5 Kg']);
        $kap6 = KapasitasApar::create(['ukuran' => '6 Kg']);

        // 5. APAR
        Apar::create([
            'kode' => 'PFE-R-A1',
            'lokasi_id' => $lokOpenOffice->id,
            'jenis_id' => $jenisPowder->id,
            'kapasitas_id' => $kap6->id,
            'tgl_kedaluwarsa' => '2026-04-20',
        ]);

        Apar::create([
            'kode' => 'PFE-R-B11',
            'lokasi_id' => $lokPrepArea->id,
            'jenis_id' => $jenisCo2->id,
            'kapasitas_id' => $kap5->id,
            'tgl_kedaluwarsa' => '2026-04-20',
        ]);

        Apar::create([
            'kode' => 'PFE-T-B21',
            'lokasi_id' => $lokCompound->id,
            'jenis_id' => $jenisCo2->id,
            'kapasitas_id' => $kap5->id,
            'tgl_kedaluwarsa' => '2023-01-01', // Expired
        ]);

        Apar::create([
            'kode' => 'PFE-R-A2',
            'lokasi_id' => $lokArchiveRoom->id,
            'jenis_id' => $jenisPowder->id,
            'kapasitas_id' => $kap6->id,
            'tgl_kedaluwarsa' => '2026-04-20',
        ]);
    }
}
