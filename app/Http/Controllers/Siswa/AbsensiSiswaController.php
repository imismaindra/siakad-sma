<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiSiswaController extends Controller
{
    private function getSiswa()
    {
        $siswa = Auth::user()->siswa;
        if (! $siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }
        return $siswa;
    }

    /**
     * Rekap absensi siswa ini.
     */
    public function index(Request $request)
    {
        $siswa = $this->getSiswa();
        $tahunAktif = TahunAjaran::aktif()->first();

        $tahunAjaranId = $request->get('tahun_ajaran_id', $tahunAktif?->id);

        $detailAbsensis = DetailAbsensi::with(['absensi.mataPelajaran', 'absensi.guru'])
            ->where('siswa_id', $siswa->id)
            ->whereHas('absensi', fn ($q) => $q->whereHas(
                'jadwalPelajaran',
                fn ($jq) => $jq->where('tahun_ajaran_id', $tahunAjaranId)
            ))
            ->orderByDesc('created_at')
            ->paginate(20);

        $rekap = $siswa->rekapAbsensi($tahunAjaranId);

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();

        return view('siswa.absensi', compact('siswa', 'detailAbsensis', 'rekap', 'tahunAjarans', 'tahunAjaranId'));
    }
}
