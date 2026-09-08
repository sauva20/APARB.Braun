<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $fillable = ['gedung_id', 'nama'];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function apar()
    {
        return $this->hasMany(Apar::class);
    }
}
