<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisApar extends Model
{
    protected $table = 'jenis_apar';

    protected $fillable = ['nama'];

    public function apar()
    {
        return $this->hasMany(Apar::class);
    }
}
