<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspeksi extends Model
{
    protected $fillable = [
        'apar_id',
        'user_id',
        'checklist',
        'status',
        'catatan_tambahan',
    ];

    protected $casts = [
        'checklist' => 'array',
    ];

    public function apar()
    {
        return $this->belongsTo(Apar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
