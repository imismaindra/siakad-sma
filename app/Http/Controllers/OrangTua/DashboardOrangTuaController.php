<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardOrangTuaController extends Controller
{
    /**
     * Tampilkan dashboard pemantauan orang tua untuk anak yang aktif.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $orangTua = $user->orangTua;

        if (! $orangTua) {
            return view('orang-tua.empty', ['message' => 'Profil orang tua belum terkonfigurasi. Silakan hubungi pihak sekolah / Admin TU.']);
        }

        $siswas = $orangTua->siswas()->with(['kelas.waliKelas'])->get();

        if ($siswas->isEmpty()) {
            return view('orang-tua.empty', ['message' => 'Belum ada data siswa/anak yang ditautkan ke akun Anda. Silakan hubungi Admin TU sekolah untuk menautkan anak Anda.']);
        }

        // Tentukan anak aktif dari session atau default anak pertama
        $activeId = session('active_anak_id');
        $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();
        session(['active_anak_id' => $activeSiswa->id]);

        $tahunAktif = TahunAjaran::aktif()->first();

        // 1. Statistik Kehadiran Anak
        $detailAbsensis = DetailAbsensi::where('siswa_id', $activeSiswa->id)->get();
        $totalSesi = $detailAbsensis->count();
        $totalHadir = $detailAbsensis->where('status', 'hadir')->count();
        $totalSakit = $detailAbsensis->where('status', 'sakit')->count();
        $totalIzin  = $detailAbsensis->where('status', 'izin')->count();
        $totalAlpa  = $detailAbsensis->where('status', 'alpa')->count();
        $persenHadir = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100, 1) : 0;

        $attendanceStats = [
            'total' => $totalSesi,
            'hadir' => $totalHadir,
            'sakit' => $totalSakit,
            'izin'  => $totalIzin,
            'alpa'  => $totalAlpa,
            'persen'=> $persenHadir,
        ];

        // 2. Riwayat Kehadiran Terbaru (5 terakhir)
        $recentAbsensi = DetailAbsensi::where('siswa_id', $activeSiswa->id)
            ->with(['absensi.jadwalPelajaran.mataPelajaran'])
            ->latest('id')
            ->take(5)
            ->get();

        // 3. Jadwal Hari Ini
        $todayEnglish = Carbon::now()->format('l');
        $dayMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $todayIndo = $dayMap[$todayEnglish] ?? 'Senin';

        $todaySchedule = collect();
        if ($activeSiswa->kelas_id && $tahunAktif) {
            $todaySchedule = JadwalPelajaran::where('kelas_id', $activeSiswa->kelas_id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('hari', $todayIndo)
                ->with(['mataPelajaran', 'guru'])
                ->orderBy('jam_mulai')
                ->get();
        }

        // 4. Ringkasan Nilai Terbaru
        $grades = collect();
        if ($tahunAktif) {
            $grades = Nilai::where('siswa_id', $activeSiswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->with('mataPelajaran')
                ->get();
        }
        $avgScore = $grades->count() > 0 ? round($grades->avg('nilai_akhir'), 1) : 0;

        // 5. Pengumuman Sekolah (F4-05)
        $pengumumans = Pengumuman::aktif()
            ->where(function ($q) use ($activeSiswa) {
                $q->where('target', 'semua')
                  ->orWhere(function ($sub) use ($activeSiswa) {
                      $sub->where('target', 'kelas')->where('kelas_id', $activeSiswa->kelas_id);
                  });
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('orang-tua.dashboard', compact(
            'user',
            'orangTua',
            'siswas',
            'activeSiswa',
            'attendanceStats',
            'recentAbsensi',
            'todaySchedule',
            'todayIndo',
            'grades',
            'avgScore',
            'pengumumans'
        ));
    }

    /**
     * Beralih ke anak lain yang tertaut (F4-02 & F4-04).
     */
    public function switchAnak(Siswa $siswa)
    {
        $orangTua = Auth::user()->orangTua;

        abort_unless(
            $orangTua && $orangTua->siswas()->where('siswas.id', $siswa->id)->exists(),
            403,
            'Anda tidak memiliki izin memantau data siswa ini.'
        );

        session(['active_anak_id' => $siswa->id]);

        return back()->with('success', "Berhasil beralih memantau anak: {$siswa->nama_lengkap}.");
    }
}
