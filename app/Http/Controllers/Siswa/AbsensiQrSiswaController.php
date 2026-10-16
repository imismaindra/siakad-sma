<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\SesiAbsensiQr;
use App\Services\GeofencingService;
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
     * Memproses data hasil pemindaian QR code dari kamera browser siswa (F1-03, F1-04, F1-07, F2).
     */
    public function scan(Request $request)
    {
        $siswa = $this->getSiswa();

        $validated = $request->validate([
            'qr' => ['nullable', 'string'],
            'sesi_id' => ['nullable', 'integer', 'exists:sesi_absensi_qrs,id'],
            'token' => ['nullable', 'string'],
            'kode_cadangan' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
            'client_time' => ['nullable', 'integer'],
        ]);

        $sesi = null;

        if ($request->filled('qr')) {
            $rawQr = trim($request->input('qr'));
            $ticketData = SesiAbsensiQr::decryptTicket($rawQr);

            if ($ticketData) {
                $sesi = SesiAbsensiQr::with([
                    'jadwalPelajaran.kelas',
                    'jadwalPelajaran.mataPelajaran',
                    'guru',
                    'absensi',
                ])->find($ticketData['sesi_id']);

                if (! $sesi) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sesi absensi tidak ditemukan.',
                    ], 404);
                }

                if ($sesi->status !== 'aktif') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sesi absensi ini sudah ditutup oleh guru.',
                    ], 422);
                }

                if (isset($ticketData['exp']) && now()->timestamp > (int) $ticketData['exp']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode QR telah kedaluwarsa. Silakan scan kode QR terbaru di layar.',
                    ], 422);
                }

                if (! $sesi->isValidTicketPayload($ticketData)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token QR tidak valid atau rotasi waktu telah berganti. Silakan scan ulang kode QR terbaru.',
                    ], 422);
                }
            } else {
                // Fallback untuk backward-compatibility jika format berupa raw JSON sesi_id & token
                $legacy = json_decode($rawQr, true);
                if (is_array($legacy) && isset($legacy['sesi_id'], $legacy['token'])) {
                    $sesi = SesiAbsensiQr::with([
                        'jadwalPelajaran.kelas',
                        'jadwalPelajaran.mataPelajaran',
                        'guru',
                        'absensi',
                    ])->find($legacy['sesi_id']);

                    if (! $sesi || ! $sesi->isValidToken($legacy['token'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Token QR sudah kedaluwarsa atau tidak valid.',
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Format kode QR tidak valid atau telah dirusak.',
                    ], 422);
                }
            }
        } elseif ($request->filled('sesi_id')) {
            $sesi = SesiAbsensiQr::with([
                'jadwalPelajaran.kelas',
                'jadwalPelajaran.mataPelajaran',
                'guru',
                'absensi',
            ])->findOrFail($validated['sesi_id']);

            if ($sesi->status !== 'aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi absensi ini sudah ditutup oleh guru.',
                ], 422);
            }

            $inputToken = trim($request->input('token') ?? $request->input('kode_cadangan') ?? '');
            $isValid = $sesi->isValidToken($inputToken) || $sesi->isValidRollingCode($inputToken);

            if (! $isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode token / OTP tidak valid atau sudah kedaluwarsa.',
                ], 422);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data presensi tidak lengkap. Pindai kode QR atau masukkan kode sesi.',
            ], 422);
        }

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

        // 3. Validasi Geofencing & Deteksi Fake Location / Mock GPS (F2-02, F2-03)
        $lat = isset($validated['latitude']) ? (float) $validated['latitude'] : null;
        $lon = isset($validated['longitude']) ? (float) $validated['longitude'] : null;
        $acc = isset($validated['accuracy']) ? (float) $validated['accuracy'] : null;
        $clientTime = isset($validated['client_time']) ? (int) $validated['client_time'] : null;

        $locationCheck = GeofencingService::validateLocation($lat, $lon, $acc, $clientTime);

        if (! $locationCheck['valid']) {
            if ($locationCheck['is_mock_suspected']) {
                activity('absensi')
                    ->causedBy(Auth::user())
                    ->event('suspicious')
                    ->withProperties([
                        'sesi_id' => $sesi->id,
                        'reason' => $locationCheck['message'],
                        'latitude' => $lat,
                        'longitude' => $lon,
                        'accuracy' => $acc,
                    ])
                    ->log("Terdeteksi anomali lokasi / dugaan Fake GPS pada presensi siswa {$siswa->nama_lengkap}");
            }

            return response()->json([
                'success' => false,
                'is_location_error' => true,
                'is_mock_suspected' => $locationCheck['is_mock_suspected'],
                'message' => $locationCheck['message'],
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
                    'jarak_meter' => $detail->jarak_meter,
                ],
            ]);
        }

        // 5. Catat kehadiran valid & simpan data geofencing (F1-04, F2-06)
        $detailData = [
            'status' => 'hadir',
            'metode' => 'qr',
            'waktu_absen' => now(),
            'jarak_meter' => $locationCheck['distance'],
            'akurasi_meter' => $locationCheck['accuracy'],
            'lokasi_valid' => true,
            'keterangan' => 'Presensi mandiri via QR Code' . ($locationCheck['distance'] !== null ? " ({$locationCheck['distance']}m dari sekolah)" : ''),
        ];

        if ($detail) {
            $detail->update($detailData);
        } else {
            $detail = DetailAbsensi::create(array_merge([
                'absensi_id' => $absensi->id,
                'siswa_id' => $siswa->id,
            ], $detailData));
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
                'jarak_meter' => $locationCheck['distance'],
                'akurasi_meter' => $locationCheck['accuracy'],
            ])
            ->log("Siswa {$siswa->nama_lengkap} melakukan presensi via QR code (Jarak: {$locationCheck['distance']}m)");

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat! Anda tercatat Hadir.',
            'data' => [
                'nama' => $siswa->nama_lengkap,
                'mata_pelajaran' => $sesi->jadwalPelajaran->mataPelajaran->nama,
                'kelas' => $sesi->jadwalPelajaran->kelas->nama_lengkap,
                'guru' => $sesi->guru->nama_lengkap,
                'waktu' => now()->format('H:i:s'),
                'jarak_meter' => $locationCheck['distance'],
                'akurasi_meter' => $locationCheck['accuracy'],
            ],
        ]);
    }
}
