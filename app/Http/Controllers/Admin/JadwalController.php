<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $kelasId = $request->get('kelas_id');
        $hari = $request->get('hari');
        $search = $request->get('search');
        $mode = $request->get('mode', 'grid');

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $kelasList = $tahunAjaranId
            ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)
                ->with(['jurusan', 'waliKelas.guru'])
                ->withCount(['jadwalPelajarans' => fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId)])
                ->orderBy('tingkat')
                ->orderBy('nomor')
                ->get()
            : collect();

        // Default ke kelas pertama jika mode grid belum memilih kelas secara spesifik
        if ($mode === 'grid' && ! $kelasId && $kelasList->isNotEmpty()) {
            $kelasId = $kelasList->first()->id;
        }

        $base = JadwalPelajaran::with(['kelas.jurusan', 'mataPelajaran', 'guru'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->when($search, fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('ruangan', 'like', "%{$search}%")
                    ->orWhereHas('mataPelajaran', fn ($m) => $m->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('guru', fn ($g) => $g->where('nama_lengkap', 'like', "%{$search}%"));
            }));

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        if ($mode === 'daftar') {
            $jadwals = (clone $base)
                ->when($hari, fn ($q) => $q->where('hari', $hari))
                ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
                ->orderBy('jam_mulai')
                ->paginate(20)
                ->withQueryString();

            return view('admin.jadwal.index', compact('jadwals', 'tahunAjarans', 'kelasList', 'tahunAjaranId', 'kelasId', 'hariList', 'hari', 'search', 'mode'));
        }

        $jadwals = (clone $base)->orderBy('jam_mulai')->get()->groupBy('hari');
        $selectedKelas = $kelasId ? $kelasList->firstWhere('id', $kelasId) : null;

        return view('admin.jadwal.grid', compact('jadwals', 'tahunAjarans', 'kelasList', 'selectedKelas', 'tahunAjaranId', 'kelasId', 'hariList', 'search', 'mode'));
    }

    /**
     * Export jadwal pelajaran kelas ke file PDF (Landscape).
     */
    public function exportPdf(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $kelasId = $request->get('kelas_id');

        $tahunAjaran = TahunAjaran::find($tahunAjaranId) ?? TahunAjaran::aktif()->first();

        $kelas = $kelasId ? Kelas::with(['jurusan', 'waliKelas.guru'])->find($kelasId) : null;
        if (! $kelas) {
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)->with(['jurusan', 'waliKelas.guru'])->orderBy('tingkat')->orderBy('nomor')->first();
        }

        if (! $kelas) {
            return back()->withErrors(['error' => 'Data kelas tidak ditemukan untuk tahun ajaran ini.']);
        }

        $jadwals = JadwalPelajaran::with(['mataPelajaran', 'guru'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('kelas_id', $kelas->id)
            ->orderBy('urutan_jam')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $pdf = Pdf::loadView('pdf.jadwal-kelas', compact('kelas', 'tahunAjaran', 'jadwals', 'hariList'))
            ->setPaper('A4', 'landscape');

        $filename = "jadwal_pelajaran_{$kelas->nama_kelas}_{$tahunAjaran->nama}_sem{$tahunAjaran->semester}.pdf";
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }

    public function create()
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $kelasList = $tahunAktif
            ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->with('jurusan')->orderBy('tingkat')->get()
            : collect();
        $mataPelajarans = MataPelajaran::aktif()->orderBy('nama')->get();
        $gurus = Guru::aktif()->orderBy('nama_lengkap')->get();

        return view('admin.jadwal.create', compact('tahunAktif', 'kelasList', 'mataPelajarans', 'gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'guru_id' => ['required', 'exists:gurus,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'urutan_jam' => ['required', 'integer', 'min:1'],
            'ruangan' => ['nullable', 'string', 'max:50'],
        ]);

        // Cek konflik guru
        $konflik = JadwalPelajaran::cekKonflikGuru(
            $validated['guru_id'],
            $validated['hari'],
            $validated['jam_mulai'],
            $validated['jam_selesai'],
            $validated['tahun_ajaran_id']
        );

        if ($konflik) {
            return back()->withErrors([
                'guru_id' => 'Guru ini sudah memiliki jadwal mengajar di hari dan jam yang sama (atau bertumpang-tindih).',
            ])->withInput();
        }

        JadwalPelajaran::create($validated);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwal)
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $kelasList = Kelas::where('tahun_ajaran_id', $jadwal->tahun_ajaran_id)->with('jurusan')->orderBy('tingkat')->get();
        $mataPelajarans = MataPelajaran::aktif()->orderBy('nama')->get();
        $gurus = Guru::aktif()->orderBy('nama_lengkap')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'tahunAktif', 'kelasList', 'mataPelajarans', 'gurus'));
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'guru_id' => ['required', 'exists:gurus,id'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'urutan_jam' => ['required', 'integer', 'min:1'],
            'ruangan' => ['nullable', 'string', 'max:50'],
        ]);

        // Cek konflik guru (exclude diri sendiri)
        $konflik = JadwalPelajaran::cekKonflikGuru(
            $validated['guru_id'],
            $validated['hari'],
            $validated['jam_mulai'],
            $validated['jam_selesai'],
            $jadwal->tahun_ajaran_id,
            $jadwal->id
        );

        if ($konflik) {
            return back()->withErrors([
                'guru_id' => 'Guru ini sudah memiliki jadwal mengajar di hari dan jam yang sama (atau bertumpang-tindih).',
            ])->withInput();
        }

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    /**
     * Tampilkan jadwal dalam format grid per kelas.
     */
    public function grid(Request $request)
    {
        return redirect()->route('admin.jadwal.index', $request->query());
    }
}
