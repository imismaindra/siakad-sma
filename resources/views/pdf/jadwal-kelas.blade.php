<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pelajaran - Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 12mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.3;
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
            margin-bottom: 10px;
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
            font-size: 8.5pt;
            color: #475569;
            margin: 2px 0 0 0;
            font-weight: 600;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .meta-table td {
            padding: 2px 4px;
        }
        .meta-table td.label {
            font-weight: bold;
            color: #475569;
            width: 14%;
        }
        .meta-table td.colon {
            width: 2%;
            font-weight: bold;
        }
        .meta-table td.val {
            font-weight: 700;
            color: #0f172a;
            width: 34%;
        }

        table.schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.schedule-table th {
            background-color: #0b192c;
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #0b192c;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        table.schedule-table th.day-th {
            width: 14.5%;
        }
        table.schedule-table th.time-th {
            width: 13%;
        }
        table.schedule-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            vertical-align: top;
            font-size: 8pt;
        }
        table.schedule-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        table.schedule-table td.slot-info {
            text-align: center;
            font-weight: bold;
            background-color: #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }
        table.schedule-table td.slot-info .jam-ke {
            font-size: 8.5pt;
            font-weight: 800;
            color: #0b192c;
        }
        table.schedule-table td.slot-info .waktu {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 1px;
            font-family: monospace;
        }

        .session-item {
            padding: 2px 1px;
        }
        .session-item .mapel {
            font-weight: 800;
            color: #0b192c;
            font-size: 8.5pt;
            display: block;
            line-height: 1.2;
        }
        .session-item .guru {
            font-size: 7.5pt;
            color: #475569;
            margin-top: 2px;
            display: block;
        }
        .session-item .ruang {
            font-size: 7pt;
            color: #0284c7;
            font-weight: bold;
            margin-top: 1px;
            display: inline-block;
        }

        .empty-slot {
            text-align: center;
            color: #cbd5e1;
            font-size: 9pt;
            padding: 8px 0;
        }

        .signatures {
            width: 100%;
            margin-top: 14px;
            font-size: 8.5pt;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }
        .sign-space {
            height: 50px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .sign-nip {
            font-size: 7.5pt;
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
        <h3>JADWAL PELAJARAN MINGGUAN KELAS {{ $kelas->nama_kelas }}</h3>
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
            <td class="label">Total Sesi Terjadwal</td>
            <td class="colon">:</td>
            <td class="val">{{ $jadwals->flatten()->count() }} Jam Pertemuan</td>
        </tr>
    </table>

    @php
        // Ambil semua urutan jam yang terdaftar secara unik
        $distinctSlots = $jadwals->flatten()->sortBy('urutan_jam')->unique('urutan_jam');
        $maxUrutan = max($distinctSlots->max('urutan_jam') ?? 8, 8);
    @endphp

    <table class="schedule-table">
        <thead>
            <tr>
                <th class="time-th">Jam Ke / Waktu</th>
                @foreach($hariList as $h)
                    <th class="day-th">{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= $maxUrutan; $i++)
                @php
                    // Cari waktu representatif untuk urutan jam ke-$i jika ada
                    $sample = $jadwals->flatten()->firstWhere('urutan_jam', $i);
                    $waktuStr = $sample ? (\Carbon\Carbon::parse($sample->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($sample->jam_selesai)->format('H:i')) : '';
                @endphp
                <tr>
                    <td class="slot-info">
                        <div class="jam-ke">Jam Ke-{{ $i }}</div>
                        @if($waktuStr)
                            <div class="waktu">{{ $waktuStr }}</div>
                        @endif
                    </td>
                    @foreach($hariList as $h)
                        @php
                            $slotJadwals = isset($jadwals[$h]) ? $jadwals[$h]->where('urutan_jam', $i) : collect();
                        @endphp
                        <td>
                            @forelse($slotJadwals as $item)
                                <div class="session-item">
                                    <span class="mapel">{{ $item->mataPelajaran->nama }}</span>
                                    <span class="guru">{{ $item->guru->nama_lengkap }}</span>
                                    @if($item->ruangan)
                                        <span class="ruang">R. {{ $item->ruangan }}</span>
                                    @endif
                                </div>
                            @empty
                                <div class="empty-slot">-</div>
                            @endforelse
                        </td>
                    @endforeach
                </tr>
            @endfor
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
