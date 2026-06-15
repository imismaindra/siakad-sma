<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil sendiri.
     */
    public function edit()
    {
        $user = Auth::user();

        // Load profil terkait berdasarkan role
        if ($user->isGuru()) {
            $user->load('guru');
        } elseif ($user->isSiswa()) {
            $user->load('siswa.kelas.jurusan');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil dan password.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'password_lama' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Ganti password jika diminta
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['password_lama'] ?? '', $user->password)) {
                return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($validated['password']);
        }

        // Handle profile photo upload with optimization
        if ($request->hasFile('foto')) {
            $oldFoto = $user->foto;
            
            // Upload & optimize using our Helper class
            $fotoPath = \App\Helpers\ImageHelper::uploadAndOptimize($request->file('foto'), 'profiles');
            
            if ($fotoPath) {
                $user->foto = $fotoPath;
                
                // Sync with Guru or Siswa photo column if applicable
                if ($user->isGuru() && $user->guru) {
                    $oldGuruFoto = $user->guru->foto;
                    $user->guru->update(['foto' => $fotoPath]);
                    if ($oldGuruFoto && $oldGuruFoto !== $fotoPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldGuruFoto);
                    }
                } elseif ($user->isSiswa() && $user->siswa) {
                    $oldSiswaFoto = $user->siswa->foto;
                    $user->siswa->update(['foto' => $fotoPath]);
                    if ($oldSiswaFoto && $oldSiswaFoto !== $fotoPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSiswaFoto);
                    }
                }

                // Delete old profile picture if exists
                if ($oldFoto && $oldFoto !== $fotoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFoto);
                }
            }
        }

        $user->name = $validated['name'];
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
