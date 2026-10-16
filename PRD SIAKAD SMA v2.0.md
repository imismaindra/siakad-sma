# PRD SIAKAD SMA v2.0

Disusun oleh Ahmad Maulana Ismaindra · Mata kuliah Evolusi Perangkat Lunak · Pembaruan: 16 Oktober 2026 · Basis: SIAKAD SMA v1.0 (Laravel)

## 1. Ringkasan

SIAKAD SMA v1.0 sudah mencakup tiga peran (Admin/TU, Guru, Siswa) dengan fitur data master, jadwal pelajaran, absensi per sesi, input nilai, e-Rapor PDF, dan RBAC dinamis. Versi 2.0 adalah rilis evolusi yang menambah kemampuan baru tanpa mengubah fondasi v1.0. Fokusnya: absensi yang lebih sulit dicurangi (QR dinamis + geofencing), keterlibatan orang tua, komunikasi sekolah (pengumuman, kalender akademik, notifikasi), akuntabilitas data (audit log), serta otomatisasi pipeline rilis (CI/CD ke shared hosting).

## 2. Latar Belakang dan Tujuan

### 2.1 Masalah pada v1.0

- Absensi masih diinput manual oleh guru, sehingga memakan waktu di awal pelajaran dan tidak ada verifikasi kehadiran fisik siswa.
- Orang tua tidak punya akses ke data anaknya dan baru tahu masalah kehadiran atau nilai saat rapor dibagikan.
- Dashboard siswa menampilkan pengumuman, tetapi belum ada modul bagi Admin untuk mengelolanya. Tidak ada kalender akademik.
- Tidak ada notifikasi ke pengguna ketika ada kejadian penting.
- Perubahan data sensitif (absensi, nilai, akun) tidak tercatat siapa pelakunya dan kapan.
- Deployment ke production server/shared hosting masih manual (FTP manual), rentan kesalahan build aset dan desinkronisasi file.

### 2.2 Tujuan v2.0

1. Mempercepat dan memvalidasi absensi dengan QR code dinamis (HMAC token berotasi + rolling OTP) dan pembatasan lokasi.
2. Memberi orang tua pemantauan read-only atas data anaknya.
3. Mengirim notifikasi otomatis melalui channel yang modular, dengan opsi gratis sebagai default.
4. Menyediakan pengumuman terarah dan kalender akademik yang dikelola Admin.
5. Mencatat jejak audit (audit log append-only) untuk seluruh perubahan data sensitif.
6. Otomatisasi deployment rilis dengan CI/CD GitHub Actions ke lingkungan cPanel shared hosting.
7. Mendokumentasikan evolusi v1.0 ke v2.0 secara terstruktur (versioning, changelog, analisis dampak).

### 2.3 Bukan tujuan (non-goals) v2.0

- Modul LMS penuh (tugas dan materi), keuangan/SPP, PPDB online, dan penyusunan jadwal otomatis.
- Aplikasi mobile native. Pemindaian QR dilakukan lewat browser smartphone/perangkat siswa.
- Integrasi WhatsApp resmi berbayar sebagai syarat rilis (cukup sediakan adapter/channel gratis).

## 3. Ruang Lingkup & Status Implementasi

| Kode | Fitur | Kategori | Status Implementasi |
| --- | --- | --- | --- |
| F1 | Absensi QR code dinamis (rotasi 30s + OTP cadangan + live monitor) | Wajib | ✅ Selesai (Feature Complete & Tested) |
| F2 | Geofencing absensi (Haversine radius + anti-fake location + GUI admin + override guru) | Wajib | ✅ Selesai (Feature Complete & Tested) |
| F3 | Audit log (jejak mutasi data append-only) | Wajib | ✅ Selesai (`spatie/laravel-activitylog` + Admin GUI) |
| F4 | Monitoring orang tua (portal read-only anak) | Wajib | ✅ Selesai (Portal Ortu + Switcher + e-Rapor PDF) |
| F5 | Pengumuman & kalender akademik | Wajib | 🟡 Pengumuman Multi-Target Selesai; Kalender Tahap 2 |
| F6 | Notifikasi modular (in-app, Telegram/email, adapter WA) | Wajib | ⏳ Rencana Tahap 3 |
| CI/CD | Pipeline deployment otomatis GitHub Actions ke cPanel | Penunjang | ✅ Selesai (`.github/workflows/deploy.yml` Aktif) |

