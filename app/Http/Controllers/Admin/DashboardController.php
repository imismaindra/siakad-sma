<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Absensi;
use App\Models\Nilai;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::aktif()->first();

        $stats = [
            'total_siswa' => Siswa::aktif()->count(),
            'total_guru' => Guru::aktif()->count(),
            'total_kelas' => $tahunAktif
                ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->count()
                : 0,
            'tahun_ajaran' => $tahunAktif,
        ];

        // Grafik absensi minggu ini
        $absensiMingguIni = Absensi::with('detailAbsensis')
            ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        $totalHadir = $absensiMingguIni->sum(fn ($a) => $a->detailAbsensis->where('status', 'hadir')->count());
        $totalAlpa = $absensiMingguIni->sum(fn ($a) => $a->detailAbsensis->where('status', 'alpa')->count());

        // Nilai yang belum dikunci per kelas aktif
        $nilaiMenunggu = $tahunAktif
            ? Nilai::where('tahun_ajaran_id', $tahunAktif->id)->where('is_final', false)->count()
            : 0;

        return view('admin.dashboard', compact('stats', 'totalHadir', 'totalAlpa', 'nilaiMenunggu', 'tahunAktif'));
    }
}
