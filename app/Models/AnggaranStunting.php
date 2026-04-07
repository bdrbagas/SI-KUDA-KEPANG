<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranStunting extends Model
{
    protected $fillable = [
        'desa_id',
        'sumber',
        'tahun',
        'program',
        'deskripsi',
        'pagu',
        'realisasi',
        'persentase_realisasi',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'pagu' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'persentase_realisasi' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    // Accessor untuk format sumber
    public function getSumberLabelAttribute(): string
    {
        return match ($this->sumber) {
            'desa' => 'Dana Desa',
            'puskesmas' => 'Puskesmas',
            'csr' => 'CSR',
            default => $this->sumber,
        };
    }
}
