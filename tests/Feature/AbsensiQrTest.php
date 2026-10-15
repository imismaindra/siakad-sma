<?php

namespace Tests\Feature;

use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\SesiAbsensiQr;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiQrTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guru_can_open_qr_session(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();

        $response = $this->actingAs($guruUser)
            ->post(route('guru.absensi.qr.buka', $jadwal));

        $sesi = SesiAbsensiQr::where('jadwal_pelajaran_id', $jadwal->id)->latest()->first();

        $this->assertNotNull($sesi);
        $this->assertEquals('aktif', $sesi->status);
        $this->assertEquals(30, $sesi->durasi_token_detik);
        $response->assertRedirect(route('guru.absensi.qr.show', $sesi));
    }

    public function test_qr_session_generates_and_validates_rotating_token(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-key-1234567890abcdef',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $validToken = $sesi->generateToken();
        $this->assertTrue($sesi->isValidToken($validToken));
        $this->assertFalse($sesi->isValidToken('invalid-fake-token-xyz'));
    }

    public function test_student_in_class_can_scan_qr_and_mark_attendance(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();
        $siswaUser = $siswa->user;

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-key-1234567890abcdef',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        $response = $this->actingAs($siswaUser)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $detail = DetailAbsensi::where('siswa_id', $siswa->id)->latest()->first();
        $this->assertNotNull($detail);
        $this->assertEquals('hadir', $detail->status);
        $this->assertEquals('qr', $detail->metode);
    }

    public function test_student_from_different_class_is_rejected(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        
        // Cari siswa dari kelas yang berbeda
        $siswaBedaKelas = Siswa::where('kelas_id', '!=', $jadwal->kelas_id)->first();
        $siswaUser = $siswaBedaKelas->user;

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-key-1234567890abcdef',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        $response = $this->actingAs($siswaUser)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
            ]);

        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }

    public function test_scanning_twice_does_not_create_duplicate_attendance(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();
        $siswaUser = $siswa->user;

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-key-1234567890abcdef',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Scan pertama
        $this->actingAs($siswaUser)->postJson(route('siswa.absensi.scan.post'), [
            'sesi_id' => $sesi->id,
            'token' => $token,
        ])->assertStatus(200);

        $countBefore = DetailAbsensi::where('siswa_id', $siswa->id)->count();

        // Scan kedua (ulang)
        $response = $this->actingAs($siswaUser)->postJson(route('siswa.absensi.scan.post'), [
            'sesi_id' => $sesi->id,
            'token' => $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['already_present' => true]);

        $countAfter = DetailAbsensi::where('siswa_id', $siswa->id)->count();
        $this->assertEquals($countBefore, $countAfter);
    }

    public function test_scanning_closed_session_is_rejected(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now()->subHour(),
            'ditutup_pada' => now()->subMinutes(10),
            'secret' => 'test-secret-key-1234567890abcdef',
            'durasi_token_detik' => 30,
            'status' => 'ditutup',
        ]);

        $token = $sesi->generateToken();

        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
            ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_guru_can_fetch_live_status(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-key-1234567890abcdef',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($guruUser)
            ->getJson(route('guru.absensi.qr.status', $sesi));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'total_siswa',
            'total_hadir',
            'total_belum',
            'siswa_hadir',
            'siswa_belum',
        ]);
    }
}