## 4. Pengguna dan Peran

| Peran | Status | Kebutuhan baru di v2.0 |
| --- | --- | --- |
| Admin/TU | Ada | Kelola pengumuman, kalender, pengaturan lokasi sekolah, akun orang tua, lihat & filter audit log |
| Guru | Ada | Membuka sesi QR, melihat status absensi real-time (live polling 3s), override manual dengan alasan wajib |
| Siswa | Ada | Memindai QR untuk absen via browser (HTML5-QRCode), fallback input OTP 6-digit, melihat pengumuman dan kalender |
| Orang Tua/Wali | Baru (Tahap 2) | Melihat jadwal, absensi, nilai, dan rapor anak (read-only), menerima notifikasi |

Peran Orang Tua ditambahkan melalui mekanisme role dan permission yang sudah ada (`spatie/laravel-permission`).

## 5. Kebutuhan Fungsional

### 5.1 F1 Absensi QR code dinamis

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| F1-01 | Guru dapat membuka sesi absensi untuk jadwal pelajaran miliknya pada hari berjalan. | Tinggi | ✅ Selesai |
| F1-02 | Sistem menampilkan QR code yang tokennya berganti otomatis setiap 30 detik berbasis HMAC-SHA256 secret per sesi. | Tinggi | ✅ Selesai |
| F1-03 | Siswa yang sudah login memindai QR melalui kamera browser; server memvalidasi tiket terenkripsi, masa berlaku, keanggotaan kelas, dan jam pelajaran. | Tinggi | ✅ Selesai |
| F1-04 | Disediakan kode cadangan (rolling OTP 6-digit) bagi siswa yang mengalami kendala kamera scanner. | Tinggi | ✅ Selesai |
| F1-05 | Absensi yang valid tercatat sebagai Hadir dengan metode QR; pemindaian ulang oleh siswa yang sama tidak membuat data ganda (*idempotent*). | Tinggi | ✅ Selesai |
| F1-06 | Guru melihat daftar siswa yang sudah hadir dan belum absen secara langsung (*live monitoring*) via AJAX polling per 3 detik tanpa refresh. | Sedang | ✅ Selesai |
| F1-07 | Guru menutup sesi; siswa yang belum absen dapat diisi manual (Sakit, Izin, Alpa) atau dioverride dengan pencatatan alasan. | Tinggi | ✅ Selesai |
| F1-08 | Pemindaian setelah sesi ditutup, token kedaluwarsa, atau payload QR dirusak ditolak dengan pesan error yang jelas (HTTP 422). | Tinggi | ✅ Selesai |

### 5.2 F2 Geofencing

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| F2-01 | Admin/Sistem mengatur titik koordinat sekolah dan radius toleransi (meter). | Tinggi | ✅ Selesai |
| F2-02 | Saat memindai QR, browser mengirim lokasi siswa; server menghitung jarak ke titik sekolah (rumus Haversine) dan menolak absensi di luar radius. | Tinggi | ✅ Selesai |
| F2-03 | Lokasi dengan akurasi buruk (di atas ambang batas) ditolak, dan siswa diminta mencoba lagi. Termasuk deteksi anomali Mock GPS. | Sedang | ✅ Selesai |
| F2-04 | Jika izin lokasi ditolak browser, absensi QR dicegah dan diarahkan meminta bantuan guru. | Tinggi | ✅ Selesai |
| F2-05 | Guru dapat melakukan override manual untuk siswa yang bermasalah dengan GPS/kamera, wajib mengisi alasan. Override tercatat di audit log. | Tinggi | ✅ Selesai |
| F2-06 | Sistem mencatat jarak meter, akurasi meter, dan status validitas lokasi pada `detail_absensis`, bukan jejak tracking kontinu siswa. | Sedang | ✅ Selesai |

### 5.3 F3 Audit log

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| F3-01 | Sistem mencatat pembuatan, perubahan, dan penghapusan pada data absensi, nilai, profil guru/siswa, dan akun pengguna (`spatie/laravel-activitylog`), mencakup pelaku, IP Address, User-Agent, waktu, properti lama, dan properti baru. | Tinggi | ✅ Selesai |
| F3-02 | Override absensi, anomali fake GPS, dan perubahan role/permission tercatat secara khusus dengan alasan, koordinat, dan pelaku. | Tinggi | ✅ Selesai |
| F3-03 | Admin TU dapat mengakses antarmuka audit log dengan filter pelaku (causer), jenis modul (`log_name`), jenis aksi (`event`), rentang tanggal, pencarian full-text, serta ekspor file CSV. | Tinggi | ✅ Selesai |
| F3-04 | Log bersifat hanya-tambah (*append-only*); tidak disediakan aksi edit maupun hapus log pada antarmuka admin demi integritas bukti. | Sedang | ✅ Selesai |
| F3-05 | Dilindungi permission khusus `lihat-audit-log` agar hanya staf berwenang yang dapat melihat rekam jejak. | Tinggi | ✅ Selesai |

