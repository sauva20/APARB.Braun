<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalInspeksi extends Model
{
    protected $fillable = [
        'jenis_jadwal',
        'tanggal_inspeksi',
        'tipe_area',
        'gedung_id',
        'lokasi_id',
        'petugas',
        'catatan_tambahan',
        'status'
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
