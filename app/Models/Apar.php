<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apar extends Model
{
    protected $table = 'apar';
    protected $fillable = ['kode', 'lokasi_id', 'jenis_id', 'kapasitas_id', 'vendor', 'tgl_kedaluwarsa', 'foto'];

    protected $casts = [
        'tgl_kedaluwarsa' => 'date',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function jenis()
    {
        return $this->belongsTo(JenisApar::class, 'jenis_id');
    }

    public function kapasitas()
    {
        return $this->belongsTo(KapasitasApar::class, 'kapasitas_id');
    }

    public function inspeksis()
    {
        return $this->hasMany(Inspeksi::class);
    }
}
