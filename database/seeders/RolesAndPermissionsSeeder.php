<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Daftar permission terstruktur per modul.
     * Key = permission name (slug), Value = deskripsi singkat
     */
    private array $permissions = [
        // Admin – Manajemen Pengguna
        'manage-users'         => 'Manajemen pengguna (CRUD)',
        // Admin – Master Data
        'manage-kelas'         => 'Manajemen kelas & jurusan',
        'manage-guru'          => 'Manajemen data guru',
        'manage-siswa'         => 'Manajemen data siswa',
        'manage-mapel'         => 'Manajemen mata pelajaran',
        'manage-jadwal'        => 'Manajemen jadwal pelajaran',
        'manage-tahun-ajaran'  => 'Manajemen tahun ajaran',
        // Admin – Monitoring
        'view-absensi-rekap'   => 'Lihat rekap absensi (admin)',
        'koreksi-absensi'      => 'Koreksi data absensi siswa',
        'view-nilai-laporan'   => 'Lihat laporan nilai rapor',
        'finalisasi-nilai'     => 'Finalisasi & publish nilai rapor',
        'view-peringkat'       => 'Lihat peringkat siswa',
        'export-rapor-kelas'   => 'Export rapor kelas (admin)',
        // Guru – Dashboard & Akademik
        'view-dashboard-guru'  => 'Akses dashboard guru',
        'input-absensi'        => 'Input kehadiran siswa',
        'view-absensi-guru'    => 'Lihat rekap absensi milik sendiri',
        'input-nilai'          => 'Input & update nilai siswa',
        'export-rapor-guru'    => 'Export rapor kelas (guru)',
        // Siswa – Portal Pribadi
        'view-dashboard-siswa' => 'Akses dashboard siswa',
        'view-absensi-siswa'   => 'Lihat absensi pribadi',
        'view-nilai-siswa'     => 'Lihat nilai & rapor pribadi',
        'download-rapor'       => 'Download rapor PDF',
        // Umum
        'view-profile'         => 'Lihat & edit profil sendiri',
        // Manajemen Role (super-admin only)
        'manage-roles'         => 'Manajemen role & permission',
    ];

    /**
     * Penetapan permission per role default.
     */
    private array $rolePermissions = [
        UserRole::Admin->value => [
            'manage-users', 'manage-kelas', 'manage-guru', 'manage-siswa',
            'manage-mapel', 'manage-jadwal', 'manage-tahun-ajaran',
            'view-absensi-rekap', 'koreksi-absensi',
            'view-nilai-laporan', 'finalisasi-nilai', 'view-peringkat', 'export-rapor-kelas',
            'view-profile', 'manage-roles',
            // Admin juga dapat akses area guru (review)
            'view-dashboard-guru', 'view-absensi-guru', 'export-rapor-guru',
        ],
        UserRole::Guru->value => [
            'view-dashboard-guru', 'input-absensi', 'view-absensi-guru',
            'input-nilai', 'export-rapor-guru',
            'view-profile',
        ],
        UserRole::Siswa->value => [
            'view-dashboard-siswa', 'view-absensi-siswa',
            'view-nilai-siswa', 'download-rapor',
            'view-profile',
        ],
    ];

    public function run(): void
    {
        // Reset cache permission Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat semua permission
        foreach ($this->permissions as $name => $description) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // 2. Buat role dan assign permission
        foreach ($this->rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }

        // 3. Sinkronisasi user yang sudah ada: assign spatie role
        //    berdasarkan kolom `role` yang sudah ada di tabel users
        User::all()->each(function (User $user) {
            if ($user->role && in_array($user->role, UserRole::values())) {
                // Assign spatie role jika belum punya
                if (! $user->hasAnyRole(UserRole::values())) {
                    $user->assignRole($user->role);
                }
            }
        });

        $this->command->info('✅ Roles & Permissions berhasil di-seed. ' . Role::count() . ' role, ' . Permission::count() . ' permission.');
    }
}
