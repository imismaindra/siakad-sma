# Product Requirements Document (PRD)
# Sistem Informasi Akademik SMA (SIAKAD SMA)

---

| Field | Detail |
|---|---|
| **Versi Dokumen** | 1.1 |
| **Tanggal** | 14 Juni 2026 |
| **Status** | MVP Selesai — Tahap Uji Coba |
| **Platform** | Web Application |
| **Target Rilis Awal** | Q4 2026 (MVP) |

---

## 1. Ringkasan Eksekutif

SIAKAD SMA adalah platform administrasi akademik berbasis web yang dirancang untuk **menjembatani komunikasi dan alur kerja** antara tiga aktor utama sekolah: **Tata Usaha (Admin)**, **Guru**, dan **Siswa**. Sistem ini mendigitalisasi proses yang selama ini dilakukan secara manual — mulai dari pengelolaan data siswa, penjadwalan pelajaran, absensi, hingga publikasi nilai rapor elektronik (e-Rapor) — sehingga menghasilkan ekosistem sekolah yang lebih efisien, transparan, dan terukur.

---

## 2. Latar Belakang & Masalah

### 2.1 Masalah yang Diidentifikasi

| # | Masalah | Dampak |
|---|---|---|
| 1 | Data siswa masih dikelola di spreadsheet/buku, rentan hilang & duplikat | Inefisiensi tinggi |
| 2 | Jadwal pelajaran dibuat manual, sulit diperbarui jika ada perubahan | Kebingungan siswa & guru |
| 3 | Rekapitulasi absensi memakan waktu lama setiap semester | Beban administratif guru |
| 4 | Nilai rapor dihitung dan direkap manual, rawan kesalahan | Ketidakakuratan data akademik |
| 5 | Siswa/orang tua tidak bisa memantau perkembangan akademik secara real-time | Kurangnya transparansi |
| 6 | Komunikasi antar-staf sekolah tidak terpusat | Informasi tidak merata |

### 2.2 Peluang

- Regulasi Kemdikbud mendorong digitalisasi sekolah (e-Rapor Kurikulum Merdeka).
- Penetrasi smartphone dan internet di kalangan pelajar SMA sudah tinggi.
- Belum banyak solusi terjangkau yang dikhususkan untuk SMA negeri/swasta kecil-menengah.

---

## 3. Visi & Tujuan Produk

> **Visi:** Menjadi platform administrasi akademik SMA yang paling mudah digunakan, sehingga guru bisa fokus mengajar dan siswa bisa fokus belajar.

### 3.1 Tujuan Bisnis (OKR)

| Objective | Key Result |
|---|---|
| Meningkatkan efisiensi administrasi sekolah | Mengurangi waktu pengerjaan rapor dari ~2 minggu menjadi ≤ 3 hari |
| Meningkatkan keterlibatan siswa & orang tua | 80% siswa aktif login dan memantau nilai dalam 3 bulan pertama |
| Menjadi solusi terpercaya bagi sekolah | Onboarding minimal 10 sekolah dalam 12 bulan pertama |

---

## 4. Target Pengguna (User Personas)

### 👤 Persona 1 — Admin / Tata Usaha (TU)

- **Profil:** Staf administrasi, usia 30–50 tahun, melek komputer dasar.
- **Goal:** Mengelola semua data sekolah dari satu tempat tanpa harus menggunakan banyak file Excel.
- **Pain Point:** Data siswa tersebar, perubahan jadwal butuh koordinasi panjang, laporan semester menyita waktu.
- **Kutipan:** *"Saya butuh sistem yang bisa langsung cetak laporan tanpa perlu rekap dulu dari banyak file."*

### 👤 Persona 2 — Guru

- **Profil:** Pengajar mata pelajaran, usia 25–55 tahun, kemampuan digital bervariasi.
- **Goal:** Input nilai dan absensi dengan cepat, melihat jadwal mengajar, berkomunikasi dengan siswa.
- **Pain Point:** Menghitung nilai akhir manual sangat rawan salah, absensi kertas mudah hilang.
- **Kutipan:** *"Kalau input nilai bisa langsung di HP atau laptop, saya tidak perlu lagi rekap di buku nilai."*

### 👤 Persona 3 — Siswa

