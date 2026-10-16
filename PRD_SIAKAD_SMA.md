# Product Requirements Document (PRD)
# Sistem Informasi Akademik SMA (SIAKAD SMA)

---

| Field | Detail |
|---|---|
| **Versi Dokumen** | 2.1 (Post-MVP, Dynamic QR Attendance, Spatie Audit Trail, & CI/CD Pipeline) |
| **Tanggal** | 16 Oktober 2026 |
| **Status** | Produksi & Stabil (Feature Complete - v2.1) |
| **Platform** | Web Application (Responsive Desktop, Tablet, & Mobile) |
| **Target Rilis** | Q4 2026 (Rilis Stabil v1.0, v2.0, & v2.1) |

---

## 1. Ringkasan Eksekutif

SIAKAD SMA adalah platform administrasi akademik berbasis web modern yang dirancang untuk **menjembatani komunikasi, tata kelola data, integritas informasi, dan alur kerja operasional** antara tiga aktor utama sekolah: **Tata Usaha (Admin)**, **Guru (Pendidik & Wali Kelas)**, dan **Siswa**. Sistem ini mentransformasi proses administrasi sekolah dari metode manual (pembukuan fisik & lembar spreadsheet yang terfragmentasi) menjadi ekosistem digital terintegrasi.

Cakupan fungsional mencakup pengelolaan data master sekolah dengan validasi integritas ketat, penyusunan jadwal bebas bentrok (*clashing-free scheduling*), pencatatan kehadiran digital per sesi (baik manual maupun **presensi mandiri QR code dinamis dengan rolling token & live monitoring**), pengolahan nilai berbobot fleksibel berstandar Kurikulum Merdeka, penerbitan dan ekspor dokumen e-Rapor resmi berformat PDF secara mandiri maupun kolektif, **jejak audit otomatis (*audit trail/activity log*) untuk akuntabilitas mutasi data sensitif**, hingga **otomatisasi deployment berkelanjutan (CI/CD via GitHub Actions ke cPanel shared hosting)**.

---

## 2. Latar Belakang & Masalah

### 2.1 Masalah yang Diidentifikasi

| # | Masalah | Dampak Nyata di Sekolah |
|---|---|---|
| 1 | Data siswa & kelas dikelola terpisah di spreadsheet, rentan duplikasi & inkonsistensi | Inefisiensi operasional tinggi dan risiko kehilangan data pokok siswa |
| 2 | Jadwal pelajaran dibuat manual, sering terjadi guru mengajar di dua kelas pada jam yang sama | Tabrakan jadwal (*schedule clashing*), kebingungan guru & siswa |
| 3 | Pengangkatan wali kelas rawan tumpang tindih (satu guru memegang banyak kelas di tahun ajaran sama) | Pelanggaran beban kerja dan pembagian tugas perwalian yang tidak merata |
| 4 | Rekapitulasi absensi harian di kertas memakan waktu lama setiap akhir bulan/semester | Beban administrasi berlebih bagi guru mata pelajaran dan wali kelas |
| 5 | Presensi manual memakan waktu berharga guru di 10–15 menit awal jam belajar | Waktu efektif kegiatan belajar mengajar (KBM) berkurang drastis |
| 6 | Kalkulasi nilai rapor dihitung manual dengan bobot yang kaku atau tidak konsisten | Potensi kesalahan hitung nilai akhir siswa dan keterlambatan rapor |
| 7 | Perubahan data sensitif (nilai siswa, absensi susulan, hak akses akun) tidak terdokumentasi pelakunya | Hilangnya akuntabilitas data jika terjadi sengketa koreksi nilai atau kehadiran |
| 8 | Siswa & wali murid tidak memiliki visibilitas real-time terhadap kehadiran dan nilai berkala | Kurangnya transparansi pemantauan akademik sebelum rapor fisik terbit |
| 9 | Pengumuman sekolah tersebar lewat grup chat informal yang mudah tertimbun | Informasi penting sekolah tidak tersampaikan secara merata dan terarsip |
| 10 | Proses deployment ke server produksi cPanel manual via FTP rentan kelalaian kompilasi aset | Terjadinya downtime atau aset CSS/JS yang desinkronisasi pada pengguna |

### 2.2 Peluang & Regulasi
- **Kurikulum Merdeka**: Mendorong pelaporan berbasis capaian kompetensi dengan deskripsi capaian pembelajaran yang terpersonalisasi per siswa.
- **Efisiensi Administrasi**: Mengurangi durasi pembuatan e-Rapor dari ~2 minggu menjadi ≤ 3 hari kerja dan memangkas waktu absensi kelas menjadi < 1 menit lewat QR code.
- **Akuntabilitas & Kepatuhan Data**: Memastikan setiap mutasi nilai dan kehadiran siswa tercatat dalam sistem audit log yang tidak dapat diubah (*append-only*).
- **Kemandirian Sekolah**: Menghadirkan sistem terpadu yang ringan, minim ketergantungan infrastruktur rumit (zero-config SQLite untuk lokal, MySQL/PostgreSQL untuk produksi cPanel).

---

## 3. Visi & Tujuan Produk

> **Visi:** Menjadi platform administrasi akademik SMA yang paling andal, intuitif, transparan, dan efisien, sehingga pendidik fokus membimbing dan siswa fokus berkembang.

### 3.1 Tujuan Bisnis & Key Results (OKR)

| Objective | Key Result | Status Pencapaian |
|---|---|:---:|
| Meningkatkan efisiensi kerja staf & guru | Memangkas waktu penyusunan jadwal dan kalkulasi rapor ≥ 70% | ✅ Tercapai |
| Menjamin integritas & validitas data akademik | Zero konflik jadwal guru dan zero duplikasi wali kelas per tahun ajaran | ✅ Tercapai |
| Mempercepat presensi kelas nirsentuh | Mempersingkat waktu absensi per sesi dari 10 menit menjadi < 60 detik via QR dinamis | ✅ Tercapai (F1) |
| Akuntabilitas mutasi data akademik | 100% perubahan absensi, nilai, dan akun tercatat di Audit Log append-only | ✅ Tercapai (F3) |
| Mendorong transparansi akademik | 100% siswa aktif dapat mengakses jadwal, kehadiran, dan e-Rapor mandiri | ✅ Tercapai |
| Keamanan & fleksibilitas tata kelola | Dynamic Role-Based Access Control (RBAC) berbasis database | ✅ Tercapai |
| Otomatisasi rilis & deployment | Zero-friction automated deployment ke cPanel via GitHub Actions | ✅ Tercapai (CI/CD) |

