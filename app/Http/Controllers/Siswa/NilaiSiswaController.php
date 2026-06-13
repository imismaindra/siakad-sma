<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiSiswaController extends Controller
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
     * Tampilkan nilai siswa (rapor).
     */
    public function index(Request $request)
    {
        $siswa = $this->getSiswa();
        $tahunAktif = TahunAjaran::aktif()->first();

        $tahunAjaranId = $request->get('tahun_ajaran_id', $tahunAktif?->id);

        $nilais = Nilai::with(['mataPelajaran', 'guru', 'detailNilais'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();

        // Cek apakah rapor sudah dipublikasikan (semua nilai is_final)
        $raporPublished = $nilais->isNotEmpty() && $nilais->every(fn ($n) => $n->is_final);

        return view('siswa.nilai', compact('siswa', 'nilais', 'tahunAjarans', 'tahunAjaranId', 'raporPublished', 'tahunAktif'));
    }

    /**
     * Download rapor PDF milik siswa (hanya jika sudah final).
     */
    public function downloadRapor(Request $request)
    {
        $siswa = $this->getSiswa();
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));

        $nilais = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('is_final', true)
            ->get();

        if ($nilais->isEmpty()) {
            return back()->withErrors(['error' => 'Rapor belum tersedia.']);
        }

        $siswa->load(['kelas.jurusan']);
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        $pdf = Pdf::loadView('pdf.rapor', compact('siswa', 'nilais', 'tahunAjaran'))
            ->setPaper('A4', 'portrait');

        $filename = "rapor_{$siswa->nis}_{$tahunAjaran->nama}_sem{$tahunAjaran->semester}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Tampilkan jadwal kelas siswa.
     */
    public function jadwal(Request $request)
    {
        $siswa = $this->getSiswa();
        $tahunAktif = TahunAjaran::aktif()->first();

        $jadwals = [];
        if ($siswa->kelas_id && $tahunAktif) {
            $jadwals = \App\Models\JadwalPelajaran::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $siswa->kelas_id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari');
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('siswa.jadwal', compact('siswa', 'jadwals', 'hariList', 'tahunAktif'));
    }
}