- **Profil:** Pelajar SMA, usia 15–18 tahun, sangat terbiasa dengan smartphone dan internet.
- **Goal:** Melihat nilai, jadwal pelajaran, dan pengumuman dari mana saja.
- **Pain Point:** Sering tidak tahu jadwal ujian atau perubahan kelas mendadak.
- **Kutipan:** *"Saya mau bisa cek nilai langsung dari HP, bukan nunggu rapor dicetak."*

---

## 5. Ruang Lingkup Produk

### 5.1 In-Scope (MVP — Fase 1)

- ✅ Manajemen data master (siswa, guru, kelas, mata pelajaran)
- ✅ Manajemen jadwal pelajaran
- ✅ Sistem absensi digital (per kelas per sesi)
- ✅ Input & kalkulasi nilai (harian, UTS, UAS, nilai akhir)
- ✅ e-Rapor (publikasi & ekspor PDF)
- ✅ Dashboard per role (Admin, Guru, Siswa)
- ✅ Manajemen akun & autentikasi

### 5.2 Out-of-Scope (Fase Berikutnya)

- ❌ Sistem pembayaran SPP / keuangan sekolah
- ❌ Aplikasi mobile native (iOS/Android)
- ❌ Integrasi dengan Dapodik secara otomatis
- ❌ Forum diskusi / LMS (Learning Management System)
- ❌ Portal orang tua (dipertimbangkan di Fase 2)

---

## 6. Fitur & Spesifikasi Fungsional

---

### 6.1 Modul Autentikasi & Akun

| ID | Fitur | Prioritas |
|---|---|---|
| AUTH-01 | Login dengan username & password | 🔴 Must Have |
| AUTH-02 | Role-based access control (Admin, Guru, Siswa) | 🔴 Must Have |
| AUTH-03 | Reset password via email | 🟡 Should Have |
| AUTH-04 | Sesi otomatis berakhir setelah idle (timeout) | 🟡 Should Have |
| AUTH-05 | Log aktivitas login/logout | 🟢 Nice to Have |

**User Story:**
> Sebagai **Admin**, saya ingin membuat akun pengguna baru dengan role tertentu, sehingga setiap staf dan siswa dapat mengakses sistem sesuai hak aksesnya.

---

### 6.2 Modul Manajemen Data Master (Admin)

| ID | Fitur | Prioritas |
|---|---|---|
| MASTER-01 | CRUD data siswa (nama, NIS, NISN, kelas, tanggal lahir, dll.) | 🔴 Must Have |
| MASTER-02 | CRUD data guru (nama, NIP, mata pelajaran, dll.) | 🔴 Must Have |
| MASTER-03 | CRUD data kelas (tingkat, jurusan, wali kelas) | 🔴 Must Have |
| MASTER-04 | CRUD mata pelajaran (nama, kode, KKM) | 🔴 Must Have |
| MASTER-05 | Pembagian siswa ke kelas (naik kelas / pindah kelas) | 🔴 Must Have |
| MASTER-06 | Import data siswa dari file Excel/CSV | 🟡 Should Have |
| MASTER-07 | Export data siswa ke Excel/PDF | 🟡 Should Have |
| MASTER-08 | Manajemen tahun ajaran & semester aktif | 🔴 Must Have |

**User Story:**
> Sebagai **Admin TU**, saya ingin mengimpor data siswa baru dari file Excel agar saya tidak perlu menginput satu per satu secara manual.

---

### 6.3 Modul Jadwal Pelajaran (Admin)

| ID | Fitur | Prioritas |
|---|---|---|
| JADWAL-01 | Buat jadwal pelajaran per kelas per minggu | 🔴 Must Have |
| JADWAL-02 | Tetapkan guru pengampu untuk setiap slot jadwal | 🔴 Must Have |
| JADWAL-03 | Deteksi konflik jadwal (guru double-booking) | 🔴 Must Have |
| JADWAL-04 | Publikasi jadwal ke semua pengguna | 🔴 Must Have |
| JADWAL-05 | Edit jadwal dengan notifikasi perubahan | 🟡 Should Have |
| JADWAL-06 | Tampilan jadwal dalam format kalender/grid | 🟡 Should Have |

**User Story:**
> Sebagai **Admin**, saya ingin sistem memberi peringatan jika seorang guru dijadwalkan mengajar dua kelas di waktu yang sama, agar tidak terjadi konflik.

---

### 6.4 Modul Absensi (Guru & Admin)

