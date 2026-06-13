<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. Admin User
        // ============================================================
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siakad.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ============================================================
        // 2. Tahun Ajaran Aktif
        // ============================================================
        $tahunAjaran = TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => '1',
            'tanggal_mulai' => '2025-07-14',
            'tanggal_selesai' => '2025-12-20',
            'is_aktif' => true,
        ]);

        TahunAjaran::create([
            'nama' => '2025/2026',
            'semester' => '2',
            'tanggal_mulai' => '2026-01-05',
            'tanggal_selesai' => '2026-06-30',
            'is_aktif' => false,
        ]);

        // ============================================================
        // 3. Jurusan
        // ============================================================
        $ipa = Jurusan::create(['nama' => 'Ilmu Pengetahuan Alam', 'kode' => 'IPA']);
        $ips = Jurusan::create(['nama' => 'Ilmu Pengetahuan Sosial', 'kode' => 'IPS']);

        // ============================================================
        // 4. Mata Pelajaran
        // ============================================================
        $mapels = [
            ['kode' => 'MTK', 'nama' => 'Matematika', 'kkm' => 75, 'jumlah_jam_per_minggu' => 4],
            ['kode' => 'IND', 'nama' => 'Bahasa Indonesia', 'kkm' => 75, 'jumlah_jam_per_minggu' => 3],
            ['kode' => 'ING', 'nama' => 'Bahasa Inggris', 'kkm' => 75, 'jumlah_jam_per_minggu' => 3],
            ['kode' => 'FIS', 'nama' => 'Fisika', 'kkm' => 70, 'jumlah_jam_per_minggu' => 3],
            ['kode' => 'KIM', 'nama' => 'Kimia', 'kkm' => 70, 'jumlah_jam_per_minggu' => 3],
            ['kode' => 'BIO', 'nama' => 'Biologi', 'kkm' => 70, 'jumlah_jam_per_minggu' => 3],
            ['kode' => 'SEJ', 'nama' => 'Sejarah Indonesia', 'kkm' => 75, 'jumlah_jam_per_minggu' => 2],
            ['kode' => 'PKN', 'nama' => 'Pendidikan Kewarganegaraan', 'kkm' => 75, 'jumlah_jam_per_minggu' => 2],
            ['kode' => 'AGM', 'nama' => 'Pendidikan Agama', 'kkm' => 75, 'jumlah_jam_per_minggu' => 2],
            ['kode' => 'PJK', 'nama' => 'Pendidikan Jasmani', 'kkm' => 75, 'jumlah_jam_per_minggu' => 2],
        ];

        $mataPelajaransCreated = [];
        foreach ($mapels as $mapel) {
            $mataPelajaransCreated[$mapel['kode']] = MataPelajaran::create($mapel);
        }

        // ============================================================
        // 5. Guru Users & Profil
        // ============================================================
        $gurusData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@siakad.sch.id', 'nip' => '198501012010011001', 'jk' => 'L', 'mapel' => ['MTK']],
            ['name' => 'Siti Rahayu', 'email' => 'siti@siakad.sch.id', 'nip' => '198703152011012002', 'jk' => 'P', 'mapel' => ['IND', 'SEJ']],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@siakad.sch.id', 'nip' => '199001202013011003', 'jk' => 'L', 'mapel' => ['FIS', 'KIM']],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@siakad.sch.id', 'nip' => '198812052012012004', 'jk' => 'P', 'mapel' => ['BIO']],
            ['name' => 'Hendra Wijaya', 'email' => 'hendra@siakad.sch.id', 'nip' => '197908102009011005', 'jk' => 'L', 'mapel' => ['ING']],
            ['name' => 'Rina Melati', 'email' => 'rina@siakad.sch.id', 'nip' => '199205302014012006', 'jk' => 'P', 'mapel' => ['PKN', 'AGM', 'PJK']],
        ];

        $gurusCreated = [];
        foreach ($gurusData as $g) {
            $user = User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
                'is_active' => true,
            ]);

            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $g['nip'],
                'nama_lengkap' => $g['name'],
                'jenis_kelamin' => $g['jk'],
                'status' => 'aktif',
            ]);

            // Assign mata pelajaran
            $syncData = [];
            foreach ($g['mapel'] as $kode) {
                $mapelId = $mataPelajaransCreated[$kode]->id;
                $syncData[$mapelId] = ['tahun_ajaran_id' => $tahunAjaran->id];
            }
            $guru->mataPelajarans()->sync($syncData);

            $gurusCreated[$g['email']] = $guru;
        }

        // ============================================================
        // 6. Kelas (X IPA 1, X IPS 1, XI IPA 1)
        // ============================================================
        $kelas1 = Kelas::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $ipa->id,
            'wali_kelas_id' => $gurusCreated['budi@siakad.sch.id']->user_id,
            'tingkat' => 'X',
            'nomor' => 1,
            'kapasitas' => 36,
        ]);

        $kelas2 = Kelas::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $ips->id,
            'wali_kelas_id' => $gurusCreated['siti@siakad.sch.id']->user_id,
            'tingkat' => 'X',
            'nomor' => 1,
            'kapasitas' => 36,
        ]);

        $kelas3 = Kelas::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $ipa->id,
            'wali_kelas_id' => $gurusCreated['ahmad@siakad.sch.id']->user_id,
            'tingkat' => 'XI',
            'nomor' => 1,
            'kapasitas' => 36,
        ]);

        // ============================================================
        // 7. Siswa
        // ============================================================
        $siswaKelas1 = [
            ['nis' => '240001', 'nama' => 'Andi Prasetyo', 'jk' => 'L', 'email' => 'andi@siakad.sch.id'],
            ['nis' => '240002', 'nama' => 'Bella Safitri', 'jk' => 'P', 'email' => 'bella@siakad.sch.id'],
            ['nis' => '240003', 'nama' => 'Cahyo Nugroho', 'jk' => 'L', 'email' => null],
            ['nis' => '240004', 'nama' => 'Diana Permata', 'jk' => 'P', 'email' => null],
            ['nis' => '240005', 'nama' => 'Eko Susanto', 'jk' => 'L', 'email' => null],
        ];

        foreach ($siswaKelas1 as $s) {
            $userId = null;
            if ($s['email']) {
                $user = User::create([
                    'name' => $s['nama'],
                    'email' => $s['email'],
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                    'is_active' => true,
                ]);
                $userId = $user->id;
            }

            Siswa::create([
                'user_id' => $userId,
                'kelas_id' => $kelas1->id,
                'nis' => $s['nis'],
                'nama_lengkap' => $s['nama'],
                'jenis_kelamin' => $s['jk'],
                'tanggal_lahir' => '2008-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Siswa kelas 2
        foreach ([
            ['nis' => '240006', 'nama' => 'Fajar Setiawan', 'jk' => 'L'],
            ['nis' => '240007', 'nama' => 'Gita Maharani', 'jk' => 'P'],
            ['nis' => '240008', 'nama' => 'Hani Putri', 'jk' => 'P'],
        ] as $s) {
            Siswa::create([
                'kelas_id' => $kelas2->id,
                'nis' => $s['nis'],
                'nama_lengkap' => $s['nama'],
                'jenis_kelamin' => $s['jk'],
                'tanggal_lahir' => '2008-05-10',
            ]);
        }

        // ============================================================
        // 8. Jadwal Pelajaran (Kelas 1 - X IPA 1)
        // ============================================================
        $jadwals = [
            // Senin
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['MTK']->id, 'guru_id' => $gurusCreated['budi@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['FIS']->id, 'guru_id' => $gurusCreated['ahmad@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['IND']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '10:15', 'jam_selesai' => '11:45', 'urutan_jam' => 3],
            // Selasa
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['ING']->id, 'guru_id' => $gurusCreated['hendra@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['KIM']->id, 'guru_id' => $gurusCreated['ahmad@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
            // Rabu
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['BIO']->id, 'guru_id' => $gurusCreated['dewi@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
            ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['PKN']->id, 'guru_id' => $gurusCreated['rina@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
        ];

        foreach ($jadwals as $j) {
            \App\Models\JadwalPelajaran::create(array_merge($j, [
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]));
        }

        $this->command->info('✅ Seeder selesai! Login dengan:');
        $this->command->info('   Admin  : admin@siakad.sch.id / password');
        $this->command->info('   Guru   : budi@siakad.sch.id  / password');
        $this->command->info('   Siswa  : andi@siakad.sch.id  / password');
    }
}
