<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (! $siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $tahunAktif = TahunAjaran::aktif()->first();

        // Jadwal hari ini
        $hariMap = [
            'Sunday' => null,
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $hariDb = $hariMap[now()->format('l')] ?? null;

        $jadwalHariIni = [];
        if ($hariDb && $siswa->kelas_id && $tahunAktif) {
            $jadwalHariIni = JadwalPelajaran::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $siswa->kelas_id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('hari', $hariDb)
                ->orderBy('jam_mulai')
                ->get();
        }

        // Nilai terbaru (yang sudah final)
        $nilaiTerbaru = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->where('is_final', true)
            ->latest()
            ->take(5)
            ->get();

        // Rekap absensi bulan ini
        $rekapAbsensi = $tahunAktif ? $siswa->rekapAbsensi($tahunAktif->id) : null;

        $siswa->load('kelas.jurusan');

        return view('siswa.dashboard', compact(
            'siswa',
            'tahunAktif',
            'jadwalHariIni',
            'nilaiTerbaru',
            'rekapAbsensi'
        ));
    }
}
