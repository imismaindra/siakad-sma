<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NilaiOrangTuaController extends Controller
{
    /**
     * Tampilkan nilai akademik dan status rapor anak aktif (F4-03).
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

        $tahunAjaran = TahunAjaran::aktif()->first();

        $nilais = collect();
        $isRaporPublished = false;

        if ($tahunAjaran) {
            $nilais = Nilai::where('siswa_id', $activeSiswa->id)
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->with(['mataPelajaran', 'guru', 'detailNilais'])
                ->get();

            // Rapor dianggap published jika semua nilai sudah is_final
            $isRaporPublished = $nilais->isNotEmpty() && $nilais->every(fn($n) => $n->is_final);
        }

        $avgScore = $nilais->count() > 0 ? round($nilais->avg('nilai_akhir'), 1) : 0;

        return view('orang-tua.nilai', compact('siswas', 'activeSiswa', 'nilais', 'tahunAjaran', 'isRaporPublished', 'avgScore'));
    }

    /**
     * Download e-Rapor PDF anak (F4-03 & F4-04).
     */
    public function raporPdf(Siswa $siswa)
    {
        // F4-04: Otorisasi bahwa siswa yang diminta adalah anak kandung/wali dari orang tua yang login
        Gate::authorize('view', $siswa);

        $tahunAjaran = TahunAjaran::aktif()->first();
        if (! $tahunAjaran) {
            return back()->withErrors(['error' => 'Tahun ajaran aktif tidak ditemukan.']);
        }

        $nilais = Nilai::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->with(['mataPelajaran', 'guru'])
            ->get();

        // Validasi: hanya bisa download jika sudah difinalisasi
        $isFinal = $nilais->isNotEmpty() && $nilais->every(fn($n) => $n->is_final);
        if (! $isFinal) {
            return back()->withErrors(['error' => 'e-Rapor semester ini belum selesai difinalisasi dan dipublikasikan oleh pihak sekolah.']);
        }

        // Rekap absensi siswa untuk rapor
        $rekapAbsensi = [
            'hadir' => DetailAbsensi::where('siswa_id', $siswa->id)->where('status', 'hadir')->count(),
            'sakit' => DetailAbsensi::where('siswa_id', $siswa->id)->where('status', 'sakit')->count(),
            'izin'  => DetailAbsensi::where('siswa_id', $siswa->id)->where('status', 'izin')->count(),
            'alpa'  => DetailAbsensi::where('siswa_id', $siswa->id)->where('status', 'alpa')->count(),
        ];

        $pdf = Pdf::loadView('pdf.rapor', compact('siswa', 'nilais', 'tahunAjaran', 'rekapAbsensi'))
            ->setPaper('a4', 'portrait');

        $safeNama = str_replace(['/', '\\'], '-', $tahunAjaran->nama);
        $filename = "rapor_{$siswa->nis}_{$safeNama}_sem{$tahunAjaran->semester}.pdf";

        return $pdf->download($filename);
    }
}
