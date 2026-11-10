<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\SesiAbsensiQr;
use App\Models\Siswa;
use App\Services\Notification\NotificationDispatcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AbsensiQrGuruController extends Controller
{
    private function getGuru()
    {
        $guru = Auth::user()->guru;
        if (! $guru && ! Auth::user()->hasRole('admin')) {
            abort(403, 'Profil guru tidak ditemukan.');
        }
        return $guru ?? JadwalPelajaran::first()?->guru;
    }

    /**
     * Membuka sesi absensi QR untuk jadwal pelajaran pada hari berjalan (F1-01).
     */
    public function buka(JadwalPelajaran $jadwal)
    {
        $guru = $this->getGuru();

        // Validasi kepemilikan jadwal (kecuali admin)
        if ($guru && $jadwal->guru_id !== $guru->id && ! Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak berhak membuka sesi untuk jadwal ini.');
        }

        // Cek apakah sudah ada sesi QR aktif hari ini
        $sesiAktif = SesiAbsensiQr::where('jadwal_pelajaran_id', $jadwal->id)
            ->whereDate('tanggal', today())
            ->where('status', 'aktif')
            ->first();

        if ($sesiAktif) {
            return redirect()->route('guru.absensi.qr.show', $sesiAktif)
                ->with('info', 'Sesi absensi QR untuk jadwal ini sudah aktif.');
        }

        // Pastikan record Absensi induk sudah ada atau dibuat
        $absensi = Absensi::firstOrCreate(
            [
                'jadwal_pelajaran_id' => $jadwal->id,
                'tanggal' => today(),
            ],
            [
                'kelas_id' => $jadwal->kelas_id,
                'guru_id' => $jadwal->guru_id,
                'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
                'metode' => 'qr',
                'materi' => 'Presensi QR Kelas ' . $jadwal->kelas->nama_lengkap,
                'catatan' => 'Sesi absensi dibuka secara digital via QR Code.',
            ]
        );

        // Buat record SesiAbsensiQr
        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'absensi_id' => $absensi->id,
            'guru_id' => $jadwal->guru_id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => Str::random(32),
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        activity('absensi')
            ->causedBy(Auth::user())
            ->performedOn($sesi)
            ->event('created')
            ->withProperties(['jadwal_id' => $jadwal->id, 'kelas' => $jadwal->kelas->nama_lengkap])
            ->log("Membuka sesi absensi QR #{$sesi->id} untuk kelas {$jadwal->kelas->nama_lengkap}");

        app(NotificationDispatcherService::class)->dispatchSesiQr($sesi);

        return redirect()->route('guru.absensi.qr.show', $sesi)
            ->with('success', 'Sesi absensi QR berhasil dibuka. Tampilkan QR code kepada siswa.');
    }

    /**
     * Tampilan layar proyektor / monitor Guru untuk QR Code dan live attendance (F1-02, F1-05).
     */
    public function show(SesiAbsensiQr $sesi)
    {
        $guru = $this->getGuru();

        if ($guru && $sesi->guru_id !== $guru->id && ! Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak berhak mengakses sesi ini.');
        }

        $sesi->load([
            'jadwalPelajaran.kelas.siswas' => fn ($q) => $q->aktif()->orderBy('nama_lengkap'),
            'jadwalPelajaran.mataPelajaran',
            'guru',
            'absensi.detailAbsensis.siswa',
        ]);

        return view('guru.absensi.qr-show', compact('sesi'));
    }

    /**
     * API JSON untuk rotasi token berkala setiap 30 detik (F1-02).
     */
    public function token(SesiAbsensiQr $sesi)
    {
        if ($sesi->status !== 'aktif') {
            return response()->json([
                'status' => 'ditutup',
                'message' => 'Sesi sudah ditutup.',
            ], 410);
        }

        return response()->json([
            'status' => 'aktif',
            'sesi_id' => $sesi->id,
            'token' => $sesi->generateToken(),
            'rolling_code' => $sesi->getRollingCode(),
            'time_remaining' => $sesi->getTimeRemaining(),
            'durasi' => $sesi->durasi_token_detik,
            'qr_payload' => $sesi->getQrPayload(),
            'qr_url' => $sesi->getQrUrl(),
        ]);
    }

    /**
     * API JSON untuk live monitoring daftar siswa hadir & belum hadir (F1-05).
     */
    public function liveStatus(SesiAbsensiQr $sesi)
    {
        $kelas = $sesi->jadwalPelajaran->kelas;
        $semuaSiswa = $kelas->siswas()->aktif()->orderBy('nama_lengkap')->get();

        $absensiId = $sesi->absensi_id;
        $detailList = $absensiId
            ? DetailAbsensi::where('absensi_id', $absensiId)->get()->keyBy('siswa_id')
            : collect();

        $siswaHadir = [];
        $siswaBelum = [];

        foreach ($semuaSiswa as $siswa) {
            $detail = $detailList->get($siswa->id);
            if ($detail && $detail->status === 'hadir') {
                $siswaHadir[] = [
                    'id' => $siswa->id,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama_lengkap,
                    'waktu' => $detail->waktu_absen ? $detail->waktu_absen->format('H:i:s') : $detail->updated_at->format('H:i:s'),
                    'metode' => $detail->metode ?? 'qr',
                ];
            } else {
                $siswaBelum[] = [
                    'id' => $siswa->id,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama_lengkap,
                    'status' => $detail ? $detail->status : 'belum',
                ];
            }
        }

        return response()->json([
            'status' => $sesi->status,
            'total_siswa' => $semuaSiswa->count(),
            'total_hadir' => count($siswaHadir),
            'total_belum' => count($siswaBelum),
            'siswa_hadir' => $siswaHadir,
            'siswa_belum' => $siswaBelum,
        ]);
    }

    /**
     * Menutup sesi absensi QR dan mengarahkan guru ke pengisian manual sisa siswa (F1-06).
     */
    public function tutup(SesiAbsensiQr $sesi)
    {
        $guru = $this->getGuru();

        if ($guru && $sesi->guru_id !== $guru->id && ! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $sesi->update([
            'status' => 'ditutup',
            'ditutup_pada' => now(),
        ]);

        activity('absensi')
            ->causedBy(Auth::user())
            ->performedOn($sesi)
            ->event('updated')
            ->log("Menutup sesi absensi QR #{$sesi->id}");

        return redirect()->route('guru.absensi.show', $sesi->absensi_id)
            ->with('success', 'Sesi absensi QR telah ditutup. Silakan tinjau dan lengkapi status kehadiran siswa.');
    }

    /**
     * Guru menolak presensi siswa langsung dari layar QR live monitor (F1-05).
     */
    public function tolakSiswa(Request $request, SesiAbsensiQr $sesi, Siswa $siswa)
    {
        $guru = $this->getGuru();

        if ($guru && $sesi->guru_id !== $guru->id && ! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        $alasan = $validated['alasan'] ?? 'Ditolak Guru saat sesi QR aktif';

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

        $detail = DetailAbsensi::firstOrNew([
            'absensi_id' => $absensi->id,
            'siswa_id' => $siswa->id,
        ]);

        $detail->fill([
            'status' => 'alpa',
            'metode' => 'override',
            'waktu_absen' => now(),
            'alasan_override' => $alasan,
            'lokasi_valid' => false,
            'keterangan' => 'Presensi ditolak guru: ' . $alasan,
        ]);
        $detail->save();

        app(NotificationDispatcherService::class)->dispatchAlpa($detail);

        activity('absensi')
            ->causedBy(Auth::user())
            ->performedOn($detail)
            ->event('rejected')
            ->withProperties([
                'sesi_id' => $sesi->id,
                'siswa' => $siswa->nama_lengkap,
                'alasan' => $alasan,
            ])
            ->log("Guru {$guru?->nama_lengkap} menolak presensi {$siswa->nama_lengkap} dari sesi QR #{$sesi->id}");

        return response()->json([
            'success' => true,
            'message' => "Presensi {$siswa->nama_lengkap} berhasil ditolak.",
        ]);
    }
}
