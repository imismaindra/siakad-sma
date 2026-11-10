<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanGuruController extends Controller
{
    /**
     * Daftar pengumuman untuk Guru (target: semua, guru).
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Pengumuman::aktif()
            ->whereIn('target', ['semua', 'guru'])
            ->with('pembuat')
            ->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $pengumumans = $query->paginate(10)->withQueryString();

        return view('guru.pengumuman.index', compact('pengumumans', 'search'));
    }

    /**
     * Detail pengumuman guru.
     */
    public function show(Pengumuman $pengumuman)
    {
        $isAccessible = $pengumuman->is_aktif &&
            $pengumuman->published_at !== null &&
            in_array($pengumuman->target, ['semua', 'guru']);

        abort_unless($isAccessible, 404, 'Pengumuman tidak ditemukan atau tidak diperuntukkan bagi Guru.');

        return view('guru.pengumuman.show', compact('pengumuman'));
    }
}
