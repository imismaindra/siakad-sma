<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiGuruController extends Controller
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
     * Daftar sesi absensi milik guru ini.
     */
    public function index(Request $request)
    {
        $guru = $this->getGuru();

        $absensis = Absensi::with(['kelas.jurusan', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->orderByDesc('tanggal')
            ->paginate(20);

        return view('guru.absensi.index', compact('absensis', 'guru'));
    }

    /**
     * Form input absensi untuk jadwal tertentu pada tanggal hari ini.
     */
    public function create(JadwalPelajaran $jadwal)
    {
        $guru = $this->getGuru();

        if ($jadwal->guru_id !== $guru->id) {
            abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        // Cek apakah sudah ada absensi hari ini
        $existing = Absensi::where('jadwal_pelajaran_id', $jadwal->id)
            ->whereDate('tanggal', today())
            ->first();

        if ($existing) {
            return redirect()->route('guru.absensi.show', $existing->id)
                ->with('info', 'Absensi untuk sesi ini sudah diinput.');
        }

        $jadwal->load(['kelas.siswas' => fn ($q) => $q->aktif()->orderBy('nama_lengkap'), 'mataPelajaran']);

        return view('guru.absensi.create', compact('jadwal'));
    }

    /**
     * Simpan absensi sesi.
     */
    public function store(Request $request)
    {
        $guru = $this->getGuru();

        $validated = $request->validate([
            'jadwal_pelajaran_id' => ['required', 'exists:jadwal_pelajarans,id'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'materi' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'siswa' => ['required', 'array'],
            'siswa.*.id' => ['required', 'exists:siswas,id'],
            'siswa.*.status' => ['required', 'in:hadir,sakit,izin,alpa'],
            'siswa.*.keterangan' => ['nullable', 'string'],
        ]);

        $jadwal = JadwalPelajaran::findOrFail($validated['jadwal_pelajaran_id']);

        if ($jadwal->guru_id !== $guru->id) {
            abort(403);
        }

        // Cek duplikasi
        $existing = Absensi::where('jadwal_pelajaran_id', $jadwal->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'Absensi untuk sesi ini sudah pernah diinput.']);
        }

        DB::transaction(function () use ($validated, $jadwal, $guru) {
            $absensi = Absensi::create([
                'jadwal_pelajaran_id' => $jadwal->id,
                'kelas_id' => $jadwal->kelas_id,
                'guru_id' => $guru->id,
                'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
                'tanggal' => $validated['tanggal'],
                'materi' => $validated['materi'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['siswa'] as $siswaData) {
                DetailAbsensi::create([
                    'absensi_id' => $absensi->id,
                    'siswa_id' => $siswaData['id'],
                    'status' => $siswaData['status'],
                    'keterangan' => $siswaData['keterangan'] ?? null,
                ]);
            }
        });

        return redirect()->route('guru.absensi.index')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    /**
     * Detail satu sesi absensi.
     */
    public function show(Absensi $absensi)
    {
        $guru = $this->getGuru();

        if ($absensi->guru_id !== $guru->id) {
            abort(403);
        }

        $absensi->load(['jadwalPelajaran', 'kelas.jurusan', 'mataPelajaran', 'detailAbsensis.siswa']);

        return view('guru.absensi.show', compact('absensi'));
    }

    /**
     * Rekap absensi per kelas yang diajar guru.
     */
    public function rekap(Request $request)
    {
        $guru = $this->getGuru();
        $tahunAktif = TahunAjaran::aktif()->first();

        $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->pluck('kelas_id')
            ->unique();

        $kelasList = Kelas::with(['jurusan', 'siswas'])
            ->whereIn('id', $kelasIds)
            ->get();

        $kelasId = $request->get('kelas_id', $kelasIds->first());
        $siswaRekap = null;

        if ($kelasId) {
            $siswaRekap = Siswa::where('kelas_id', $kelasId)
                ->aktif()
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function ($siswa) use ($guru, $tahunAktif) {
                    $counts = DetailAbsensi::where('siswa_id', $siswa->id)
                        ->whereHas('absensi', fn ($q) => $q->where('guru_id', $guru->id))
                        ->selectRaw('status, count(*) as total')
                        ->groupBy('status')
                        ->pluck('total', 'status');

                    $siswa->rekap = [
                        'hadir' => $counts->get('hadir', 0),
                        'sakit' => $counts->get('sakit', 0),
                        'izin'  => $counts->get('izin', 0),
                        'alpa'  => $counts->get('alpa', 0),
                    ];
                    return $siswa;
                });
        }

        return view('guru.absensi.rekap', compact('guru', 'kelasList', 'kelasId', 'siswaRekap', 'tahunAktif'));
    }
}
