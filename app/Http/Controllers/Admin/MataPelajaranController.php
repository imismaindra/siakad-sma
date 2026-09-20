<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BobotNilai;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MataPelajaran::withCount(['gurus', 'jadwalPelajarans']);

        if ($request->filled('search')) {
            $query->where(function ($qq) use ($request) {
                $qq->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('kode', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_aktif', $request->status === 'aktif');
        }

        $mapels = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('admin.mata-pelajaran.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_pelajarans,kode'],
            'nama' => ['required', 'string', 'max:150'],
            'kkm' => ['required', 'integer', 'min:0', 'max:100'],
            'jumlah_jam_per_minggu' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:mata_pelajarans,kode,' . $mataPelajaran->id],
            'nama' => ['required', 'string', 'max:150'],
            'kkm' => ['required', 'integer', 'min:0', 'max:100'],
            'jumlah_jam_per_minggu' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'is_aktif' => ['boolean'],
        ]);

        $mataPelajaran->update($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        if ($mataPelajaran->jadwalPelajarans()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus mata pelajaran yang masih memiliki jadwal.']);
        }

        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    // ---- Bobot Nilai ----

    public function bobotIndex(MataPelajaran $mataPelajaran)
    {
        $bobotNilais = BobotNilai::with(['tahunAjaran', 'kelas'])
            ->where('mata_pelajaran_id', $mataPelajaran->id)
            ->orderByDesc('created_at')
            ->get();

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $kelasList = Kelas::with('tahunAjaran')->orderBy('tingkat')->orderBy('nomor')->get();

        return view('admin.mata-pelajaran.bobot', compact('mataPelajaran', 'bobotNilais', 'tahunAjarans', 'kelasList'));
    }

    public function bobotStore(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'bobot_harian' => ['required', 'numeric', 'min:0', 'max:100'],
            'bobot_uts' => ['required', 'numeric', 'min:0', 'max:100'],
            'bobot_uas' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $totalBobot = $validated['bobot_harian'] + $validated['bobot_uts'] + $validated['bobot_uas'];
        if ($totalBobot != 100) {
            return back()->withErrors(['bobot' => "Total bobot harus 100%. Saat ini: {$totalBobot}%"])->withInput();
        }

        BobotNilai::updateOrCreate(
            [
                'mata_pelajaran_id' => $mataPelajaran->id,
                'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                'kelas_id' => $validated['kelas_id'] ?? null,
            ],
            [
                'bobot_harian' => $validated['bobot_harian'],
                'bobot_uts' => $validated['bobot_uts'],
                'bobot_uas' => $validated['bobot_uas'],
            ]
        );

        return back()->with('success', 'Konfigurasi bobot nilai berhasil disimpan.');
    }
}
