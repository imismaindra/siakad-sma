# SIAKAD SMA — Sistem Informasi Akademik Sekolah Menengah Atas

SIAKAD SMA adalah platform administrasi akademik berbasis web modern yang dirancang untuk mendigitalisasi, mengotomatisasi, dan menyederhanakan tata kelola sekolah menengah atas. Sistem ini menjembatani komunikasi dan alur operasional tiga aktor utama sekolah: **Tata Usaha / Admin**, **Guru (Pengajar & Wali Kelas)**, dan **Siswa**.

SIAKAD SMA memfasilitasi pengelolaan terpadu mulai dari pembagian kelas, penyusunan jadwal pelajaran bebas bentrok, pencatatan absensi per sesi, pengolahan nilai terbobot berstandar Kurikulum Merdeka, hingga publikasi dan unduhan e-Rapor resmi berformat PDF.

---

## 🌟 Fitur Utama (Berdasarkan Role)

### 👤 1. Admin / Tata Usaha (TU)
*   **Dashboard Terintegrasi**: Statistik real-time jumlah siswa, guru, kelas, tahun ajaran aktif, metrik kehadiran, serta ringkasan progres nilai.
*   **Data Master Komprehensif**: CRUD lengkap Tahun Ajaran (dengan kontrol status aktif global), Jurusan, Kelas, Mata Pelajaran, Guru, dan Siswa.
*   **Validasi Integritas Kelas & Wali Kelas**:
    *   Pencegahan guru yang sama ditugaskan sebagai wali kelas di lebih dari satu kelas pada tahun ajaran yang sama.
    *   Pencegahan duplikasi kombinasi Tingkat, Jurusan, dan Nomor Kelas.
*   **Jadwal Bebas Tabrakan (*Clashing-Free Scheduling*)**:
    *   Pendeteksian tabrakan jadwal otomatis (*time interval overlap detection*) agar guru tidak terplot pada jam yang sama di kelas berbeda.
    *   Tampilan Jadwal mode Grid/Kalender visual dan mode Tabel tabular dengan pencarian & filter hari/kelas.
*   **Konfigurasi Bobot Nilai Interaktif**:
    *   Pengaturan bobot fleksibel per mata pelajaran (Tugas/Harian, UTS, UAS).
    *   Indikator live kalkulasi total persentase bobot dengan visualisasi validasi 100%.
*   **Monitoring Absensi & Koreksi**: Rekapitulasi kehadiran semesteran per kelas serta kemampuan koreksi data kehadiran siswa.
*   **Laporan & Finalisasi Nilai Rapor**:
    *   Verifikasi nilai rapor per kelas dan kunci/finalisasi nilai secara massal.
    *   Peringkat siswa otomatis berdasarkan nilai akhir rata-rata kelas.
    *   Ekspor e-Rapor PDF per siswa maupun cetak kolektif per kelas (*batch export*).
