<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $kelas = Kelas::with(['jurusan', 'waliKelas', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('tingkat')
            ->orderBy('nomor')
            ->paginate(15);

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();

        return view('admin.kelas.index', compact('kelas', 'tahunAjarans', 'tahunAjaranId'));
    }

    public function create()
    {
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $jurusans = Jurusan::all();
        $gurus = User::role('guru')->aktif()->get();

        return view('admin.kelas.create', compact('tahunAjarans', 'jurusans', 'gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
            'tingkat' => ['required', 'in:X,XI,XII'],
            'nomor' => ['required', 'integer', 'min:1'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:50'],
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

        return view('admin.kelas.edit', [
            'kelas' => $kela,
            'tahunAjarans' => $tahunAjarans,
            'jurusans' => $jurusans,
            'gurus' => $gurus,
        ]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
            'tingkat' => ['required', 'in:X,XI,XII'],
            'nomor' => ['required', 'integer', 'min:1'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:50'],
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

    public function jurusanIndex()
    {
        $jurusans = Jurusan::withCount('kelas')->paginate(10);
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
