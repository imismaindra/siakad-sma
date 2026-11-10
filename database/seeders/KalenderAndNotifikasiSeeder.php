<?php

namespace Database\Seeders;

use App\Models\KalenderAkademik;
use App\Models\Pengumuman;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KalenderAndNotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first() ?? User::first();
        $tahunAjaran = TahunAjaran::aktif()->first() ?? TahunAjaran::first();

        // 1. Pengumuman Tambahan
        if (Pengumuman::where('target', 'orang_tua')->doesntExist()) {
            Pengumuman::create([
                'user_id' => $admin->id,
                'judul' => 'Undangan Pertemuan Wali Murid & Peluncuran Portal e-Rapor',
                'konten' => "Kepada Yth. Bapak/Ibu Orang Tua dan Wali Murid,\n\nKami mengundang kehadiran Bapak/Ibu pada rapat koordinasi akhir semester serta pengenalan fitur pemantauan e-Rapor dan absensi mandiri siswa.\n\nKehadiran Bapak/Ibu sangat kami harapkan demi kelancaran putra/putri kita bersama.",
                'target' => 'orang_tua',
                'is_aktif' => true,
                'published_at' => Carbon::now(),
            ]);
        }

        // 2. Kalender Akademik
        if (KalenderAkademik::count() === 0) {
            $agendas = [
                [
                    'judul' => 'Simulasi & Uji Coba Presensi QR Dinamis',
                    'kategori' => 'kegiatan',
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => now()->toDateString(),
                    'warna' => 'blue',
                    'target' => 'semua',
                    'is_libur' => false,
                    'deskripsi' => 'Simulasi penerapan presensi digital mandiri siswa via scan QR code kelas.',
                ],
                [
                    'judul' => 'Penilaian Akhir Semester (PAS) Ganjil',
                    'kategori' => 'ujian',
                    'tanggal_mulai' => now()->addDays(5)->toDateString(),
                    'tanggal_selesai' => now()->addDays(12)->toDateString(),
                    'warna' => 'amber',
                    'target' => 'semua',
                    'is_libur' => false,
                    'deskripsi' => 'Pekan asesmen sumatif akhir semester ganjil seluruh mata pelajaran.',
                ],
                [
                    'judul' => 'Pengolahan Nilai & Rapat Dewan Guru',
                    'kategori' => 'rapor',
                    'tanggal_mulai' => now()->addDays(14)->toDateString(),
                    'tanggal_selesai' => now()->addDays(15)->toDateString(),
                    'warna' => 'purple',
                    'target' => 'guru',
                    'is_libur' => false,
                    'deskripsi' => 'Finalisasi bobot nilai asesmen dan persiapan cetak e-Rapor Kurikulum Merdeka.',
                ],
                [
                    'judul' => 'Pembagian Dokumen e-Rapor Semester Ganjil',
                    'kategori' => 'rapor',
                    'tanggal_mulai' => now()->addDays(18)->toDateString(),
                    'tanggal_selesai' => now()->addDays(18)->toDateString(),
                    'warna' => 'purple',
                    'target' => 'semua',
                    'is_libur' => false,
                    'deskripsi' => 'Penerimaan dokumen e-Rapor resmi PDF oleh siswa dan orang tua murid.',
                ],
                [
                    'judul' => 'Libur Akhir Semester Ganjil',
                    'kategori' => 'libur',
                    'tanggal_mulai' => now()->addDays(20)->toDateString(),
                    'tanggal_selesai' => now()->addDays(32)->toDateString(),
                    'warna' => 'rose',
                    'target' => 'semua',
                    'is_libur' => true,
                    'deskripsi' => 'Hari libur resmi akhir semester ganjil bagi seluruh siswa.',
                ],
            ];

            foreach ($agendas as $agenda) {
                KalenderAkademik::create(array_merge($agenda, [
                    'tahun_ajaran_id' => $tahunAjaran?->id,
                    'is_aktif' => true,
                    'created_by' => $admin->id,
                ]));
            }
        }
    }
}
