<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user')->withCount('jadwalPelajarans');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $gurus = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        $mataPelajarans = MataPelajaran::aktif()->orderBy('nama')->get();
        $tahunAktif = TahunAjaran::aktif()->first();

        return view('admin.guru.create', compact('mataPelajarans', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:gurus,nip'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'mata_pelajaran_ids' => ['nullable', 'array'],
            'mata_pelajaran_ids.*' => ['exists:mata_pelajarans,id'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Buat user
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'guru',
            ]);
            $user->assignRole('guru');

            // Handle foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = \App\Helpers\ImageHelper::uploadAndOptimize($request->file('foto'), 'guru/foto');
                if ($fotoPath) {
                    $user->update(['foto' => $fotoPath]);
                }
            }

            // Buat guru
            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_telepon' => $validated['no_telepon'] ?? null,
                'email' => $validated['email'],
                'foto' => $fotoPath,
            ]);

            // Assign mata pelajaran
            if (! empty($validated['mata_pelajaran_ids'])) {
                $tahunAktif = TahunAjaran::aktif()->first();
                if ($tahunAktif) {
                    $syncData = collect($validated['mata_pelajaran_ids'])->mapWithKeys(fn ($id) => [
                        $id => ['tahun_ajaran_id' => $tahunAktif->id],
                    ])->toArray();
                    $guru->mataPelajarans()->sync($syncData);
                }
            }
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru)
    {
        $guru->load(['user', 'mataPelajarans', 'jadwalPelajarans.kelas', 'jadwalPelajarans.mataPelajaran']);
        return view('admin.guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        $guru->load('mataPelajarans');
        $mataPelajarans = MataPelajaran::aktif()->orderBy('nama')->get();
        $tahunAktif = TahunAjaran::aktif()->first();

        return view('admin.guru.edit', compact('guru', 'mataPelajarans', 'tahunAktif'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'string', 'max:20', 'unique:gurus,nip,' . $guru->id],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email,' . $guru->user_id],
            'status' => ['required', 'in:aktif,tidak_aktif'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'mata_pelajaran_ids' => ['nullable', 'array'],
            'mata_pelajaran_ids.*' => ['exists:mata_pelajarans,id'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated, $request, $guru) {
            // Update user
            $userUpdate = [
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userUpdate['password'] = Hash::make($validated['password']);
            }
            $guru->user->update($userUpdate);

            // Handle foto
            if ($request->hasFile('foto')) {
                $oldFoto = $guru->foto;
                $fotoPath = \App\Helpers\ImageHelper::uploadAndOptimize($request->file('foto'), 'guru/foto');
                if ($fotoPath) {
                    $validated['foto'] = $fotoPath;
                    $guru->user->update(['foto' => $fotoPath]);
                    if ($oldFoto) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFoto);
                    }
                }
            } else {
                unset($validated['foto']);
            }

            $guru->update(array_merge($validated, ['email' => $validated['email']]));

            // Sync mata pelajaran
            $tahunAktif = TahunAjaran::aktif()->first();
            if ($tahunAktif && isset($validated['mata_pelajaran_ids'])) {
                $syncData = collect($validated['mata_pelajaran_ids'])->mapWithKeys(fn ($id) => [
                    $id => ['tahun_ajaran_id' => $tahunAktif->id],
                ])->toArray();
                $guru->mataPelajarans()->sync($syncData);
            }
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            $guru->user->delete(); // Cascade ke guru via FK
        });

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