| ID | Fitur | Prioritas |
|---|---|---|
| ABSEN-01 | Guru input absensi siswa per sesi pelajaran | 🔴 Must Have |
| ABSEN-02 | Status absensi: Hadir, Sakit, Izin, Alpa | 🔴 Must Have |
| ABSEN-03 | Rekap absensi bulanan & semesteran per siswa | 🔴 Must Have |
| ABSEN-04 | Admin dapat melihat dan mengoreksi absensi | 🔴 Must Have |
| ABSEN-05 | Notifikasi otomatis jika siswa alpa melebihi batas | 🟡 Should Have |
| ABSEN-06 | Siswa dapat melihat rekap absensi miliknya | 🟡 Should Have |

**User Story:**
> Sebagai **Guru**, saya ingin bisa menginput absensi kelas langsung dari browser laptop/tablet saya sebelum pelajaran dimulai.

---

### 6.5 Modul Penilaian & e-Rapor (Guru & Admin)

| ID | Fitur | Prioritas |
|---|---|---|
| NILAI-01 | Guru input nilai harian (tugas, kuis) per siswa | 🔴 Must Have |
| NILAI-02 | Guru input nilai UTS dan UAS | 🔴 Must Have |
| NILAI-03 | Kalkulasi otomatis nilai akhir berdasarkan bobot | 🔴 Must Have |
| NILAI-04 | Konfigurasi bobot nilai per mata pelajaran | 🔴 Must Have |
| NILAI-05 | Input nilai deskripsi/catatan per siswa per mapel | 🔴 Must Have |
| NILAI-06 | Tampilan nilai per siswa untuk guru dan siswa | 🔴 Must Have |
| NILAI-07 | Admin dapat memfinalisasi & mengunci nilai rapor | 🔴 Must Have |
| NILAI-08 | Publikasi e-Rapor ke siswa setelah dikunci | 🔴 Must Have |
| NILAI-09 | Ekspor rapor ke format PDF per siswa / per kelas | 🔴 Must Have |
| NILAI-10 | Rekap peringkat kelas berdasarkan nilai akhir | 🟡 Should Have |
| NILAI-11 | Riwayat rapor per semester (arsip digital) | 🟡 Should Have |
| NILAI-12 | Notifikasi ke siswa saat rapor dipublikasikan | 🟡 Should Have |

**User Story:**
> Sebagai **Guru Matematika**, saya ingin sistem secara otomatis menghitung nilai akhir siswa berdasarkan bobot yang sudah saya atur (40% harian + 30% UTS + 30% UAS), agar saya tidak perlu menghitung manual.

---

### 6.6 Dashboard & Pelaporan

| ID | Fitur | Prioritas |
|---|---|---|
| DASH-01 | Dashboard Admin: ringkasan jumlah siswa, guru, kelas aktif | 🔴 Must Have |
| DASH-02 | Dashboard Guru: jadwal mengajar hari ini, kelas yang belum absen | 🔴 Must Have |
| DASH-03 | Dashboard Siswa: jadwal hari ini, nilai terbaru, pengumuman | 🔴 Must Have |
| DASH-04 | Laporan rekap absensi per kelas (Admin) | 🟡 Should Have |
| DASH-05 | Laporan progres nilai per kelas (Admin) | 🟡 Should Have |
| DASH-06 | Widget statistik visual (grafik/chart) | 🟢 Nice to Have |

---

### 6.7 Modul Pengumuman (Admin & Guru)

| ID | Fitur | Prioritas |
|---|---|---|
| ANNC-01 | Admin/Guru membuat pengumuman teks | 🟡 Should Have |
| ANNC-02 | Target pengumuman: semua, per kelas, atau per role | 🟡 Should Have |
| ANNC-03 | Siswa melihat pengumuman di dashboard | 🟡 Should Have |

---

## 7. Spesifikasi Non-Fungsional

| Kategori | Spesifikasi |
|---|---|
| **Performa** | Halaman utama load < 3 detik pada koneksi 10 Mbps |
| **Ketersediaan** | Uptime 99.5% (eksklusif maintenance terjadwal) |
| **Keamanan** | HTTPS wajib, password di-hash (bcrypt), proteksi CSRF & XSS |
| **Skalabilitas** | Mendukung hingga 2.000 pengguna aktif per sekolah |
| **Kompatibilitas** | Browser: Chrome, Firefox, Edge (versi 2 tahun terakhir) |
| **Responsivitas** | UI responsif untuk desktop, tablet, dan mobile browser |
| **Aksesibilitas** | Kontras warna WCAG AA minimum |
| **Backup Data** | Backup otomatis harian, retensi 30 hari |
| **Audit Trail** | Log perubahan data penting (nilai, absensi) |

