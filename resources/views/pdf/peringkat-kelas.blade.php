<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peringkat Kelas {{ $kelas->nama_kelas }} - {{ $tahunAjaran->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5pt;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header .school-gov {
            font-size: 9pt;
            font-weight: bold;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #475569;
            margin: 0;
        }
        .header .school-dept {
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #1e293b;
            margin: 1px 0;
        }
        .header .school-name {
            font-size: 14pt;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0b192c;
            margin: 2px 0;
        }
        .header .school-addr {
            font-size: 8pt;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .title-box {
            text-align: center;
            margin-bottom: 12px;
        }
        .title-box h3 {
            font-size: 12pt;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin: 0;
            color: #0b192c;
        }
        .title-box p {
            font-size: 9pt;
            color: #475569;
            margin: 2px 0 0 0;
            font-weight: 600;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .meta-table td {
            padding: 2px 4px;
        }
        .meta-table td.label {
            font-weight: bold;
            color: #475569;
            width: 18%;
        }
        .meta-table td.colon {
            width: 2%;
            font-weight: bold;
        }
        .meta-table td.val {
            font-weight: 700;
            color: #0f172a;
            width: 30%;
        }

        table.ranking-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.ranking-table th {
            background-color: #0b192c;
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: bold;
            text-align: center;
            padding: 7px 6px;
            border: 1px solid #0b192c;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        table.ranking-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 8.5pt;
            vertical-align: middle;
        }
        table.ranking-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        table.ranking-table tr.top-rank-1 td {
            background-color: #fefce8;
        }
        table.ranking-table tr.top-rank-2 td {
            background-color: #f0fdf4;
        }
        table.ranking-table tr.top-rank-3 td {
            background-color: #eff6ff;
        }

        .rank-badge {
            display: inline-block;
            font-weight: 900;
            font-size: 9pt;
            text-align: center;
            width: 22px;
            height: 22px;
            line-height: 22px;
            border-radius: 50%;
        }
        .rank-badge-1 { background-color: #eab308; color: #ffffff; }
        .rank-badge-2 { background-color: #94a3b8; color: #ffffff; }
        .rank-badge-3 { background-color: #b45309; color: #ffffff; }

        .signatures {
            width: 100%;
            margin-top: 18px;
            font-size: 9pt;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }
        .sign-space {
            height: 55px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .sign-nip {
            font-size: 8pt;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="header">
        <p class="school-gov">PEMERINTAH DAERAH PROVINSI JAWA TIMUR</p>
        <p class="school-dept">DINAS PENDIDIKAN - CABANG DINAS WILAYAH KOTA</p>
        <h1 class="school-name">SMA NEGERI SIADAK INDONESIA</h1>
        <p class="school-addr">Jl. Pendidikan No. 45, Telp: (031) 8291024, Email: info@siakad-sma.sch.id - Website: www.siakad-sma.sch.id</p>
    </div>

    <div class="title-box">
        <h3>LEMBAR HASIL PERINGKAT KELAS & PRESTASI AKADEMIK</h3>
        <p>TAHUN AJARAN {{ $tahunAjaran->nama }} - SEMESTER {{ strtoupper($tahunAjaran->semester_label) }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Kelas / Rombel</td>
            <td class="colon">:</td>
            <td class="val">{{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})</td>
            <td class="label">Wali Kelas</td>
            <td class="colon">:</td>
            <td class="val">{{ $kelas->waliKelas?->name ?? 'Belum Ditentukan' }}</td>
        </tr>
        <tr>
            <td class="label">Peminatan / Jurusan</td>
            <td class="colon">:</td>
            <td class="val">{{ $kelas->jurusan->nama ?? 'Umum' }}</td>
            <td class="label">Total Siswa</td>
            <td class="colon">:</td>
            <td class="val">{{ $peringkat->count() }} Siswa</td>
        </tr>
    </table>

    <table class="ranking-table">
        <thead>
            <tr>
                <th style="width: 8%;">Rank</th>
                <th style="width: 16%;">NIS / NISN</th>
                <th>Nama Lengkap Siswa</th>
                <th style="width: 14%;">Jml Mapel</th>
                <th style="width: 14%;">Rata-rata</th>
                <th style="width: 12%;">Predikat</th>
                <th style="width: 14%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peringkat as $siswa)
                @php
                    $avg = $siswa->nilais_avg_nilai_akhir;
                    $topClass = $siswa->peringkat === 1 ? 'top-rank-1' : ($siswa->peringkat === 2 ? 'top-rank-2' : ($siswa->peringkat === 3 ? 'top-rank-3' : ''));
                    $predikat = !is_null($avg) ? ($avg >= 90 ? 'A' : ($avg >= 80 ? 'B' : ($avg >= 70 ? 'C' : 'D'))) : '-';
                    $status = !is_null($avg) && $avg >= 75 ? 'Tuntas' : (!is_null($avg) ? 'Perlu Bimbingan' : 'Belum Lengkap');
                @endphp
                <tr class="{{ $topClass }}">
                    <td style="text-align: center;">
                        @if($siswa->peringkat <= 3)
                            <span class="rank-badge rank-badge-{{ $siswa->peringkat }}">{{ $siswa->peringkat }}</span>
                        @else
                            <span style="font-weight: bold; color: #475569;">{{ $siswa->peringkat }}</span>
                        @endif
                    </td>
                    <td style="font-family: monospace; font-size: 8pt; color: #475569;">
                        {{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}
                    </td>
                    <td style="font-weight: bold; color: #0b192c;">
                        {{ $siswa->nama_lengkap }}
                    </td>
                    <td style="text-align: center; font-family: monospace;">
                        {{ $siswa->nilais_count }} Mapel
                    </td>
                    <td style="text-align: right; font-weight: bold; font-family: monospace; font-size: 9.5pt; color: #0b192c;">
                        {{ !is_null($avg) ? number_format($avg, 2) : '-' }}
                    </td>
                    <td style="text-align: center; font-weight: 800; color: #1e3a8a;">
                        {{ $predikat }}
                    </td>
                    <td style="text-align: center; font-weight: 600; font-size: 8pt;">
                        {{ $status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px; color: #94a3b8;">
                        Tidak ada data siswa untuk kelas ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah
                <div class="sign-space"></div>
                <div class="sign-name">Dr. H. Sulaiman, M.Pd.</div>
                <div class="sign-nip">NIP. 19700512 199503 1 002</div>
            </td>
            <td>
                {{ config('app.school_city', 'Surabaya') }}, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                Wali Kelas {{ $kelas->nama_kelas }}
                <div class="sign-space"></div>
                <div class="sign-name">{{ $kelas->waliKelas?->name ?? '............................................' }}</div>
                <div class="sign-nip">NIP. {{ $kelas->waliKelas?->guru?->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
