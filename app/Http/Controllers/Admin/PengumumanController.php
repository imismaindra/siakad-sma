<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Services\Notification\NotificationDispatcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman untuk Admin (F5-01).
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $target = $request->get('target');
        $status = $request->get('status');

        $query = Pengumuman::with(['pembuat', 'kelas.jurusan'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        if ($target) {
            $query->where('target', $target);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_aktif', (bool) $status);
        }

        $pengumumans = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Pengumuman::count(),
            'aktif' => Pengumuman::where('is_aktif', true)->count(),
            'non_aktif' => Pengumuman::where('is_aktif', false)->count(),
        ];

        return view('admin.pengumuman.index', compact('pengumumans', 'stats', 'search', 'target', 'status'));
    }

    /**
     * Tampilkan form pembuatan pengumuman baru.
     */
    public function create()
    {
        $kelasList = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.pengumuman.create', compact('kelasList'));
    }

    /**
     * Simpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'target' => ['required', 'in:semua,guru,siswa,kelas,orang_tua'],
            'kelas_id' => ['nullable', 'required_if:target,kelas', 'exists:kelas,id'],
            'is_aktif' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $isAktif = $request->boolean('is_aktif', true);
        $publishedAt = $validated['published_at'] ?? ($isAktif ? now() : null);

        $pengumuman = Pengumuman::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'target' => $validated['target'],
            'kelas_id' => $validated['target'] === 'kelas' ? $validated['kelas_id'] : null,
            'is_aktif' => $isAktif,
            'published_at' => $publishedAt,
        ]);

        // Kirim notifikasi jika aktif (F6-04)
        if ($isAktif && $publishedAt) {
            app(NotificationDispatcherService::class)->dispatchPengumuman($pengumuman);
        }

        return redirect()->route('admin.pengumuman.index')
            ->with('success', "Pengumuman '{$pengumuman->judul}' berhasil diterbitkan.");
    }

    /**
     * Form edit pengumuman.
     */
    public function edit(Pengumuman $pengumuman)
    {
        $kelasList = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.pengumuman.edit', compact('pengumuman', 'kelasList'));
    }

    /**
     * Update pengumuman.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'target' => ['required', 'in:semua,guru,siswa,kelas,orang_tua'],
            'kelas_id' => ['nullable', 'required_if:target,kelas', 'exists:kelas,id'],
            'is_aktif' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $wasAktif = $pengumuman->is_aktif;
        $isAktif = $request->boolean('is_aktif', true);
        $publishedAt = $validated['published_at'] ?? ($isAktif ? ($pengumuman->published_at ?? now()) : null);

        $pengumuman->update([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'target' => $validated['target'],
            'kelas_id' => $validated['target'] === 'kelas' ? $validated['kelas_id'] : null,
            'is_aktif' => $isAktif,
            'published_at' => $publishedAt,
        ]);

        // Jika baru diaktifkan, dispatch notifikasi
        if (! $wasAktif && $isAktif && $publishedAt) {
            app(NotificationDispatcherService::class)->dispatchPengumuman($pengumuman);
        }

        return redirect()->route('admin.pengumuman.index')
            ->with('success', "Pengumuman '{$pengumuman->judul}' berhasil diperbarui.");
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $judul = $pengumuman->judul;
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', "Pengumuman '{$judul}' berhasil dihapus.");
    }

    /**
     * Toggle status aktif.
     */
    public function toggleAktif(Pengumuman $pengumuman)
    {
        $newStatus = ! $pengumuman->is_aktif;
        $publishedAt = $newStatus ? ($pengumuman->published_at ?? now()) : $pengumuman->published_at;

        $pengumuman->update([
            'is_aktif' => $newStatus,
            'published_at' => $publishedAt,
        ]);

        $msg = $newStatus ? "Pengumuman diaktifkan." : "Pengumuman dinonaktifkan.";

        if ($newStatus) {
            app(NotificationDispatcherService::class)->dispatchPengumuman($pengumuman);
        }

        return back()->with('success', $msg);
    }
}