---

## 8. Arsitektur Sistem (High-Level)

```
┌─────────────────────────────────────────────────────┐
│                   PENGGUNA (Browser)                │
│         Admin / TU     Guru       Siswa             │
└────────────────────┬────────────────────────────────┘
                     │ HTTPS
┌────────────────────▼────────────────────────────────┐
│              WEB APPLICATION (Frontend)             │
│          React.js / Next.js + Tailwind CSS          │
└────────────────────┬────────────────────────────────┘
                     │ REST API / JSON
┌────────────────────▼────────────────────────────────┐
│               BACKEND SERVER (API)                  │
│         Node.js + Express / Laravel (PHP)           │
│  [ Auth ] [ Data Master ] [ Jadwal ] [ Nilai ]      │
│  [ Absensi ] [ Rapor PDF Generator ] [ Notif ]      │
└─────────┬──────────────────────────────┬────────────┘
          │                              │
┌─────────▼──────────┐      ┌────────────▼────────────┐
│   DATABASE         │      │   FILE STORAGE          │
│   PostgreSQL /     │      │   PDF Rapor, Upload     │
│   MySQL            │      │   Foto Siswa, dsb.      │
└────────────────────┘      └─────────────────────────┘
```

---

## 9. Alur Pengguna Utama (Key User Flows)

### Flow 1: Publikasi Rapor Semester

```
Admin buka modul Rapor
  → Pilih Tahun Ajaran & Semester
  → Guru input semua nilai (NILAI-01 s.d. NILAI-05)
  → Sistem kalkulasi otomatis nilai akhir (NILAI-03)
  → Admin review & finalisasi nilai (NILAI-07)
  → Admin kunci & publikasikan rapor (NILAI-08)
  → Siswa terima notifikasi & buka e-Rapor (NILAI-12)
  → Siswa/Admin ekspor PDF (NILAI-09)
```

### Flow 2: Input Absensi Harian

```
Guru login → Dashboard Guru
  → Lihat jadwal hari ini (JADWAL-04)
  → Klik "Input Absensi" untuk kelas aktif
  → Daftar siswa muncul otomatis
  → Guru klik status per siswa (Hadir/Sakit/Izin/Alpa)
  → Simpan → Rekap terupdate otomatis (ABSEN-03)
```

### Flow 3: Onboarding Sekolah Baru (Admin)

```
Admin login pertama kali
  → Isi profil sekolah (nama, NPSN, alamat)
  → Set tahun ajaran & semester aktif (MASTER-08)
  → Import/input data siswa (MASTER-01, MASTER-06)
  → Input data guru & mata pelajaran
  → Buat kelas & tetapkan wali kelas (MASTER-03)
  → Susun jadwal pelajaran (JADWAL-01, 02)
  → Buat akun guru & siswa (AUTH-01)
  → Sistem siap digunakan
```

---

## 10. Matriks Hak Akses (Role-Based Access Control)

| Fitur / Modul | Admin/TU | Guru | Siswa |
|---|:---:|:---:|:---:|
| Data Master Siswa (CRUD) | ✅ | 👁️ Read | ✅ Data sendiri |
| Data Master Guru | ✅ | 👁️ Read | ❌ |
| Kelola Kelas & Jadwal | ✅ | 👁️ Read | 👁️ Read |
| Input Absensi | ✅ | ✅ (kelasnya) | ❌ |
| Lihat Rekap Absensi | ✅ | ✅ (kelasnya) | ✅ (sendiri) |
| Input Nilai | ✅ | ✅ (mapelnya) | ❌ |
| Finalisasi & Kunci Nilai | ✅ | ❌ | ❌ |
| Lihat Nilai & Rapor | ✅ | ✅ (kelasnya) | ✅ (sendiri) |
| Ekspor PDF Rapor | ✅ | ✅ (kelasnya) | ✅ (sendiri) |
| Buat Pengumuman | ✅ | ✅ | ❌ |
| Kelola Akun Pengguna | ✅ | ❌ | ❌ |

---

## 11. Rencana Rilis (Roadmap)

### 🚀 Fase 1 — MVP (Bulan 1–4)
> Target: Sistem berjalan penuh di 1 sekolah pilot