### 5.4 F4 Monitoring orang tua (Tahap 2)

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| F4-01 | Admin membuat akun orang tua dan menautkannya ke satu atau lebih siswa beserta hubungan (ayah, ibu, wali). | Tinggi | ✅ Selesai |
| F4-02 | Orang tua login dan melihat daftar anak yang tertaut; bila lebih dari satu, dapat berpindah antar anak. | Tinggi | ✅ Selesai |
| F4-03 | Per anak, orang tua dapat melihat jadwal pelajaran, riwayat absensi, nilai, dan e-Rapor yang sudah difinalisasi. | Tinggi | ✅ Selesai |
| F4-04 | Akses orang tua bersifat read-only dan hanya untuk anak yang tertaut; akses ke data siswa lain ditolak (`AnakPolicy` HTTP 403). | Tinggi | ✅ Selesai |
| F4-05 | Orang tua melihat pengumuman resmi sekolah dan kelas anak yang ditargetkan kepadanya. | Sedang | ✅ Selesai |

### 5.5 F5 Pengumuman dan kalender akademik

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| F5-01 | Admin membuat, mengubah, dan menghapus pengumuman dengan judul, isi, target audiens, dan status aktif. | Tinggi | ✅ Selesai (v1.0/v2.0) |
| F5-02 | Pengumuman dapat ditargetkan ke Semua, Guru, Siswa, atau Siswa Kelas tertentu. | Tinggi | ✅ Selesai |
| F5-03 | Pengumuman muncul di widget dashboard masing-masing pengguna sesuai target. | Tinggi | ✅ Selesai |
| F5-04 | Admin mengelola event kalender akademik: judul, kategori (libur, ujian, pembagian rapor, kegiatan), tanggal, deskripsi. | Tinggi | ⏳ Tahap 2 |
| F5-05 | Semua peran dapat melihat kalender dalam tampilan bulanan dan daftar. | Sedang | ⏳ Tahap 2 |

### 5.6 F6 Notifikasi modular (Tahap 3)

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| F6-01 | Notifikasi dibangun di atas Laravel Notifications dan antrean (`queue`), sehingga pengiriman tidak memperlambat request pengguna. | Tinggi | ⏳ Direncanakan |
| F6-02 | Channel default: notifikasi di dalam aplikasi (database notification), ditambah Telegram Bot atau email (gratis). | Tinggi | ⏳ Direncanakan |
| F6-03 | Channel WhatsApp diimplementasikan sebagai adapter terpisah dengan antarmuka yang sama (modular contract). | Sedang | ⏳ Direncanakan |
| F6-04 | Pemicu notifikasi: siswa alpa pada sesi QR/manual, nilai baru dipublikasikan, e-Rapor terbit, dan pengumuman baru. | Tinggi | ⏳ Direncanakan |
| F6-05 | Pengguna/orang tua dapat mengonfigurasi kontak channel dan preferensi notifikasi. | Sedang | ⏳ Direncanakan |

### 5.7 CI/CD & Deployment Otomatis (Penunjang Produksi)

| ID | Kebutuhan | Prioritas | Status |
| --- | --- | --- | :---:|
| CICD-01 | Workflow GitHub Actions terpicu secara otomatis pada push branch `v2.0-development` dan `main`. | Tinggi | ✅ Selesai |
| CICD-02 | Setup otomatis environment PHP 8.2 & Node.js 20, eksekusi `composer install --no-dev`, dan kompilasi aset frontend via Vite. | Tinggi | ✅ Selesai |
| CICD-03 | Deployment terisolasi ke cPanel Shared Hosting (subdomain) via SSH/Rsync dengan preservasi environment `.env` dan direktori storage. | Tinggi | ✅ Selesai |
| CICD-04 | Eksekusi otomatis migrasi basis data (`php artisan migrate --force`), caching konfigurasi/rute, dan symlink storage setelah rilis. | Tinggi | ✅ Selesai |

