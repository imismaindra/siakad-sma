<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanOrangTuaController extends Controller
{
    /**
     * Tampilkan daftar pengumuman untuk orang tua dan kelas anak aktif (F4-05).
     */
    public function index()
    {
        $orangTua = Auth::user()->orangTua;
        if (! $orangTua) {
            return redirect()->route('orang-tua.dashboard');
        }

        $siswas = $orangTua->siswas()->with('kelas')->get();
        if ($siswas->isEmpty()) {
            return redirect()->route('orang-tua.dashboard');
        }

        $activeId = session('active_anak_id');
        $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();

        $pengumumans = Pengumuman::aktif()
            ->where(function ($q) use ($activeSiswa) {
                $q->where('target', 'semua');
                if ($activeSiswa && $activeSiswa->kelas_id) {
                    $q->orWhere(function ($sub) use ($activeSiswa) {
                        $sub->where('target', 'kelas')->where('kelas_id', $activeSiswa->kelas_id);
                    });
                }
            })
            ->with(['pembuat', 'kelas'])
            ->latest('published_at')
            ->paginate(10);

        return view('orang-tua.pengumuman', compact('siswas', 'activeSiswa', 'pengumumans'));
    }

    /**
     * Tampilkan detail pengumuman.
     */
    public function show(Pengumuman $pengumuman)
    {
        $orangTua = Auth::user()->orangTua;
        if (! $orangTua) {
            return redirect()->route('orang-tua.dashboard');
        }

        $siswas = $orangTua->siswas()->with('kelas')->get();
        $activeId = session('active_anak_id');
        $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();

        // Verifikasi apakah pengumuman ini dapat diakses oleh orang tua / kelas anak
        $isAccessible = $pengumuman->is_aktif && $pengumuman->published_at !== null && (
            $pengumuman->target === 'semua' ||
            ($activeSiswa && $pengumuman->target === 'kelas' && $pengumuman->kelas_id === $activeSiswa->kelas_id)
        );

        abort_unless($isAccessible, 404, 'Pengumuman tidak ditemukan atau tidak diperuntukkan bagi kelas anak Anda.');

        return view('orang-tua.pengumuman-show', compact('siswas', 'activeSiswa', 'pengumuman'));
    }
}
