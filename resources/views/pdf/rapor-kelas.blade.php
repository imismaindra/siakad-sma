<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Kelas - {{ $kelas->nama_kelas }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .page {
            padding: 15mm 20mm 10mm 20mm;
        }
        .page-break { page-break-after: always; }

        .header {
            text-align: center;
            border-bottom: 2px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 13pt;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .header h2 {
            font-size: 12pt;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .header p {
            margin: 1px 0;
            font-size: 10pt;
        }

        h3.section-title {
            font-size: 10pt;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin: 8px 0 5px 0;
        }

        table.identitas-table {
            width: 100%;
            margin-bottom: 8px;
        }
        table.identitas-table td {
            padding: 1px 4px;
            font-size: 10pt;
        }
        table.identitas-table td.label {
            width: 22%;
        }
        table.identitas-table td.divider {
            width: 2%;
        }
        table.identitas-table td.value {
            width: 26%;
        }

        table.nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        table.nilai-table th,
        table.nilai-table td {
            border: 1px solid #333;
            padding: 3px 6px;
            font-size: 9pt;
        }
        table.nilai-table th {
            background-color: #e8e8e8;
            text-align: center;
            font-weight: bold;
        }
        table.nilai-table td.no { width: 5%; text-align: center; }
        table.nilai-table td.nilai { text-align: center; }
        .lulus { color: #006400; }
        .tidak-lulus { color: #cc0000; }

        table.ekstra-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        table.ekstra-table th,
        table.ekstra-table td {
            border: 1px solid #333;
            padding: 3px 6px;
            font-size: 9pt;
        }
        table.ekstra-table th {
            background-color: #e8e8e8;
            text-align: center;
        }

        .absensi-section {
            margin-bottom: 10px;
        }
        .absensi-section table {
            border-collapse: collapse;
        }
        .absensi-section td {
            padding: 1px 15px 1px 4px;
            font-size: 10pt;
        }

        .catatan-box {
            border: 1px solid #333;
            min-height: 50px;
            padding: 6px;
            margin-bottom: 8px;
        }
        .catatan-box .content {
            font-size: 10pt;
            min-height: 40px;
        }

        .ttd-section {
            margin-top: 15px;
            width: 100%;
        }
        .ttd-box {
            text-align: center;
            width: 33%;
        }
        .ttd-box .kota-tgl {
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .ttd-box .jabatan {
            font-size: 10pt;
            margin-bottom: 50px;
        }
        .ttd-box .nama {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
            font-size: 10pt;
        }
        .ttd-box .nip {
            font-size: 9pt;
        }

        .footer {
            margin-top: 10px;
            font-size: 8pt;
            color: #555;
            text-align: center;
        }
    </style>
</head>
<body>
@foreach($siswaDenganNilai as $loopIndex => $siswa)
<div class="page">

    {{-- Header Sekolah --}}
    <div class="header">
        <h1>Laporan Hasil Pencapaian Kompetensi Peserta Didik</h1>
        <h2>{{ config('app.name') }}</h2>
        <p>Tahun Pelajaran {{ $tahunAjaran->nama }} | Semester {{ $tahunAjaran->semester == '1' ? 'Ganjil' : 'Genap' }}</p>
    </div>

    {{-- Identitas Siswa --}}
    <h3 class="section-title">Identitas Siswa</h3>
    <table class="identitas-table">
        <tr>
            <td class="label">Nama Peserta Didik</td><td class="divider">:</td><td class="value"><strong>{{ $siswa->nama_lengkap }}</strong></td>
            <td class="label">Kelas</td><td class="divider">:</td><td class="value"><strong>{{ $kelas->nama_kelas }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIS / NISN</td><td class="divider">:</td><td class="value">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td>
            <td class="label">Semester</td><td class="divider">:</td><td class="value">{{ $tahunAjaran->semester == '1' ? 'Ganjil' : 'Genap' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td><td class="divider">:</td><td class="value">{{ $siswa->tempat_lahir ? $siswa->tempat_lahir . ', ' : '' }}{{ $siswa->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
            <td class="label">Wali Kelas</td><td class="divider">:</td><td class="value">{{ $kelas->waliKelas?->guru?->nama_lengkap ?? $kelas->waliKelas?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td><td class="divider">:</td><td class="value" colspan="3">{{ $siswa->alamat ?? '-' }}</td>
        </tr>
    </table>

    {{-- Nilai Akademik --}}
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
            @forelse($siswa->nilaiRapor as $idx => $nilai)
            <tr>
                <td class="no">{{ $idx + 1 }}</td>
                <td>{{ $nilai->mataPelajaran->nama }}</td>
                <td class="nilai">{{ $nilai->mataPelajaran->kkm }}</td>
                <td class="nilai">{{ number_format($nilai->rata_rata_harian ?? 0, 1) }}</td>
                <td class="nilai">{{ number_format($nilai->nilai_uts ?? 0, 1) }}</td>
                <td class="nilai">{{ number_format($nilai->nilai_uas ?? 0, 1) }}</td>
                <td class="nilai {{ $nilai->tuntas ? 'lulus' : 'tidak-lulus' }}">{{ number_format($nilai->nilai_akhir ?? 0, 1) }}</td>
                <td class="nilai">{{ $nilai->predikat }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;">Belum ada data nilai.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Ekstrakurikuler --}}
    <h3 class="section-title">Ekstrakurikuler</h3>
    <table class="ekstra-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->ekstrakurikulers as $ek => $ekskul)
            <tr>
                <td style="text-align:center; width:5%;">{{ $ek + 1 }}</td>
                <td>{{ $ekskul->nama }}</td>
                <td>{{ $ekskul->pivot->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center; font-style:italic;">Tidak ada data ekstrakurikuler.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Prestasi --}}
    <h3 class="section-title">Prestasi</h3>
    <table class="ekstra-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Prestasi</th>
                <th>Tingkat</th>
                <th>Juara</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->prestasis as $pr => $prestasi)
            <tr>
                <td style="text-align:center; width:5%;">{{ $pr + 1 }}</td>
                <td>{{ $prestasi->nama_prestasi }}</td>
                <td style="text-align:center;">{{ $prestasi->tingkat ?? '-' }}</td>
                <td style="text-align:center;">{{ $prestasi->juara ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center; font-style:italic;">Tidak ada data prestasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Ketidakhadiran --}}
    <h3 class="section-title">Rekap Ketidakhadiran</h3>
    <div class="absensi-section">
        <table>
            <tr>
                <td>Sakit</td><td>: {{ $siswa->rekapAbsensi['sakit'] }} hari</td>
                <td style="padding-left:30px;">Izin</td><td>: {{ $siswa->rekapAbsensi['izin'] }} hari</td>
                <td style="padding-left:30px;">Alpa</td><td>: {{ $siswa->rekapAbsensi['alpa'] }} hari</td>
            </tr>
        </table>
    </div>

    {{-- Catatan Wali Kelas --}}
    <h3 class="section-title">Catatan Wali Kelas</h3>
    <div class="catatan-box"><div class="content"></div></div>

    {{-- Tanggapan Orang Tua/Wali --}}
    <h3 class="section-title">Tanggapan Orang Tua/Wali</h3>
    <div class="catatan-box"><div class="content"></div></div>

    {{-- Tanda Tangan --}}
    <div style="margin-top: 10px;">
        <table style="width:100%;">
            <tr>
                <td style="text-align:center; width:33%;">
                    <div class="kota-tgl">{{ config('app.name') }}, .....................................</div>
                    <div class="jabatan">Orang Tua / Wali</div>
                    <div style="margin-bottom:50px;"></div>
                    <div style="border-top:1px solid #000; padding-top:3px; font-weight:bold; font-size:10pt; display:inline-block; min-width:150px;">( ________________________ )</div>
                </td>
                <td style="text-align:center; width:33%;">
                    <div class="kota-tgl">{{ config('app.name') }}, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="jabatan">Wali Kelas</div>
                    <div style="margin-bottom:50px;"></div>
                    <div style="border-top:1px solid #000; padding-top:3px; font-weight:bold; font-size:10pt; display:inline-block; min-width:150px;">
                        {{ $kelas->waliKelas?->guru?->nama_lengkap ?? $kelas->waliKelas?->name ?? '________________________' }}
                    </div>
                    <div class="nip">{{ $kelas->waliKelas?->guru?->nip ? 'NIP. ' . $kelas->waliKelas?->guru?->nip : '' }}</div>
                </td>
                <td style="text-align:center; width:33%;">
                    <div style="margin-top:30px;"></div>
                    <div class="jabatan">Mengetahui,<br>Kepala Sekolah</div>
                    <div style="margin-bottom:50px;"></div>
                    <div style="border-top:1px solid #000; padding-top:3px; font-weight:bold; font-size:10pt; display:inline-block; min-width:150px;">( ________________________ )</div>
                    <div class="nip">NIP. ........................................</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara digital melalui {{ config('app.name') }} | {{ now()->format('d/m/Y H:i') }}
    </div>

</div>
@if(!$loop->last)
<div class="page-break"></div>
@endif
@endforeach
</body>
</html>