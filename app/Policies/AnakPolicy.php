<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class AnakPolicy
{
    /**
     * Menentukan apakah user (orang tua / siswa / admin) berhak melihat data anak/siswa.
     * F4-04: Akses orang tua bersifat read-only dan hanya untuk anak yang tertaut.
     */
    public function view(User $user, Siswa $siswa): bool
    {
        // 1. Admin/TU memiliki akses menyeluruh
        if ($user->hasRole('admin') || $user->role === 'admin') {
            return true;
        }

        // 2. Orang Tua hanya boleh melihat anak yang tertaut pada profilnya
        if ($user->isOrangTua()) {
            return $user->orangTua && $user->orangTua->siswas()->where('siswas.id', $siswa->id)->exists();
        }

        // 3. Siswa hanya boleh melihat datanya sendiri
        if ($user->isSiswa()) {
            return $user->siswa && $user->siswa->id === $siswa->id;
        }

        return false;
    }
}
