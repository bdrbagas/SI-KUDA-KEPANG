<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IbuHamilKek extends Model
{
    protected $fillable = [
        'desa_id',
        'periode',
        'jumlah_ibu_hamil',
        'jumlah_kek',
        'persentase_kek',
        'keterangan',
    ];

    protected $casts = [
        'periode' => 'date',
        'persentase_kek' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
