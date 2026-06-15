<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\BobotNilai;
use App\Models\DetailAbsensi;
use App\Models\DetailNilai;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. Admin User
        // ============================================================
        $admin = User::create([
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
        // 6. Kelas (X IPA 1, X IPS 1, XI IPA 1, XI IPS 1)
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

        $kelas4 = Kelas::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $ips->id,
            'wali_kelas_id' => $gurusCreated['dewi@siakad.sch.id']->user_id,
            'tingkat' => 'XI',
            'nomor' => 1,
            'kapasitas' => 36,
        ]);

        // ============================================================
        // 7. Siswa per Kelas
        // ============================================================
        $siswasByKelas = [
            $kelas1->id => [
                ['nis' => '240001', 'nama' => 'Andi Prasetyo', 'jk' => 'L', 'email' => 'andi@siakad.sch.id'],
                ['nis' => '240002', 'nama' => 'Bella Safitri', 'jk' => 'P', 'email' => 'bella@siakad.sch.id'],
                ['nis' => '240003', 'nama' => 'Cahyo Nugroho', 'jk' => 'L', 'email' => 'cahyo@siakad.sch.id'],
                ['nis' => '240004', 'nama' => 'Diana Permata', 'jk' => 'P', 'email' => 'diana@siakad.sch.id'],
                ['nis' => '240005', 'nama' => 'Eko Susanto', 'jk' => 'L', 'email' => 'eko@siakad.sch.id'],
            ],
            $kelas2->id => [
                ['nis' => '240006', 'nama' => 'Fajar Setiawan', 'jk' => 'L', 'email' => 'fajar@siakad.sch.id'],
                ['nis' => '240007', 'nama' => 'Gita Maharani', 'jk' => 'P', 'email' => 'gita@siakad.sch.id'],
                ['nis' => '240008', 'nama' => 'Hani Putri', 'jk' => 'P', 'email' => 'hani@siakad.sch.id'],
                ['nis' => '240009', 'nama' => 'Indra Wijaya', 'jk' => 'L', 'email' => 'indra@siakad.sch.id'],
                ['nis' => '240010', 'nama' => 'Julia Perez', 'jk' => 'P', 'email' => 'julia@siakad.sch.id'],
            ],
            $kelas3->id => [
                ['nis' => '230001', 'nama' => 'Kevin Sanjaya', 'jk' => 'L', 'email' => 'kevin@siakad.sch.id'],
                ['nis' => '230002', 'nama' => 'Lesti Kejora', 'jk' => 'P', 'email' => 'lesti@siakad.sch.id'],
                ['nis' => '230003', 'nama' => 'Mamat Alkatiri', 'jk' => 'L', 'email' => 'mamat@siakad.sch.id'],
                ['nis' => '230004', 'nama' => 'Nabila Syakieb', 'jk' => 'P', 'email' => 'nabila@siakad.sch.id'],
                ['nis' => '230005', 'nama' => 'Owen Wijaya', 'jk' => 'L', 'email' => 'owen@siakad.sch.id'],
            ],
            $kelas4->id => [
                ['nis' => '230006', 'nama' => 'Putra Siregar', 'jk' => 'L', 'email' => 'putra@siakad.sch.id'],
                ['nis' => '230007', 'nama' => 'Queen Elizabeth', 'jk' => 'P', 'email' => 'queen@siakad.sch.id'],
                ['nis' => '230008', 'nama' => 'Rizky Billar', 'jk' => 'L', 'email' => 'rizky@siakad.sch.id'],
                ['nis' => '230009', 'nama' => 'Siska Kohl', 'jk' => 'P', 'email' => 'siska@siakad.sch.id'],
                ['nis' => '230010', 'nama' => 'Taufik Hidayat', 'jk' => 'L', 'email' => 'taufik@siakad.sch.id'],
            ],
        ];

        $siswasCreated = [];
        foreach ($siswasByKelas as $kelasId => $siswaList) {
            $siswasCreated[$kelasId] = [];
            foreach ($siswaList as $s) {
                $user = User::create([
                    'name' => $s['nama'],
                    'email' => $s['email'],
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                    'is_active' => true,
                ]);

                $siswa = Siswa::create([
                    'user_id' => $user->id,
                    'kelas_id' => $kelasId,
                    'nis' => $s['nis'],
                    'nisn' => '008' . rand(1000000, 9999999),
                    'nama_lengkap' => $s['nama'],
                    'jenis_kelamin' => $s['jk'],
                    'tanggal_lahir' => '2008-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'status' => 'aktif',
                    'alamat' => 'Jl. Nusantara No. ' . rand(1, 100) . ', Kota Bandung',
                    'no_telepon' => '0812' . rand(10000000, 99999999),
                    'no_telepon_ortu' => '0813' . rand(10000000, 99999999),
                    'nama_ortu' => 'Bapak/Ibu ' . explode(' ', $s['nama'])[count(explode(' ', $s['nama'])) - 1],
                ]);

                $siswasCreated[$kelasId][] = $siswa;
            }
        }

        // ============================================================
        // 8. Jadwal Pelajaran untuk Semua Kelas
        // ============================================================
        $jadwalData = [
            // Kelas 1 (X IPA 1)
            [
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['MTK']->id, 'guru_id' => $gurusCreated['budi@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['FIS']->id, 'guru_id' => $gurusCreated['ahmad@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['IND']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '10:15', 'jam_selesai' => '11:45', 'urutan_jam' => 3],
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['ING']->id, 'guru_id' => $gurusCreated['hendra@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['KIM']->id, 'guru_id' => $gurusCreated['ahmad@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['BIO']->id, 'guru_id' => $gurusCreated['dewi@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas1->id, 'mata_pelajaran_id' => $mataPelajaransCreated['PKN']->id, 'guru_id' => $gurusCreated['rina@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
            ],
            // Kelas 2 (X IPS 1)
            [
                ['kelas_id' => $kelas2->id, 'mata_pelajaran_id' => $mataPelajaransCreated['IND']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas2->id, 'mata_pelajaran_id' => $mataPelajaransCreated['SEJ']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas2->id, 'mata_pelajaran_id' => $mataPelajaransCreated['MTK']->id, 'guru_id' => $gurusCreated['budi@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas2->id, 'mata_pelajaran_id' => $mataPelajaransCreated['ING']->id, 'guru_id' => $gurusCreated['hendra@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas2->id, 'mata_pelajaran_id' => $mataPelajaransCreated['PKN']->id, 'guru_id' => $gurusCreated['rina@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas2->id, 'mata_pelajaran_id' => $mataPelajaransCreated['AGM']->id, 'guru_id' => $gurusCreated['rina@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
            ],
            // Kelas 3 (XI IPA 1)
            [
                ['kelas_id' => $kelas3->id, 'mata_pelajaran_id' => $mataPelajaransCreated['FIS']->id, 'guru_id' => $gurusCreated['ahmad@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas3->id, 'mata_pelajaran_id' => $mataPelajaransCreated['KIM']->id, 'guru_id' => $gurusCreated['ahmad@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas3->id, 'mata_pelajaran_id' => $mataPelajaransCreated['MTK']->id, 'guru_id' => $gurusCreated['budi@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas3->id, 'mata_pelajaran_id' => $mataPelajaransCreated['BIO']->id, 'guru_id' => $gurusCreated['dewi@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas3->id, 'mata_pelajaran_id' => $mataPelajaransCreated['ING']->id, 'guru_id' => $gurusCreated['hendra@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas3->id, 'mata_pelajaran_id' => $mataPelajaransCreated['IND']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
            ],
            // Kelas 4 (XI IPS 1)
            [
                ['kelas_id' => $kelas4->id, 'mata_pelajaran_id' => $mataPelajaransCreated['SEJ']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas4->id, 'mata_pelajaran_id' => $mataPelajaransCreated['IND']->id, 'guru_id' => $gurusCreated['siti@siakad.sch.id']->id, 'hari' => 'Senin', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas4->id, 'mata_pelajaran_id' => $mataPelajaransCreated['ING']->id, 'guru_id' => $gurusCreated['hendra@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas4->id, 'mata_pelajaran_id' => $mataPelajaransCreated['MTK']->id, 'guru_id' => $gurusCreated['budi@siakad.sch.id']->id, 'hari' => 'Selasa', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
                ['kelas_id' => $kelas4->id, 'mata_pelajaran_id' => $mataPelajaransCreated['PKN']->id, 'guru_id' => $gurusCreated['rina@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30', 'urutan_jam' => 1],
                ['kelas_id' => $kelas4->id, 'mata_pelajaran_id' => $mataPelajaransCreated['PJK']->id, 'guru_id' => $gurusCreated['rina@siakad.sch.id']->id, 'hari' => 'Rabu', 'jam_mulai' => '08:30', 'jam_selesai' => '10:00', 'urutan_jam' => 2],
            ],
        ];

        $jadwalsCreated = [];
        foreach ($jadwalData as $jadwalList) {
            foreach ($jadwalList as $j) {
                $jadwalsCreated[] = JadwalPelajaran::create(array_merge($j, [
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ]));
            }
        }

        // ============================================================
        // 9. Bobot Nilai
        // ============================================================
        // Buat bobot nilai untuk setiap kelas dan mapel yang ada di jadwal pelajaran
        foreach ($jadwalsCreated as $jadwal) {
            BobotNilai::firstOrCreate([
                'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'kelas_id' => $jadwal->kelas_id,
            ], [
                'bobot_harian' => 40,
                'bobot_uts' => 30,
                'bobot_uas' => 30,
            ]);
        }

        // ============================================================
        // 10. Absensi & Detail Absensi (2 Minggu Terakhir)
        // ============================================================
        $hariIndoToEng = [
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
        ];

        // Loop 10 hari ke belakang
        for ($i = 10; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Lewati Sabtu dan Minggu
            if ($date->isWeekend()) {
                continue;
            }

            // Dapatkan hari dalam Bahasa Indonesia
            $hariEng = $date->format('l');
            $hariIndo = array_search($hariEng, $hariIndoToEng);

            if (!$hariIndo) {
                continue;
            }

            // Cari jadwal yang diajarkan pada hari ini
            $jadwalHariIni = JadwalPelajaran::where('hari', $hariIndo)
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->get();

            foreach ($jadwalHariIni as $jadwal) {
                $absensi = Absensi::create([
                    'jadwal_pelajaran_id' => $jadwal->id,
                    'kelas_id' => $jadwal->kelas_id,
                    'guru_id' => $jadwal->guru_id,
                    'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
                    'tanggal' => $date->format('Y-m-d'),
                    'materi' => 'Materi Pertemuan ke-' . (11 - $i) . ' mengenai pembahasan bab ' . rand(1, 3),
                    'catatan' => 'Pembelajaran berjalan lancar.',
                ]);

                // Detail absensi untuk setiap siswa di kelas tersebut
                $siswasInKelas = $siswasCreated[$jadwal->kelas_id] ?? [];
                foreach ($siswasInKelas as $siswa) {
                    $rand = rand(1, 100);
                    if ($rand <= 92) {
                        $status = 'hadir';
                        $keterangan = null;
                    } elseif ($rand <= 95) {
                        $status = 'sakit';
                        $keterangan = 'Demam dan sakit kepala';
                    } elseif ($rand <= 98) {
                        $status = 'izin';
                        $keterangan = 'Acara keluarga';
                    } else {
                        $status = 'alpa';
                        $keterangan = 'Tanpa keterangan';
                    }

                    DetailAbsensi::create([
                        'absensi_id' => $absensi->id,
                        'siswa_id' => $siswa->id,
                        'status' => $status,
                        'keterangan' => $keterangan,
                    ]);
                }
            }
        }

        // ============================================================
        // 11. Nilai & Detail Nilai
        // ============================================================
        // Loop setiap kelas dan siswanya
        foreach ($siswasCreated as $kelasId => $siswas) {
            $kelas = Kelas::find($kelasId);
            // Cari mata pelajaran yang diampu di kelas ini lewat jadwal pelajaran
            $mapelIds = JadwalPelajaran::where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->pluck('mata_pelajaran_id')
                ->unique();

            foreach ($siswas as $siswa) {
                foreach ($mapelIds as $mapelId) {
                    // Cari guru yang mengajar mapel ini di kelas ini
                    $jadwal = JadwalPelajaran::where('kelas_id', $kelasId)
                        ->where('mata_pelajaran_id', $mapelId)
                        ->where('tahun_ajaran_id', $tahunAjaran->id)
                        ->first();

                    $guruId = $jadwal ? $jadwal->guru_id : $gurusCreated[array_key_first($gurusCreated)]->id;

                    // Kelas X (tingkat X) akan difinalisasi (is_final = true) agar rapor siap didownload
                    // Kelas XI (tingkat XI) tidak difinalisasi (is_final = false) agar admin bisa demo finalisasi
                    $isFinal = ($kelas->tingkat === 'X');

                    // Base score generator untuk variansi murid pintar / sedang
                    $baseScore = rand(70, 90);

                    $nilai = Nilai::create([
                        'siswa_id' => $siswa->id,
                        'mata_pelajaran_id' => $mapelId,
                        'kelas_id' => $kelasId,
                        'guru_id' => $guruId,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'nilai_uts' => min(100, $baseScore + rand(-5, 8)),
                        'nilai_uas' => min(100, $baseScore + rand(-6, 9)),
                        'deskripsi' => $siswa->jenis_kelamin === 'L' ? 
                            'Menunjukkan penguasaan materi yang baik, pertahankan konsentrasi saat belajar mandiri.' : 
                            'Sangat aktif berpartisipasi di kelas dan memahami konsep pembelajaran dengan sangat baik.',
                        'is_final' => $isFinal,
                    ]);

                    // Tambahkan Detail Nilai (Tugas 1, Tugas 2, Ulangan Harian)
                    $details = [
                        ['judul' => 'Tugas 1', 'jenis' => 'tugas', 'nilai' => min(100, $baseScore + rand(-10, 10))],
                        ['judul' => 'Kuis 1', 'jenis' => 'kuis', 'nilai' => min(100, $baseScore + rand(-15, 10))],
                        ['judul' => 'Ulangan Harian 1', 'jenis' => 'ulangan_harian', 'nilai' => min(100, $baseScore + rand(-8, 10))],
                    ];

                    foreach ($details as $d) {
                        DetailNilai::create([
                            'nilai_id' => $nilai->id,
                            'judul' => $d['judul'],
                            'jenis' => $d['jenis'],
                            'nilai' => $d['nilai'],
                            'tanggal' => Carbon::now()->subWeeks(rand(1, 4))->format('Y-m-d'),
                            'keterangan' => 'Dinilai oleh guru mata pelajaran.',
                        ]);
                    }

                    // Panggil fungsi model untuk menghitung rata-rata harian dan nilai akhir
                    $nilai->hitungNilaiAkhir();
                }
            }
        }

        // ============================================================
        // 12. Pengumuman
        // ============================================================
        Pengumuman::create([
            'user_id' => $admin->id,
            'judul' => 'Selamat Datang di Portal SIAKAD SMA!',
            'konten' => 'Selamat datang di Sistem Informasi Akademik SMA. Portal ini digunakan untuk mengelola data akademik, absensi, jadwal pelajaran, dan penilaian e-Rapor secara terintegrasi.',
            'target' => 'semua',
            'is_aktif' => true,
            'published_at' => Carbon::now(),
        ]);

        Pengumuman::create([
            'user_id' => $admin->id,
            'judul' => 'Pemberitahuan Input Nilai Rapor Semester Ganjil',
            'konten' => 'Diberitahukan kepada seluruh bapak/ibu guru untuk segera melengkapi input nilai harian, UTS, dan UAS sebelum tanggal penguncian nilai rapor yaitu 15 Desember 2025.',
            'target' => 'guru',
            'is_aktif' => true,
            'published_at' => Carbon::now(),
        ]);

        Pengumuman::create([
            'user_id' => $admin->id,
            'judul' => 'Persiapan Pelaksanaan Ujian Akhir Semester (UAS)',
            'konten' => 'Untuk seluruh siswa, harap mempersiapkan diri menyambut UAS yang akan diselenggarakan mulai 1 Desember 2025. Pastikan kartu ujian sudah dicetak melalui akun masing-masing.',
            'target' => 'siswa',
            'is_aktif' => true,
            'published_at' => Carbon::now(),
        ]);

        Pengumuman::create([
            'user_id' => $gurusCreated['ahmad@siakad.sch.id']->user_id, // Wali kelas XI IPA 1
            'judul' => 'Kerja Bakti Kelas XI IPA 1',
            'konten' => 'Diharapkan kehadiran seluruh siswa XI IPA 1 hari Sabtu ini jam 08.00 pagi untuk melakukan kegiatan gotong royong dan dekorasi kelas.',
            'target' => 'kelas',
            'kelas_id' => $kelas3->id,
            'is_aktif' => true,
            'published_at' => Carbon::now(),
        ]);

        // ============================================================
        // Selesai
        // ============================================================
        $this->command->info('Sub: DatabaseSeeder');
        $this->command->info('✅ Seeder sukses besar! Semua menu kini memiliki data lengkap untuk demo.');
        $this->command->info('   Informasi Akun Demo:');
        $this->command->info('   --------------------------------------------------------------');
        $this->command->info('   Admin : admin@siakad.sch.id / password');
        $this->command->info('   Guru  : budi@siakad.sch.id (MTK), ahmad@siakad.sch.id (FIS/KIM) / password');
        $this->command->info('   Siswa : andi@siakad.sch.id (X IPA 1), kevin@siakad.sch.id (XI IPA 1) / password');
        $this->command->info('   --------------------------------------------------------------');

        // ── RBAC: Seed Roles & Permissions (Spatie) ──────────────
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
