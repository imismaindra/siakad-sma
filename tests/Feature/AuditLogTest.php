<?php

namespace Tests\Feature;

use App\Models\Nilai;
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
}
