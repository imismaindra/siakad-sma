<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiOrangTuaController extends Controller
{
    /**
     * Tampilkan riwayat dan rekap absensi anak aktif (F4-03).
     */
    public function index(Request $request)
    {
        $orangTua = Auth::user()->orangTua;
        if (! $orangTua) {
            return redirect()->route('orang-tua.dashboard');
        }

        $siswas = $orangTua->siswas()->with('kelas')->get();
        if ($siswas->isEmpty()) {
            return redirect()->route('orang-tua.dashboard');
        }

        $activeId = session('active_anak_id');
        $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();

        // Query Detail Absensi
        $query = DetailAbsensi::where('siswa_id', $activeSiswa->id)
            ->with(['absensi.jadwalPelajaran.mataPelajaran', 'absensi.guru'])
            ->latest('id');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter bulan
        if ($request->filled('bulan')) {
            $query->whereHas('absensi', function ($q) use ($request) {
                $q->whereMonth('tanggal', $request->bulan);
            });
        }

        $riwayatAbsensi = $query->paginate(20)->withQueryString();

        // Ringkasan Statistik Total
        $allRecords = DetailAbsensi::where('siswa_id', $activeSiswa->id)->get();
        $totalSesi = $allRecords->count();
        $totalHadir = $allRecords->where('status', 'hadir')->count();
        $totalSakit = $allRecords->where('status', 'sakit')->count();
        $totalIzin  = $allRecords->where('status', 'izin')->count();
        $totalAlpa  = $allRecords->where('status', 'alpa')->count();
        $persenHadir = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100, 1) : 0;

        $stats = [
            'total' => $totalSesi,
            'hadir' => $totalHadir,
            'sakit' => $totalSakit,
            'izin'  => $totalIzin,
            'alpa'  => $totalAlpa,
            'persen'=> $persenHadir,
        ];

        return view('orang-tua.absensi', compact('siswas', 'activeSiswa', 'riwayatAbsensi', 'stats'));
    }
}
