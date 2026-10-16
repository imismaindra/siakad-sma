<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\DetailAbsensi;
use App\Models\Guru;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_with_permission_can_access_audit_log_page(): void
    {
        $admin = User::role('admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.audit-log.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Audit Log & Jejak Aktivitas');
        $response->assertSeeText('Ekspor CSV');
    }

    public function test_guru_cannot_access_audit_log_page(): void
    {
        $guru = User::role('guru')->first();

        $response = $this->actingAs($guru)->get(route('admin.audit-log.index'));

        $response->assertStatus(403);
    }

    public function test_user_changes_are_logged(): void
    {
        $admin = User::role('admin')->first();
        $user = User::factory()->create(['name' => 'Original Name']);

        $this->actingAs($admin);
        $user->update(['name' => 'Updated Name']);

        $log = Activity::forSubject($user)->orderByDesc('id')->first();

        $this->assertNotNull($log);
        $this->assertEquals('user', $log->log_name);
        $this->assertEquals('updated', $log->event);
        $this->assertEquals('Original Name', $log->properties['old']['name'] ?? null);
        $this->assertEquals('Updated Name', $log->properties['attributes']['name'] ?? null);
    }

    public function test_nilai_changes_are_logged(): void
    {
        $admin = User::role('admin')->first();
        $nilai = Nilai::first();

        if ($nilai) {
            $this->actingAs($admin);
            $nilai->update(['deskripsi' => 'Catatan nilai baru']);

            $log = Activity::forSubject($nilai)->orderByDesc('id')->first();

            $this->assertNotNull($log);
            $this->assertEquals('nilai', $log->log_name);
            $this->assertEquals('updated', $log->event);
            $this->assertEquals('Catatan nilai baru', $log->properties['attributes']['deskripsi'] ?? null);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_role_changes_are_logged(): void
    {
        $admin = User::role('admin')->first();
        $role = Role::firstOrCreate(['name' => 'staf-tu', 'guard_name' => 'web']);

        $response = $this->actingAs($admin)->put(route('admin.roles.update', $role), [
            'name' => 'staf-tu-updated',
            'permissions' => ['view-profile'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));

        $log = Activity::where('log_name', 'role_permission')->latest()->first();

        $this->assertNotNull($log);
        $this->assertEquals('role_permission', $log->log_name);
        $this->assertEquals('updated', $log->event);
    }

    public function test_activity_logs_include_ip_and_user_agent(): void
    {
        $admin = User::role('admin')->first();
        $user = User::factory()->create(['name' => 'Testing IP Log', 'role' => 'siswa']);

        $response = $this->actingAs($admin)
            ->withHeaders([
                'X-Forwarded-For' => '203.0.113.195',
                'User-Agent' => 'SIAKAD-Testing-Browser/2.0',
            ])
            ->put(route('admin.user.update', $user), [
                'name' => 'Testing IP Log Updated',
                'email' => $user->email,
                'role' => 'siswa',
                'is_active' => true,
            ]);

        $response->assertSessionHasNoErrors();

        $log = Activity::forSubject($user)->where('event', 'updated')->latest()->first();

        $this->assertNotNull($log);
        $this->assertNotNull($log->properties);
        $this->assertEquals('203.0.113.195', $log->properties['ip'] ?? null);
        $this->assertEquals('SIAKAD-Testing-Browser/2.0', $log->properties['user_agent'] ?? null);
    }

    public function test_admin_can_filter_audit_log_by_event_and_module(): void
    {
        $admin = User::role('admin')->first();

        activity('nilai')
            ->causedBy($admin)
            ->event('updated')
            ->log('Pembaruan nilai ujian');

        activity('absensi')
            ->causedBy($admin)
            ->event('override')
            ->log('Override presensi siswa');

        $response = $this->actingAs($admin)->get(route('admin.audit-log.index', [
            'log_name' => 'absensi',
            'event' => 'override',
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Override presensi siswa');
        $response->assertDontSeeText('Pembaruan nilai ujian');
    }

    public function test_admin_can_view_audit_log_detail_json(): void
    {
        $admin = User::role('admin')->first();

        $activity = activity('pengaturan')
            ->causedBy($admin)
            ->event('updated')
            ->withProperties(['radius' => 150, 'ip' => '10.0.0.1'])
            ->log('Memperbarui radius sekolah');

        $response = $this->actingAs($admin)->getJson(route('admin.audit-log.show', $activity));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $activity->id,
            'log_name' => 'pengaturan',
            'event' => 'updated',
            'ip' => '10.0.0.1',
            'description' => 'Memperbarui radius sekolah',
        ]);
    }

    public function test_admin_can_export_audit_log_to_csv(): void
    {
        $admin = User::role('admin')->first();

        activity('absensi')
            ->causedBy($admin)
            ->event('override')
            ->log('Tes ekspor CSV override');

        $response = $this->actingAs($admin)->get(route('admin.audit-log.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Tes ekspor CSV override', $response->streamedContent());
        $this->assertStringContainsString('IP Address', $response->streamedContent());
    }

    public function test_guru_attendance_override_is_logged_with_reason_and_actor(): void
    {
        $guruUser = User::role('guru')->first();
        $guru = Guru::where('user_id', $guruUser->id)->first();
        $siswa = Siswa::first();
        $absensi = Absensi::where('guru_id', $guru->id)->first();

        if (! $absensi) {
            $absensi = Absensi::create([
                'kelas_id' => $siswa->kelas_id,
                'guru_id' => $guru->id,
                'mata_pelajaran_id' => 1,
                'tanggal' => today(),
                'metode' => 'manual',
            ]);
        }

        $response = $this->actingAs($guruUser)->post(route('guru.absensi.override', [
            'absensi' => $absensi->id,
            'siswa' => $siswa->id,
        ]), [
            'status' => 'hadir',
            'alasan_override' => 'GPS bermasalah di lantai 3 gedung utama',
        ]);

        $response->assertRedirect();

        $log = Activity::where('event', 'override')
            ->where('log_name', 'absensi')
            ->latest()
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals($guruUser->id, $log->causer_id);
        $this->assertEquals('GPS bermasalah di lantai 3 gedung utama', $log->properties['alasan'] ?? null);
        $this->assertEquals('hadir', $log->properties['new_status'] ?? null);
    }
}
