<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'desa_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // Relations
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    // Helper methods
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminDesa(): bool
    {
        return $this->role === 'admin_desa';
    }

    public function isAdminKecamatan(): bool
    {
        return $this->role === 'admin_kecamatan';
    }

    public function isAdminPuskesmas(): bool
    {
        return $this->role === 'admin_puskesmas';
    }

    public function isAdminCsr(): bool
    {
        return $this->role === 'admin_csr';
    }

    public function canManageAllDesa(): bool
    {
        return in_array($this->role, ['super_admin', 'admin_kecamatan', 'admin_puskesmas', 'admin_csr']);
    }
}

