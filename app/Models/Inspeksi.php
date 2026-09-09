<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Inspeksi extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Inspeksi telah di-{$eventName}");
    }

    public function apar()
    {
        return $this->belongsTo(Apar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
