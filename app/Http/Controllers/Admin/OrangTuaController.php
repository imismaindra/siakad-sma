<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OrangTuaController extends Controller
{
    /**
     * Tampilkan daftar akun orang tua / wali murid.
     */
    public function index(Request $request)
    {
        $query = OrangTua::with(['user', 'siswas.kelas'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('siswas', function ($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        $orangTuas = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => OrangTua::count(),
            'ayah' => DB::table('orang_tua_siswa')->where('hubungan', 'ayah')->count(),
            'ibu' => DB::table('orang_tua_siswa')->where('hubungan', 'ibu')->count(),
            'wali' => DB::table('orang_tua_siswa')->where('hubungan', 'wali')->count(),
        ];

        return view('admin.orang-tua.index', compact('orangTuas', 'stats'));
    }

    /**
     * Form tambah akun orang tua baru.
     */
    public function create()
    {
        $siswas = Siswa::with('kelas')->orderBy('nama_lengkap')->get();

        return view('admin.orang-tua.create', compact('siswas'));
    }

    /**
     * Simpan akun orang tua baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin'  => ['required', 'in:L,P'],
            'no_telepon'     => ['nullable', 'string', 'max:20'],
            'pekerjaan'      => ['nullable', 'string', 'max:100'],
            'alamat'         => ['nullable', 'string'],
            'anak'           => ['nullable', 'array'],
            'anak.*.siswa_id'=> ['required', 'exists:siswas,id'],
            'anak.*.hubungan'=> ['required', 'in:ayah,ibu,wali'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            // 1. Buat User Account
            $user = User::create([
                'name'      => $validated['nama_lengkap'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role'      => UserRole::OrangTua->value,
                'is_active' => true,
            ]);
            $user->assignRole(UserRole::OrangTua->value);

            // 2. Buat Profil Orang Tua
            $orangTua = OrangTua::create([
                'user_id'       => $user->id,
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon'    => $validated['no_telepon'] ?? null,
                'pekerjaan'     => $validated['pekerjaan'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
            ]);

            // 3. Tautkan Siswa (Anak)
            if (! empty($validated['anak'])) {
                $attachData = [];
                foreach ($validated['anak'] as $item) {
                    $attachData[$item['siswa_id']] = ['hubungan' => $item['hubungan']];
                }
                $orangTua->siswas()->sync($attachData);
            }

            activity('orang_tua')
                ->causedBy(auth()->user())
                ->performedOn($orangTua)
                ->event('created')
                ->log("Admin membuat akun orang tua {$orangTua->nama_lengkap} ({$user->email})");
        });

        return redirect()->route('admin.orang-tua.index')
            ->with('success', 'Akun orang tua / wali berhasil dibuat dan ditautkan ke siswa.');
    }

    /**
     * Tampilkan detail profil orang tua dan anak-anaknya.
     */
    public function show(OrangTua $orangTua)
    {
        $orangTua->load(['user', 'siswas.kelas.waliKelas', 'siswas.nilais']);

        return view('admin.orang-tua.show', compact('orangTua'));
    }

    /**
     * Form edit akun orang tua.
     */
    public function edit(OrangTua $orangTua)
    {
        $orangTua->load(['user', 'siswas']);
        $siswas = Siswa::with('kelas')->orderBy('nama_lengkap')->get();

        return view('admin.orang-tua.edit', compact('orangTua', 'siswas'));
    }

    /**
     * Perbarui akun orang tua di database.
     */
    public function update(Request $request, OrangTua $orangTua)
    {
        $validated = $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($orangTua->user_id)],
            'password'       => ['nullable', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin'  => ['required', 'in:L,P'],
            'no_telepon'     => ['nullable', 'string', 'max:20'],
            'pekerjaan'      => ['nullable', 'string', 'max:100'],
            'alamat'         => ['nullable', 'string'],
            'anak'           => ['nullable', 'array'],
            'anak.*.siswa_id'=> ['required', 'exists:siswas,id'],
            'anak.*.hubungan'=> ['required', 'in:ayah,ibu,wali'],
        ]);

        DB::transaction(function () use ($validated, $orangTua) {
            // 1. Update User
            $userData = [
                'name'  => $validated['nama_lengkap'],
                'email' => $validated['email'],
            ];
            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $orangTua->user?->update($userData);

            // 2. Update Orang Tua
            $orangTua->update([
                'nama_lengkap'  => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon'    => $validated['no_telepon'] ?? null,
                'pekerjaan'     => $validated['pekerjaan'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
            ]);

            // 3. Sinkronkan Tautan Anak
            $attachData = [];
            if (! empty($validated['anak'])) {
                foreach ($validated['anak'] as $item) {
                    $attachData[$item['siswa_id']] = ['hubungan' => $item['hubungan']];
                }
            }
            $orangTua->siswas()->sync($attachData);

            activity('orang_tua')
                ->causedBy(auth()->user())
                ->performedOn($orangTua)
                ->event('updated')
                ->log("Admin memperbarui data akun orang tua {$orangTua->nama_lengkap}");
        });

        return redirect()->route('admin.orang-tua.index')
            ->with('success', 'Data akun orang tua / wali berhasil diperbarui.');
    }

    /**
     * Hapus akun orang tua.
     */
    public function destroy(OrangTua $orangTua)
    {
        DB::transaction(function () use ($orangTua) {
            $nama = $orangTua->nama_lengkap;
            $user = $orangTua->user;

            $orangTua->siswas()->detach();
            $orangTua->delete();

            if ($user) {
                $user->delete();
            }

            activity('orang_tua')
                ->causedBy(auth()->user())
                ->event('deleted')
                ->log("Admin menghapus akun orang tua {$nama}");
        });

        return redirect()->route('admin.orang-tua.index')
            ->with('success', 'Akun orang tua / wali berhasil dihapus.');
    }
}
