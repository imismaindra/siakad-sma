<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Services\Notification\NotificationDispatcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'kirim_email' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip', 'max:10240'],
        ]);

        $isAktif = $request->boolean('is_aktif', true);
        $kirimEmail = $request->boolean('kirim_email', true);
        $publishedAt = $validated['published_at'] ?? ($isAktif ? now() : null);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = ImageHelper::uploadAndOptimize($request->file('gambar'), 'pengumuman_gambar', 1600, 1200, 85);
        }

        $dokumenPath = null;
        $dokumenNama = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $dokumenPath = $file->store('pengumuman_dokumen', 'public');
        }

        $pengumuman = Pengumuman::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'gambar' => $gambarPath,
            'dokumen' => $dokumenPath,
            'dokumen_nama' => $dokumenNama,
            'target' => $validated['target'],
            'kelas_id' => $validated['target'] === 'kelas' ? $validated['kelas_id'] : null,
            'is_aktif' => $isAktif,
            'kirim_email' => $kirimEmail,
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
            'kirim_email' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip', 'max:10240'],
            'hapus_gambar' => ['boolean'],
            'hapus_dokumen' => ['boolean'],
        ]);

        $gambarPath = $pengumuman->gambar;
        if ($request->boolean('hapus_gambar')) {
            if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
                Storage::disk('public')->delete($gambarPath);
            }
            $gambarPath = null;
        }
        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $gambarPath = ImageHelper::uploadAndOptimize($request->file('gambar'), 'pengumuman_gambar', 1600, 1200, 85);
        }

        $dokumenPath = $pengumuman->dokumen;
        $dokumenNama = $pengumuman->dokumen_nama;
        if ($request->boolean('hapus_dokumen')) {
            if ($dokumenPath && Storage::disk('public')->exists($dokumenPath)) {
                Storage::disk('public')->delete($dokumenPath);
            }
            $dokumenPath = null;
            $dokumenNama = null;
        }
        if ($request->hasFile('dokumen')) {
            if ($pengumuman->dokumen && Storage::disk('public')->exists($pengumuman->dokumen)) {
                Storage::disk('public')->delete($pengumuman->dokumen);
            }
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $dokumenPath = $file->store('pengumuman_dokumen', 'public');
        }

        $wasAktif = $pengumuman->is_aktif;
        $isAktif = $request->boolean('is_aktif', true);
        $kirimEmail = $request->boolean('kirim_email', true);
        $publishedAt = $validated['published_at'] ?? ($isAktif ? ($pengumuman->published_at ?? now()) : null);

        $pengumuman->update([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'gambar' => $gambarPath,
            'dokumen' => $dokumenPath,
            'dokumen_nama' => $dokumenNama,
            'target' => $validated['target'],
            'kelas_id' => $validated['target'] === 'kelas' ? $validated['kelas_id'] : null,
            'is_aktif' => $isAktif,
            'kirim_email' => $kirimEmail,
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

        if ($pengumuman->gambar && Storage::disk('public')->exists($pengumuman->gambar)) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }
        if ($pengumuman->dokumen && Storage::disk('public')->exists($pengumuman->dokumen)) {
            Storage::disk('public')->delete($pengumuman->dokumen);
        }

        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', "Pengumuman '{$judul}' berhasil dihapus.");
    }

    /**
     * Toggle status aktif.
     */
    public function toggleAktif(Request $request, Pengumuman $pengumuman)
    {
        $newStatus = ! $pengumuman->is_aktif;
        $publishedAt = $newStatus ? ($pengumuman->published_at ?? now()) : $pengumuman->published_at;

        $pengumuman->update([
            'is_aktif' => $newStatus,
            'published_at' => $publishedAt,
        ]);

        $msg = $newStatus ? "Pengumuman '{$pengumuman->judul}' berhasil diaktifkan." : "Pengumuman '{$pengumuman->judul}' dinonaktifkan.";

        if ($newStatus) {
            app(NotificationDispatcherService::class)->dispatchPengumuman($pengumuman);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_aktif' => $newStatus,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }
}
