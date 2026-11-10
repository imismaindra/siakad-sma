<?php

namespace Tests\Feature;

use App\Models\KalenderAkademik;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Notifications\PengumumanBaruNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PengumumanDanKalenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * F5-01: Admin dapat mengakses antarmuka manajemen pengumuman.
     */
    public function test_admin_can_view_pengumuman_index(): void
    {
        $admin = User::role('admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.pengumuman.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Pengumuman Sekolah');
        $response->assertSeeText('Buat Pengumuman Baru');

        // Test form create
        $createResp = $this->actingAs($admin)->get(route('admin.pengumuman.create'));
        $createResp->assertStatus(200);
        $createResp->assertSeeText('Buat Pengumuman Baru');
    }

    /**
     * F5-01 & F6-04: Admin dapat membuat pengumuman baru dan sistem memicu notifikasi.
     */
    public function test_admin_can_create_pengumuman_and_dispatches_notification(): void
    {
        Notification::fake();

        $admin = User::role('admin')->first();

        $payload = [
            'judul' => 'Maklumat Libur Nasional Hari Guru',
            'konten' => 'Diberitahukan bahwa seluruh kegiatan belajar mengajar diliburkan dalam rangka Hari Guru.',
            'target' => 'semua',
            'is_aktif' => 1,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->actingAs($admin)->post(route('admin.pengumuman.store'), $payload);

        $response->assertRedirect(route('admin.pengumuman.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengumumans', [
            'judul' => 'Maklumat Libur Nasional Hari Guru',
            'target' => 'semua',
            'is_aktif' => true,
        ]);

        Notification::assertSentTo(
            User::role('guru')->get(),
            PengumumanBaruNotification::class
        );
    }

    /**
     * F5-01: Admin dapat memperbarui dan menghapus pengumuman.
     */
    public function test_admin_can_update_and_delete_pengumuman(): void
    {
        $admin = User::role('admin')->first();
        $pengumuman = Pengumuman::first();

        // View edit form
        $editResp = $this->actingAs($admin)->get(route('admin.pengumuman.edit', $pengumuman));
        $editResp->assertStatus(200);
        $editResp->assertSeeText('Edit Pengumuman');

        // Update
        $response = $this->actingAs($admin)->put(route('admin.pengumuman.update', $pengumuman), [
            'judul' => 'Judul Pengumuman Diperbarui',
            'konten' => 'Isi konten pengumuman telah direvisi oleh tim kurikulum.',
            'target' => 'guru',
            'is_aktif' => 1,
        ]);

        $response->assertRedirect(route('admin.pengumuman.index'));
        $this->assertDatabaseHas('pengumumans', [
            'id' => $pengumuman->id,
            'judul' => 'Judul Pengumuman Diperbarui',
            'target' => 'guru',
        ]);

        // Delete
        $delResponse = $this->actingAs($admin)->delete(route('admin.pengumuman.destroy', $pengumuman));
        $delResponse->assertRedirect(route('admin.pengumuman.index'));
        $this->assertDatabaseMissing('pengumumans', ['id' => $pengumuman->id]);
    }

    /**
     * F5-01: Admin dapat beralih status tayang pengumuman (toggle aktif).
     */
    public function test_admin_can_toggle_pengumuman_aktif(): void
    {
        $admin = User::role('admin')->first();
        $pengumuman = Pengumuman::create([
            'user_id' => $admin->id,
            'judul' => 'Pengumuman Draf',
            'konten' => 'Isi draf pengumuman...',
            'target' => 'semua',
            'is_aktif' => false,
            'published_at' => null,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.pengumuman.toggle-aktif', $pengumuman));
        $response->assertStatus(302);

        $this->assertTrue($pengumuman->fresh()->is_aktif);
        $this->assertNotNull($pengumuman->fresh()->published_at);
    }

    /**
     * F5-02 & F5-03: Guru hanya dapat melihat pengumuman untuk Guru atau Semua.
     */
    public function test_guru_can_view_appropriate_pengumuman(): void
    {
        $guruUser = User::role('guru')->first();

        $response = $this->actingAs($guruUser)->get(route('guru.pengumuman.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Pengumuman Sekolah');
    }

    /**
     * F5-02 & F5-03: Siswa dapat melihat pengumuman kelasnya dan umum.
     */
    public function test_siswa_can_view_appropriate_pengumuman(): void
    {
        $siswaUser = User::role('siswa')->first();

        $response = $this->actingAs($siswaUser)->get(route('siswa.pengumuman.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Pengumuman & Agenda');
    }

    /**
     * F5-04: Admin dapat mengelola agenda kalender akademik (tambah & hapus).
     */
    public function test_admin_can_manage_kalender_akademik(): void
    {
        $admin = User::role('admin')->first();
        $ta = TahunAjaran::aktif()->first();

        // View index
        $indexResp = $this->actingAs($admin)->get(route('admin.kalender.index'));
        $indexResp->assertStatus(200);
        $indexResp->assertSeeText('Kalender Akademik');

        // Store
        $storeResp = $this->actingAs($admin)->post(route('admin.kalender.store'), [
            'judul' => 'Pekan Penilaian Sumatif Akhir Jenjang',
            'kategori' => 'ujian',
            'tanggal_mulai' => now()->addDays(2)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(7)->format('Y-m-d'),
            'target' => 'semua',
            'warna' => 'amber',
            'is_libur' => 0,
            'is_aktif' => 1,
            'tahun_ajaran_id' => $ta->id,
            'deskripsi' => 'Ujian akhir untuk kelas XII.',
        ]);

        $storeResp->assertStatus(302);
        $this->assertDatabaseHas('kalender_akademiks', [
            'judul' => 'Pekan Penilaian Sumatif Akhir Jenjang',
            'kategori' => 'ujian',
        ]);
    }

    /**
     * F5-05: Seluruh peran (Guru, Siswa, Orang Tua) dapat melihat tampilan kalender akademik.
     */
    public function test_all_roles_can_view_kalender_akademik(): void
    {
        // 1. Guru
        $guru = User::role('guru')->first();
        $this->actingAs($guru)->get(route('guru.kalender'))->assertStatus(200)->assertSeeText('Kalender Akademik');

        // 2. Siswa
        $siswa = User::role('siswa')->first();
        $this->actingAs($siswa)->get(route('siswa.kalender'))->assertStatus(200)->assertSeeText('Kalender Akademik');

        // 3. Orang Tua
        $ortu = User::role('orang_tua')->first();
        $this->actingAs($ortu)->get(route('orang-tua.kalender'))->assertStatus(200)->assertSeeText('Kalender Akademik');
    }
}
