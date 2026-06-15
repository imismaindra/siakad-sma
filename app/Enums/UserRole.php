<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Guru  = 'guru';
    case Siswa = 'siswa';

    /**
     * Label yang ditampilkan di UI.
     */
    public function label(): string
    {
        return match($this) {
            UserRole::Admin => 'Administrator',
            UserRole::Guru  => 'Guru / Tenaga Pendidik',
            UserRole::Siswa => 'Siswa / Peserta Didik',
        };
    }

    /**
     * Warna badge untuk UI.
     */
    public function badgeClass(): string
    {
        return match($this) {
            UserRole::Admin => 'badge-navy',
            UserRole::Guru  => 'badge-gold',
            UserRole::Siswa => 'badge-success',
        };
    }

    /**
     * Route dashboard berdasarkan role.
     */
    public function dashboardRoute(): string
    {
        return match($this) {
            UserRole::Admin => 'admin.dashboard',
            UserRole::Guru  => 'guru.dashboard',
            UserRole::Siswa => 'siswa.dashboard',
        };
    }

    /**
     * Daftar semua nilai role sebagai array string.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Daftar semua label sebagai ['value' => 'label'].
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
