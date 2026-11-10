<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanSiswaController extends Controller
{
    /**
     * Daftar pengumuman untuk Siswa (target: semua, siswa, atau kelas siswa).
     */
    public function index(Request $request)
    {
        $siswa = Auth::user()->siswa;
        if (! $siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $search = $request->get('search');

        $query = Pengumuman::aktif()
            ->where(function ($q) use ($siswa) {
                $q->whereIn('target', ['semua', 'siswa']);
                if ($siswa->kelas_id) {
                    $q->orWhere(function ($sub) use ($siswa) {
                        $sub->where('target', 'kelas')->where('kelas_id', $siswa->kelas_id);
                    });
                }
            })
            ->with(['pembuat', 'kelas'])
            ->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $pengumumans = $query->paginate(10)->withQueryString();

        return view('siswa.pengumuman.index', compact('pengumumans', 'siswa', 'search'));
    }

    /**
     * Detail pengumuman siswa.
     */
    public function show(Pengumuman $pengumuman)
    {
        $siswa = Auth::user()->siswa;
        if (! $siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $isAccessible = $pengumuman->is_aktif &&
            $pengumuman->published_at !== null && (
                in_array($pengumuman->target, ['semua', 'siswa']) ||
                ($pengumuman->target === 'kelas' && $pengumuman->kelas_id === $siswa->kelas_id)
            );

        abort_unless($isAccessible, 404, 'Pengumuman tidak ditemukan atau tidak diperuntukkan bagi kelas Anda.');

        return view('siswa.pengumuman.show', compact('pengumuman', 'siswa'));
    }
}