---

## 4. Target Pengguna (User Personas)

### 👤 Persona 1 — Admin / Tata Usaha (TU)
- **Profil:** Staf administrasi sekolah, mengelola operasional harian dan data pokok sekolah.
- **Goal:** Mengontrol seluruh master data, memvalidasi pembagian kelas & wali kelas, menyusun jadwal pelajaran, memverifikasi nilai rapor, mengelola akun dan izin hak akses secara fleksibel, mengawasi **Jejak Audit (Audit Log)** untuk menginvestigasi mutasi nilai/absensi, serta memantau rilis sistem via pipeline deployment otomatis.
- **Pain Point:** Sering terjadi jadwal guru bentrok saat awal semester, koreksi data nilai siswa memakan waktu lama, serta tidak adanya bukti log saat terjadi perubahan nilai tanpa izin.

### 👤 Persona 2 — Guru (Pendidik & Wali Kelas)
- **Profil:** Tenaga pengajar mata pelajaran dan wali kelas bimbingan.
- **Goal:** Melihat jadwal mengajar pribadi, membuka **Sesi Absensi QR Dinamis** di layar kelas dengan pemantauan siswa realtime (*live polling*), melakukan koreksi/override kehadiran manual dengan alasan tercatat, menginput nilai harian/UTS/UAS dengan kalkulasi otomatis, mengisi deskripsi capaian kompetensi, serta mengunduh e-Rapor kelas bimbingannya.
- **Pain Point:** Memanggil nama puluhan siswa satu per satu di awal jam, menghitung manual persentase bobot nilai, dan merekap kehadiran ratusan siswa di akhir semester.

### 👤 Persona 3 — Siswa
- **Profil:** Pelajar tingkat SMA (Kelas X, XI, XII).
- **Goal:** Melakukan absensi mandiri instan via pemindai kamera browser smartphone (**HTML5-QRCode**) atau kode OTP cadangan, memantau jadwal pelajaran mingguan, mengecek riwayat absensi harian, membaca pengumuman sekolah, serta melihat lembar nilai dan mengunduh berkas resmi e-Rapor PDF setelah difinalisasi.
- **Pain Point:** Proses absensi manual lambat, tidak mengetahui rekap kehadiran berjalan, dan kartu rapor fisik sering rusak atau lambat dibagikan.

---

## 5. Ruang Lingkup Produk

