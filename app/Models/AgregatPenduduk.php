<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgregatPenduduk extends Model
{
    protected $fillable = [
        'desa_id',
        'tahun',
        'jumlah_penduduk',
        'jumlah_kk',
        'balita',
        'ibu_hamil',
        'laki_laki',
        'perempuan',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
