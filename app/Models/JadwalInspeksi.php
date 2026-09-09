<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JadwalInspeksi extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'jenis_jadwal',
        'tanggal_inspeksi',
        'tipe_area',
        'gedung_id',
        'lokasi_id',
        'user_id',
        'catatan_tambahan',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Jadwal Inspeksi telah di-{$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
