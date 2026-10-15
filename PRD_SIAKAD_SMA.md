# Product Requirements Document (PRD)
# Sistem Informasi Akademik SMA (SIAKAD SMA)

---

| Field | Detail |
|---|---|
| **Versi Dokumen** | 2.0 (Post-MVP & Feature Enhancement) |
| **Tanggal** | 15 Oktober 2026 |
| **Status** | Produksi & Stabil (Feature Complete) |
| **Platform** | Web Application (Responsive Desktop, Tablet, & Mobile) |
| **Target Rilis** | Q4 2026 (Rilis Stabil v1.0 & v2.0) |

---

## 1. Ringkasan Eksekutif

SIAKAD SMA adalah platform administrasi akademik berbasis web modern yang dirancang untuk **menjembatani komunikasi, tata kelola data, dan alur kerja** antara tiga aktor utama sekolah: **Tata Usaha (Admin)**, **Guru (Pendidik & Wali Kelas)**, dan **Siswa**. Sistem ini mentransformasi proses administrasi sekolah dari metode manual (pembukuan fisik & lembar spreadsheet yang terfragmentasi) menjadi ekosistem digital terintegrasi.

Cakupan fungsional mencakup pengelolaan data master sekolah dengan validasi integritas ketat, penyusunan jadwal bebas bentrok (*clashing-free scheduling*), pencatatan kehadiran digital per sesi pelajaran, pengolahan nilai berbobot fleksibel berstandar Kurikulum Merdeka, hingga penerbitan dan ekspor dokumen e-Rapor resmi berformat PDF secara mandiri maupun kolektif.

---

## 2. Latar Belakang & Masalah

### 2.1 Masalah yang Diidentifikasi

| # | Masalah | Dampak Nyata di Sekolah |
|---|---|---|
| 1 | Data siswa & kelas dikelola terpisah di spreadsheet, rentan duplikasi & inkonsistensi | Inefisiensi operasional tinggi dan risiko kehilangan data |
| 2 | Jadwal pelajaran dibuat manual, sering terjadi guru mengajar di dua kelas pada jam yang sama | Tabrakan jadwal (*schedule clashing*), kebingungan guru & siswa |
| 3 | Pengangkatan wali kelas rawan tumpang tindih (satu guru memegang banyak kelas di tahun ajaran sama) | Pelanggaran beban kerja dan pembagian tugas perwalian yang tidak merata |
| 4 | Rekapitulasi absensi harian di kertas memakan waktu lama setiap akhir bulan/semester | Beban administrasi berlebih bagi guru mata pelajaran dan wali kelas |
| 5 | Kalkulasi nilai rapor dihitung manual dengan bobot yang kaku atau tidak konsisten | Potensi kesalahan hitung nilai akhir siswa dan keterlambatan rapor |
| 6 | Siswa & wali murid tidak memiliki visibilitas real-time terhadap kehadiran dan nilai berkala | Kurangnya transparansi pemantauan akademik sebelum rapor fisik terbit |
| 7 | Pengumuman sekolah tersebar lewat grup chat informal yang mudah tertimbun | Informasi penting sekolah tidak tersampaikan secara merata dan terarsip |

### 2.2 Peluang & Regulasi
- **Kurikulum Merdeka**: Mendorong pelaporan berbasis capaian kompetensi dengan deskripsi capaian pembelajaran yang terpersonalisasi per siswa.
- **Efisiensi Administrasi**: Mengurangi durasi pembuatan e-Rapor dari ~2 minggu menjadi ≤ 3 hari kerja.
- **Kemandirian Sekolah**: Menghadirkan sistem terpadu yang ringan, minim ketergantungan infrastruktur rumit (zero-config SQLite untuk lokal dan MySQL/PostgreSQL untuk skala penuh).

---

## 3. Visi & Tujuan Produk

> **Visi:** Menjadi platform administrasi akademik SMA yang paling andal, intuitif, dan efisien, sehingga pendidik fokus membimbing dan siswa fokus berkembang.

### 3.1 Tujuan Bisnis & Key Results (OKR)

