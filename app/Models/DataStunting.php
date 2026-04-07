<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataStunting extends Model
{
    protected $fillable = [
        'desa_id',
        'periode',
        'jumlah_balita_ditimbang',
        'jumlah_stunting',
        'persentase_stunting',
        'keterangan',
    ];

    protected $casts = [
        'periode' => 'date',
        'persentase_stunting' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
