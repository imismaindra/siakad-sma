<?php

use App\Enums\UserRole;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pastikan tipe kolom 'role' pada MySQL production adalah VARCHAR(30)
        // agar tidak terkena pembatasan ENUM v1.0 yang belum memuat 'orang_tua'.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'siswa'");
        }

        // 2. Buat Permission & Role Spatie RBAC untuk Orang Tua
        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            $permLihatAnak = Permission::firstOrCreate(['name' => 'lihat-data-anak', 'guard_name' => 'web']);
            $permViewProfile = Permission::firstOrCreate(['name' => 'view-profile', 'guard_name' => 'web']);

            $roleOrtu = Role::firstOrCreate(['name' => UserRole::OrangTua->value, 'guard_name' => 'web']);
            $roleOrtu->givePermissionTo([$permLihatAnak, $permViewProfile]);
        } catch (\Throwable $e) {
            // Lanjutkan jika tabel permission belum siap
        }

        // 3. Buat atau perbarui akun demo Orang Tua di database production
        // sehingga langsung dapat login tanpa perlu menjalankan db:seed manual.
        $userOrtu = User::where('email', 'ortu@siakad.sch.id')->first();
        if (! $userOrtu) {
            $userOrtu = User::create([
                'name'      => 'Bapak Hermawan (Orang Tua)',
                'email'     => 'ortu@siakad.sch.id',
                'password'  => Hash::make('password'),
                'role'      => UserRole::OrangTua->value,
                'is_active' => true,
            ]);
        } else {
            $userOrtu->update([
                'role'      => UserRole::OrangTua->value,
                'is_active' => true,
            ]);
        }

        try {
            if (! $userOrtu->hasRole(UserRole::OrangTua->value)) {
                $userOrtu->assignRole(UserRole::OrangTua->value);
            }
        } catch (\Throwable $e) {
            // Ignore if role assignment fails
        }

        // 4. Buat profil Orang Tua terkait
        $ortu = OrangTua::where('user_id', $userOrtu->id)->first();
        if (! $ortu) {
            $ortu = OrangTua::create([
                'user_id'       => $userOrtu->id,
                'nama_lengkap'  => 'Hermawan Wijaya, S.E.',
                'jenis_kelamin' => 'L',
                'no_telepon'    => '081234567890',
                'pekerjaan'     => 'Wiraswasta',
                'alamat'        => 'Jl. Merdeka No. 45, Jakarta',
            ]);
        }

        // 5. Tautkan anak pertama jika belum ada siswa yang tertaut
        if ($ortu && $ortu->siswas()->count() === 0) {
            $firstSiswa = Siswa::first();
            if ($firstSiswa) {
                $ortu->siswas()->syncWithoutDetaching([
                    $firstSiswa->id => ['hubungan' => 'ayah'],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak menghapus akun untuk mencegah data loss
    }
};