| Objective | Key Result | Status Pencapaian |
|---|---|:---:|
| Meningkatkan efisiensi kerja staf & guru | Memangkas waktu penyusunan jadwal dan kalkulasi rapor ≥ 70% | ✅ Tercapai |
| Menjamin integritas & validitas data akademik | Zero konflik jadwal guru dan zero duplikasi wali kelas per tahun ajaran | ✅ Tercapai |
| Mendorong transparansi akademik | 100% siswa aktif dapat mengakses jadwal, kehadiran, dan e-Rapor mandiri | ✅ Tercapai |
| Keamanan & fleksibilitas tata kelola | Penerapan Dynamic Role-Based Access Control (RBAC) berbasis database | ✅ Tercapai |

---

## 4. Target Pengguna (User Personas)

### 👤 Persona 1 — Admin / Tata Usaha (TU)
- **Profil:** Staf administrasi sekolah, mengelola operasional harian dan data pokok sekolah.
- **Goal:** Mengontrol seluruh master data, memvalidasi pembagian kelas & wali kelas, menyusun jadwal pelajaran, memverifikasi nilai rapor, serta mengelola akun dan izin hak akses secara fleksibel.
- **Pain Point:** Sering terjadi jadwal guru bentrok saat awal semester dan koreksi data nilai siswa memakan waktu lama.

### 👤 Persona 2 — Guru (Pendidik & Wali Kelas)
- **Profil:** Tenaga pengajar mata pelajaran dan wali kelas bimbingan.
- **Goal:** Melihat jadwal mengajar pribadi, mengisi absensi per jam pelajaran, menginput nilai harian/UTS/UAS dengan kalkulasi otomatis, mengisi deskripsi capaian kompetensi, serta mengunduh e-Rapor kelas bimbingannya.
- **Pain Point:** Menghitung manual persentase bobot nilai dan merekap kehadiran ratusan siswa di akhir semester.

### 👤 Persona 3 — Siswa
- **Profil:** Pelajar tingkat SMA (Kelas X, XI, XII).
- **Goal:** Memantau jadwal pelajaran mingguan, mengecek riwayat absensi, membaca pengumuman sekolah, serta melihat lembar nilai dan mengunduh berkas resmi e-Rapor PDF setelah difinalisasi.
- **Pain Point:** Tidak mengetahui rekap nilai berjalan dan kartu rapor fisik sering rusak atau lambat dibagikan.

---

## 5. Ruang Lingkup Produk

