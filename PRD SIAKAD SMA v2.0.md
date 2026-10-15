# PRD SIAKAD SMA v2.0

Disusun oleh Ahmad Maulana Ismaindra · Mata kuliah Evolusi Perangkat Lunak · Oktober 2026 · Basis: SIAKAD SMA v1.0 (Laravel)

## 1. Ringkasan

SIAKAD SMA v1.0 sudah mencakup tiga peran (Admin/TU, Guru, Siswa) dengan fitur data master, jadwal pelajaran, absensi per sesi, input nilai, e-Rapor PDF, dan RBAC dinamis. Versi 2.0 adalah rilis evolusi yang menambah kemampuan baru tanpa mengubah fondasi v1.0. Fokusnya: absensi yang lebih sulit dicurangi (QR dinamis + geofencing), keterlibatan orang tua, komunikasi sekolah (pengumuman, kalender akademik, notifikasi), dan akuntabilitas data (audit log).

## 2. Latar Belakang dan Tujuan

### 2.1 Masalah pada v1.0

- Absensi masih diinput manual oleh guru, sehingga memakan waktu di awal pelajaran dan tidak ada verifikasi kehadiran fisik siswa.
- Orang tua tidak punya akses ke data anaknya dan baru tahu masalah kehadiran atau nilai saat rapor dibagikan.
- Dashboard siswa menampilkan pengumuman, tetapi belum ada modul bagi Admin untuk mengelolanya. Tidak ada kalender akademik.
- Tidak ada notifikasi ke pengguna ketika ada kejadian penting.
- Perubahan data sensitif (absensi, nilai, akun) tidak tercatat siapa pelakunya dan kapan.

### 2.2 Tujuan v2.0

1. Mempercepat dan memvalidasi absensi dengan QR code dinamis dan pembatasan lokasi.
2. Memberi orang tua pemantauan read-only atas data anaknya.
3. Mengirim notifikasi otomatis melalui channel yang modular, dengan opsi gratis sebagai default.
4. Menyediakan pengumuman dan kalender akademik yang dikelola Admin.
5. Mencatat jejak audit untuk perubahan data sensitif.
6. Mendokumentasikan evolusi v1.0 ke v2.0 secara terstruktur (versioning, changelog, analisis dampak).

### 2.3 Bukan tujuan (non-goals) v2.0

- Modul LMS penuh (tugas dan materi), keuangan/SPP, PPDB online, dan penyusunan jadwal otomatis.
- Aplikasi mobile native. Pemindaian QR dilakukan lewat browser.
- Integrasi WhatsApp resmi berbayar sebagai syarat rilis.

## 3. Ruang Lingkup

| Kode | Fitur | Status |
| --- | --- | --- |
| F1 | Absensi QR code dinamis | Wajib |
| F2 | Geofencing absensi (validasi di server) | Wajib |
| F3 | Audit log | Wajib |
| F4 | Monitoring orang tua | Wajib |
| F5 | Pengumuman dan kalender akademik | Wajib |
| F6 | Notifikasi modular (in-app, Telegram/email, adapter WA) | Wajib untuk arsitektur dan satu channel gratis; adapter WA opsional |

## 4. Pengguna dan Peran

| Peran | Status | Kebutuhan baru di v2.0 |
| --- | --- | --- |
| Admin/TU | Ada | Kelola pengumuman, kalender, pengaturan lokasi sekolah, akun orang tua, lihat audit log |
| Guru | Ada | Membuka sesi QR, melihat status absensi real-time, override manual dengan alasan |
| Siswa | Ada | Memindai QR untuk absen, melihat pengumuman dan kalender, menerima notifikasi |
| Orang Tua/Wali | Baru | Melihat jadwal, absensi, nilai, dan rapor anak (read-only), menerima notifikasi |

Peran Orang Tua ditambahkan melalui mekanisme role dan permission yang sudah ada (spatie/laravel-permission).

## 5. Kebutuhan Fungsional

### 5.1 F1 Absensi QR code dinamis

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| F1-01 | Guru dapat membuka sesi absensi untuk jadwal pelajaran miliknya pada hari berjalan. | Tinggi |
| F1-02 | Sistem menampilkan QR code yang tokennya berganti otomatis setiap 30-60 detik (nilai dapat dikonfigurasi). | Tinggi |
| F1-03 | Siswa yang sudah login memindai QR melalui kamera browser; server memvalidasi token, masa berlaku, keanggotaan siswa di kelas tersebut, dan jam pelajaran. | Tinggi |
| F1-04 | Absensi yang valid tercatat sebagai Hadir dengan metode QR; pemindaian ulang oleh siswa yang sama tidak membuat data ganda. | Tinggi |
| F1-05 | Guru melihat daftar siswa yang sudah dan belum absen secara langsung selama sesi berlangsung. | Sedang |
| F1-06 | Guru menutup sesi; siswa yang belum absen dapat diisi manual (Sakit, Izin, Alpa) seperti alur v1.0. | Tinggi |
| F1-07 | Pemindaian setelah sesi ditutup atau token kedaluwarsa ditolak dengan pesan yang jelas. | Tinggi |

