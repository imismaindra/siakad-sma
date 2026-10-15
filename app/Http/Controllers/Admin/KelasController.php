<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $jurusanId = $request->get('jurusan_id');
        $search = $request->get('search');

        $kelas = Kelas::with(['jurusan', 'waliKelas', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->when($jurusanId, fn ($q) => $q->where('jurusan_id', $jurusanId))
            ->when($search, fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('nama', 'like', "%{$search}%")
                    ->orWhere('tingkat', 'like', "%{$search}%")
                    ->orWhereHas('waliKelas', fn ($w) => $w->where('name', 'like', "%{$search}%"));
            }))
            ->orderBy('tingkat')
            ->orderBy('nomor')
            ->paginate(15)
            ->withQueryString();

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $jurusans = Jurusan::orderBy('nama')->get();

        return view('admin.kelas.index', compact('kelas', 'tahunAjarans', 'tahunAjaranId', 'jurusans', 'jurusanId', 'search'));
    }

    public function create()
    {
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $jurusans = Jurusan::all();
        $gurus = User::role('guru')->aktif()->get();

        $assignedWali = Kelas::whereNotNull('wali_kelas_id')->get();
        $assignedWaliMap = [];
        foreach ($assignedWali as $k) {
            $assignedWaliMap[$k->tahun_ajaran_id][$k->wali_kelas_id] = $k->nama_kelas;
        }

        return view('admin.kelas.create', compact('tahunAjarans', 'jurusans', 'gurus', 'assignedWaliMap'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
            'tingkat' => ['required', 'in:X,XI,XII'],
            'nomor' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kelas', 'nomor')
                    ->where(fn ($q) => $q->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                                        ->where('jurusan_id', $request->jurusan_id)
                                        ->where('tingkat', $request->tingkat)),
            ],
            'wali_kelas_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('kelas', 'wali_kelas_id')
                    ->where(fn ($q) => $q->where('tahun_ajaran_id', $request->tahun_ajaran_id)),
            ],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:50'],
        ], [
            'nomor.unique' => 'Kelas dengan kombinasi Tingkat, Jurusan, dan Nomor ini sudah ada pada tahun ajaran tersebut.',
            'wali_kelas_id.unique' => 'Guru yang dipilih sudah menjadi wali kelas di kelas lain pada tahun ajaran ini.',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kela)
    {
        $kela->load(['jurusan', 'waliKelas', 'tahunAjaran', 'siswas', 'jadwalPelajarans.guru', 'jadwalPelajarans.mataPelajaran']);
        return view('admin.kelas.show', ['kelas' => $kela]);
    }

    public function edit(Kelas $kela)
    {
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $jurusans = Jurusan::all();
        $gurus = User::role('guru')->aktif()->get();

        $assignedWali = Kelas::whereNotNull('wali_kelas_id')
            ->where('id', '!=', $kela->id)
            ->get();
        $assignedWaliMap = [];
        foreach ($assignedWali as $k) {
            $assignedWaliMap[$k->tahun_ajaran_id][$k->wali_kelas_id] = $k->nama_kelas;
        }

        return view('admin.kelas.edit', [
            'kelas' => $kela,
            'tahunAjarans' => $tahunAjarans,
            'jurusans' => $jurusans,
            'gurus' => $gurus,
            'assignedWaliMap' => $assignedWaliMap,
        ]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
            'tingkat' => ['required', 'in:X,XI,XII'],
            'nomor' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kelas', 'nomor')
                    ->where(fn ($q) => $q->where('tahun_ajaran_id', $kela->tahun_ajaran_id)
                                        ->where('jurusan_id', $request->jurusan_id)
                                        ->where('tingkat', $request->tingkat))
                    ->ignore($kela->id),
            ],
            'wali_kelas_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('kelas', 'wali_kelas_id')
                    ->where(fn ($q) => $q->where('tahun_ajaran_id', $kela->tahun_ajaran_id))
                    ->ignore($kela->id),
            ],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:50'],
        ], [
            'nomor.unique' => 'Kelas dengan kombinasi Tingkat, Jurusan, dan Nomor ini sudah ada pada tahun ajaran tersebut.',
            'wali_kelas_id.unique' => 'Guru yang dipilih sudah menjadi wali kelas di kelas lain pada tahun ajaran ini.',
        ]);

        $kela->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->siswas()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus kelas yang masih memiliki siswa.']);
        }

        $kela->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    // ---- Jurusan sub-resource ----

    public function jurusanIndex(Request $request)
    {
        $jurusans = Jurusan::withCount('kelas')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($qq) use ($request) {
                $qq->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('kode', 'like', "%{$request->search}%");
            }))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jurusan.index', compact('jurusans'));
    }

    public function jurusanStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:10', 'unique:jurusans,kode'],
        ]);

        Jurusan::create($validated);

        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function jurusanUpdate(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kode' => ['required', 'string', 'max:10', 'unique:jurusans,kode,' . $jurusan->id],
        ]);

        $jurusan->update($validated);

        return back()->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function jurusanDestroy(Jurusan $jurusan)
    {
        if ($jurusan->kelas()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus jurusan yang masih digunakan oleh kelas.']);
        }

        $jurusan->delete();

        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
