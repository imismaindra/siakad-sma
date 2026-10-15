<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\SesiAbsensiQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiQrSiswaController extends Controller
{
    private function getSiswa()
    {
        $siswa = Auth::user()->siswa;
        if (! $siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }
        return $siswa;
    }

    /**
     * Tampilan antarmuka pemindai (scanner) kamera browser siswa (F1-03).
     */
    public function index()
    {
        $siswa = $this->getSiswa();

        // Cari sesi aktif hari ini yang relevan dengan kelas siswa
        $sesiAktifList = SesiAbsensiQr::with(['jadwalPelajaran.mataPelajaran', 'guru'])
            ->where('status', 'aktif')
            ->whereDate('tanggal', today())
            ->whereHas('jadwalPelajaran', fn ($q) => $q->where('kelas_id', $siswa->kelas_id))
            ->get();

        // Riwayat absensi hari ini milik siswa
        $absensiHariIni = DetailAbsensi::with(['absensi.mataPelajaran', 'absensi.guru'])
            ->where('siswa_id', $siswa->id)
            ->whereHas('absensi', fn ($q) => $q->whereDate('tanggal', today()))
            ->get();

        return view('siswa.absensi-scan', compact('siswa', 'sesiAktifList', 'absensiHariIni'));
    }

    /**
     * Memproses data hasil pemindaian QR code dari kamera browser siswa (F1-03, F1-04, F1-07).
     */
    public function scan(Request $request)
    {
        $siswa = $this->getSiswa();

        $validated = $request->validate([
            'sesi_id' => ['required', 'integer', 'exists:sesi_absensi_qrs,id'],
            'token' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
        ]);

        $sesi = SesiAbsensiQr::with([
            'jadwalPelajaran.kelas',
            'jadwalPelajaran.mataPelajaran',
            'guru',
            'absensi',
        ])->findOrFail($validated['sesi_id']);

        // 1. Validasi status sesi (F1-07)
        if ($sesi->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi absensi ini sudah ditutup oleh guru.',
            ], 422);
        }

        // 2. Validasi keanggotaan kelas siswa (F1-03)
        if ($siswa->kelas_id !== $sesi->jadwalPelajaran->kelas_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota kelas ' . $sesi->jadwalPelajaran->kelas->nama_lengkap . '.',
            ], 403);
        }

        // 3. Validasi token QR (F1-02, F1-03, F1-07)
        if (! $sesi->isValidToken($validated['token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Token QR sudah kedaluwarsa atau tidak valid. Silakan scan ulang kode QR terbaru.',
            ], 422);
        }

        // Pastikan absensi induk terhubung
        $absensi = $sesi->absensi;
        if (! $absensi) {
            $absensi = Absensi::firstOrCreate(
                [
                    'jadwal_pelajaran_id' => $sesi->jadwal_pelajaran_id,
                    'tanggal' => $sesi->tanggal,
                ],
                [
                    'kelas_id' => $sesi->jadwalPelajaran->kelas_id,
                    'guru_id' => $sesi->guru_id,
                    'mata_pelajaran_id' => $sesi->jadwalPelajaran->mata_pelajaran_id,
                    'metode' => 'qr',
                    'materi' => 'Presensi QR Kelas ' . $sesi->jadwalPelajaran->kelas->nama_lengkap,
                ]
            );
            $sesi->update(['absensi_id' => $absensi->id]);
        }

        // 4. Mencegah duplikasi absensi (F1-04)
        $detail = DetailAbsensi::where('absensi_id', $absensi->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($detail && $detail->status === 'hadir') {
            return response()->json([
                'success' => true,
                'already_present' => true,
                'message' => 'Anda sudah tercatat hadir pada sesi ini sebelumnya.',
                'data' => [
                    'nama' => $siswa->nama_lengkap,
                    'mata_pelajaran' => $sesi->jadwalPelajaran->mataPelajaran->nama,
                    'kelas' => $sesi->jadwalPelajaran->kelas->nama_lengkap,
                    'waktu' => $detail->waktu_absen ? $detail->waktu_absen->format('H:i:s') : $detail->updated_at->format('H:i:s'),
                ],
            ]);
        }

        // 5. Catat kehadiran valid (F1-04)
        if ($detail) {
            $detail->update([
                'status' => 'hadir',
                'metode' => 'qr',
                'waktu_absen' => now(),
                'keterangan' => 'Presensi mandiri via QR Code',
            ]);
        } else {
            $detail = DetailAbsensi::create([
                'absensi_id' => $absensi->id,
                'siswa_id' => $siswa->id,
                'status' => 'hadir',
                'metode' => 'qr',
                'waktu_absen' => now(),
                'keterangan' => 'Presensi mandiri via QR Code',
            ]);
        }

        // Audit Log (F3)
        activity('absensi')
            ->causedBy(Auth::user())
            ->performedOn($detail)
            ->event('created')
            ->withProperties([
                'metode' => 'qr',
                'sesi_qr_id' => $sesi->id,
                'mapel' => $sesi->jadwalPelajaran->mataPelajaran->nama,
            ])
            ->log("Siswa {$siswa->nama_lengkap} melakukan presensi via QR code");

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat! Anda tercatat Hadir.',
            'data' => [
                'nama' => $siswa->nama_lengkap,
                'mata_pelajaran' => $sesi->jadwalPelajaran->mataPelajaran->nama,
                'kelas' => $sesi->jadwalPelajaran->kelas->nama_lengkap,
                'guru' => $sesi->guru->nama_lengkap,
                'waktu' => now()->format('H:i:s'),
            ],
        ]);
    }
}
