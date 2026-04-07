<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataOjeng extends Model
{
    protected $fillable = [
        'desa_id',
        'nama_pangkalan',
        'lokasi',
        'jumlah_ojek',
        'koordinator',
        'telepon',
        'keterangan',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
