<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_export_jadwal_pdf(): void
    {
        $admin = User::where('role', 'admin')->first();
        $kelas = Kelas::first();

        $response = $this->actingAs($admin)->get(route('admin.jadwal.export-pdf', [
            'kelas_id' => $kelas->id,
        ]));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-type'), 'application/pdf'),
            'Response header should be application/pdf'
        );
    }

    public function test_admin_can_export_peringkat_kelas_pdf(): void
    {
        $admin = User::where('role', 'admin')->first();
        $kelas = Kelas::first();

        $response = $this->actingAs($admin)->get(route('admin.nilai.peringkat.export', [
            'kelas_id' => $kelas->id,
        ]));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-type'), 'application/pdf'),
            'Response header should be application/pdf'
        );
    }
}
