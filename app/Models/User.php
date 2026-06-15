<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',       // Denormalized primary role string (kept for backward compat)
        'foto',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─────────────────────────────────────────────
    //  Scopes
    // ─────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * @deprecated Gunakan hasRole() dari Spatie.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ─────────────────────────────────────────────
    //  Role Helpers (backward-compatible)
    // ─────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::Admin->value);
    }

    public function isGuru(): bool
    {
        return $this->hasRole(UserRole::Guru->value);
    }

    public function isSiswa(): bool
    {
        return $this->hasRole(UserRole::Siswa->value);
    }

    /**
     * Primary role enum (dari kolom `role`).
     * Digunakan untuk redirect dashboard & layout selection.
     */
    public function primaryRole(): ?UserRole
    {
        return UserRole::tryFrom($this->role);
    }

    // ─────────────────────────────────────────────
    //  Relationships
    // ─────────────────────────────────────────────

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    public function kelasWali(): HasOne
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function getFotoUrl(): ?string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        
        if ($this->isGuru() && $this->guru && $this->guru->foto) {
            return asset('storage/' . $this->guru->foto);
        }
        
        if ($this->isSiswa() && $this->siswa && $this->siswa->foto) {
            return asset('storage/' . $this->siswa->foto);
        }
        
        return null;
    }
}

