<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisasiPkh extends Model
{
    protected $fillable = [
        'desa_id',
        'tahun',
        'jumlah_kpm',
        'anggaran',
        'realisasi',
        'persentase_realisasi',
        'periode_penyaluran',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'anggaran' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'persentase_realisasi' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