### 5.2 F2 Geofencing

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| F2-01 | Admin mengatur titik koordinat sekolah dan radius yang diizinkan (meter). | Tinggi |
| F2-02 | Saat memindai QR, browser mengirim lokasi siswa; server menghitung jarak ke titik sekolah (rumus Haversine) dan menolak absensi di luar radius. | Tinggi |
| F2-03 | Lokasi dengan akurasi buruk (di atas ambang yang dikonfigurasi) ditolak, dan siswa diminta mencoba lagi. | Sedang |
| F2-04 | Jika izin lokasi ditolak browser, absensi QR tidak dapat diselesaikan dan siswa diberi petunjuk. | Tinggi |
| F2-05 | Guru dapat melakukan override manual untuk siswa yang bermasalah dengan GPS, wajib mengisi alasan. Override tercatat di audit log. | Tinggi |
| F2-06 | Sistem hanya menyimpan jarak, akurasi, dan status validitas lokasi pada data absensi, bukan jejak koordinat mentah siswa. | Sedang |

Catatan: geofencing berbasis browser bersifat best-effort. Lokasi dapat dipalsukan dengan aplikasi fake GPS. Pengamanan berlapis dicapai dengan menggabungkannya dengan QR yang cepat kedaluwarsa dan audit log.

### Alur absensi QR dan geofencing (gabungan F1 dan F2)

&#91;embedded content: alur absensi QR dan geofencing · 3 pemeriksaan di server\]

Server menolak absensi di tiga titik pemeriksaan (token, kelas dan jam, lokasi). Hanya yang lolos ketiganya tercatat Hadir, sedangkan siswa yang bermasalah dengan GPS ditangani lewat override guru yang masuk audit log.

### 5.3 F3 Audit log

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| F3-01 | Sistem mencatat pembuatan, perubahan, dan penghapusan pada data absensi, nilai, dan akun pengguna, beserta pelaku, waktu, nilai lama, dan nilai baru. | Tinggi |
| F3-02 | Override absensi dan perubahan role/permission tercatat dengan alasan. | Tinggi |
| F3-03 | Admin dapat melihat log dengan filter pelaku, jenis data, dan rentang tanggal. | Tinggi |
| F3-04 | Log bersifat hanya-tambah (tidak dapat diedit atau dihapus dari antarmuka). | Sedang |

Implementasi yang diusulkan: paket spatie/laravel-activitylog.

### 5.4 F4 Monitoring orang tua

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| F4-01 | Admin membuat akun orang tua dan menautkannya ke satu atau lebih siswa beserta hubungan (ayah, ibu, wali). | Tinggi |
| F4-02 | Orang tua login dan melihat daftar anak yang tertaut; bila lebih dari satu, dapat berpindah antar anak. | Tinggi |
| F4-03 | Per anak, orang tua dapat melihat jadwal pelajaran, riwayat absensi, nilai, dan e-Rapor yang sudah difinalisasi. | Tinggi |
| F4-04 | Akses orang tua bersifat read-only dan hanya untuk anak yang tertaut; akses ke data siswa lain ditolak. | Tinggi |
| F4-05 | Orang tua melihat pengumuman dan kalender akademik yang ditargetkan kepadanya. | Sedang |

### 5.5 F5 Pengumuman dan kalender akademik

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| F5-01 | Admin membuat, mengubah, dan menghapus pengumuman dengan judul, isi, waktu terbit, dan masa berlaku. | Tinggi |
| F5-02 | Pengumuman dapat ditargetkan ke semua pengguna, role tertentu, atau kelas tertentu. | Tinggi |
| F5-03 | Pengumuman muncul di dashboard sesuai target (menggantikan data pengumuman statis di v1.0). | Tinggi |
| F5-04 | Admin mengelola event kalender akademik: judul, kategori (libur, ujian, pembagian rapor, kegiatan), tanggal mulai dan selesai, deskripsi. | Tinggi |
| F5-05 | Semua peran dapat melihat kalender dalam tampilan bulanan dan daftar. | Sedang |

