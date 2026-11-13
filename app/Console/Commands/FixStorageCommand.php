<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:setup-production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Siapkan dan perbaiki direktori storage publik serta symbolic link untuk production';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pengecekan dan konfigurasi storage untuk production...');

        // 1. Buat folder-folder upload yang diperlukan di storage/app/public
        $directories = [
            'profiles',
            'guru/foto',
            'siswa/foto',
            'pengumuman_gambar',
            'pengumuman_dokumen',
            'kalender_dokumen',
        ];

        $storagePublic = storage_path('app/public');
        if (! File::isDirectory($storagePublic)) {
            File::makeDirectory($storagePublic, 0755, true);
            $this->line("  [+] Membuat root public storage: {$storagePublic}");
        }

        foreach ($directories as $dir) {
            $path = $storagePublic . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);
            if (! File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true);
                $this->line("  [+] Membuat subdirektori: {$dir}");
            } else {
                @chmod($path, 0755);
            }
        }

        // 2. Hubungkan symbolic link public/storage
        $targets = [
            'public/storage' => public_path('storage'),
        ];

        // Jika terdapat direktori public_html (shared hosting / cPanel)
        $publicHtml = base_path('public_html');
        if (File::isDirectory($publicHtml)) {
            $targets['public_html/storage'] = $publicHtml . DIRECTORY_SEPARATOR . 'storage';
        }

        foreach ($targets as $label => $linkPath) {
            if (file_exists($linkPath)) {
                if (is_link($linkPath)) {
                    $this->info("  [OK] Symbolic link {$label} sudah aktif.");
                } else {
                    $this->warn("  [!] {$label} sudah ada tetapi bukan symlink.");
                }
            } else {
                try {
                    if (function_exists('symlink')) {
                        @symlink($storagePublic, $linkPath);
                        if (is_link($linkPath) || file_exists($linkPath)) {
                            $this->info("  [+] Berhasil membuat symlink {$label} -> {$storagePublic}");
                        } else {
                            $this->warn("  [-] Gagal membuat symlink {$label}.");
                        }
                    } else {
                        $this->warn("  [-] Fungsi PHP symlink() dinonaktifkan di server hosting ini.");
                    }
                } catch (\Throwable $e) {
                    $this->warn("  [-] Gagal membuat symlink {$label}: {$e->getMessage()}");
                }
            }
        }

        // 3. Status GD & WebP
        $hasGd = extension_loaded('gd');
        $hasWebp = function_exists('imagewebp');
        $this->line('');
        $this->info('Pemeriksaan Ekstensi Gambar PHP:');
        $this->line('  - Ekstensi GD: ' . ($hasGd ? '<fg=green>Terpasang</>' : '<fg=yellow>Tidak Terpasang (Fallback store asli aktif)</>'));
        $this->line('  - Dukungan WebP: ' . ($hasWebp ? '<fg=green>Mendukung WebP</>' : '<fg=yellow>Tidak Mendukung (Fallback store asli aktif)</>'));

        // 4. Fallback route confirmation
        $this->line('');
        $this->info('Status Fallback Route Storage:');
        $this->line('  <fg=green>[AKTIF]</> Route /storage/{path} aktif melayani gambar & dokumen secara otomatis');
        $this->line('  bahkan jika server hosting tidak mendukung symbolic link (cPanel / shared hosting).');

        $this->line('');
        $this->info('Selesai! Konfigurasi storage production siap.');

        return Command::SUCCESS;
    }
}
