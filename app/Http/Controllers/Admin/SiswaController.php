<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $tahunAjaranId = $request->get('tahun_ajaran_id', $tahunAktif?->id ?? TahunAjaran::first()?->id);
        $kelasId = $request->get('kelas_id');

        $query = Siswa::with(['kelas.jurusan', 'kelas.tahunAjaran', 'user']);

        if ($tahunAjaranId === 'tanpa_kelas') {
            $query->whereNull('kelas_id');
        } elseif ($tahunAjaranId && $tahunAjaranId !== 'all') {
            $query->whereHas('kelas', fn ($kq) => $kq->where('tahun_ajaran_id', $tahunAjaranId));
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswas = $query->orderBy('nama_lengkap')->paginate(20)->withQueryString();

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->orderBy('semester')->get();
        
        // Kelas untuk dropdown filter di toolbar
        $kelas = ($tahunAjaranId && $tahunAjaranId !== 'all' && $tahunAjaranId !== 'tanpa_kelas')
            ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->with('jurusan')->orderBy('tingkat')->orderBy('nomor')->get()
            : Kelas::with(['jurusan', 'tahunAjaran'])->orderBy('tingkat')->orderBy('nomor')->get();

        // Kelas tujuan untuk modal mutasi cepat: kelas-kelas pada Tahun Ajaran Aktif
        $kelasListAktif = $tahunAktif
            ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->with(['jurusan', 'tahunAjaran'])->orderBy('tingkat')->orderBy('nomor')->get()
            : Kelas::with(['jurusan', 'tahunAjaran'])->orderBy('tingkat')->orderBy('nomor')->get();

        return view('admin.siswa.index', compact(
            'siswas',
            'tahunAjarans',
            'kelas',
            'kelasListAktif',
            'tahunAktif',
            'tahunAjaranId',
            'kelasId'
        ));
    }

    public function create()
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $kelas = $tahunAktif
            ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->with('jurusan')->orderBy('tingkat')->orderBy('nomor')->get()
            : collect();

        return view('admin.siswa.create', compact('tahunAktif', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:siswas,nis'],
            'nisn' => ['nullable', 'string', 'max:20', 'unique:siswas,nisn'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'no_telepon_ortu' => ['nullable', 'string', 'max:20'],
            'nama_ortu' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $userId = null;

            // Buat akun user jika email disediakan
            if (!empty($validated['email'])) {
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'] ?? $validated['nis']),
                    'role' => 'siswa',
                ]);
                $user->assignRole('siswa');
                $userId = $user->id;
            }

            // Handle foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = \App\Helpers\ImageHelper::uploadAndOptimize($request->file('foto'), 'siswa/foto');
                if ($fotoPath && isset($user)) {
                    $user->update(['foto' => $fotoPath]);
                }
            }

            Siswa::create([
                'user_id' => $userId,
                'kelas_id' => $validated['kelas_id'] ?? null,
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'no_telepon_ortu' => $validated['no_telepon_ortu'] ?? null,
                'nama_ortu' => $validated['nama_ortu'] ?? null,
                'foto' => $fotoPath,
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas.jurusan', 'user', 'nilais.mataPelajaran', 'detailAbsensis.absensi.mataPelajaran']);
        $tahunAktif = TahunAjaran::aktif()->first();

        $rekapAbsensi = $tahunAktif ? $siswa->rekapAbsensi($tahunAktif->id) : null;

        return view('admin.siswa.show', compact('siswa', 'tahunAktif', 'rekapAbsensi'));
    }

    public function edit(Siswa $siswa)
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $kelas = $tahunAktif
            ? Kelas::where('tahun_ajaran_id', $tahunAktif->id)->with('jurusan')->orderBy('tingkat')->orderBy('nomor')->get()
            : collect();

        return view('admin.siswa.edit', compact('siswa', 'tahunAktif', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:siswas,nis,' . $siswa->id],
            'nisn' => ['nullable', 'string', 'max:20', 'unique:siswas,nisn,' . $siswa->id],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'no_telepon_ortu' => ['nullable', 'string', 'max:20'],
            'nama_ortu' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:aktif,lulus,pindah,dikeluarkan'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated, $request, $siswa) {
            if ($request->hasFile('foto')) {
                $oldFoto = $siswa->foto;
                $fotoPath = \App\Helpers\ImageHelper::uploadAndOptimize($request->file('foto'), 'siswa/foto');
                if ($fotoPath) {
                    $validated['foto'] = $fotoPath;
                    if ($siswa->user) {
                        $siswa->user->update(['foto' => $fotoPath]);
                    }
                    if ($oldFoto) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFoto);
                    }
                }
            } else {
                unset($validated['foto']);
            }

            $siswa->update($validated);

            // Update user jika ada
            if ($siswa->user && !empty($validated['password'])) {
                $siswa->user->update([
                    'name' => $validated['nama_lengkap'],
                    'password' => Hash::make($validated['password']),
                ]);
            }
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            if ($siswa->user) {
                $siswa->user->delete();
            } else {
                $siswa->delete();
            }
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Pindahkan siswa ke kelas lain (naik kelas / pindah kelas / penempatan kelas).
     */
    public function pindahKelas(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $kelasTujuan = Kelas::with('tahunAjaran')->findOrFail($validated['kelas_id']);

        if ($kelasTujuan->kapasitas && $kelasTujuan->siswas()->count() >= $kelasTujuan->kapasitas) {
            return back()->withErrors(['kelas_id' => "Kelas {$kelasTujuan->nama_kelas} sudah penuh (kapasitas maksimal: {$kelasTujuan->kapasitas} siswa)."]);
        }

        $siswa->update(['kelas_id' => $validated['kelas_id']]);

        $namaTa = $kelasTujuan->tahunAjaran ? " ({$kelasTujuan->tahunAjaran->nama_lengkap})" : '';

        return back()->with('success', "Siswa {$siswa->nama_lengkap} berhasil ditempatkan ke kelas {$kelasTujuan->nama_kelas}{$namaTa}.");
    }

    /**
     * Tindakan massal (bulk action) untuk data siswa terpilih.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:pindah_kelas,ubah_status,hapus'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['exists:siswas,id'],
            'kelas_id' => ['nullable', 'required_if:action,pindah_kelas', 'exists:kelas,id'],
            'status' => ['nullable', 'required_if:action,ubah_status', 'in:aktif,lulus,pindah,dikeluarkan'],
        ], [
            'siswa_ids.required' => 'Pilih minimal satu siswa untuk melakukan aksi massal.',
            'kelas_id.required_if' => 'Pilih kelas tujuan untuk pemindahan massal.',
            'status.required_if' => 'Pilih status baru yang diinginkan.',
        ]);

        $siswaIds = $validated['siswa_ids'];
        $count = count($siswaIds);

        // 1. Mutasi / Naik Kelas Massal
        if ($validated['action'] === 'pindah_kelas') {
            $kelasTujuan = Kelas::with('tahunAjaran')->findOrFail($validated['kelas_id']);
            $currentCount = $kelasTujuan->siswas()->count();

            // Validasi batas kapasitas kelas
            if ($kelasTujuan->kapasitas && ($currentCount + $count) > $kelasTujuan->kapasitas) {
                $sisaKapasitas = max(0, $kelasTujuan->kapasitas - $currentCount);
                return back()->with('error', "Kapasitas kelas {$kelasTujuan->nama_kelas} tidak mencukupi. Sisa kursi hanya {$sisaKapasitas}, sedangkan Anda memilih {$count} siswa.");
            }

            Siswa::whereIn('id', $siswaIds)->update(['kelas_id' => $validated['kelas_id']]);
            $namaTa = $kelasTujuan->tahunAjaran ? " ({$kelasTujuan->tahunAjaran->nama_lengkap})" : '';

            return back()->with('success', "Berhasil menempatkan {$count} siswa ke kelas {$kelasTujuan->nama_kelas}{$namaTa}.");
        }

        // 2. Perubahan Status Massal (Aktif, Lulus, Pindah, Dikeluarkan)
        if ($validated['action'] === 'ubah_status') {
            Siswa::whereIn('id', $siswaIds)->update(['status' => $validated['status']]);
            $statusLabels = [
                'aktif' => 'Aktif',
                'lulus' => 'Lulus',
                'pindah' => 'Pindah Sekolah',
                'dikeluarkan' => 'Dikeluarkan',
            ];
            $label = $statusLabels[$validated['status']] ?? ucfirst($validated['status']);

            return back()->with('success', "Berhasil memperbarui status {$count} siswa menjadi {$label}.");
        }

        // 3. Penghapusan Massal
        if ($validated['action'] === 'hapus') {
            DB::transaction(function () use ($siswaIds) {
                $siswas = Siswa::whereIn('id', $siswaIds)->get();
                $userIds = $siswas->pluck('user_id')->filter()->toArray();

                Siswa::whereIn('id', $siswaIds)->delete();
                if (!empty($userIds)) {
                    User::whereIn('id', $userIds)->delete();
                }
            });

            return back()->with('success', "Berhasil menghapus {$count} data siswa terpilih.");
        }

        return back();
    }
}
