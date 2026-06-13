<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->orderBy('semester')->paginate(10);
        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', 'in:1,2'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ], [
            'nama.regex' => 'Format nama harus YYYY/YYYY, contoh: 2025/2026.',
        ]);

        TahunAjaran::create($validated);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', 'in:1,2'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ]);

        $tahunAjaran->update($validated);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->is_aktif) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus tahun ajaran yang sedang aktif.']);
        }

        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Set tahun ajaran sebagai aktif (non-aktifkan yang lain).
     */
    public function setAktif(TahunAjaran $tahunAjaran)
    {
        TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
        $tahunAjaran->update(['is_aktif' => true]);

        return back()->with('success', "Tahun ajaran {$tahunAjaran->nama_lengkap} kini aktif.");
    }
}
