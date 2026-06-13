# SIAKAD SMA — Sistem Informasi Akademik Sekolah Menengah Atas

SIAKAD SMA adalah platform administrasi akademik berbasis web modern yang dirancang untuk mendigitalisasi, mengotomatisasi, dan menyederhanakan alur kerja di sekolah menengah atas. Sistem ini menjembatani komunikasi dan operasional data tiga aktor utama sekolah: **Tata Usaha/Admin**, **Guru**, dan **Siswa**.

SIAKAD SMA memfasilitasi pengelolaan data sekolah mulai dari pembagian kelas, penjadwalan pelajaran, pencatatan absensi harian per sesi kelas, input nilai berkurikulum hingga publikasi dan ekspor rapor digital (e-Rapor) berformat PDF resmi.

---

## 🌟 Fitur Utama (Berdasarkan Role)

### 👤 1. Admin / Tata Usaha (TU)
*   **Dashboard**: Ringkasan data statistik jumlah siswa, guru, kelas, tahun ajaran aktif, dan metrik sekolah lainnya.
*   **Data Master**: CRUD data Tahun Ajaran, Jurusan, Kelas, Mata Pelajaran, Guru, dan Siswa secara lengkap.
*   **Manajemen Siswa**: Pengelolaan registrasi siswa, penempatan kelas, hingga mutasi/kenaikan kelas.
*   **Jadwal Pelajaran**: Penyusunan jadwal pelajaran mingguan per kelas lengkap dengan pendeteksian tabrakan jadwal (*clashing conflict prevention*) untuk guru pengampu.
*   **Absensi & Nilai**: Rekapitulasi absensi bulanan/semester serta verifikasi laporan nilai rapor siswa.
*   **Manajemen Akun**: Kontrol pembuatan akun pengguna dan perubahan kata sandi untuk semua pengguna sistem.

### 👤 2. Guru
*   **Dashboard**: Informasi jadwal mengajar pribadi hari ini, ringkasan kelas perwalian, dan kelas-kelas yang membutuhkan input absensi.
*   **Jadwal Mengajar**: Tampilan kalender jadwal mengajar mingguan secara detail.
*   **Absensi Digital**: Input kehadiran siswa per sesi pelajaran secara instan (Hadir, Sakit, Izin, Alpa).
*   **Input Nilai & Deskripsi**: Pengisian nilai harian, UTS, UAS, konfigurasi bobot nilai, serta pengisian catatan deskripsi capaian pembelajaran per mata pelajaran.
*   **Wali Kelas**: Hak khusus untuk wali kelas dalam meninjau rangkuman nilai kelas perwalian, peringkat siswa, dan mencetak e-Rapor PDF siswa.

### 👤 3. Siswa
*   **Dashboard**: Ringkasan persentase kehadiran pribadi, rata-rata nilai, serta pengumuman penting sekolah.
*   **Jadwal Pelajaran**: Tampilan visual jadwal pelajaran mingguan yang terstruktur.
*   **Riwayat Absensi**: Detail kehadiran harian untuk memantau kedisiplinan belajar.
*   **Rapor Digital (e-Rapor)**: Tinjauan nilai akhir per semester dan tombol cetak/unduh dokumen Rapor Resmi berformat PDF secara langsung setelah difinalisasi oleh sekolah.

---

## 🛠️ Teknologi & Arsitektur

*   **Backend Framework**: [Laravel 11+](https://laravel.com)
*   **Frontend Styling**: [Tailwind CSS v4](https://tailwindcss.com) (integrasi Vite) & Google Fonts (*Inter* & *Plus Jakarta Sans*)
*   **Build Automation**: [Vite](https://vite.dev)
*   **Database Default**: SQLite (Konfigurasi instan tanpa perlu setting server database luar, mendukung migrasi ke MySQL/Postgres)
*   **Ekspor PDF**: Laravel PDF generator terintegrasi.

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan SIAKAD SMA pada komputer lokal Anda:

### 1. Prasyarat Sistem
Pastikan perangkat Anda sudah terpasapang perangkat lunak berikut:
*   PHP >= 8.2 (dilengkapi ekstensi `pdo_sqlite`, `mbstring`, `openssl`, `xml`, dll.)
*   Composer
*   Node.js (LTS recommended) & npm

### 2. Kloning Repository & Setup Depedensi
Buka terminal/command prompt lalu ketik:
```bash
# Clone repository
git clone https://github.com/username/siakad-sma.git
cd siakad-sma

# Install library backend (PHP)
composer install

# Install library frontend (Node.js)
npm install
```

### 3. Konfigurasi Environment File
Salin template konfigurasi `.env` bawaan:
```bash
copy .env.example .env
```
*(Buka file `.env` di text editor. Secara default database menggunakan SQLite, sehingga tidak perlu merubah konfigurasi DB_* kecuali jika Anda ingin memakai MySQL).*

### 4. Database Setup & Seeding
Jalankan migrasi database beserta pembuatan data uji coba awal (seeder):
```bash
# Generate application key
php artisan key:generate

# Jalankan migrasi dan seeder
php artisan migrate --seed
```

### 5. Kompilasi Aset Frontend (Vite)
Jalankan kompilasi aset CSS dan JavaScript agar aset visual tampil dengan optimal:
```bash
# Untuk mode development (hot reload)
npm run dev

# ATAU kompilasi final untuk production
npm run build
```

### 6. Jalankan Local Server
Nyalakan server development lokal Laravel:
```bash
php artisan serve
```
Akses sistem di browser melalui alamat: [http://localhost:8000](http://localhost:8000)

---

## 🔑 Kredensial Login Demo (Default Seeds)

Gunakan akun di bawah ini untuk menguji coba fitur sesuai hak akses (Password untuk semua akun adalah: `password`):

| Peran (Role) | Username / Email | Password | Keterangan |
|---|---|---|---|
| **Admin TU** | `admin@siakad.sch.id` | `password` | Akses penuh manajemen data master |
| **Guru (Wali Kelas X IPA 1)** | `budi@siakad.sch.id` | `password` | Guru Matematika & Wali Kelas |
| **Guru Mata Pelajaran** | `ahmad@siakad.sch.id` | `password` | Guru Fisika & Kimia |
| **Siswa 1 (X IPA 1)** | `andi@siakad.sch.id` | `password` | Mengakses jadwal pribadi, absen, dan rapor |
| **Siswa 2 (X IPA 1)** | `bella@siakad.sch.id` | `password` | Mengakses jadwal pribadi, absen, dan rapor |

---
*Dikembangkan untuk digitalisasi administrasi sekolah yang efisien.*