### 5.6 F6 Notifikasi modular

| ID | Kebutuhan | Prioritas |
| --- | --- | --- |
| F6-01 | Notifikasi dibangun di atas Laravel Notifications dan antrean (queue), sehingga pengiriman tidak memperlambat permintaan pengguna. | Tinggi |
| F6-02 | Channel default yang berfungsi penuh: notifikasi di dalam aplikasi, ditambah Telegram bot atau email (gratis). | Tinggi |
| F6-03 | Channel WhatsApp diimplementasikan sebagai adapter terpisah dengan antarmuka yang sama sehingga penyedia dapat diganti tanpa mengubah logika pemicu. | Sedang |
| F6-04 | Pemicu notifikasi: siswa tercatat alpa, nilai baru dipublikasikan, e-Rapor terbit, pengumuman baru, dan event kalender yang mendekat. | Tinggi |
| F6-05 | Pengguna (atau orang tua) dapat mengisi kontak channel (nomor atau akun Telegram) dan mengaktifkan atau menonaktifkan jenis notifikasi. | Sedang |
| F6-06 | Kegagalan pengiriman tercatat dan dicoba ulang beberapa kali tanpa menghentikan proses utama. | Sedang |

Catatan biaya WhatsApp: WhatsApp Business API resmi (Cloud API Meta) menagih per pesan template yang terkirim, dengan tarif berbeda per negara yang diperbarui berkala; pesan balasan dalam jendela layanan 24 jam gratis. Cek kartu tarif terbaru Meta sebelum memutuskan memakai jalur resmi. Gateway tidak resmi lebih murah tetapi berisiko pemblokiran nomor dan melanggar ketentuan WhatsApp, sehingga hanya cocok untuk demo dengan nomor cadangan.

## 6. Kebutuhan Non-Fungsional

| Aspek | Kebutuhan |
| --- | --- |
| Keamanan | Seluruh validasi QR, lokasi, dan otorisasi dilakukan di server. Token QR acak, berumur pendek, dan tidak dapat ditebak. Akses orang tua dibatasi policy per anak. |
| Privasi | Koordinat mentah siswa tidak disimpan jangka panjang. Data anak hanya dapat diakses orang tua yang tertaut. |
| Kinerja | Validasi pemindaian QR selesai kurang dari 2 detik pada kondisi jaringan normal. Pengiriman notifikasi berjalan di queue. |
| Kompatibilitas | Pemindaian QR berjalan di browser modern pada ponsel. Fitur kamera dan lokasi mensyaratkan HTTPS pada lingkungan produksi. |
| Keandalan | Kegagalan channel notifikasi tidak menggagalkan transaksi utama. |
| Maintainability | Channel notifikasi bersifat plug-in. Fitur baru dilengkapi feature test PHPUnit untuk alur kritis (QR, geofence, otorisasi orang tua). |
| Kompatibilitas mundur | Data dan alur v1.0 (absensi manual, nilai, e-Rapor) tetap berfungsi tanpa perubahan perilaku. |

## 7. Perubahan Data

Tabel dan kolom baru yang diusulkan (nama final menyesuaikan konvensi migrasi proyek):

| Tabel | Isi utama |
| --- | --- |
| pengaturan\_sekolah | latitude, longitude, radius\_meter, ambang\_akurasi\_meter, durasi\_token\_detik |
| sesi\_absensi\_qr | jadwal\_id, guru\_id, tanggal, dibuka\_pada, ditutup\_pada, secret, status |
| absensi (perubahan) | kolom tambahan: metode (manual, qr, override), jarak\_meter, akurasi\_meter, lokasi\_valid, alasan\_override |
| wali\_siswa | user\_id (orang tua), siswa\_id, hubungan |
| kontak\_notifikasi | user\_id, telegram\_chat\_id, nomor\_wa, preferensi jenis notifikasi |
| notifications | tabel notifikasi bawaan Laravel (in-app) |
| pengumuman | judul, isi, target\_tipe, target\_id, terbit\_pada, berlaku\_sampai, dibuat\_oleh |
| kalender\_akademik | judul, kategori, mulai, selesai, deskripsi, target\_tipe, target\_id |
| activity\_log | tabel dari paket activitylog: pelaku, subjek, event, properti lama dan baru, waktu |

Permission baru: kelola-pengumuman, kelola-kalender, kelola-lokasi-sekolah, buka-sesi-qr, override-absensi, lihat-audit-log, lihat-data-anak.

## 8. Arsitektur dan Teknologi

