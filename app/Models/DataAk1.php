<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataAk1 extends Model
{
    protected $fillable = [
        'desa_id',
        'tahun',
        'total_pencaker',
        'laki_laki',
        'perempuan',
        'sd_sederajat',
        'smp_sederajat',
        'sma_sederajat',
        'diploma',
        'sarjana',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