### 5.1 In-Scope (Fitur Selesai & Aktif di v2.1)
- ✅ **Dynamic RBAC**: Manajemen Role & Permission dinamis melalui antarmuka web ([spatie/laravel-permission](https://spatie.be/docs/laravel-permission)).
- ✅ **Manajemen Data Master**: Tahun Ajaran (kontrol aktif global), Jurusan, Kelas, Mata Pelajaran, Guru, Siswa, serta mutasi pindah kelas.
- ✅ **Validasi Integritas Ketat**:
  - Pencegahan penugasan guru yang sama sebagai wali kelas ganda pada tahun ajaran yang sama.
  - Pencegahan duplikasi kombinasi Tingkat, Jurusan, dan Nomor Kelas.
- ✅ **Jadwal Pelajaran Bebas Bentrok**:
  - Algoritma pencegah tabrakan jadwal (*time overlap detection*) antar-kelas untuk guru yang sama.
  - Tampilan visual Grid Kalender mingguan dan Tabel tabular dengan fitur pencarian & penyaringan.
- ✅ **Absensi Digital Multi-Metode**:
  - Pencatatan kehadiran konvensional per sesi kelas (Hadir, Sakit, Izin, Alpa) dan koreksi administratif oleh Admin.
  - **Absensi QR Code Dinamis (F1)**: Guru membuka sesi dengan QR code dinamis yang berotasi tiap 30 detik (HMAC secret hash), tiket payload terenkripsi, pencegahan scan kedaluwarsa, dan pencegahan duplikasi data (*idempotent*).
  - **Scanner Kamera Browser Siswa**: Pemindaian kamera mandiri smartphone siswa menggunakan library lokal `html5-qrcode.min.js` (100% offline localhost ready).
  - **Kode Cadangan (OTP Rolling 6-Digit)**: Alternatif presensi bagi siswa dengan kendala kamera tanpa perlu reload.
  - **Live Monitoring Kehadiran Guru**: Polling AJAX ringan setiap 3 detik menampilkan daftar siswa hadir vs belum absen secara real-time.
  - **Override Kehadiran Guru**: Guru dapat meng-override siswa yang berkendala dengan kewajiban mengisi alasan yang otomatis tercatat di audit log.
- ✅ **Audit Trail & Log Aktivitas Sistem (F3)**:
  - Integrasi [spatie/laravel-activitylog](https://spatie.be/docs/laravel-activitylog) untuk mencatat otomatis seluruh pembuatan, pembaruan, dan penghapusan data pada model `Absensi`, `DetailAbsensi`, `Nilai`, dan `User`.
  - GUI Admin Audit Log dengan filter multi-kriteria: Pelaku (*causer*), Modul (*log_name*), Jenis Aksi (*event*), Rentang Tanggal, dan Pencarian teks bebas pada data lama vs baru.
  - Log bersifat hanya-tambah (*append-only*, immutable).
- ✅ **Pengolahan Nilai Berbobot & e-Rapor**:
  - Pengaturan persentase bobot nilai (Harian, UTS, UAS) fleksibel dengan live validasi total 100%.
  - Perhitungan otomatis nilai akhir dengan algoritma normalisasi dinamis saat komponen nilai belum lengkap.
  - Pencatatan kegiatan Ekstrakurikuler dan Prestasi Siswa.
  - Lembar e-Rapor PDF resmi standar Kurikulum Merdeka (ekspor mandiri siswa, per siswa oleh guru/admin, dan ekspor kolektif per kelas).
- ✅ **Peringkat Siswa**: Penentuan ranking otomatis kelas berdasarkan rerata nilai akhir.
- ✅ **Modul Pengumuman Terarah**: Publikasi pengumuman dengan target audiens spesifik (Semua, Guru, Siswa, atau Kelas tertentu).
- ✅ **Continuous Integration & Continuous Deployment (CI/CD)**:
  - Workflow GitHub Actions (`.github/workflows/deploy.yml`) untuk rilis otomatis ke cPanel Shared Hosting (subdomain) saat push ke branch `v2.0-development` atau `main`.
  - Kompatibilitas runtime PHP ^8.2 di composer.json dan optimasi build aset Vite frontend.
- ✅ **Automated Testing Suite**: Pengujian logika bisnis, integritas data, alur QR code, dan audit trail menggunakan feature test PHPUnit.

### 5.2 Out-of-Scope (Rencana Pengembangan Masa Depan / Fase 3)
- ⏳ Portal akses khusus Orang Tua / Wali Murid (Tahap 2 Evolusi - F4).
- ⏳ Notifikasi Multi-Channel modular (Telegram Bot, Email, Adapter WhatsApp - F6).
- ⏳ Kalender Akademik interaktif terpadu (F5).
- ⏳ Integrasi sinkronisasi langsung dua arah dengan API Dapodik Kemdikbud.
- ⏳ Modul Pembayaran SPP & Keuangan Sekolah (dipisahkan ke sistem microservice terpisah).

---

## 6. Fitur & Spesifikasi Fungsional

---

### 6.1 Modul Autentikasi & Dynamic RBAC

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| AUTH-01 | Autentikasi Pengguna | Login via email dan kata sandi dengan hash Bcrypt, proteksi CSRF, proteksi brute-force | 🔴 Must Have | ✅ Selesai |
| AUTH-02 | Dynamic Role Management | Admin dapat membuat, mengubah, dan menghapus Role dari database | 🔴 Must Have | ✅ Selesai |
| AUTH-03 | Dynamic Permission Mapping | Penugasan daftar izin fitur (permissions) ke masing-masing Role tanpa ubah kode | 🔴 Must Have | ✅ Selesai |
| AUTH-04 | User-Role Assignment | Admin dapat mengubah role spesifik pada akun pengguna via antarmuka web | 🔴 Must Have | ✅ Selesai |
| AUTH-05 | Manajemen Profil Mandiri | Setiap pengguna dapat memperbarui informasi profil dan mengubah kata sandi pribadi | 🔴 Must Have | ✅ Selesai |

---

### 6.2 Modul Manajemen Data Master (Admin TU)

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| MASTER-01 | CRUD Tahun Ajaran | Menetapkan nama, semester (Ganjil/Genap), tanggal periode, dan toggle `is_aktif` global | 🔴 Must Have | ✅ Selesai |
| MASTER-02 | CRUD Jurusan | Pengelolaan data program keahlian/jurusan (IPA, IPS, dll.) beserta kode unik | 🔴 Must Have | ✅ Selesai |
| MASTER-03 | CRUD Kelas & Kapasitas | Pengaturan tingkat (X, XI, XII), nomor kelas, jurusan, wali kelas, dan batas kapasitas | 🔴 Must Have | ✅ Selesai |
| MASTER-04 | Validasi Wali Kelas Unik | Guru tidak dapat ditugaskan sebagai wali kelas di lebih dari satu kelas pada tahun ajaran yang sama | 🔴 Must Have | ✅ Selesai |
| MASTER-05 | Validasi Kelas Unik | Kombinasi Tahun Ajaran, Tingkat, Jurusan, dan Nomor Kelas tidak boleh duplikat | 🔴 Must Have | ✅ Selesai |
| MASTER-06 | CRUD Guru & NIP | Manajemen data guru, NIP, status kepegawaian, dan relasi mata pelajaran yang diampu | 🔴 Must Have | ✅ Selesai |
| MASTER-07 | CRUD Siswa & NIS/NISN | Profil siswa lengkap dengan NIS, NISN, kelas saat ini, data orang tua, dan kontak | 🔴 Must Have | ✅ Selesai |
| MASTER-08 | Pindah Kelas Siswa | Fitur mutasi kelas siswa secara cepat dengan riwayat kelas yang terbarui | 🔴 Must Have | ✅ Selesai |
| MASTER-09 | CRUD Mata Pelajaran | Kode mapel, nama mapel, jumlah jam mengajar per minggu, dan KKM | 🔴 Must Have | ✅ Selesai |
| MASTER-10 | Instant Search & Filter | Filter tahun ajaran, jurusan, dan pencarian teks cepat di seluruh tabel master | 🟡 Should Have | ✅ Selesai |

---

### 6.3 Modul Jadwal Pelajaran (Admin TU)

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| JADWAL-01 | Plotting Jadwal Mingguan | Menghubungkan kelas, mata pelajaran, guru pengampu, hari, jam mulai, dan jam selesai | 🔴 Must Have | ✅ Selesai |
| JADWAL-02 | Deteksi Bentrok Jadwal | Sistem menolak penyimpanan jika jam guru pengampu bertabrakan dengan jadwal di kelas lain | 🔴 Must Have | ✅ Selesai |
| JADWAL-03 | Tampilan Mode Tabel | Daftar jadwal terstruktur dengan filter hari, kelas, dan fitur pencarian dinamis | 🔴 Must Have | ✅ Selesai |
| JADWAL-04 | Tampilan Mode Grid Visual | Visualisasi kalender mingguan per jam pelajaran untuk scannability jadwal yang tinggi | 🟡 Should Have | ✅ Selesai |
| JADWAL-05 | Hak Akses Jadwal Pribadi | Guru dan Siswa otomatis melihat jadwal yang difilter khusus untuk kelas/jadwal mereka | 🔴 Must Have | ✅ Selesai |

---

### 6.4 Modul Absensi Digital & Dynamic QR Attendance (Guru, Siswa, & Admin)

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| ABSEN-01 | Input Kehadiran Sesi Manual | Guru mata pelajaran menginput kehadiran per siswa (Hadir, Sakit, Izin, Alpa) pada jadwal aktif | 🔴 Must Have | ✅ Selesai |
| ABSEN-02 | Rekapitulasi Otomatis | Sistem otomatis menghitung total H/S/I/A dan persentase kehadiran kumulatif per siswa | 🔴 Must Have | ✅ Selesai |
| ABSEN-03 | Rekap Semester Kelas | Tinjauan menyeluruh kehadiran seluruh siswa dalam satu kelas per semester | 🔴 Must Have | ✅ Selesai |
| ABSEN-04 | Koreksi Absensi Admin | Admin TU memiliki wewenang mengoreksi catatan kehadiran jika terdapat surat dokter/izin susulan | 🔴 Must Have | ✅ Selesai |
| ABSEN-05 | Portal Kehadiran Siswa | Siswa dapat memantau akumulasi kehadiran dan tingkat kedisiplinan belajar secara mandiri | 🟡 Should Have | ✅ Selesai |
| ABSEN-06 | Sesi Absensi QR Dinamis | Guru membuka sesi presensi QR; token QR berganti otomatis tiap 30 detik berbasis HMAC secret | 🔴 Must Have | ✅ Selesai (F1) |
| ABSEN-07 | Presensi Kamera Mandiri Siswa | Siswa memindai QR lewat browser smartphone (HTML5-QRCode); server memvalidasi masa berlaku token, kelas, dan jam pelajaran | 🔴 Must Have | ✅ Selesai (F1) |
| ABSEN-08 | Fallback OTP Rolling Code | Tersedia 6-digit OTP rolling code bagi siswa yang mengalami kendala teknis pada kamera perangkat | 🔴 Must Have | ✅ Selesai (F1) |
| ABSEN-09 | Live Monitoring Kehadiran | Tampilan monitor guru melakukan polling otomatis per 3 detik memperbarui status hadir siswa tanpa reload | 🟡 Should Have | ✅ Selesai (F1) |
| ABSEN-10 | Override Presensi Guru | Guru dapat meng-override status siswa yang berkendala dengan input alasan wajib yang terdata di audit log | 🔴 Must Have | ✅ Selesai (F1/F2) |

---

### 6.5 Modul Penilaian & e-Rapor Resmi Kurikulum Merdeka

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| NILAI-01 | Konfigurasi Bobot Nilai | Admin mengatur persentase bobot Harian, UTS, dan UAS per mata pelajaran dengan live counter total 100% | 🔴 Must Have | ✅ Selesai |
| NILAI-02 | Input Nilai Harian Rinci | Guru menginput nilai tugas, kuis, dan ulangan harian secara berkala per siswa | 🔴 Must Have | ✅ Selesai |
| NILAI-03 | Input Nilai UTS & UAS | Guru menginput nilai ujian tengah semester dan akhir semester | 🔴 Must Have | ✅ Selesai |
| NILAI-04 | Kalkulasi Terbobot Otomatis | Sistem menghitung rata-rata harian dan nilai akhir terbobot secara instan | 🔴 Must Have | ✅ Selesai |
| NILAI-05 | Normalisasi Dinamis | Jika salah satu komponen ujian belum berlangsung, nilai dihitung proporsional berbasis komponen terisi | 🔴 Must Have | ✅ Selesai |
| NILAI-06 | Deskripsi Capaian Kompetensi | Guru menginput capaian pembelajaran (kompetensi tertinggi & kompetensi yang perlu ditingkatkan) | 🔴 Must Have | ✅ Selesai |
| NILAI-07 | Ekstrakurikuler & Prestasi | Wali kelas mencatat kegiatan ekstrakurikuler serta prestasi kejuaraan yang diraih siswa | 🟡 Should Have | ✅ Selesai |
| NILAI-08 | Finalisasi & Penguncian Nilai | Admin TU dapat memverifikasi nilai rapor seluruh kelas dan mengunci nilai rapor (*lock/publish*) | 🔴 Must Have | ✅ Selesai |
| NILAI-09 | Peringkat Kelas | Sistem otomatis menyusun urutan peringkat prestasi siswa dalam satu kelas | 🟡 Should Have | ✅ Selesai |
| NILAI-10 | Ekspor e-Rapor PDF Siswa | Format PDF resmi Kurikulum Merdeka (memuat identitas, nilai akademik, ekskul, prestasi, catatan) | 🔴 Must Have | ✅ Selesai |
| NILAI-11 | Ekspor Rapor Kolektif Kelas | Fitur batch print seluruh rapor siswa dalam satu kelas sekaligus dalam satu berkas PDF terstruktur | 🟡 Should Have | ✅ Selesai |

---

### 6.6 Modul Dashboard & Analitik

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| DASH-01 | Dashboard Admin TU | Metrik jumlah data master, tahun ajaran aktif, grafik kehadiran sekolah, dan status finalisasi rapor | 🔴 Must Have | ✅ Selesai |
| DASH-02 | Dashboard Guru | Jadwal mengajar aktif hari ini, ringkasan kelas perwalian, pengumuman sekolah, dan alert kelas belum absen | 🔴 Must Have | ✅ Selesai |
| DASH-03 | Dashboard Siswa | Kartu kehadiran pribadi, rerata nilai akademik, jadwal pelajaran hari ini, dan pengumuman sekolah | 🔴 Must Have | ✅ Selesai |

---

### 6.7 Modul Pengumuman Multi-Target

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| ANNC-01 | Buat Pengumuman Sekolah | Admin dan Guru dapat mempublikasikan pengumuman dengan judul, konten, dan status aktif | 🟡 Should Have | ✅ Selesai |
| ANNC-02 | Penargetan Audiens Fleksibel | Pilihan target distribusi: Semua Pengguna, Khusus Guru, Khusus Siswa, atau Khusus Siswa Kelas Tertentu | 🟡 Should Have | ✅ Selesai |
| ANNC-03 | Widget Pengumuman Dashboard | Pengumuman otomatis ditampilkan di dashboard masing-masing pengguna sesuai filter target | 🟡 Should Have | ✅ Selesai |

---

### 6.8 Modul Jejak Audit & Keamanan (Audit Log)

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| AUDIT-01 | Auto Activity Logging | Sistem otomatis merekam event `created`, `updated`, `deleted` pada model `Absensi`, `DetailAbsensi`, `Nilai`, dan `User` | 🔴 Must Have | ✅ Selesai (F3) |
| AUDIT-02 | Dashboard Audit Trail Admin | Antarmuka khusus bagi Admin TU untuk meninjau log aktivitas dengan pagination dan visual badge | 🔴 Must Have | ✅ Selesai (F3) |
| AUDIT-03 | Multi-Dimensional Filtering | Penyaringan log berdasarkan Pelaku (*causer*), Modul (*log_name*), Jenis Aksi (*event*), Rentang Tanggal, dan Full-Text Search properti | 🔴 Must Have | ✅ Selesai (F3) |
| AUDIT-04 | Immutabilitas Log (Append-Only) | Rekam audit log tidak dapat diedit atau dihapus melalui GUI demi integritas barang bukti data | 🔴 Must Have | ✅ Selesai (F3) |

---

### 6.9 Modul DevOps & Continuous Deployment (CI/CD Pipeline)

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| DEVOPS-01 | GitHub Actions Deployment | Otomatisasi alur CI/CD saat push ke branch `v2.0-development` dan `main` menuju cPanel Shared Hosting | 🔴 Must Have | ✅ Selesai |
| DEVOPS-02 | Automated Asset Compilation | Setup runtime Node.js 20 & Vite build untuk Tailwind CSS v4 dan tracking bundle manifest di repository | 🔴 Must Have | ✅ Selesai |
| DEVOPS-03 | Deployment Terisolasi via SSH/Rsync | Transfer berkas produksi terisolasi dengan proteksi direktori `storage` dan file `.env` di hosting | 🔴 Must Have | ✅ Selesai |
| DEVOPS-04 | Post-Deployment Automation | Eksekusi otomatis `php artisan migrate --force`, caching route & view, serta storage link di server | 🔴 Must Have | ✅ Selesai |

---

## 7. Spesifikasi Non-Fungsional

| Kategori | Parameter | Spesifikasi & Tolok Ukur |
|---|---|---|
| **Performa & Responsivitas** | Response Time | Waktu load halaman dashboard dan data master < 2.0 detik pada koneksi internet standar (10 Mbps). Polling live monitoring guru < 300 ms |
| **Keamanan Presensi** | Rotasi Token QR | Token berbasis hash HMAC-SHA256 berotasi tiap 30 detik; verifikasi server memastikan token kedaluwarsa tidak dapat dipakai kembali |
| **Kemandirian Aset** | Zero External CDN | Seluruh library pendukung pemindai QR (`html5-qrcode.min.js`, `qrcode.min.js`, `alpine.min.js`) disediakan lokal di `public/js` |
| **Keamanan & Akuntabilitas** | Audit Log & Enkripsi | Kata sandi di-hash Bcrypt; formulir diproteksi token CSRF; mutasi data dicatat append-only di tabel `activity_log` |
| **Otorisasi & Hak Akses** | Dynamic RBAC | Seluruh rute dilindungi middleware Spatie Permission; aksi tanpa izin menghasilkan respon HTTP 403 Forbidden |
| **Integritas Basis Data** | Constraints & Transactions | Relasi dilindungi foreign keys cascade/restrict; operasi ganda dibungkus dalam `DB::transaction` |
| **Kompatibilitas Tampilan** | Cross-Browser & Device | UI responsif menggunakan Tailwind CSS v4, berjalan optimal pada Chrome, Firefox, Edge, Safari (Desktop, Tablet, Smartphone) |
| **Generasi Dokumen** | PDF Engine | Render e-Rapor PDF menggunakan format kertas A4 standar cetak dinas dengan margin presisi dan font ramah cetak |
| **Keandalan & Pengujian** | Test Automation | Dilengkapi automated Feature Tests PHPUnit/Pest untuk validasi jadwal anti-bentrok, presensi QR, dan audit log |

---

## 8. Arsitektur Sistem (Implementasi Aktual)

Sistem SIAKAD SMA mengadopsi pola arsitektur **Monolith Modern (Model-View-Controller)** yang menggabungkan performa server-side rendering dengan kecepatan build modern serta pipeline deployment otomatis:

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    KLIEN / BROWSER PENGGUNA                             │
│       Admin / TU                  Guru / Wali Kelas           Siswa     │
│   (Desktop / Laptop)             (Laptop / Tablet)      (Smartphone/PC) │
│                                                                         │
│   • Dashboard & Master Data       • QR Show & Live Monitor • HTML5 Camera│
│   • Audit Trail Interface        • Override Presensi      • OTP Fallback│
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ HTTPS
┌────────────────────────────────────▼────────────────────────────────────┐
│                    FRONTEND LAYER (Server-Side Blade)                   │
│   • Blade View Templates (app, admin, guru, siswa, pdf)                 │
│   • Tailwind CSS v4 (Modern Utility Engine)                             │
│   • Asset Bundling & Hot Module Replacement via Vite 8.x                │
│   • Script Lokal: html5-qrcode.min.js, qrcode.min.js, alpine.min.js    │
│   • Google Fonts (Inter & Plus Jakarta Sans)                            │
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ PHP 8.2 / 8.3 Dispatcher
┌────────────────────────────────────▼────────────────────────────────────┐
│                  BACKEND CORE (Laravel 13.x MVC)                        │
│                                                                         │
│  [ Routing & Middleware (Auth, Spatie Dynamic RBAC) ]                  │
│                                                                         │
│  Controllers:                                                           │
│  ├─ Admin: Master Data, Kelas, Jadwal, Absensi, Nilai, RBAC, AuditLog   │
│  ├─ Guru : Jadwal, Input Absensi, AbsensiQrGuru, Nilai, Rapor Perwalian  │
│  ├─ Siswa: Dashboard, Jadwal, AbsensiQrSiswa, e-Rapor Download          │
│  └─ Auth & Profile: Sesi, Kredensial, Pengaturan Akun                   │
│                                                                         │
│  Services & Libraries:                                                  │
│  ├─ Barryvdh Laravel-DomPDF (Penerbit e-Rapor PDF Resmi)                │
│  ├─ Spatie Laravel-Activitylog (Audit Trail & Tracking Mutasi Data)     │
│  ├─ Maatwebsite Excel (Pengolahan Lembar Kerja)                         │
│  └─ Logika Anti-Bentrok Jadwal, HMAC QR Token, & Normalisasi Nilai      │
└───────────────────┬─────────────────────────────────┬───────────────────┘
                    │                                 │
┌───────────────────▼───────────────┐ ┌───────────────▼───────────────────┐
│         DATABASE STORAGE          │ │           FILE STORAGE            │
│  • SQLite (Default Dev/Testing)   │ │  • Storage / Public Directory    │
│  • MySQL / PostgreSQL (Production)│ │  • Aset Logo Sekolah, Tanda Tangan│
│  • Spatie RBAC & Activity Tables  │ │  • Cache Template & Rapor PDF     │
│  • Sesi Absensi QR & Detail Absen │ │                                   │
└───────────────────────────────────┘ └───────────────────────────────────┘
                    ▲
                    │ Automated Delivery
┌───────────────────┴─────────────────────────────────────────────────────┐
│                 CI/CD PIPELINE (GitHub Actions)                         │
│  • Push Trigger: v2.0-development & main                                │
│  • Automated Setup: PHP 8.2, Composer --no-dev, Node.js 20, Vite Build  │
│  • Automated Deploy: SSH / Rsync ke cPanel Shared Hosting Subdomain     │
│  • Post-Deploy: php artisan migrate --force, route cache, symlink      │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 9. Alur Pengguna Utama (Key User Flows)

### Flow 1: Siklus Pengolahan & Penerbitan e-Rapor

```
Guru Mata Pelajaran
  → Input Nilai Harian, Kuis, & Tugas (NILAI-02)
  → Input Nilai UTS & UAS (NILAI-03)
  → Sistem kalkulasi nilai akhir terbobot secara otomatis (NILAI-04)
  → Input deskripsi capaian kompetensi per siswa (NILAI-06)
       │
       ▼
Wali Kelas
  → Input data Ekstrakurikuler & Prestasi Siswa (NILAI-07)
  → Review lembar nilai & peringkat kelas (NILAI-09)
       │
       ▼
Admin TU
  → Review ketercapaian nilai seluruh kelas (NILAI-08)
  → Finalisasi & Penguncian Nilai Rapor (is_final = true)
  → Publikasi rapor ke portal siswa
       │
       ▼
Siswa & Wali Kelas
  → Siswa mengunduh e-Rapor mandiri berformat PDF resmi (NILAI-10)
  → Wali Kelas / Admin mencetak e-Rapor kolektif per kelas (NILAI-11)
```

### Flow 2: Pembuatan Jadwal Bebas Bentrok (*Clashing-Free*)

```
Admin TU buka form pembuatan Jadwal Pelajaran
  → Pilih Tahun Ajaran, Kelas, Mata Pelajaran, & Guru Pengampu
  → Masukkan Hari, Jam Mulai, & Jam Selesai
  → Klik Simpan
       │
       ▼
Sistem memeriksa overlap waktu (Interval Clashing Check):
  T_start1 < T_end2  DAN  T_end1 > T_start2  (untuk guru & hari yang sama)
       │
       ├─► [Ada Tabrakan] ── Gagal disimpan! Tampilkan alert detail bentrok.
       │
       └─► [Bebas Bentrok] ── Data tersimpan! Jadwal muncul di tabel & kalender grid.
```

### Flow 3: Pembuatan Kelas & Validasi Wali Kelas Tunggal

```
Admin TU membuka form Tambah/Edit Kelas
  → Masukkan Tingkat (X/XI/XII), Jurusan, Nomor, Kapasitas, & Pilih Guru Wali Kelas
  → Klik Simpan
       │
       ▼
Sistem memvalidasi aturan integritas:
  1. Apakah kombinasi (Tingkat + Jurusan + Nomor) sudah ada di tahun ajaran ini?
  2. Apakah guru yang dipilih sudah menjadi wali kelas di kelas lain pada tahun ajaran ini?
       │
       ├─► [Melanggar Aturan] ── Muncul error validasi informatif pada form.
       │
       └─► [Valid] ── Kelas berhasil dibuat dan ditugaskan.
```

### Flow 4: Presensi Mandiri QR Code Dinamis & Pemantauan Real-Time

```
Guru Mata Pelajaran
  → Masuk ke Menu Absensi & Buka Sesi QR untuk Jadwal Hari Ini
  → Layar proyektor menampilkan QR Code Dinamis (rotasi tiap 30s) + Kode OTP Cadangan
  → Sistem menjalankan background live polling tiap 3 detik
       │
       ▼
Siswa (di kelas)
  → Buka menu Scanner Absensi di smartphone
  → Arahkan kamera ke QR di proyektor (atau input 6-digit rolling OTP)
       │
       ▼
Backend Server Validation:
  1. Apakah sesi absensi masih berstatus aktif?
  2. Apakah token QR / OTP cocok dengan signature HMAC dan belum expired?
  3. Apakah siswa tercatat sebagai anggota kelas jadwal tersebut?
  4. Apakah siswa sudah tercatat hadir sebelumnya? (Idempotent check)
       │
       ├─► [Gagal Validasi] ── Muncul modal error interaktif (HTTP 422/403).
       │
       └─► [Valid] ── Tercatat Hadir (metode = qr), detail waktu tercatat,
                     dan Audit Log tercipta otomatis.
       │
       ▼
Layar Guru
  → Tanpa reload, nama siswa yang baru absen otomatis berpindah dari daftar
    "Belum Absen" ke daftar "Sudah Hadir" dengan animasi transisi halus.
  → Guru dapat meng-override siswa bermasalah secara langsung dengan alasan wajib.
```

### Flow 5: Investigasi Jejak Audit (*Audit Trail Tracking*)

```
Admin TU
  → Membuka menu Audit Log (`admin.audit-log.index`)
  → Memilih filter:
      - Pelaku (User Tertentu)
      - Modul (misal: 'absensi', 'nilai', 'user')
      - Jenis Aksi ('created', 'updated', 'deleted')
      - Rentang Tanggal Kejadian
  → Membaca detail properti: Nilai Lama vs Nilai Baru
  → Admin dapat membuktikan secara sah siapa yang mengubah nilai/kehadiran dan kapan.
```

---

## 10. Matriks Hak Akses (Role-Based Access Control)

Hak akses dikelola secara terstruktur melalui permission dinamis Spatie dan terbagi ke dalam matriks peran default:

| Fitur / Modul | Permission Slug | Admin TU | Guru Mapel | Wali Kelas | Siswa |
|---|---|:---:|:---:|:---:|:---:|
| **Manajemen Pengguna (User CRUD)** | `manage-users` | ✅ | ❌ | ❌ | ❌ |
| **Manajemen Role & Izin RBAC** | `manage-roles` | ✅ | ❌ | ❌ | ❌ |
| **Master Tahun Ajaran & Status Aktif** | `manage-tahun-ajaran` | ✅ | ❌ | ❌ | ❌ |
| **Master Jurusan & Kelas** | `manage-kelas` | ✅ | ❌ | ❌ | ❌ |
| **Master Data Guru & Siswa** | `manage-guru`, `manage-siswa` | ✅ | ❌ | ❌ | ❌ |
| **Master Mapel & Konfigurasi Bobot** | `manage-mapel` | ✅ | ❌ | ❌ | ❌ |
| **Manajemen Jadwal & Mode Grid** | `manage-jadwal` | ✅ | 👁️ (Mapelnya) | 👁️ (Kelasnya) | 👁️ (Kelasnya) |
| **Input Kehadiran Siswa Manual** | `input-absensi` | ✅ | ✅ (Sesi Mengajar) | ✅ (Kelasnya) | ❌ |
| **Buka Sesi Absensi QR Dinamis** | `buka-sesi-qr` | ✅ | ✅ (Sesi Mengajar) | ❌ | ❌ |
| **Override Presensi Kehadiran** | `override-absensi` | ✅ | ✅ (Sesi Mengajar) | ❌ | ❌ |
| **Pindai Presensi Mandiri Siswa** | - (Role Siswa) | ❌ | ❌ | ❌ | ✅ (Kelasnya) |
| **Koreksi Absensi Administratif** | `koreksi-absensi` | ✅ | ❌ | ❌ | ❌ |
| **Tinjau Rekap Kehadiran** | `view-absensi-rekap` | ✅ | 👁️ (Sesi Mengajar) | 👁️ (Kelasnya) | 👁️ (Pribadi) |
| **Tinjau Jejak Audit (Audit Log)** | `lihat-audit-log` | ✅ | ❌ | ❌ | ❌ |
| **Input Nilai & Deskripsi Capaian** | `input-nilai` | ✅ | ✅ (Mapel yang Diampu) | ✅ (Mapelnya) | ❌ |
| **Finalisasi & Kunci Nilai Rapor** | `finalisasi-nilai` | ✅ | ❌ | ❌ | ❌ |
| **Lihat Peringkat Siswa** | `view-peringkat` | ✅ | ❌ | ✅ (Kelas Bimbingan) | ❌ |
| **Kelola Ekstrakurikuler & Prestasi** | `manage-siswa` / Nilai | ✅ | ❌ | ✅ (Kelas Bimbingan) | ❌ |
| **Cetak e-Rapor PDF Siswa Individual** | `export-rapor-kelas` / `download-rapor`| ✅ | ❌ | ✅ (Kelas Bimbingan) | ✅ (Rapor Sendiri) |
| **Cetak Kolektif e-Rapor Kelas (Batch)**| `export-rapor-kelas` | ✅ | ❌ | ✅ (Kelas Bimbingan) | ❌ |
| **Publikasi Pengumuman Sekolah** | - | ✅ | ✅ (Kelasnya) | ✅ (Kelasnya) | ❌ |
| **Kelola Profil & Ganti Password** | `view-profile` | ✅ | ✅ | ✅ | ✅ |

---

## 11. Rencana Rilis & Status Implementasi (Roadmap)

### 🚀 Fase 1 — MVP Inti (Bulan 1–4) — [SELESAI 100%]
- [x] Inisialisasi arsitektur Laravel 13, Tailwind CSS v4, dan skema migrasi database.
- [x] Modul autentikasi aman dan sistem role default.
- [x] Manajemen data master lengkap (Tahun Ajaran, Jurusan, Kelas, Guru, Siswa, Mapel).
- [x] Modul jadwal pelajaran mingguan dan absensi harian per sesi.
- [x] Pengolahan nilai harian, UTS, UAS, dan e-Rapor PDF dasar.
- [x] Dashboard fungsional untuk Admin, Guru, dan Siswa.

### 🔧 Fase 2 — Penyempurnaan, Validasi & RBAC (Bulan 5–8) — [SELESAI 100%]
- [x] **Dynamic Role-Based Access Control (RBAC)**: Integrasi penuh `spatie/laravel-permission` v8 dengan GUI web untuk kelola role, permission, dan penugasan role.
- [x] **Algoritma Anti-Bentrok Jadwal**: Logika deteksi irisan waktu mengajar guru serta visualisasi jadwal mode Grid Kalender.
- [x] **Validasi Integritas Wali Kelas**: Penegakan aturan database & form agar guru tidak memegang lebih dari satu perwalian di tahun ajaran aktif, serta validasi unik kombinasi kelas.
- [x] **Konfigurasi Bobot Nilai Interaktif**: GUI pengisian bobot dinamis dengan live visual feedback validasi total 100%.
- [x] **Penyempurnaan e-Rapor Kurikulum Merdeka**: Format PDF lengkap memuat identitas resmi, capaian kompetensi, kehadiran, ekstrakurikuler, prestasi siswa, dan catatan wali kelas.
- [x] **Ekspor Batch Rapor Kelas**: Kemampuan mencetak seluruh rapor siswa dalam satu kelas sekaligus dalam format PDF siap cetak.
- [x] **Peringkat Siswa Otomatis**: Kalkulasi ranking kelas berdasarkan nilai rerata akhir.
- [x] **Modul Pengumuman Multi-Target**: Publikasi pengumuman dengan segmentasi audiens (Semua, Guru, Siswa, atau Kelas).
- [x] **Pencarian & Penyaringan Responsif**: Input search dan filter instan di seluruh tabel data master.
- [x] **Automated Feature Testing**: Test suite PHPUnit/Pest untuk memvalidasi aturan integritas data.

### 🛡️ Fase 2.5 — Evolusi Keamanan, Absensi QR & Otomatisasi DevOps (Bulan 9–10) — [SELESAI 100%]
- [x] **Absensi QR Code Dinamis (F1)**:
  - Model `SesiAbsensiQr` dengan rotasi token per 30 detik berbasis signature HMAC-SHA256.
  - Tiket payload terenkripsi URL dan verifikasi kedaluwarsa ketat di sisi server.
  - Fallback input 6-digit rolling code OTP untuk perangkat dengan kendala kamera.
  - Pemindai kamera mandiri siswa berbasis library lokal `html5-qrcode.min.js`.
  - Live monitoring pemantauan presensi guru via AJAX polling per 3 detik.
  - Mekanisme override manual oleh guru dengan kewajiban input alasan.
- [x] **Audit Trail & Activity Log Sistem (F3)**:
  - Integrasi paket `spatie/laravel-activitylog` v4 untuk pelacakan mutasi model `Absensi`, `DetailAbsensi`, `Nilai`, dan `User`.
  - Antarmuka Admin Audit Log dengan pagination dan filter multi-dimensi (causer, log_name, event, tanggal, text search).
  - Skema append-only immutable untuk menjaga keabsahan bukti log.
  - Penambahan permission Spatie `lihat-audit-log`, `buka-sesi-qr`, dan `override-absensi`.
- [x] **Continuous Integration & Continuous Deployment (CI/CD)**:
  - Workflow GitHub Actions (`.github/workflows/deploy.yml`) untuk rilis otomatis ke cPanel Shared Hosting (subdomain).
  - Setup otomatis PHP 8.2, kompilasi aset Vite, rsync SSH, dan post-deployment migration database.
  - Penyesuaian constraint PHP `^8.2` pada `composer.json` untuk kompatibilitas hosting produksi.

### 🌟 Fase 3 — Skalabilitas & Komunikasi Eksternal (Masa Depan)
- [ ] **Portal Akses Orang Tua / Wali (F4)**: Tampilan khusus untuk orang tua murid guna memantau perkembangan nilai dan kehadiran anak secara langsung.
- [ ] **Notifikasi Multi-Channel Modular (F6)**: Integrasi pengiriman notifikasi pengumuman atau rekap kehadiran via Telegram Bot, Email, atau WhatsApp API adapter.
- [ ] **Kalender Akademik Terpadu (F5)**: Manajemen event sekolah (libur, ujian, kegiatan) dengan tampilan kalender visual.
- [ ] **Integrasi Dua Arah Dapodik**: Fasilitas impor/ekspor data format resmi Dapodik Kemdikbud secara berkala.
- [ ] **Multi-Tenancy**: Arsitektur multi-sekolah untuk mendukung pengelolaan banyak SMA dalam satu instansi yayasan.

---

## 12. Metrik Keberhasilan (Success Metrics)

| Metrik | Target Awal | Realisasi Terkini | Status |
|---|---|---|:---:|
| Waktu pengerjaan & penerbitan e-Rapor | Berkurang ≥ 70% | Dari ~14 hari kerja menjadi ≤ 2 hari | 🎯 Melampaui Target |
| Durasi absensi harian per kelas | < 2 menit | Dari ~10–15 menit menjadi ≤ 45 detik via QR | 🎯 Melampaui Target |
| Kasus guru mengajar bentrok di jadwal | 0 insiden | 0 insiden (dicegah di level validasi form & model) | 🎯 Tercapai |
| Kasus duplikasi penugasan wali kelas | 0 insiden | 0 insiden (dicegah oleh database unique rule & test) | 🎯 Tercapai |
| Akuntabilitas mutasi nilai & presensi | 100% terlacak | Seluruh aksi create/update/delete tercatat di Audit Log | 🎯 Tercapai |
| Waktu muat halaman dashboard & tabel | < 3.0 detik | Rata-rata 0.6 – 1.2 detik | 🎯 Tercapai |
| Keandalan CI/CD Deployment | 100% automated | Deploy otomatis sukses ke cPanel hosting via Git push | 🎯 Tercapai |
| Adopsi Guru & Siswa login | ≥ 80% | 100% akun terakomodasi dan aktif pada simulasi data | 🎯 Tercapai |

---

## 13. Risiko & Strategi Mitigasi

| Risiko | Tingkat | Dampak | Strategi Mitigasi Terapan |
|---|:---:|:---:|---|
| Siswa membagikan foto QR ke teman di luar kelas | Sedang | Sedang | Token QR berotasi otomatis setiap 30 detik berbasis HMAC secret, sehingga foto QR cepat usang dan ditolak server |
| Siswa mengalami kendala kamera scanner | Sedang | Rendah | Disediakan kode cadangan 6-digit OTP rolling code dan tombol override langsung oleh guru dengan pencatatan alasan |
| Kamera browser gagal berfungsi di jaringan lokal | Rendah | Tinggi | Library scanner `html5-qrcode.min.js` disimpan lokal (zero CDN dependency) dan HTTPS diwajibkan di lingkungan rilis |
| Perubahan nilai tidak sah tanpa jejak | Tinggi | Tinggi | Setiap mutasi model `Nilai` dan `Absensi` otomatis mencatat snapshot nilai lama vs baru di tabel `activity_log` append-only |
| Variasi bobot nilai antar-mata pelajaran | Sedang | Sedang | Menyediakan modul pengaturan bobot kustom per mapel dengan validasi live total harus 100% |
| Nilai siswa belum lengkap saat masa berjalan | Tinggi | Sedang | Algoritma normalisasi nilai dinamis yang menghitung proporsional nilai sementara tanpa distorsi angka |
| Kesalahan saat deploy manual ke cPanel | Sedang | Tinggi | Penerapan pipeline CI/CD GitHub Actions terstandarisasi yang mengeksekusi build, rsync, dan migrasi otomatis |

---

## 14. Asumsi & Ketergantungan Teknis

### Asumsi
- Sekolah beroperasi dengan sistem semester (2 semester per tahun ajaran: Ganjil & Genap).
- Komponen penilaian akademik standar terdiri dari Nilai Harian (tugas/kuis/ulangan harian), UTS, dan UAS dalam skala 0–100.
- Siswa membawa smartphone atau perangkat berkamera yang mendukung browser modern (Chrome, Safari, Firefox).
- Sekolah memiliki setidaknya 1 staf Admin/TU yang memegang peran super-admin pengelola sistem.

### Ketergantungan
- Lingkungan server PHP 8.2 / 8.3+ dengan ekstensi SQLite / MySQL, Mbstring, OpenSSL, GD, dan DomPDF dependencies.
- Font engine standar TrueType (DejaVu Sans / Helvetica) untuk kompilasi dokumen PDF berstandar cetak dinas.
- GitHub Secrets (`CPANEL_SSH_HOST`, `CPANEL_SSH_USER`, `CPANEL_SSH_KEY`, dll.) untuk pipeline otomatisasi deployment.

---

## 15. Catatan Perubahan Dokumen (Change Log)

| Versi | Tanggal | Pembaruan Utama | Kontributor |
|---|---|---|---|
| **v1.0** | 10 Mei 2026 | Draf inisial PRD, definisi user persona, perancangan kebutuhan MVP | Tim Pengembang |
| **v1.1** | 14 Juni 2026 | Penyelesaian MVP, penyesuaian dependensi Laravel, penambahan arsitektur data | Tim Pengembang |
| **v2.0** | 15 Oktober 2026 | Pembaruan spesifikasi produksi: integrasi Dynamic RBAC Spatie, validasi integritas wali kelas & kelas, jadwal anti-bentrok dengan grid kalender, konfigurasi bobot nilai interaktif, e-Rapor lengkap Kurikulum Merdeka (Ekskul, Prestasi, Catatan Wali Kelas, Batch Print), modul pengumuman multi-target, serta dokumentasi automated feature testing | Tim Pengembang |
| **v2.1** | 16 Oktober 2026 | **Evolusi Keamanan, Absensi QR & DevOps**: Penambahan spesifikasi Absensi QR Dinamis (F1) berotasi 30s + OTP cadangan, scanner kamera lokal `html5-qrcode`, live monitoring presensi guru (polling 3s), integrasi Jejak Audit append-only `spatie/laravel-activitylog` (F3) beserta GUI filter admin, penambahan permission RBAC (`buka-sesi-qr`, `override-absensi`, `lihat-audit-log`), otomatisasi deployment CI/CD GitHub Actions ke cPanel Shared Hosting, dan pembaruan constraint runtime PHP ^8.2 | Tim Pengembang |

---
*Dokumen ini merupakan spesifikasi kebutuhan produk resmi SIAKAD SMA.*