- Tetap: Laravel 11+, Tailwind CSS v4 dengan Vite, spatie/laravel-permission, ekspor PDF.
- Tambahan: spatie/laravel-activitylog, library pemindai QR di sisi browser, pembuat QR di sisi server atau klien, Laravel Queue (driver database untuk awal), Telegram Bot API.
- Rekomendasi lingkungan: pindah dari SQLite ke MySQL/PostgreSQL untuk uji bersama dan produksi; HTTPS wajib untuk kamera dan geolokasi.

## 9. Analisis Dampak Perubahan

### 9.1 Klasifikasi perubahan

| Perubahan | Jenis |
| --- | --- |
| Absensi QR dan geofencing, pengumuman dan kalender, notifikasi | Perfektif (menambah kemampuan) |
| Role orang tua dan integrasi channel eksternal | Adaptif (menyesuaikan dengan kebutuhan dan lingkungan baru) |
| Audit log, feature test, arsitektur channel modular | Preventif (mengurangi risiko dan memudahkan perawatan ke depan) |

### 9.2 Modul v1.0 yang terdampak

| Modul v1.0 | Dampak |
| --- | --- |
| Absensi digital | Menambah metode dan kolom validasi; alur manual tetap dipertahankan |
| Autentikasi dan RBAC | Penambahan role dan permission baru, serta policy akses per anak |
| Dashboard (Admin, Guru, Siswa) | Widget pengumuman dan kalender; dashboard baru untuk orang tua |
| Manajemen akun | Pembuatan akun orang tua dan penautan ke siswa |
| Input nilai dan e-Rapor | Hanya menambah pemicu notifikasi dan pencatatan audit; logika inti tidak berubah |

## 10. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Lokasi dipalsukan dengan fake GPS | Absensi tidak valid | QR berumur pendek, ambang akurasi, audit log, tinjauan guru |
| GPS tidak akurat di dalam gedung | Siswa sah ditolak | Radius yang wajar, override guru dengan alasan |
| Biaya dan kebijakan WhatsApp | Notifikasi WA tidak berkelanjutan | Arsitektur channel modular, Telegram/email sebagai default gratis |
| Beban lingkup untuk UTS | Fitur tidak selesai | Prioritas pengerjaan (bagian 11), WA sebagai opsional |
| Kebocoran data anak ke orang tua lain | Pelanggaran privasi | Policy per anak dan feature test otorisasi |
| Kamera atau lokasi membutuhkan HTTPS | Fitur gagal pada HTTP | Siapkan HTTPS pada lingkungan demo (misalnya tunnel atau domain bersertifikat) |

## 11. Prioritas dan Rencana Rilis

| Tahap | Isi | Keluaran |
| --- | --- | --- |
| 1 Inti | F3 audit log (fondasi), F1 QR dinamis, F2 geofence di server | Jejak audit data aktif; absensi QR berfungsi dan tervalidasi lokasi |
| 2 Pendukung | F4 role orang tua, F5 pengumuman dan kalender | Orang tua dapat memantau; Admin mengelola pengumuman dan kalender |
| 3 Notifikasi | F6 arsitektur channel, Telegram atau email, adapter WA (opsional) | Notifikasi otomatis untuk alpa, nilai, rapor, pengumuman |
| 4 Penutupan | Feature test, CHANGELOG, tag v2.0.0, pembaruan README | Rilis v2.0.0 terdokumentasi |

Jika waktu terbatas, adapter WA pada tahap 3 menjadi bagian yang dapat ditunda ke v2.1.

## 12. Kriteria Penerimaan

- Siswa di dalam radius sekolah dapat absen dengan QR yang masih berlaku, dan ditolak bila token kedaluwarsa atau lokasi di luar radius.
- Setiap override absensi dan perubahan nilai muncul di audit log dengan pelaku dan alasan.
- Orang tua hanya dapat melihat data anak yang tertaut, dan tidak dapat mengubah data apa pun.
- Pengumuman muncul hanya pada target yang dipilih; kalender menampilkan event yang dibuat Admin.
- Siswa tercatat alpa memicu notifikasi ke orang tua melalui minimal satu channel yang aktif.
- Alur v1.0 (absensi manual, input nilai, e-Rapor PDF) tetap berjalan seperti sebelumnya.

## 13. Manajemen Versi

- Tag git: v1.0.0 untuk kondisi saat ini dan v2.0.0 untuk rilis ini (semantic versioning).
- CHANGELOG.md mencatat tambahan, perubahan, dan perbaikan per versi.
- Dokumen ini menggantikan PRD v1.0 sebagai acuan kebutuhan; PRD v1.0 tetap disimpan sebagai riwayat.