## 6. Kebutuhan Non-Fungsional

| Aspek | Kebutuhan |
| --- | --- |
| Keamanan | Seluruh validasi QR, dekripsi tiket, dan otorisasi dilakukan di server. Token QR acak berbasis secret HMAC, berumur pendek (30 detik), dan tidak dapat diprediksi. Akses log audit dilindungi permission ketat. |
| Privasi | Koordinat mentah siswa tidak disimpan kontinu. Data anak hanya dapat diakses orang tua yang tertaut. |
| Kinerja | Validasi pemindaian QR selesai < 1 detik. Monitoring guru melakukan polling ringan payload status tanpa full-page refresh. |
| Kemandirian Aset | Library `html5-qrcode.min.js`, `qrcode.min.js`, dan `alpine.min.js` disediakan lokal di direktori `public/js` agar scanner berfungsi tanpa koneksi internet eksternal CDN. |
| Kompatibilitas | Pemindaian QR berjalan optimal di browser ponsel modern (Chrome, Safari, Firefox). Fitur kamera browser mensyaratkan protokol HTTPS pada lingkungan produksi/staging. Kompatibel dengan PHP 8.2 dan PHP 8.3+. |
| Keandalan | Append-only audit logging menjamin catatan perubahan tidak dapat dimanipulasi dari UI. |
| Maintainability | Fitur baru dilengkapi automated feature test PHPUnit untuk alur kritis (pembuatan sesi, scan QR, token kedaluwarsa, duplikasi scan, otorisasi guru, audit log). |
| Kompatibilitas mundur | Data dan alur v1.0 (absensi manual per sesi, pengolahan nilai, e-Rapor PDF) tetap berfungsi 100% tanpa perubahan perilaku (*backward-compatible*). |

## 7. Perubahan Data & Skema Database

Tabel dan kolom baru yang diimplementasikan pada migrasi v2.0:

### 7.1 Tabel `sesi_absensi_qrs` (Baru - F1)
Menyimpan sesi aktif absensi QR per jadwal dan tanggal:
- `id`: unsigned bigint (PK)
- `jadwal_pelajaran_id`: unsigned bigint (FK -> `jadwal_pelajarans`, onDelete cascade)
- `absensi_id`: unsigned bigint, nullable (FK -> `absensis`, onDelete set null)
- `guru_id`: unsigned bigint (FK -> `gurus`, onDelete cascade)
- `tanggal`: date
- `dibuka_pada`: timestamp
- `ditutup_pada`: timestamp, nullable
- `secret`: string(64) (secret key unik untuk hashing HMAC token)
- `durasi_token_detik`: unsigned smallint (default: 30)
- `status`: enum ('aktif', 'ditutup')
- `created_at`, `updated_at`: timestamps
- *Index*: `['jadwal_pelajaran_id', 'tanggal', 'status']`

### 7.2 Perubahan Tabel `absensis` (F1)
- `metode`: enum ('manual', 'qr') default 'manual'

### 7.3 Perubahan Tabel `detail_absensis` (F1 & F2)
- `metode`: enum ('manual', 'qr', 'override') default 'manual'
- `waktu_absen`: timestamp, nullable
- `jarak_meter`: double, nullable
- `akurasi_meter`: double, nullable
- `lokasi_valid`: boolean default true
- `alasan_override`: string, nullable

### 7.4 Tabel `activity_log` (Baru - F3)
Dikelola oleh paket `spatie/laravel-activitylog`:
- `id`: unsigned bigint (PK)
- `log_name`: string, nullable (misal: 'absensi', 'nilai', 'user', 'default')
- `description`: text
- `subject_type`, `subject_id`: morphs (model yang dimutasi)
- `causer_type`, `causer_id`: morphs (user yang melakukan aksi)
- `event`: string, nullable ('created', 'updated', 'deleted')
- `properties`: json, nullable (memuat `old` dan `attributes` nilai)
- `batch_uuid`: uuid, nullable
- `created_at`, `updated_at`: timestamps

