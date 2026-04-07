<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'alamat_kantor',
        'kepala_desa',
        'telepon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function agregatPenduduks(): HasMany
    {
        return $this->hasMany(AgregatPenduduk::class);
    }

    public function dataStuntings(): HasMany
    {
        return $this->hasMany(DataStunting::class);
    }

    public function ibuHamilKeks(): HasMany
    {
        return $this->hasMany(IbuHamilKek::class);
    }

    public function anggaranStuntings(): HasMany
    {
        return $this->hasMany(AnggaranStunting::class);
    }

    public function bnbaStuntings(): HasMany
    {
        return $this->hasMany(BnbaStunting::class);
    }

    public function desilKemiskinans(): HasMany
    {
        return $this->hasMany(DesilKemiskinan::class);
    }

    public function dataPenganggurans(): HasMany
    {
        return $this->hasMany(DataPengangguran::class);
    }

    public function realisasiPkhs(): HasMany
    {
        return $this->hasMany(RealisasiPkh::class);
    }

    public function realisasiSembakos(): HasMany
    {
        return $this->hasMany(RealisasiSembako::class);
    }

    public function dataAk1s(): HasMany
    {
        return $this->hasMany(DataAk1::class);
    }

    public function dataOjengs(): HasMany
    {
        return $this->hasMany(DataOjeng::class);
    }

    public function dokumentasiLingkungans(): HasMany
    {
        return $this->hasMany(DokumentasiLingkungan::class);
    }
}