### 5.1 In-Scope (Fitur Selesai & Aktif di v2.0)
- ✅ **Dynamic RBAC**: Manajemen Role & Permission dinamis melalui antarmuka web ([spatie/laravel-permission](https://spatie.be/docs/laravel-permission)).
- ✅ **Manajemen Data Master**: Tahun Ajaran (kontrol aktif global), Jurusan, Kelas, Mata Pelajaran, Guru, Siswa, serta mutasi pindah kelas.
- ✅ **Validasi Integritas Ketat**:
  - Pencegahan penugasan guru yang sama sebagai wali kelas ganda pada tahun ajaran yang sama.
  - Pencegahan duplikasi kombinasi Tingkat, Jurusan, dan Nomor Kelas.
- ✅ **Jadwal Pelajaran Bebas Bentrok**:
  - Algoritma pencegah tabrakan jadwal (*time overlap detection*) antar-kelas untuk guru yang sama.
  - Tampilan visual Grid Kalender mingguan dan Tabel tabular dengan fitur pencarian & penyaringan.
- ✅ **Absensi Digital**: Pencatatan kehadiran per sesi kelas (Hadir, Sakit, Izin, Alpa) dan fitur koreksi administratif oleh Admin.
- ✅ **Pengolahan Nilai Berbobot & e-Rapor**:
  - Pengaturan persentase bobot nilai (Harian, UTS, UAS) fleksibel dengan live validasi total 100%.
  - Perhitungan otomatis nilai akhir dengan algoritma normalisasi dinamis saat komponen nilai belum lengkap.
  - Pencatatan kegiatan Ekstrakurikuler dan Prestasi Siswa.
  - Lembar e-Rapor PDF resmi standar Kurikulum Merdeka (ekspor mandiri siswa, per siswa oleh guru/admin, dan ekspor kolektif per kelas).
- ✅ **Peringkat Siswa**: Penentuan ranking otomatis kelas berdasarkan rerata nilai akhir.
- ✅ **Modul Pengumuman Terarah**: Publikasi pengumuman dengan target audiens spesifik (Semua, Guru, Siswa, atau Kelas tertentu).
- ✅ **Automated Testing Suite**: Pengujian logika bisnis dan validasi aturan database menggunakan automated Feature Tests.

### 5.2 Out-of-Scope (Rencana Pengembangan Masa Depan / Fase 3)
- ⏳ Portal akses khusus Orang Tua / Wali Murid (akan diintegrasikan dengan notifikasi WhatsApp).
- ⏳ Integrasi sinkronisasi langsung dua arah dengan API Dapodik Kemdikbud.
- ⏳ Aplikasi Mobile Native (iOS & Android) — saat ini web sudah sepenuhnya responsif via mobile browser.
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

### 6.4 Modul Absensi Digital (Guru & Admin)

| ID | Fitur | Keterangan & Aturan Bisnis | Prioritas | Status |
|---|---|---|---|:---:|
| ABSEN-01 | Input Kehadiran Sesi | Guru mata pelajaran menginput kehadiran per siswa (Hadir, Sakit, Izin, Alpa) pada jadwal aktif | 🔴 Must Have | ✅ Selesai |
| ABSEN-02 | Rekapitulasi Otomatis | Sistem otomatis menghitung total H/S/I/A dan persentase kehadiran kumulatif per siswa | 🔴 Must Have | ✅ Selesai |
| ABSEN-03 | Rekap Semester Kelas | Tinjauan menyeluruh kehadiran seluruh siswa dalam satu kelas per semester | 🔴 Must Have | ✅ Selesai |
| ABSEN-04 | Koreksi Absensi Admin | Admin TU memiliki wewenang mengoreksi catatan kehadiran jika terdapat surat dokter/izin susulan | 🔴 Must Have | ✅ Selesai |
| ABSEN-05 | Portal Kehadiran Siswa | Siswa dapat memantau akumulasi kehadiran dan tingkat kedisiplinan belajar secara mandiri | 🟡 Should Have | ✅ Selesai |

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

## 7. Spesifikasi Non-Fungsional

| Kategori | Parameter | Spesifikasi & Tolok Ukur |
|---|---|---|
| **Performa & Responsivitas** | Response Time | Waktu load halaman dashboard dan data master < 2.0 detik pada koneksi internet standar (10 Mbps) |
| **Keamanan** | Enkripsi & Proteksi | Kata sandi di-hash menggunakan algoritma Bcrypt; seluruh form dilengkapi proteksi CSRF token; input tersanitasi dari ancaman XSS & SQL Injection |
| **Otorisasi & Hak Akses** | Dynamic RBAC | Seluruh rute dan aksi terlindungi oleh middleware Spatie Permission; pengguna dilarang mengakses modul di luar izin yang diberikan (HTTP 403) |
| **Integritas Basis Data** | Constraints & Transactions | Relasi database dilindungi oleh foreign keys dan index; operasi transaksi majemuk (seperti perhitungan rapor massal) dibungkus dalam `DB::transaction` |
| **Kompatibilitas Tampilan** | Cross-Browser & Device | UI responsif menggunakan Tailwind CSS v4, berjalan optimal pada Chrome, Firefox, Edge, Safari (Desktop, Tablet, Smartphone) |
| **Generasi Dokumen** | PDF Engine | Hasil render e-Rapor PDF menggunakan format kertas A4 standar cetak dinas dengan margin presisi dan font ramah cetak |
| **Keandalan & Pengujian** | Test Automation | Dilengkapi automated Feature Tests untuk memverifikasi integritas aturan bisnis (seperti validasi wali kelas ganda) |

---

## 8. Arsitektur Sistem (Implementasi Aktual)

Sistem SIAKAD SMA mengadopsi pola arsitektur **Monolith Modern (Model-View-Controller)** yang menggabungkan performa server-side rendering dengan kecepatan build modern:

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    KLIEN / BROWSER PENGGUNA                             │
│       Admin / TU                  Guru / Wali Kelas           Siswa     │
│   (Desktop / Laptop)             (Laptop / Tablet)      (Smartphone/PC) │
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ HTTPS
┌────────────────────────────────────▼────────────────────────────────────┐
│                    FRONTEND LAYER (Server-Side Blade)                   │
│   • Blade View Templates (app, admin, guru, siswa, pdf)                 │
│   • Tailwind CSS v4 (Modern Utility Engine)                             │
│   • Asset Bundling & Hot Module Replacement via Vite 8.x                │
│   • Google Fonts (Inter & Plus Jakarta Sans)                            │
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ PHP 8.3+ Dispatcher
┌────────────────────────────────────▼────────────────────────────────────┐
│                  BACKEND CORE (Laravel 13.x MVC)                        │
│                                                                         │
│  [ Routing & Middleware (Auth, Spatie Dynamic RBAC) ]                  │
│                                                                         │
│  Controllers:                                                           │
│  ├─ Admin: Master Data, Kelas, Jadwal, Absensi, Nilai, RBAC, Users     │
│  ├─ Guru : Jadwal, Input Absensi, Nilai, Deskripsi, Rapor Perwalian     │
│  ├─ Siswa: Dashboard, Jadwal, Absensi, e-Rapor Download                 │
│  └─ Auth & Profile: Sesi, Kredensial, Pengaturan Akun                   │
│                                                                         │
│  Services & Libraries:                                                  │
│  ├─ Barryvdh Laravel-DomPDF (Penerbit e-Rapor PDF Resmi)                │
│  ├─ Maatwebsite Excel (Pengolahan Lembar Kerja)                         │
│  └─ Logika Anti-Bentrok Jadwal & Normalisasi Nilai                      │
└───────────────────┬─────────────────────────────────┬───────────────────┘
                    │                                 │
┌───────────────────▼───────────────┐ ┌───────────────▼───────────────────┐
│         DATABASE STORAGE          │ │           FILE STORAGE            │
│  • SQLite (Default Dev/Testing)   │ │  • Storage / Public Directory    │
│  • MySQL / PostgreSQL (Production)│ │  • Aset Logo Sekolah, Tanda Tangan│
│  • Spatie RBAC Tables (Roles,     │ │  • Cache Template & Rapor PDF     │
│    Permissions, ModelHasRoles)    │ │                                   │
└───────────────────────────────────┘ └───────────────────────────────────┘
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
  → Finalisasi & Penguncian Nilai Rapor (*is_final = true*)
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
| **Input Kehadiran Siswa** | `input-absensi` | ✅ | ✅ (Sesi Mengajar) | ✅ (Kelasnya) | ❌ |
| **Koreksi Absensi Administratif** | `koreksi-absensi` | ✅ | ❌ | ❌ | ❌ |
| **Tinjau Rekap Kehadiran** | `view-absensi-rekap` | ✅ | 👁️ (Sesi Mengajar) | 👁️ (Kelasnya) | 👁️ (Pribadi) |
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

### 🌟 Fase 3 — Skalabilitas & Ekspansi (Masa Depan)
- [ ] **Portal Akses Orang Tua / Wali**: Tampilan khusus untuk orang tua murid guna memantau perkembangan nilai dan kehadiran anak secara langsung.
- [ ] **Notifikasi Multi-Channel**: Integrasi pengiriman notifikasi pengumuman atau rekap kehadiran via WhatsApp API / Email.
- [ ] **Integrasi Dua Arah Dapodik**: Fasilitas impor/ekspor data format resmi Dapodik Kemdikbud secara berkala.
- [ ] **PWA (Progressive Web App)**: Peningkatan kapabilitas web untuk instalasi layar utama smartphone dan mode offline terbatas.
- [ ] **Multi-Tenancy**: Arsitektur multi-sekolah untuk mendukung pengelolaan banyak SMA dalam satu instansi yayasan.

---

## 12. Metrik Keberhasilan (Success Metrics)

| Metrik | Target Awal | Realisasi Terkini | Status |
|---|---|---|:---:|
| Waktu pengerjaan & penerbitan e-Rapor | Berkurang ≥ 70% | Dari ~14 hari kerja menjadi ≤ 2 hari | 🎯 Melampaui Target |
| Kasus guru mengajar bentrok di jadwal | 0 insiden | 0 insiden (dicegah di level validasi form & model) | 🎯 Tercapai |
| Kasus duplikasi penugasan wali kelas | 0 insiden | 0 insiden (dicegah oleh database unique rule & test) | 🎯 Tercapai |
| Waktu muat halaman dashboard & tabel | < 3.0 detik | Rata-rata 0.6 – 1.2 detik | 🎯 Tercapai |
| Adopsi Guru & Siswa login | ≥ 80% | 100% akun terakomodasi dan aktif pada simulasi data | 🎯 Tercapai |

---

## 13. Risiko & Strategi Mitigasi

| Risiko | Tingkat | Dampak | Strategi Mitigasi Terapan |
|---|:---:|:---:|---|
| Variasi bobot nilai antar-mata pelajaran | Sedang | Sedang | Menyediakan modul pengaturan bobot kustom per mapel dengan validasi live total harus 100% |
| Nilai siswa belum lengkap saat masa berjalan | Tinggi | Sedang | Algoritma normalisasi nilai dinamis yang menghitung proporsional nilai sementara tanpa distorsi angka |
| Kesalahan atau keterlambatan surat izin absensi | Rendah | Rendah | Disediakan modul Koreksi Absensi khusus bagi Admin TU untuk audit dan perbaikan data kehadiran |
| Human error saat penugasan guru & pembagian kelas | Sedang | Tinggi | Penerapan validasi unik ganda di level Request Validation dan Database Constraints, serta didukung unit testing |

---

## 14. Asumsi & Ketergantungan Teknis

### Asumsi
- Sekolah beroperasi dengan sistem semester (2 semester per tahun ajaran: Ganjil & Genap).
- Komponen penilaian akademik standar terdiri dari Nilai Harian (tugas/kuis/ulangan harian), UTS, dan UAS dalam skala 0–100.
- Sekolah memiliki setidaknya 1 staf Admin/TU yang memegang peran super-admin pengelola sistem.

### Ketergantungan
- Lingkungan server PHP 8.3+ dengan ekstensi SQLite / MySQL, Mbstring, OpenSSL, dan DomPDF dependencies.
- Font engine standar TrueType (DejaVu Sans / Helvetica) untuk kompilasi dokumen PDF berstandar cetak.

---

## 15. Catatan Perubahan Dokumen (Change Log)

| Versi | Tanggal | Pembaruan Utama | Kontributor |
|---|---|---|---|
| **v1.0** | 10 Mei 2026 | Draf inisial PRD, definisi user persona, perancangan kebutuhan MVP | Tim Pengembang |
| **v1.1** | 14 Juni 2026 | Penyelesaian MVP, penyesuaian dependensi Laravel, penambahan arsitektur data | Tim Pengembang |
| **v2.0** | 15 Oktober 2026 | Pembaruan spesifikasi produksi: integrasi Dynamic RBAC Spatie, validasi integritas wali kelas & kelas, jadwal anti-bentrok dengan grid kalender, konfigurasi bobot nilai interaktif, e-Rapor lengkap Kurikulum Merdeka (Ekskul, Prestasi, Catatan Wali Kelas, Batch Print), modul pengumuman multi-target, serta dokumentasi automated feature testing | Tim Pengembang |

---
*Dokumen ini merupakan spesifikasi kebutuhan produk resmi SIAKAD SMA.*

