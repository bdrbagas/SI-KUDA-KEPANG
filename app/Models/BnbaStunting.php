<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BnbaStunting extends Model
{
    protected $fillable = [
        'desa_id',
        'periode',
        'nik',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'rt_rw',
        'nama_ibu',
        'nama_ayah',
        'status',
        'posyandu',
        'berat_badan',
        'tinggi_badan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'periode' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    // Accessor untuk format status
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'stunting' => 'Stunting',
            'kek' => 'KEK',
            'normal' => 'Normal',
            'resiko' => 'Resiko',
            default => $this->status,
        };
    }

    // Accessor untuk umur
    public function getUmurAttribute(): ?int
    {
        if (!$this->tanggal_lahir)
            return null;
        return $this->tanggal_lahir->age;
    }
}
