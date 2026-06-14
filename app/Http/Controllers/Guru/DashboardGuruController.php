<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardGuruController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (! $guru) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $tahunAktif = TahunAjaran::aktif()->first();
        $hari = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dst
        // Map isoFormat ke enum database
        $hariMap = [
            'Minggu' => null,
            'Senin' => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
        ];
        $hariDb = $hariMap[$hari] ?? null;

        // Jadwal hari ini
        $jadwalHariIni = [];
        if ($hariDb && $tahunAktif) {
            $jadwalHariIni = JadwalPelajaran::with(['kelas.jurusan', 'mataPelajaran'])
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('hari', $hariDb)
                ->orderBy('jam_mulai')
                ->get();
        }

        // Kelas yang belum input absensi hari ini
        $belumAbsen = collect($jadwalHariIni)->filter(function ($jadwal) {
            return ! Absensi::where('jadwal_pelajaran_id', $jadwal->id)
                ->whereDate('tanggal', today())
                ->exists();
        });

        // Statistik bulan ini
        $totalSesiMengajar = Absensi::where('guru_id', $guru->id)
            ->whereMonth('tanggal', now()->month)
            ->count();

        return view('guru.dashboard', compact(
            'guru',
            'tahunAktif',
            'jadwalHariIni',
            'belumAbsen',
            'totalSesiMengajar'
        ));
    }

    /**
     * Tampilkan jadwal mengajar guru ini.
     */
    public function jadwal()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (! $guru) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $tahunAktif = TahunAjaran::aktif()->first();

        $jadwals = [];
        if ($tahunAktif) {
            $jadwals = JadwalPelajaran::with(['mataPelajaran', 'kelas.jurusan'])
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari');
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('guru.jadwal', compact('guru', 'jadwals', 'hariList', 'tahunAktif'));
    }
}
