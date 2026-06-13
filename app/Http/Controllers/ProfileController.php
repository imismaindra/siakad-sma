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

        $user->name = $validated['name'];
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
