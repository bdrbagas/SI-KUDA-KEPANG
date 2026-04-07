<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumentasiLingkungan extends Model
{
    protected $fillable = [
        'desa_id',
        'user_id',
        'jenis_kegiatan',
        'judul',
        'deskripsi',
        'tanggal_kegiatan',
        'lokasi',
        'jumlah_peserta',
        'penanggung_jawab',
        'foto',
        'hasil_kegiatan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'foto' => 'array',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format jenis kegiatan
    public function getJenisKegiatanLabelAttribute(): string
    {
        return match ($this->jenis_kegiatan) {
            'penanaman_pohon' => 'Penanaman Pohon',
            'pembersihan_sampah' => 'Pembersihan Sampah',
            'penebangan_liar' => 'Penebangan Pohon Liar',
            default => $this->jenis_kegiatan,
        };
    }
}
