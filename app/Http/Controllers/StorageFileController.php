<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFileController extends Controller
{
    /**
     * Layani file publik (gambar profil, banner pengumuman, dokumen lampiran, dll)
     * langsung dari storage/app/public jika symbolic link public/storage belum dibuat
     * atau tidak didukung oleh hosting production (seperti cPanel / shared hosting).
     */
    public function show(string $path): BinaryFileResponse
    {
        // Cegah path traversal (keamanan dari directory traversal attack)
        if (str_contains($path, '..') || str_starts_with($path, '/') || str_starts_with($path, '\\')) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);

        $mimeType = $disk->mimeType($path) ?: (@mime_content_type($fullPath) ?: 'application/octet-stream');

        // Header respons optimal untuk performa & caching browser
        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ];

        return response()->file($fullPath, $headers);
    }
}
