<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\SesiAbsensiQr;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserNotificationSetting;
use App\Notifications\AlpaPresensiNotification;
use App\Notifications\NilaiTerbitNotification;
use App\Notifications\SesiQrDibukaNotification;
use App\Notifications\TestNotification;
use App\Services\Notification\Adapters\LogWhatsAppAdapter;
use App\Services\Notification\NotificationDispatcherService;
use App\Services\Notification\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifikasiModularTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * F6-01: Pengguna terautentikasi dapat melihat kotak masuk notifikasi.
     */
    public function test_authenticated_user_can_access_notifications_inbox(): void
    {
        $guru = User::role('guru')->first();

        $response = $this->actingAs($guru)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Notifikasi');
        $response->assertSee('Kotak Masuk');
    }

    /**
     * F6-01: Notifikasi dapat ditandai telah dibaca dan mengarahkan pengguna ke tautan target.
     */
    public function test_user_can_mark_notification_as_read_and_redirect(): void
    {
        $siswaUser = User::role('siswa')->first();

        $siswaUser->notify(new TestNotification());

        $notification = $siswaUser->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $response = $this->actingAs($siswaUser)->get(route('notifications.read', $notification->id));

        $response->assertRedirect(route('notifications.settings'));
        $this->assertNotNull($notification->fresh()->read_at);
    }

    /**
     * F6-01: Pengguna dapat menandai seluruh notifikasi yang belum dibaca menjadi terbaca sekaligus.
     */
    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $admin = User::role('admin')->first();

        $admin->notify(new TestNotification());
        $admin->notify(new TestNotification());

        $this->assertEquals(2, $admin->unreadNotifications()->count());

        $response = $this->actingAs($admin)->post(route('notifications.mark-all-read'));

        $response->assertRedirect();
        $this->assertEquals(0, $admin->unreadNotifications()->count());
    }

    /**
     * F6-02: Pengguna dapat mengakses dan memperbarui preferensi saluran & topik notifikasi.
     */
    public function test_user_can_view_and_update_notification_settings(): void
    {
        $guru = User::role('guru')->first();

        // View settings page
        $viewResponse = $this->actingAs($guru)->get(route('notifications.settings'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Pengaturan Notifikasi');
        $viewResponse->assertSee('Saluran Notifikasi');

        // Update settings
        $payload = [
            'telegram_chat_id' => '987654321',
            'whatsapp_number' => '081299887766',
            'channel_in_app' => 1,
            'channel_mail' => 1,
            'channel_telegram' => 1,
            'channel_whatsapp' => 1,
            'notify_alpa' => 1,
            'notify_nilai' => 1,
            'notify_pengumuman' => 1,
            'notify_qr_sesi' => 0,
        ];

        $updateResponse = $this->actingAs($guru)->put(route('notifications.settings.update'), $payload);

        $updateResponse->assertRedirect(route('notifications.settings'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('user_notification_settings', [
            'user_id' => $guru->id,
            'telegram_chat_id' => '987654321',
            'whatsapp_number' => '081299887766',
            'channel_telegram' => true,
            'channel_whatsapp' => true,
            'notify_qr_sesi' => false,
        ]);
    }

    /**
     * F6-02: Pengguna dapat memicu pengiriman notifikasi uji coba ke seluruh saluran yang aktif.
     */
    public function test_user_can_dispatch_test_notification(): void
    {
        Notification::fake();

        $guru = User::role('guru')->first();

        $response = $this->actingAs($guru)->put(route('notifications.settings.update'), [
            'channel_in_app' => 1,
            'kirim_test' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Notification::assertSentTo($guru, TestNotification::class);
    }

    /**
     * F6-03: Pemicu otomatis notifikasi alpa presensi ke Siswa dan Orang Tua yang bersangkutan.
     */
    public function test_alpa_presensi_dispatches_notification_to_siswa_and_orang_tua(): void
    {
        Notification::fake();

        $siswa = Siswa::with('user', 'kelas')->first();
        $this->assertNotNull($siswa, 'Siswa harus ada di database seeder.');

        // Hubungkan siswa ke orang tua bila belum ada
        $orangTuaUser = User::role('orang_tua')->first();
        if ($orangTuaUser && $orangTuaUser->orangTua) {
            $orangTuaUser->orangTua->siswas()->syncWithoutDetaching([$siswa->id]);
        }

        $jadwal = JadwalPelajaran::first();

        $absensi = Absensi::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $jadwal->guru_id,
            'kelas_id' => $jadwal->kelas_id,
            'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
            'tanggal' => Carbon::today()->toDateString(),
            'pertemuan_ke' => 1,
            'materi' => 'Materi Uji Presensi',
            'metode' => 'manual',
            'status' => 'selesai',
        ]);

        $detail = DetailAbsensi::create([
            'absensi_id' => $absensi->id,
            'siswa_id' => $siswa->id,
            'status' => 'alpa',
            'catatan' => 'Tanpa keterangan sama sekali.',
        ]);

        // Panggil Dispatcher
        app(NotificationDispatcherService::class)->dispatchAlpa($detail);

        // Verifikasi notifikasi terkirim ke User Siswa
        if ($siswa->user) {
            Notification::assertSentTo($siswa->user, AlpaPresensiNotification::class);
        }

        // Verifikasi notifikasi terkirim ke User Orang Tua
        if ($orangTuaUser) {
            Notification::assertSentTo($orangTuaUser, AlpaPresensiNotification::class);
        }
    }

    /**
     * F6-03: Pemicu otomatis notifikasi terbitnya nilai e-Rapor ke Siswa dan Orang Tua di kelas terkait.
     */
    public function test_nilai_terbit_dispatches_notification_to_class_members(): void
    {
        Notification::fake();

        $kelas = Kelas::with('siswas.user')->first();
        $tahunAjaran = TahunAjaran::where('is_aktif', true)->first() ?? TahunAjaran::first();

        $siswa = $kelas->siswas->first();
        $orangTuaUser = User::role('orang_tua')->first();
        if ($siswa && $orangTuaUser && $orangTuaUser->orangTua) {
            $orangTuaUser->orangTua->siswas()->syncWithoutDetaching([$siswa->id]);
        }

        app(NotificationDispatcherService::class)->dispatchNilaiTerbit($kelas, $tahunAjaran);

        if ($siswa && $siswa->user) {
            Notification::assertSentTo($siswa->user, NilaiTerbitNotification::class);
        }

        if ($orangTuaUser) {
            Notification::assertSentTo($orangTuaUser, NilaiTerbitNotification::class);
        }
    }

    /**
     * F6-03: Pemicu otomatis notifikasi pembukaan sesi QR ke seluruh Siswa kelas bersangkutan.
     */
    public function test_sesi_qr_dibuka_dispatches_notification_to_students(): void
    {
        Notification::fake();

        $jadwal = JadwalPelajaran::with('kelas.siswas.user')->first();
        $absensi = Absensi::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $jadwal->guru_id,
            'kelas_id' => $jadwal->kelas_id,
            'mata_pelajaran_id' => $jadwal->mata_pelajaran_id,
            'tanggal' => Carbon::today()->toDateString(),
            'pertemuan_ke' => 2,
            'materi' => 'Praktikum QR Code',
            'metode' => 'qr',
            'status' => 'aktif',
        ]);

        $sesiQr = SesiAbsensiQr::create([
            'jadwal_pelajaran_id' => $jadwal->id,
            'guru_id' => $jadwal->guru_id,
            'absensi_id' => $absensi->id,
            'tanggal' => Carbon::today()->toDateString(),
            'dibuka_pada' => now(),
            'secret' => 'SECRETKEY987',
            'durasi_token_detik' => 30,
            'status' => 'aktif',
        ]);

        app(NotificationDispatcherService::class)->dispatchSesiQr($sesiQr);

        $siswa = $jadwal->kelas->siswas->first();
        if ($siswa && $siswa->user) {
            Notification::assertSentTo($siswa->user, SesiQrDibukaNotification::class);
        }
    }

    /**
     * F6-04: WhatsApp Service mengembalikan adapter log default di lingkungan pengujian/lokal.
     */
    public function test_whatsapp_service_uses_log_adapter_by_default(): void
    {
        /** @var WhatsAppService $service */
        $service = app(WhatsAppService::class);

        $this->assertEquals('log', $service->adapterName());
        $this->assertInstanceOf(LogWhatsAppAdapter::class, $service->adapter());

        $sent = $service->send('081234567890', 'Pesan Uji WhatsApp SIAKAD');
        $this->assertTrue($sent);
    }

    /**
     * F6-05: API endpoint unread count mengembalikan struktur JSON yang valid untuk polling Topbar Bell.
     */
    public function test_unread_count_api_returns_valid_json(): void
    {
        $guru = User::role('guru')->first();

        $guru->notify(new TestNotification());

        $response = $this->actingAs($guru)->getJson(route('notifications.unread-count'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'unread_count',
            'notifications' => [
                '*' => ['id', 'title', 'message', 'url', 'time_ago', 'read', 'color'],
            ],
        ]);
        $this->assertGreaterThanOrEqual(1, $response->json('unread_count'));
    }
}
