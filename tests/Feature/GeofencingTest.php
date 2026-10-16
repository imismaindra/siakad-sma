<?php

namespace Tests\Feature;

use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\PengaturanSekolah;
use App\Models\SesiAbsensiQr;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeofencingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        // Pastikan pengaturan sekolah default terpasang
        PengaturanSekolah::clearCache();
        PengaturanSekolah::updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah' => 'SMA Negeri 1',
                'alamat' => 'Jl. Pendidikan No. 1, Kota Bandung',
                'latitude' => -6.9147440,
                'longitude' => 107.6098100,
                'radius_meter' => 100,
                'max_accuracy_meter' => 80,
                'is_geofencing_active' => true,
                'enforce_anti_mock' => true,
            ]
        );
    }

    public function test_student_within_school_radius_can_attend_and_records_geofence_data(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-geofence-key-12345678',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Koordinat tepat di titik sekolah dengan akurasi 15 meter
        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
                'latitude' => -6.9147440,
                'longitude' => 107.6098100,
                'accuracy' => 15.0,
                'client_time' => now()->timestamp * 1000,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $detail = DetailAbsensi::where('siswa_id', $siswa->id)->latest()->first();
        $this->assertNotNull($detail);
        $this->assertEquals('hadir', $detail->status);
        $this->assertEquals('qr', $detail->metode);
        $this->assertTrue($detail->lokasi_valid);
        $this->assertEquals(0.0, $detail->jarak_meter);
        $this->assertEquals(15.0, $detail->akurasi_meter);
    }

    public function test_student_outside_school_radius_is_rejected(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-geofence-key-12345678',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Koordinat berjarak ~1.5 km dari sekolah
        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
                'latitude' => -6.9250000,
                'longitude' => 107.6200000,
                'accuracy' => 12.0,
                'client_time' => now()->timestamp * 1000,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'is_location_error' => true,
        ]);
        $this->assertStringContainsString('luar area sekolah', $response->json('message'));
    }

    public function test_poor_gps_accuracy_exceeding_threshold_is_rejected(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-geofence-key-12345678',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Koordinat tepat di sekolah, namun akurasi sinyal sangat buruk (150m > batas 80m)
        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
                'latitude' => -6.9147440,
                'longitude' => 107.6098100,
                'accuracy' => 150.0,
                'client_time' => now()->timestamp * 1000,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'is_location_error' => true,
        ]);
        $this->assertStringContainsString('kurang akurat', $response->json('message'));
    }

    public function test_fake_location_anomaly_zero_accuracy_is_rejected_and_mock_suspected(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-geofence-key-12345678',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Anomali Fake GPS: Akurasi bernilai 0.0m (artifisial)
        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
                'latitude' => -6.9147440,
                'longitude' => 107.6098100,
                'accuracy' => 0.0,
                'client_time' => now()->timestamp * 1000,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'is_mock_suspected' => true,
        ]);
        $this->assertStringContainsString('Fake GPS', $response->json('message'));
    }

    public function test_missing_location_is_rejected_when_geofencing_active(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-geofence-key-12345678',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Tidak mengirim koordinat latitude & longitude
        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
            ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertStringContainsString('Koordinat lokasi (GPS) tidak ditemukan', $response->json('message'));
    }

    public function test_attendance_succeeds_without_location_when_geofencing_is_disabled(): void
    {
        // Nonaktifkan geofencing di pengaturan sekolah
        $settings = PengaturanSekolah::getSettings();
        $settings->update(['is_geofencing_active' => false]);
        PengaturanSekolah::clearCache();

        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        $sesi = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $guruUser->guru->id,
            'tanggal' => today(),
            'dibuka_pada' => now(),
            'secret' => 'test-secret-geofence-key-12345678',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        $token = $sesi->generateToken();

        // Scan tanpa koordinat tetap lolos
        $response = $this->actingAs($siswa->user)
            ->postJson(route('siswa.absensi.scan.post'), [
                'sesi_id' => $sesi->id,
                'token' => $token,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_admin_can_update_school_location_and_geofencing_settings(): void
    {
        $admin = User::role('admin')->first();

        $response = $this->actingAs($admin)
            ->put(route('admin.pengaturan-lokasi.update'), [
                'nama_sekolah' => 'SMA Negeri 1 Prestasi',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
                'latitude' => -6.9200000,
                'longitude' => 107.6100000,
                'radius_meter' => 150,
                'max_accuracy_meter' => 70,
                'is_geofencing_active' => '1',
                'enforce_anti_mock' => '1',
            ]);

        $response->assertRedirect(route('admin.pengaturan-lokasi.index'));
        $response->assertSessionHas('success');

        $updated = PengaturanSekolah::getSettings();
        $this->assertEquals('SMA Negeri 1 Prestasi', $updated->nama_sekolah);
        $this->assertEquals(150, $updated->radius_meter);
        $this->assertEquals(70, $updated->max_accuracy_meter);
        $this->assertTrue($updated->is_geofencing_active);
    }

    public function test_teacher_can_override_student_attendance_with_reason(): void
    {
        $guruUser = User::role('guru')->first();
        $jadwal = JadwalPelajaran::where('guru_id', $guruUser->guru->id)->first();
        $siswa = Siswa::where('kelas_id', $jadwal->kelas_id)->first();

        // Buat absensi induk
        $absensi = \App\Models\Absensi::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'kelas_id' => $jadwal->kelas_id,
            'guru_id' => $jadwal->guru_id,
            'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
            'tanggal' => today(),
            'materi' => 'Pertemuan Praktikum',
        ]);

        $response = $this->actingAs($guruUser)
            ->post(route('guru.absensi.override', [$absensi, $siswa]), [
                'status' => 'hadir',
                'alasan_override' => 'Ponsel siswa mengalami kendala GPS di ruang lab, siswa hadir fisik.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $detail = DetailAbsensi::where('absensi_id', $absensi->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        $this->assertNotNull($detail);
        $this->assertEquals('hadir', $detail->status);
        $this->assertEquals('override', $detail->metode);
        $this->assertEquals('Ponsel siswa mengalami kendala GPS di ruang lab, siswa hadir fisik.', $detail->alasan_override);
        $this->assertFalse($detail->lokasi_valid);
    }
}