- [x] Setup infrastruktur & autentikasi (AUTH-01, 02)
- [x] Modul data master: siswa, guru, kelas, mapel (MASTER-01 s.d. 05, 08)
- [x] Modul jadwal pelajaran (JADWAL-01 s.d. 04)
- [x] Modul absensi dasar (ABSEN-01 s.d. 04)
- [x] Modul penilaian & e-Rapor (NILAI-01 s.d. 09)
- [x] Dashboard per role (DASH-01 s.d. 03)

### 🔧 Fase 2 — Penyempurnaan (Bulan 5–7)
> Target: Stabil untuk 5+ sekolah

- [ ] Import/export data via Excel (MASTER-06, 07)
- [ ] Notifikasi email/push (NILAI-12, ABSEN-05)
- [x] Rekap peringkat kelas (NILAI-10) — *Selesai*
- [ ] Riwayat rapor semester (NILAI-11)
- [ ] Modul pengumuman (ANNC-01 s.d. 03)
- [x] Dashboard analytics & laporan (DASH-04, 05) — *Selesai*

### 🌟 Fase 3 — Ekspansi (Bulan 8–12)
> Target: Skala ke 10+ sekolah, portal orang tua

- [ ] Portal orang tua (lihat nilai & absensi anak)
- [ ] Integrasi Dapodik (semi-manual/CSV)
- [ ] PWA (Progressive Web App) untuk mobile
- [ ] Multi-tenant (satu platform, banyak sekolah)
- [ ] Manajemen ekstrakurikuler

---

## 12. Metrik Keberhasilan (Success Metrics)

| Metrik | Target (6 bulan) |
|---|---|
| Waktu input rapor (vs. manual) | Berkurang ≥ 70% |
| Jumlah error/keluhan data nilai | < 5 insiden per semester |
| DAU Guru (Daily Active Users) | ≥ 80% dari total guru terdaftar |
| DAU Siswa | ≥ 60% dari total siswa |
| Waktu load halaman dashboard | < 3 detik |
| Net Promoter Score (NPS) Admin | ≥ 40 |

---

## 13. Risiko & Mitigasi

| Risiko | Kemungkinan | Dampak | Mitigasi |
|---|:---:|:---:|---|
| Guru kurang melek teknologi, resistensi adopsi | Tinggi | Tinggi | Onboarding & pelatihan, UI yang sangat sederhana |
| Kehilangan data karena kegagalan server | Rendah | Sangat Tinggi | Backup otomatis harian + replikasi database |
| Koneksi internet sekolah tidak stabil | Sedang | Sedang | Mode offline terbatas (cache browser) di Fase 2 |
| Kesalahan input nilai tidak terdeteksi | Sedang | Tinggi | Audit log + fitur "review sebelum kunci" |
| Scope creep selama pengembangan | Tinggi | Sedang | Prioritisasi ketat menggunakan MoSCoW |

---

## 14. Asumsi & Dependensi

### Asumsi
- Setiap sekolah memiliki minimal 1 Admin/TU yang bertugas sebagai super-admin lokal.
- Guru dan siswa memiliki akses ke browser web (PC/laptop/smartphone).
- Sekolah menggunakan sistem semester (2 semester per tahun ajaran).
- Format nilai mengikuti skala 0–100 (dapat dikonfigurasi).

### Dependensi
- Infrastruktur server/hosting (cloud atau on-premise sekolah).
- Kebijakan PDPA/privasi data siswa sesuai regulasi Indonesia.
- Desain format rapor mengikuti ketentuan Kemdikbud (Kurikulum Merdeka).

---

## 15. Pertanyaan Terbuka (Open Questions)

> [!IMPORTANT]
> Item-item berikut perlu keputusan sebelum pengembangan dimulai.

1. **Multi-sekolah:** Single-tenant (1 sekolah pilot, i.e. SMA Nusantara).
2. **Format Rapor:** Menggunakan template e-Rapor Kurikulum Merdeka resmi (PDF generator via DomPDF).
3. **Hosting:** VPS/Localhost (configured via Laravel `.env`).
4. **Bahasa Deskripsi Nilai:** Guru mengisi deskripsi capaian kompetensi per siswa per mapel.
5. **Orang Tua:** Masuk Fase berikutnya (tidak di Fase 1).
6. **Ekstrakurikuler:** Dipertimbangkan di Fase berikutnya (tidak masuk rapor Fase 1).

---

*Dokumen ini adalah PRD versi 1.0 dan bersifat living document. Setiap perubahan signifikan akan dibuatkan versi baru dan didokumentasikan dalam change log.*
