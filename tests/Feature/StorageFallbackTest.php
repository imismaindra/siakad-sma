<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageFallbackTest extends TestCase
{
    public function test_can_serve_file_from_public_storage(): void
    {
        Storage::disk('public')->put('testing/sample.txt', 'Isi file sample');

        $response = $this->get('/storage/testing/sample.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('max-age=31536000', (string) $response->headers->get('Cache-Control'));
        $this->assertEquals(
            'Isi file sample',
            file_get_contents($response->getFile()->getPathname())
        );

        Storage::disk('public')->delete('testing/sample.txt');
    }

    public function test_returns_404_for_non_existent_file(): void
    {
        $response = $this->get('/storage/non_existent_file_12345.jpg');

        $response->assertStatus(404);
    }

    public function test_blocks_path_traversal(): void
    {
        $response = $this->get('/storage/../.env');

        $response->assertStatus(404);
    }
}
