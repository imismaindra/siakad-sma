<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class JadwalOrangTuaController extends Controller
{
    /**
     * Tampilkan jadwal pelajaran anak aktif (F4-03).
     */
    public function index()
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

        $tahunAktif = TahunAjaran::aktif()->first();

        $jadwals = collect();
        if ($activeSiswa->kelas_id && $tahunAktif) {
            $jadwals = JadwalPelajaran::where('kelas_id', $activeSiswa->kelas_id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->with(['mataPelajaran', 'guru'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari');
        }

        return view('orang-tua.jadwal', compact('siswas', 'activeSiswa', 'jadwals', 'tahunAktif'));
    }
}
