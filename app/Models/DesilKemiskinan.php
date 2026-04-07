<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesilKemiskinan extends Model
{
    protected $fillable = [
        'desa_id',
        'tahun',
        'desil_1',
        'desil_2',
        'desil_3',
        'desil_4',
        'desil_5',
        'total_keluarga',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    // Accessor untuk total desil miskin (1-5)
    public function getTotalMiskinAttribute(): int
    {
        return $this->desil_1 + $this->desil_2 + $this->desil_3 + $this->desil_4 + $this->desil_5;
    }
}