### 7.5 Tabel `orang_tuas` & `orang_tua_siswa` (Baru - F4)
Menyimpan profil orang tua dan relasi banyak-ke-banyak dengan anak:
- `orang_tuas`: `id`, `user_id` (FK -> `users`, cascade), `nama_lengkap`, `jenis_kelamin` (L/P), `no_telepon`, `pekerjaan`, `alamat`, timestamps.
- `orang_tua_siswa`: `id`, `orang_tua_id` (FK -> `orang_tuas`, cascade), `siswa_id` (FK -> `siswas`, cascade), `hubungan` (enum: 'ayah', 'ibu', 'wali'), timestamps. Unik pada `[orang_tua_id, siswa_id]`.

### 7.6 Permission Baru (Spatie RBAC)
- `buka-sesi-qr`: Diberikan kepada Guru dan Admin untuk membuat sesi QR.
- `override-absensi`: Diberikan kepada Guru dan Admin untuk melakukan override presensi bermasalah.
- `lihat-audit-log`: Diberikan kepada Admin/TU untuk mengakses rekam jejak aktivitas sistem.
- `lihat-data-anak`: Diberikan kepada Role `orang_tua` untuk otorisasi akses monitoring anak.

## 8. Arsitektur dan Teknologi

- **Backend**: Laravel 11/13 MVC, PHP ^8.2 (kompatibel hosting produksi cPanel).
- **Frontend & UI**: Tailwind CSS v4, Blade Templating Engine, Vite 8.x bundler, Alpine.js untuk state reaktif lokal.
- **QR Engine & Scanner**:
  - Generator QR: `qrcode.min.js` (rendering canvas/SVG dinamis di browser guru).
  - Scanner QR: `html5-qrcode.min.js` lokal (zero external CDN dependency) untuk pemindaian instan via kamera smartphone siswa.
- **Audit Logging**: `spatie/laravel-activitylog` v4 dengan trait `LogsActivity` pada model `Absensi`, `DetailAbsensi`, `Nilai`, `DetailNilai`, `User`, `Siswa`, `Guru`, `PengaturanSekolah`, dan `SesiAbsensiQr`. Rekam jejak otomatis mencakup alamat IP dan peramban pengguna. Dilengkapi fitur Ekspor CSV dan modal diff perbandingan data lama vs baru.
- **Dynamic RBAC**: `spatie/laravel-permission` v8.
- **PDF Engine**: `barryvdh/laravel-dompdf` v3.1.
- **CI/CD Pipeline**: GitHub Actions (`.github/workflows/deploy.yml`) mengotomatisasi build dan rsync deployment via SSH ke cPanel Shared Hosting.

## 9. Analisis Dampak Perubahan

### 9.1 Klasifikasi perubahan

| Perubahan | Jenis | Dampak pada Sistem |
| --- | --- | --- |
| Absensi QR Dinamis (F1) & Geofencing (F2) | Perfektif | Menambah opsi presensi mandiri nirsentuh tanpa merusak presensi manual v1.0. |
| Audit Log (F3) & Feature Testing | Preventif | Menutup risiko integritas data, memberi akuntabilitas mutasi nilai/absensi. |
| Monitoring Orang Tua (F4) | Adaptif | Portal pemantauan read-only anak untuk wali murid dengan kontrol akses ketat (`AnakPolicy`). |
| Otomatisasi CI/CD cPanel & PHP 8.2 Constraint | Adaptif | Memastikan kemudahan deployment berkelanjutan ke hosting nyata. |
| Notifikasi Modular (F6) | Adaptif (Mendatang) | Menghubungkan ekosistem sekolah dengan pihak wali murid lewat in-app, Telegram, atau WA. |

### 9.2 Modul v1.0 yang terdampak

| Modul v1.0 | Dampak Aktual |
| --- | --- |
| Absensi digital | Mendukung metode ganda (`manual` dan `qr`). Tabel detail absensi mencatat waktu scan dan status override. Alur manual guru tetap 100% utuh. |
| Autentikasi dan RBAC | Penambahan 3 permission baru (`buka-sesi-qr`, `override-absensi`, `lihat-audit-log`). |
| Modul Nilai & Pengguna | Model ditambahkan trait logging otomatis `LogsActivity` untuk rekam jejak audit tanpa mengubah logika penilaian. |
| Deployment | Beralih dari FTP manual menjadi automated GitHub Actions pipeline. |

## 10. Risiko dan Mitigasi

