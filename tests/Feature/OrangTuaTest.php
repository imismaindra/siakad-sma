<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\DetailAbsensi;
use App\Models\Nilai;
use App\Models\OrangTua;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class OrangTuaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * F4-01: Admin dapat melihat daftar akun orang tua / wali.
     */
    public function test_admin_can_view_orang_tua_index(): void
    {
        $admin = User::role('admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.orang-tua.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Data Orang Tua');
        $response->assertSeeText('Hermawan Wijaya');
    }

    /**
     * F4-01: Admin dapat membuat akun orang tua baru dan menautkan banyak anak (siswa).
     */
    public function test_admin_can_create_orang_tua_with_multiple_children(): void
    {
        $admin = User::role('admin')->first();
        $siswas = Siswa::take(2)->get();
        $this->assertCount(2, $siswas);

        $payload = [
            'nama_lengkap'          => 'Ibu Suryati',
            'email'                 => 'suryati@siakad.sch.id',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'jenis_kelamin'         => 'P',
            'no_telepon'            => '081399887766',
            'pekerjaan'             => 'PNS',
            'alamat'                => 'Jl. Diponegoro No. 10',
            'anak'                  => [
                ['siswa_id' => $siswas[0]->id, 'hubungan' => 'ibu'],
                ['siswa_id' => $siswas[1]->id, 'hubungan' => 'ibu'],
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.orang-tua.store'), $payload);

        $response->assertRedirect(route('admin.orang-tua.index'));
        $response->assertSessionHas('success');

        // Pastikan User & OrangTua terbuat
        $this->assertDatabaseHas('users', [
            'email' => 'suryati@siakad.sch.id',
            'role'  => UserRole::OrangTua->value,
        ]);

        $orangTua = OrangTua::where('nama_lengkap', 'Ibu Suryati')->first();
        $this->assertNotNull($orangTua);
        $this->assertCount(2, $orangTua->siswas);
        $this->assertEquals('ibu', $orangTua->siswas->first()->pivot->hubungan);

        // Pastikan terekam di Audit Log
        $log = Activity::where('log_name', 'orang_tua')->latest('id')->first();
        $this->assertNotNull($log);
        $this->assertStringContainsString('Ibu Suryati', $log->description);
    }

    /**
     * F4-01: Admin dapat memperbarui profil orang tua dan relasi anak.
     */
    public function test_admin_can_update_orang_tua_and_sync_children(): void
    {
        $admin = User::role('admin')->first();
        $orangTua = OrangTua::first();
        $siswaBaru = Siswa::skip(1)->first();

        $payload = [
            'nama_lengkap'  => 'Hermawan Wijaya Updated',
            'email'         => $orangTua->user->email,
            'jenis_kelamin' => 'L',
            'no_telepon'    => '081299998888',
            'pekerjaan'     => 'Direktur',
            'alamat'        => 'Jl. Merdeka Baru',
            'anak'          => [
                ['siswa_id' => $siswaBaru->id, 'hubungan' => 'wali'],
            ],
        ];

        $response = $this->actingAs($admin)->put(route('admin.orang-tua.update', $orangTua), $payload);

        $response->assertRedirect(route('admin.orang-tua.index'));
        $this->assertDatabaseHas('orang_tuas', ['nama_lengkap' => 'Hermawan Wijaya Updated']);

        $orangTua->refresh();
        $this->assertEquals(1, $orangTua->siswas()->count());
        $this->assertEquals($siswaBaru->id, $orangTua->siswas()->first()->id);
        $this->assertEquals('wali', $orangTua->siswas()->first()->pivot->hubungan);
    }

    /**
     * F4-01: Admin dapat menghapus akun orang tua beserta relasinya.
     */
    public function test_admin_can_delete_orang_tua(): void
    {
        $admin = User::role('admin')->first();
        $orangTua = OrangTua::first();
        $userId = $orangTua->user_id;

        $response = $this->actingAs($admin)->delete(route('admin.orang-tua.destroy', $orangTua));

        $response->assertRedirect(route('admin.orang-tua.index'));
        $this->assertDatabaseMissing('orang_tuas', ['id' => $orangTua->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * F4-02: Orang Tua dapat login dan diarahkan ke dashboard pemantauan anak.
     */
    public function test_orang_tua_can_login_and_redirect_to_dashboard(): void
    {
        $response = $this->post(route('login.post'), [
            'email'    => 'ortu@siakad.sch.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('orang-tua.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * F4-02 & F4-03: Orang Tua dapat melihat dashboard anak dengan data jadwal, absensi, nilai.
     */
    public function test_orang_tua_can_view_linked_child_dashboard(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();
        $ortu = $ortuUser->orangTua;
        $child = $ortu->siswas->first();

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText($child->nama_lengkap);
        $response->assertSeeText('Tingkat Hadir');
        $response->assertSeeText('Jadwal Pelajaran Hari Ini');
        $response->assertSeeText('Perkembangan Nilai Akademik');
    }

    /**
     * F4-02 & F4-04: Orang Tua dapat beralih anak aktif jika menautkan lebih dari 1 anak.
     */
    public function test_orang_tua_can_switch_active_child_if_has_multiple_children(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();
        $ortu = $ortuUser->orangTua;

        // Tautkan anak kedua ke orang tua
        $secondChild = Siswa::skip(1)->first();
        $ortu->siswas()->attach($secondChild->id, ['hubungan' => 'ayah']);

        $response = $this->actingAs($ortuUser)->post(route('orang-tua.switch-anak', $secondChild));

        $response->assertRedirect();
        $this->assertEquals($secondChild->id, session('active_anak_id'));

        // Cek dashboard menampilkan nama anak kedua
        $dashboardResponse = $this->actingAs($ortuUser)->get(route('orang-tua.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSeeText($secondChild->nama_lengkap);
    }

    /**
     * F4-04: Orang Tua ditolak (403) jika mencoba beralih ke siswa yang tidak tertaut.
     */
    public function test_orang_tua_forbidden_from_switching_to_unlinked_child(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();
        $unlinkedSiswa = Siswa::skip(1)->first(); // Tidak tertaut ke $ortu

        $response = $this->actingAs($ortuUser)->post(route('orang-tua.switch-anak', $unlinkedSiswa));

        $response->assertStatus(403);
    }

    /**
     * F4-03: Orang Tua dapat melihat jadwal pelajaran mingguan anak.
     */
    public function test_orang_tua_can_view_jadwal(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.jadwal'));

        $response->assertStatus(200);
        $response->assertSeeText('Jadwal Pelajaran Mingguan');
    }

    /**
     * F4-03: Orang Tua dapat melihat riwayat presensi dan kehadiran anak.
     */
    public function test_orang_tua_can_view_absensi(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.absensi'));

        $response->assertStatus(200);
        $response->assertSeeText('Presensi & Kehadiran Anak');
        $response->assertSeeText('Persentase Hadir');
    }

    /**
     * F4-03: Orang Tua dapat melihat nilai akademik semester berjalan.
     */
    public function test_orang_tua_can_view_nilai(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.nilai'));

        $response->assertStatus(200);
        $response->assertSeeText('Nilai Akademik & e-Rapor');
    }

    /**
     * F4-05: Orang Tua dapat melihat pengumuman umum dan pengumuman kelas anak.
     */
    public function test_orang_tua_can_view_pengumuman(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.pengumuman'));

        $response->assertStatus(200);
        $response->assertSeeText('Pengumuman & Informasi Sekolah');

        $pengumuman = Pengumuman::first();
        if ($pengumuman) {
            $showResponse = $this->actingAs($ortuUser)->get(route('orang-tua.pengumuman.show', $pengumuman));
            $showResponse->assertStatus(200);
            $showResponse->assertSeeText($pengumuman->judul);
        }
    }

    /**
     * F4-03: Orang Tua dapat mengunduh e-Rapor PDF anak jika semua nilai sudah final.
     */
    public function test_orang_tua_can_download_finalized_rapor_pdf(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();
        $child = $ortuUser->orangTua->siswas->first();
        $tahun = TahunAjaran::aktif()->first();

        // Buat atau tandai nilai final untuk anak
        Nilai::where('siswa_id', $child->id)
            ->where('tahun_ajaran_id', $tahun->id)
            ->update(['is_final' => true]);

        // Jika belum ada nilai, buat satu nilai final
        if (Nilai::where('siswa_id', $child->id)->where('tahun_ajaran_id', $tahun->id)->count() === 0) {
            $guru = User::role('guru')->first();
            $mapel = \App\Models\MataPelajaran::first();
            Nilai::create([
                'siswa_id'        => $child->id,
                'guru_id'         => $guru->id,
                'mata_pelajaran_id' => $mapel->id,
                'tahun_ajaran_id' => $tahun->id,
                'nilai_akhir'     => 85,
                'predikat'        => 'A',
                'is_final'        => true,
            ]);
        }

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.nilai.rapor-pdf', $child));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /**
     * F4-04: Orang Tua dilarang mengunduh e-Rapor untuk siswa yang BUKAN anaknya (HTTP 403 via AnakPolicy).
     */
    public function test_orang_tua_cannot_download_rapor_pdf_for_unlinked_child(): void
    {
        $ortuUser = User::where('email', 'ortu@siakad.sch.id')->first();
        $unlinkedChild = Siswa::skip(1)->first(); // Bukan anak ortu

        $response = $this->actingAs($ortuUser)->get(route('orang-tua.nilai.rapor-pdf', $unlinkedChild));

        $response->assertStatus(403);
    }

    /**
     * F4-04: Role lain tanpa hak akses orang tua (misal siswa) ditolak mengakses portal orang tua.
     */
    public function test_non_orang_tua_cannot_access_parent_portal(): void
    {
        $siswaUser = User::role('siswa')->first();

        $response = $this->actingAs($siswaUser)->get(route('orang-tua.dashboard'));

        $response->assertStatus(403);
    }
}
