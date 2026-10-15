<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaliKelasValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_cannot_assign_guru_who_is_already_wali_kelas_in_same_tahun_ajaran(): void
    {
        $admin = User::role('admin')->first();
        $tahunAjaran = TahunAjaran::aktif()->first();
        $jurusan = Jurusan::first();
        
        // Find teacher Budi who is already wali kelas of X IPA 1 from seeder
        $budi = User::where('email', 'budi@siakad.sch.id')->first();

        // Attempt to create another class with Budi as wali kelas
        $response = $this->actingAs($admin)
            ->post(route('admin.kelas.store'), [
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jurusan_id' => $jurusan->id,
                'wali_kelas_id' => $budi->id,
                'tingkat' => 'X',
                'nomor' => 2,
                'kapasitas' => 36,
            ]);

        $response->assertSessionHasErrors(['wali_kelas_id']);
    }

    public function test_can_assign_guru_who_is_not_wali_kelas_yet(): void
    {
        $admin = User::role('admin')->first();
        $tahunAjaran = TahunAjaran::aktif()->first();
        $jurusan = Jurusan::first();
        
        // Create a new teacher who is not wali kelas
        $guruBaru = User::factory()->create([
            'role' => 'guru',
            'is_active' => true,
        ]);
        $guruBaru->assignRole('guru');

        $response = $this->actingAs($admin)
            ->post(route('admin.kelas.store'), [
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jurusan_id' => $jurusan->id,
                'wali_kelas_id' => $guruBaru->id,
                'tingkat' => 'X',
                'nomor' => 2,
                'kapasitas' => 36,
            ]);

        $response->assertRedirect(route('admin.kelas.index'));
        $this->assertDatabaseHas('kelas', [
            'wali_kelas_id' => $guruBaru->id,
            'tingkat' => 'X',
            'nomor' => 2,
        ]);
    }

    public function test_can_update_kelas_keeping_its_own_wali_kelas(): void
    {
        $admin = User::role('admin')->first();
        $kelas1 = Kelas::first(); // Budi is wali kelas of $kelas1

        $response = $this->actingAs($admin)
            ->put(route('admin.kelas.update', $kelas1), [
                'jurusan_id' => $kelas1->jurusan_id,
                'wali_kelas_id' => $kelas1->wali_kelas_id,
                'tingkat' => $kelas1->tingkat,
                'nomor' => $kelas1->nomor,
                'kapasitas' => 40,
            ]);

        $response->assertRedirect(route('admin.kelas.index'));
        $this->assertEquals(40, $kelas1->fresh()->kapasitas);
    }

    public function test_cannot_create_duplicate_kelas_combination(): void
    {
        $admin = User::role('admin')->first();
        $kelasExisting = Kelas::first(); // E.g. X IPA 1

        $response = $this->actingAs($admin)
            ->post(route('admin.kelas.store'), [
                'tahun_ajaran_id' => $kelasExisting->tahun_ajaran_id,
                'jurusan_id' => $kelasExisting->jurusan_id,
                'wali_kelas_id' => null,
                'tingkat' => $kelasExisting->tingkat,
                'nomor' => $kelasExisting->nomor,
                'kapasitas' => 36,
            ]);

        $response->assertSessionHasErrors(['nomor']);
    }

    public function test_cannot_update_kelas_to_duplicate_combination(): void
    {
        $admin = User::role('admin')->first();
        $allKelas = Kelas::take(2)->get();
        $kelas1 = $allKelas[0]; // E.g. X IPA 1
        $kelas2 = $allKelas[1]; // E.g. X IPS 1

        // Attempting to update $kelas2 to have same jurusan, tingkat, and nomor as $kelas1
        $response = $this->actingAs($admin)
            ->put(route('admin.kelas.update', $kelas2), [
                'jurusan_id' => $kelas1->jurusan_id,
                'wali_kelas_id' => null,
                'tingkat' => $kelas1->tingkat,
                'nomor' => $kelas1->nomor,
                'kapasitas' => 36,
            ]);

        $response->assertSessionHasErrors(['nomor']);
    }
}
