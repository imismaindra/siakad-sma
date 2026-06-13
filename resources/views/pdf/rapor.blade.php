<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor - {{ $siswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page {
            padding: 20mm 20mm 15mm 25mm;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .header h2 {
            font-size: 12pt;
            margin: 0 0 4px 0;
        }
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .identitas-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .identitas-table td {
            padding: 2px 5px;
            font-size: 10.5pt;
        }
        .identitas-table td:first-child {
            width: 40%;
        }
        h3.section-title {
            font-size: 11pt;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin: 10px 0 8px 0;
        }
        table.nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.nilai-table th,
        table.nilai-table td {
            border: 1px solid #333;
            padding: 4px 8px;
            font-size: 10pt;
        }
        table.nilai-table th {
            background-color: #e8e8e8;
            text-align: center;
            font-weight: bold;
        }
        table.nilai-table td:nth-child(1) { width: 5%; text-align: center; }
        table.nilai-table td:nth-child(2) { width: 35%; }
        table.nilai-table td:nth-child(3),
        table.nilai-table td:nth-child(4),
        table.nilai-table td:nth-child(5),
        table.nilai-table td:nth-child(6) { width: 10%; text-align: center; }
        table.nilai-table td:nth-child(7) { text-align: center; font-weight: bold; }
        table.nilai-table td:nth-child(8) { width: 12%; text-align: center; }
        .lulus { color: #006400; }
        .tidak-lulus { color: #cc0000; }
        .ttd-section {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-box {
            text-align: center;
            width: 40%;
        }
        .ttd-box .nama {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 9pt;
            color: #555;
            text-align: center;
        }
        .absensi-section {
            margin-bottom: 15px;
        }
        .absensi-section table {
            border-collapse: collapse;
        }
        .absensi-section td {
            padding: 2px 10px;
            font-size: 10pt;
        }
    </style>
</head>
<body>
<div class="page">
    <!-- Header Sekolah -->
    <div class="header">
        <h1>Rapor Semester {{ $tahunAjaran->semester == '1' ? 'Ganjil' : 'Genap' }}</h1>
        <h2>SMA — SIAKAD SMA</h2>
        <p>Tahun Pelajaran {{ $tahunAjaran->nama }}</p>
    </div>

    <!-- Identitas Siswa -->
    <h3 class="section-title">Identitas Siswa</h3>
    <table class="identitas-table">
        <tr>
            <td>Nama Siswa</td>
            <td>: <strong>{{ $siswa->nama_lengkap }}</strong></td>
            <td>Kelas</td>
            <td>: <strong>{{ $siswa->kelas?->nama ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>: {{ $siswa->nis }}</td>
            <td>Semester</td>
            <td>: {{ $tahunAjaran->semester == '1' ? 'Ganjil' : 'Genap' }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>: {{ $siswa->nisn ?? '-' }}</td>
            <td>Wali Kelas</td>
            <td>: {{ $siswa->kelas?->waliKelas?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat, Tgl Lahir</td>
            <td>: {{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}{{ $siswa->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
            <td>Tahun Pelajaran</td>
            <td>: {{ $tahunAjaran->nama }}</td>
        </tr>
    </table>

    <!-- Tabel Nilai -->
    <h3 class="section-title">Nilai Akademik</h3>
    <table class="nilai-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>KKM</th>
                <th>NH</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $index => $nilai)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $nilai->mataPelajaran->nama }}</td>
                <td>{{ $nilai->mataPelajaran->kkm }}</td>
                <td>{{ number_format($nilai->rata_rata_harian ?? 0, 1) }}</td>
                <td>{{ number_format($nilai->nilai_uts ?? 0, 1) }}</td>
                <td>{{ number_format($nilai->nilai_uas ?? 0, 1) }}</td>
                <td class="{{ $nilai->tuntas ? 'lulus' : 'tidak-lulus' }}">
                    <strong>{{ number_format($nilai->nilai_akhir ?? 0, 1) }}</strong>
                </td>
                <td>{{ $nilai->predikat }}</td>
            </tr>
            @if($nilai->deskripsi)
            <tr>
                <td></td>
                <td colspan="7" style="font-style: italic; font-size: 9pt; color: #333;">
                    Catatan: {{ $nilai->deskripsi }}
                </td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Belum ada data nilai.</td>
            </tr>
            @endforelse
        </tbody>
        @if($nilais->isNotEmpty())
        <tfoot>
            <tr>
                <th colspan="6" style="text-align: right;">Rata-rata Nilai Akhir</th>
                <th>{{ number_format($nilais->avg('nilai_akhir'), 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Kehadiran -->
    @php
        $rekap = $siswa->rekapAbsensi($tahunAjaran->id);
        $totalHadir = $rekap['hadir'];
        $totalTidakHadir = $rekap['sakit'] + $rekap['izin'] + $rekap['alpa'];
    @endphp
    <h3 class="section-title">Catatan Kehadiran</h3>
    <div class="absensi-section">
        <table>
            <tr>
                <td>Hadir</td><td>: {{ $rekap['hadir'] }} kali</td>
                <td style="padding-left:30px">Sakit</td><td>: {{ $rekap['sakit'] }} kali</td>
            </tr>
            <tr>
                <td>Izin</td><td>: {{ $rekap['izin'] }} kali</td>
                <td style="padding-left:30px">Alpa</td><td>: {{ $rekap['alpa'] }} kali</td>
            </tr>
        </table>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd-section">
        <div class="ttd-box">
            <p>Orang Tua / Wali</p>
            <div class="nama">( __________________________ )</div>
        </div>
        <div class="ttd-box">
            <p>{{ config('app.name') }}, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Wali Kelas</p>
            <div class="nama">{{ $siswa->kelas?->waliKelas?->guru?->nama_lengkap ?? $siswa->kelas?->waliKelas?->name ?? '________________________' }}</div>
        </div>
    </div>

    <!-- Mengetahui Kepala Sekolah -->
    <div class="ttd-section" style="margin-top: 15px; justify-content: flex-end;">
        <div class="ttd-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah</p>
            <div class="nama">( __________________________ )</div>
        </div>
    </div>

    <div class="footer">
        Rapor ini dicetak secara digital oleh Sistem Informasi Akademik SMA (SIAKAD SMA).<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</div>
</body>
</html>
