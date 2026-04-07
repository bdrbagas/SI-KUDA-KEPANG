<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPengangguran extends Model
{
    protected $fillable = [
        'desa_id',
        'tahun',
        'jumlah_pengangguran',
        'usia_produktif',
        'angkatan_kerja',
        'tingkat_pengangguran',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tingkat_pengangguran' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