*   **Dynamic RBAC (Role-Based Access Control)**:
    *   Manajemen Role dan Permission dinamis via web menggunakan [spatie/laravel-permission](https://spatie.be/docs/laravel-permission).
    *   Penetapan izin fitur per role tanpa perlu mengubah kode sumber.
*   **Manajemen Pengumuman Multi-Target**: Publikasi pengumuman sekolah dengan target audiens fleksibel (Semua Pengguna, Khusus Guru, Khusus Siswa, atau Khusus Kelas Tertentu).

### 👤 2. Guru
*   **Dashboard Guru**: Jadwal mengajar aktif hari ini, ringkasan kelas perwalian, pengumuman sekolah, dan daftar kelas yang belum diabsen.
*   **Jadwal Mengajar Pribadi**: Kalender jadwal mengajar mingguan yang terstruktur.
*   **Absensi Digital Per Sesi**: Input kehadiran siswa per jam pelajaran secara instan (Hadir, Sakit, Izin, Alpa) dengan perhitungan otomatis.
*   **Input Nilai & Deskripsi Capaian**:
    *   Input nilai harian (tugas, kuis, ulangan harian), UTS, dan UAS.
    *   Input catatan deskripsi capaian kompetensi per siswa.
    *   Normalisasi otomatis nilai akhir jika komponen nilai belum lengkap.
*   **Hak Khusus Wali Kelas**:
    *   Rangkuman nilai kelas perwalian dan peringkat siswa.
    *   Pengelolaan catatan prestasi dan kegiatan ekstrakurikuler siswa.
    *   Cetak e-Rapor resmi PDF untuk seluruh siswa di kelas bimbingannya.

### 👤 3. Siswa
*   **Dashboard Siswa**: Ringkasan persentase kehadiran kumulatif, rata-rata capaian nilai, jadwal aktif hari ini, dan pengumuman sekolah.
*   **Jadwal Pelajaran Mingguan**: Jadwal kelas terstruktur berdasarkan hari dan jam pelajaran.
*   **Riwayat Absensi**: Detail kehadiran harian dan rekap per status (H/S/I/A) untuk pemantauan disiplin mandiri.
*   **e-Rapor Digital**: Akses lembar nilai akhir semester yang telah difinalisasi serta tombol cetak/unduh dokumen e-Rapor resmi berformat PDF.

---

## 🛠️ Teknologi & Arsitektur

| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Backend Framework** | [Laravel 13.x](https://laravel.com) | Arsitektur MVC modern berbasis PHP 8.3+ |
| **Frontend Styling** | [Tailwind CSS v4](https://tailwindcss.com) | Styling berbasis utilities modern dengan dukungan Vite |
| **Asset Bundler** | [Vite 8.x](https://vite.dev) | Hot Module Replacement (HMR) & build otomatis |
| **Database** | SQLite (Default) / MySQL | Zero-config database lokal, mendukung migrasi instan ke MySQL/PostgreSQL |
| **Dynamic RBAC** | [spatie/laravel-permission v8.0](https://spatie.be/docs/laravel-permission) | Manajemen hak akses peran & izin dinamis dari basis data |
| **PDF Generator** | [barryvdh/laravel-dompdf v3.1](https://github.com/barryvdh/laravel-dompdf) | Render dokumen e-Rapor standar Kurikulum Merdeka |
| **Spreadsheet Tool** | [maatwebsite/excel v3.1](https://maatwebsite.nl/) | Ekspor/impor lembar kerja data akademik |
| **Automated Testing** | PHPUnit 12.x / Pest | Test-driven assertions untuk validasi aturan bisnis |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan SIAKAD SMA pada komputer lokal Anda:

### 1. Prasyarat Sistem
Pastikan perangkat Anda sudah terpasang:
*   **PHP >= 8.3** (dengan ekstensi `pdo_sqlite` / `pdo_mysql`, `mbstring`, `openssl`, `xml`, `curl`, `fileinfo`)
*   **Composer 2.x**
*   **Node.js (LTS v20+) & npm**

### 2. Kloning Repository
```bash
git clone https://github.com/username/siakad-sma.git
cd siakad-sma
```

### 3. Setup Otomatis (Direkomendasikan)
Proyek ini dilengkapi skrip automasi setup satu langkah:
```bash
composer run setup
```
> **Catatan:** Perintah di atas akan secara otomatis menginstal dependensi PHP, membuat file `.env`, men-generate APP_KEY, menjalankan migrasi database SQLite, menginstal dependensi NPM, dan melakukan kompilasi aset frontend.

---

### ATAU: Setup Manual Langkah demi Langkah

Jika Anda memilih setup manual:

#### a. Instal Dependensi
```bash
composer install
npm install
```

#### b. Konfigurasi Environment File
```bash
# Windows
copy .env.example .env

# Linux / macOS
cp .env.example .env
```

#### c. Database Setup & Seeding
```bash
# Generate application key
php artisan key:generate

# Buat file sqlite jika belum ada (opsional jika menggunakan SQLite)
# Windows PowerShell:
New-Item -ItemType File -Path database\database.sqlite -Force

# Jalankan migrasi dan seeder data demo
php artisan migrate --seed
```

#### d. Kompilasi Aset Frontend
```bash
# Mode development (live hot reload)
npm run dev

# ATAU kompilasi final untuk production
npm run build
```

#### e. Jalankan Local Server
```bash
php artisan serve
```
Akses aplikasi melalui browser: [http://localhost:8000](http://localhost:8000)

> [!TIP]
> Anda juga dapat menjalankan seluruh layanan (server, queue, log viewer, dan vite) secara bersamaan menggunakan:
> ```bash
> composer run dev
> ```

---

## 🧪 Menjalankan Automated Testing

Untuk memverifikasi fungsionalitas dan logika bisnis sistem (seperti validasi wali kelas ganda dan pencegahan duplikasi kelas):
```bash
php artisan test
```

---

## 🔑 Kredensial Login Demo (Default Seeds)

Semua akun demo menggunakan kata sandi default: **`password`**

| Peran (Role) | Email Login | Password | Keterangan Akses |
|---|---|---|---|
| **Admin TU** | `admin@siakad.sch.id` | `password` | Akses penuh seluruh master data, RBAC, jadwal, absensi, & finalisasi rapor |
| **Guru (Wali Kelas X IPA 1)** | `budi@siakad.sch.id` | `password` | Guru Matematika & Wali Kelas X IPA 1 (bisa cetak rapor kelas) |
| **Guru (Wali Kelas XI IPA 1)** | `ahmad@siakad.sch.id` | `password` | Guru Fisika & Kimia, Wali Kelas XI IPA 1 |
| **Guru Mata Pelajaran** | `siti@siakad.sch.id` | `password` | Guru Bahasa Indonesia & Sejarah Indonesia |
| **Siswa 1 (X IPA 1)** | `andi@siakad.sch.id` | `password` | Rapor Semester 1 sudah difinalisasi (siap unduh PDF) |
| **Siswa 2 (X IPA 1)** | `bella@siakad.sch.id` | `password` | Akses jadwal pribadi, absensi, dan riwayat nilai |
| **Siswa 3 (XI IPA 1)** | `kevin@siakad.sch.id` | `password` | Siswa kelas XI (nilai belum difinalisasi untuk demo admin) |

---

## 📁 Struktur Direktori Penting

*   [`app/Http/Controllers/Admin/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/app/Http/Controllers/Admin): Controller modul Admin (Master Data, Jadwal, Absensi, Nilai, RBAC).
*   [`app/Http/Controllers/Guru/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/app/Http/Controllers/Guru): Controller modul Guru (Jadwal Mengajar, Input Absensi, Pengolahan Nilai & Rapor).
*   [`app/Http/Controllers/Siswa/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/app/Http/Controllers/Siswa): Controller portal Siswa (Jadwal, Absensi, Rapor PDF).
*   [`app/Models/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/app/Models): Model Eloquent (User, Siswa, Guru, Kelas, JadwalPelajaran, Nilai, BobotNilai, Prestasi, Ekstrakurikuler, Pengumuman).
*   [`resources/views/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/resources/views): Blade templates terstruktur per role dan layout aplikasi responsif.
*   [`resources/views/pdf/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/resources/views/pdf): Template dokumen e-Rapor resmi Kurikulum Merdeka (individual & kelas).
*   [`tests/Feature/`](file:///d:/Pribadi/Kuliah/Semester_6/APL/siakad-sma/tests/Feature): Pengujian fungsionalitas dan integritas aturan bisnis.

---

## 📄 Lisensi
SIAKAD SMA dikembangkan untuk keperluan akademik dan digitalisasi administrasi sekolah menengah atas di bawah lisensi open-source MIT.

