<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanLokasiController extends Controller
{
    /**
     * Tampilan pengaturan koordinat sekolah dan batas radius geofencing (F2-01).
     */
    public function index()
    {
        $settings = PengaturanSekolah::getSettings();
        return view('admin.pengaturan-lokasi.index', compact('settings'));
    }

    /**
     * Menyimpan perubahan titik lokasi sekolah dan parameter geofencing.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:5000'],
            'max_accuracy_meter' => ['required', 'integer', 'min:10', 'max:500'],
            'is_geofencing_active' => ['nullable', 'boolean'],
            'enforce_anti_mock' => ['nullable', 'boolean'],
        ]);

        $validated['is_geofencing_active'] = $request->boolean('is_geofencing_active');
        $validated['enforce_anti_mock'] = $request->boolean('enforce_anti_mock');

        $settings = PengaturanSekolah::firstOrNew(['id' => 1]);
        $oldRadius = $settings->radius_meter;
        $settings->fill($validated);
        $settings->save();

        PengaturanSekolah::clearCache();

        activity('pengaturan')
            ->causedBy(Auth::user())
            ->performedOn($settings)
            ->event('updated')
            ->withProperties([
                'latitude' => $settings->latitude,
                'longitude' => $settings->longitude,
                'radius_meter' => $settings->radius_meter,
                'is_geofencing_active' => $settings->is_geofencing_active,
                'enforce_anti_mock' => $settings->enforce_anti_mock,
            ])
            ->log("Admin memperbarui pengaturan lokasi sekolah (Radius: {$settings->radius_meter}m, Geofencing: " . ($settings->is_geofencing_active ? 'Aktif' : 'Nonaktif') . ")");

        return redirect()->route('admin.pengaturan-lokasi.index')
            ->with('success', 'Pengaturan lokasi sekolah dan parameter geofencing berhasil disimpan.');
    }
}
