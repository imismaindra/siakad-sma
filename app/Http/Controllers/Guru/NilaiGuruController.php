<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailNilai;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiGuruController extends Controller
{
    private function getGuru()
    {
        $guru = Auth::user()->guru;
        if (! $guru) {
            abort(403, 'Profil guru tidak ditemukan.');
        }
        return $guru;
    }

    /**
     * Daftar kelas yang diajar guru ini (untuk pilih kelas input nilai).
     */
    public function index(Request $request)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();

        // Ambil kelas unik yang guru ini ajar di tahun aktif
        $kelasIds = $guru->jadwalPelajarans()
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->pluck('kelas_id')
            ->unique();

        $kelasList = Kelas::with('jurusan')
            ->whereIn('id', $kelasIds)
            ->orderBy('tingkat')
            ->get();

        return view('guru.nilai.index', compact('guru', 'kelasList', 'tahunAktif'));
    }

    /**
     * Daftar siswa satu kelas + nilai per mata pelajaran yang diajar guru ini.
     */
    public function kelas(Request $request, Kelas $kelas)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();

        // Mata pelajaran yang diajar guru di kelas ini
        $mapelIds = $guru->jadwalPelajarans()
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->pluck('mata_pelajaran_id')
            ->unique();

        $siswas = Siswa::where('kelas_id', $kelas->id)
            ->aktif()
            ->orderBy('nama_lengkap')
            ->get();

        $nilais = Nilai::with('mataPelajaran')
            ->where('kelas_id', $kelas->id)
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->whereIn('mata_pelajaran_id', $mapelIds)
            ->get()
            ->keyBy(fn ($n) => "{$n->siswa_id}_{$n->mata_pelajaran_id}");

        $kelas->load('jurusan');

        return view('guru.nilai.kelas', compact('guru', 'kelas', 'siswas', 'nilais', 'mapelIds', 'tahunAktif'));
    }

    /**
     * Form input/edit nilai satu siswa untuk satu mata pelajaran.
     */
    public function editNilai(Siswa $siswa, Request $request)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();
        $mataPelajaranId = $request->get('mata_pelajaran_id');

        $nilai = Nilai::with(['detailNilais', 'mataPelajaran'])
            ->firstOrNew([
                'siswa_id' => $siswa->id,
                'mata_pelajaran_id' => $mataPelajaranId,
                'tahun_ajaran_id' => $tahunAktif?->id,
                'guru_id' => $guru->id,
                'kelas_id' => $siswa->kelas_id,
            ]);

        if ($nilai->is_final) {
            return back()->withErrors(['error' => 'Nilai sudah dikunci, tidak dapat diubah.']);
        }

        return view('guru.nilai.edit', compact('nilai', 'siswa', 'guru', 'tahunAktif'));
    }

    /**
     * Simpan nilai UTS/UAS/deskripsi.
     */
    public function storeNilai(Request $request)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();

        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'nilai_uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $nilai = Nilai::firstOrNew([
            'siswa_id' => $validated['siswa_id'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'tahun_ajaran_id' => $tahunAktif->id,
        ]);

        if ($nilai->is_final) {
            return back()->withErrors(['error' => 'Nilai sudah dikunci.']);
        }

        $nilai->fill([
            'guru_id' => $guru->id,
            'kelas_id' => $validated['kelas_id'],
            'nilai_uts' => $validated['nilai_uts'] ?? $nilai->nilai_uts,
            'nilai_uas' => $validated['nilai_uas'] ?? $nilai->nilai_uas,
            'deskripsi' => $validated['deskripsi'] ?? $nilai->deskripsi,
        ]);
        $nilai->save();

        // Recalculate nilai akhir
        $nilai->hitungNilaiAkhir();

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Tambah detail nilai harian (tugas/kuis).
     */
    public function storeDetailNilai(Request $request)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();

        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'judul' => ['required', 'string', 'max:150'],
            'jenis' => ['required', 'in:tugas,kuis,ulangan_harian,praktik,lainnya'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $guru, $tahunAktif) {
            $nilai = Nilai::firstOrCreate(
                [
                    'siswa_id' => $validated['siswa_id'],
                    'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                    'tahun_ajaran_id' => $tahunAktif->id,
                ],
                [
                    'guru_id' => $guru->id,
                    'kelas_id' => $validated['kelas_id'],
                ]
            );

            if ($nilai->is_final) {
                abort(422, 'Nilai sudah dikunci.');
            }

            DetailNilai::create([
                'nilai_id' => $nilai->id,
                'judul' => $validated['judul'],
                'jenis' => $validated['jenis'],
                'nilai' => $validated['nilai'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Recalculate
            $nilai->hitungNilaiAkhir();
        });

        return back()->with('success', 'Nilai harian berhasil ditambahkan.');
    }

    /**
     * Hapus detail nilai harian.
     */
    public function destroyDetailNilai(DetailNilai $detailNilai)
    {
        $guru = $this->getGuru();
        $nilai = $detailNilai->nilai;

        if ($nilai->guru_id !== $guru->id || $nilai->is_final) {
            abort(403);
        }

        $detailNilai->delete();
        $nilai->hitungNilaiAkhir();

        return back()->with('success', 'Detail nilai berhasil dihapus.');
    }

    /**
     * Export rapor kelas (guru) ke PDF.
     */
    public function exportRapor(Request $request, Kelas $kelas)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();

        $siswas = Siswa::where('kelas_id', $kelas->id)->aktif()->orderBy('nama_lengkap')->get();
        $kelas->load('jurusan');

        $tahunAjaranId = $tahunAktif?->id;
        $siswaDenganNilai = $siswas->map(function ($siswa) use ($guru, $tahunAjaranId) {
            $siswa->load([
                'ekstrakurikulers' => fn ($q) => $q->wherePivot('tahun_ajaran_id', $tahunAjaranId),
                'prestasis' => fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId),
            ]);
            $siswa->nilaiRapor = Nilai::with('mataPelajaran')
                ->where('siswa_id', $siswa->id)
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('is_final', true)
                ->get();
            $siswa->rekapAbsensi = $siswa->rekapAbsensi($tahunAjaranId);
            return $siswa;
        });

        $tahunAjaran = $tahunAktif;
        $pdf = Pdf::loadView('pdf.rapor-kelas', compact('kelas', 'tahunAjaran', 'siswaDenganNilai'))
            ->setPaper('A4', 'portrait');

        $filename = "rapor_{$kelas->nama_kelas}.pdf";
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }
}
