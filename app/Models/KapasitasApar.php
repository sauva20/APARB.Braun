<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KapasitasApar extends Model
{
    protected $table = 'kapasitas_apar';
    protected $fillable = ['ukuran'];

    public function apar()
    {
        return $this->hasMany(Apar::class);
    }
}