| Risiko | Dampak | Status & Mitigasi Terapan |
| --- | --- | --- |
| Siswa memotret QR untuk dikirim ke teman | Absensi fiktif | Mitigasi aktif: Token QR berotasi tiap 30 detik berbasis HMAC secret, kedaluwarsa cepat, dan terenkripsi. |
| Kamera siswa rusak / browser tidak mendukung scanner | Siswa gagal absen | Mitigasi aktif: Disediakan fallback input OTP 6-digit rolling code dan override langsung oleh guru dengan alasan. |
| Akses kamera terblokir di HTTP | Scanner gagal | Mitigasi aktif: CI/CD dikonfigurasi ke domain bersertifikat SSL/HTTPS aktif di cPanel. |
| Manipulasi riwayat audit log | Bukti audit diragukan | Mitigasi aktif: Audit log bersifat append-only, GUI admin tidak menyediakan aksi delete/edit log. |
| Kegagalan koneksi CDN eksternal | Scanner tidak terbuka | Mitigasi aktif: Library scanner (`html5-qrcode.min.js`, `qrcode.min.js`) disimpan sebagai aset lokal di repo. |

## 11. Prioritas dan Rencana Rilis

| Tahap | Isi | Status | Keluaran |
| --- | --- | :---:| --- |
| **1 Inti (Core Evolution)** | F1 QR dinamis, F2 skema geofence, F3 audit log, CI/CD pipeline | **100% Selesai** | Presensi QR aktif dengan live monitoring, audit trail aktif, pipeline deployment cPanel beroperasi |
| **2 Pendukung** | F4 role orang tua, F5 kalender akademik, penyempurnaan UI | Terjadwal | Portal wali murid read-only dan kalender akademik sekolah |
| **3 Notifikasi** | F6 arsitektur channel, Telegram/email, adapter WA opsional | Terjadwal | Notifikasi otomatis pemicu alpa, pengumuman, dan e-Rapor |
| **4 Penutupan** | Final documentation, test regression, tag v2.0.0 resmi | Terjadwal | Rilis v2.0.0 produksi |

## 12. Kriteria Penerimaan (Acceptance Criteria)

- [x] Guru dapat membuka sesi absensi QR dengan QR code dinamis yang berotasi per 30 detik.
- [x] Siswa yang memindai QR dalam kelas dan jadwal aktif langsung tercatat Hadir (*metode = qr*).
- [x] Pemindaian menggunakan token yang sudah lewat 30 detik atau sesi ditutup otomatis ditolak (HTTP 422).
- [x] Siswa yang scan berulang tidak menimbulkan data ganda (idempotent).
- [x] Siswa dengan kendala kamera dapat menggunakan kode rolling OTP 6-digit yang cocok.
- [x] Guru dapat melihat daftar kehadiran terupdate setiap 3 detik secara live.
- [x] Seluruh perubahan data absensi, nilai, profil, dan akun tercatat di audit log beserta nilai lama, nilai baru, pelaku, IP address, dan user agent.
- [x] Admin dapat memfilter rekam audit log berdasarkan pelaku, modul, event, dan tanggal.
- [x] Admin dapat mengekspor seluruh atau riwayat terfilter audit log ke format CSV untuk keperluan akreditasi/audit resmi.
- [x] Catatan override absensi oleh guru/admin tercatat secara khusus dengan alasan wajib dan detail siswa.
- [x] Push ke branch `v2.0-development` otomatis memicu pipeline CI/CD dan men-deploy pembaruan ke cPanel hosting.
- [x] Alur bawaan v1.0 (absensi manual, perhitungan nilai terbobot, ekspor e-Rapor PDF) tetap berjalan normal.

## 13. Manajemen Versi & Log Perubahan

- **v1.0.0** (Mei 2026): Rilis fondasi awal SIAKAD SMA (Data Master, Jadwal bebas bentrok, Absensi manual, Nilai Kurikulum Merdeka, e-Rapor PDF, Spatie Dynamic RBAC).
- **v2.0.0-beta / v2.0-development** (Oktober 2026):
  - Penambahan modul Absensi QR Dinamis (F1) dengan rotasi HMAC token 30s & rolling OTP.
  - Penambahan Live Monitoring presensi guru via AJAX polling 3s.
  - Implementasi Audit Trail otomatis (F3) via `spatie/laravel-activitylog` beserta GUI admin.
  - Penambahan permission baru (`buka-sesi-qr`, `override-absensi`, `lihat-audit-log`).
  - Pembuatan automated CI/CD pipeline GitHub Actions untuk deployment ke cPanel Shared Hosting.
  - Pembaruan dependensi PHP ^8.2 pada `composer.json` untuk kompatibilitas shared hosting.
