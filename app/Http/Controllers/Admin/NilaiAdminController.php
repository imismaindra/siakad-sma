<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class NilaiAdminController extends Controller
{
    /**
     * Laporan progres nilai per kelas.
     */
    public function laporan(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $kelasId = $request->get('kelas_id');
        $mapelId = $request->get('mata_pelajaran_id');
        $statusFinal = $request->get('is_final');
        $search = $request->get('search');

        $nilais = Nilai::with(['siswa', 'mataPelajaran', 'guru', 'kelas.jurusan'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId, fn ($q) => $q->where('mata_pelajaran_id', $mapelId))
            ->when($statusFinal !== null && $statusFinal !== '', fn ($q) => $q->where('is_final', (bool) $statusFinal))
            ->when($search, fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->whereHas('siswa', fn ($s) => $s->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"))
                    ->orWhereHas('mataPelajaran', fn ($m) => $m->where('nama', 'like', "%{$search}%"));
            }))
            ->orderBy('is_final')
            ->paginate(25)
            ->withQueryString();

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $kelasList = $tahunAjaranId
            ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->with('jurusan')->orderBy('tingkat')->get()
            : collect();
        $mapelList = MataPelajaran::orderBy('nama')->get();

        return view('admin.nilai.laporan', compact('nilais', 'tahunAjarans', 'kelasList', 'tahunAjaranId', 'kelasId', 'mapelList', 'mapelId', 'statusFinal', 'search'));
    }

    /**
     * Finalisasi & kunci semua nilai rapor untuk satu kelas.
     */
    public function finalisasi(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $count = Nilai::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('is_final', false)
            ->update(['is_final' => true]);

        return back()->with('success', "{$count} data nilai berhasil dikunci (finalisasi).");
    }

    /**
     * Publikasikan rapor untuk satu kelas (mengaktifkan akses siswa).
     * Catatan: Dalam implementasi ini, finalisasi = published.
     * Nilai is_final = true berarti siswa sudah bisa lihat.
     */
    public function publikasi(Request $request)
    {
        // Alias finalisasi
        return $this->finalisasi($request);
    }

    /**
     * Rekap peringkat kelas.
     */
    public function peringkat(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $kelasId = $request->get('kelas_id');

        if (! $kelasId) {
            return redirect()->back()->withErrors(['kelas_id' => 'Pilih kelas terlebih dahulu.']);
        }

        // Hitung rata-rata nilai akhir per siswa
        $peringkat = Siswa::with(['kelas'])
            ->where('kelas_id', $kelasId)
            ->when($request->filled('search'), fn ($q) => $q->where(function ($qq) use ($request) {
                $qq->where('nama_lengkap', 'like', "%{$request->search}%")
                    ->orWhere('nis', 'like', "%{$request->search}%");
            }))
            ->withAvg(
                ['nilais' => fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId)->where('is_final', true)],
                'nilai_akhir'
            )
            ->orderByDesc('nilais_avg_nilai_akhir')
            ->get()
            ->map(function ($siswa, $index) {
                $siswa->peringkat = $index + 1;
                return $siswa;
            });

        $kelas = Kelas::with('jurusan')->find($kelasId);
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        return view('admin.nilai.peringkat', compact('peringkat', 'kelas', 'tahunAjaran'));
    }

    /**
     * Export rapor per siswa ke PDF.
     */
    public function exportRaporSiswa(Siswa $siswa, Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));

        $nilais = Nilai::with(['mataPelajaran'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('is_final', true)
            ->get();

        if ($nilais->isEmpty()) {
            return back()->withErrors(['error' => 'Rapor belum tersedia atau belum dikunci.']);
        }

        $siswa->load([
            'kelas.jurusan',
            'kelas.waliKelas.guru',
            'ekstrakurikulers' => fn ($q) => $q->wherePivot('tahun_ajaran_id', $tahunAjaranId),
            'prestasis' => fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId),
        ]);
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);

        $rekapAbsensi = $siswa->rekapAbsensi($tahunAjaranId);

        $pdf = Pdf::loadView('pdf.rapor', compact('siswa', 'nilais', 'tahunAjaran', 'rekapAbsensi'))
            ->setPaper('A4', 'portrait');

        $filename = "rapor_{$siswa->nis}_{$tahunAjaran->nama}_sem{$tahunAjaran->semester}.pdf";
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Export rapor seluruh kelas ke satu file PDF (per siswa).
     */
    public function exportRaporKelas(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $kelas = Kelas::with(['jurusan', 'waliKelas.guru', 'siswas'])->find($validated['kelas_id']);
        $tahunAjaran = TahunAjaran::find($validated['tahun_ajaran_id']);

        $siswaDenganNilai = $kelas->siswas->map(function ($siswa) use ($validated) {
            $siswa->load([
                'ekstrakurikulers' => fn ($q) => $q->wherePivot('tahun_ajaran_id', $validated['tahun_ajaran_id']),
                'prestasis' => fn ($q) => $q->where('tahun_ajaran_id', $validated['tahun_ajaran_id']),
            ]);
            $siswa->nilaiRapor = Nilai::with('mataPelajaran')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                ->where('is_final', true)
                ->get();
            $siswa->rekapAbsensi = $siswa->rekapAbsensi($validated['tahun_ajaran_id']);
            return $siswa;
        });

        $pdf = Pdf::loadView('pdf.rapor-kelas', compact('kelas', 'tahunAjaran', 'siswaDenganNilai'))
            ->setPaper('A4', 'portrait');

        $filename = "rapor_kelas_{$kelas->nama_kelas}_{$tahunAjaran->nama}_sem{$tahunAjaran->semester}.pdf";
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
