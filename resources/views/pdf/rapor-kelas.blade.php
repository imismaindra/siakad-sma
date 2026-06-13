<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Kelas - {{ $kelas->nama }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; }
        .page-break { page-break-after: always; }
        /* Reuse same styles from rapor.blade.php */
        .page { padding: 15mm 15mm 10mm 20mm; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { font-size: 13pt; text-transform: uppercase; margin: 0 0 3px 0; }
        .header h2 { font-size: 11pt; margin: 0 0 3px 0; }
        table.nilai-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.nilai-table th, table.nilai-table td { border: 1px solid #333; padding: 3px 6px; font-size: 9pt; }
        table.nilai-table th { background-color: #e8e8e8; text-align: center; font-weight: bold; }
        .identitas-table td { padding: 2px 4px; font-size: 10pt; }
        h3.section-title { font-size: 10pt; text-transform: uppercase; border-bottom: 1px solid #000; padding-bottom: 2px; margin: 8px 0 6px 0; }
        .lulus { color: #006400; font-weight: bold; }
        .tidak-lulus { color: #cc0000; font-weight: bold; }
    </style>
</head>
<body>
@foreach($siswaDenganNilai as $loopIndex => $siswa)
<div class="page">
    <div class="header">
        <h1>Rapor Semester {{ $tahunAktif->semester == '1' ? 'Ganjil' : 'Genap' }}</h1>
        <h2>{{ config('app.name') }}</h2>
        <p>Tahun Pelajaran {{ $tahunAktif->nama }}</p>
    </div>

    <h3 class="section-title">Identitas Siswa</h3>
    <table class="identitas-table">
        <tr>
            <td width="30%">Nama Siswa</td><td>: <strong>{{ $siswa->nama_lengkap }}</strong></td>
            <td width="20%">Kelas</td><td>: <strong>{{ $kelas->nama }}</strong></td>
        </tr>
        <tr>
            <td>NIS / NISN</td><td>: {{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td>
            <td>Wali Kelas</td><td>: {{ $kelas->waliKelas?->name ?? '-' }}</td>
        </tr>
    </table>

    <h3 class="section-title">Nilai Akademik</h3>
    <table class="nilai-table">
        <thead>
            <tr>
                <th>No</th><th>Mata Pelajaran</th><th>KKM</th>
                <th>NH</th><th>UTS</th><th>UAS</th>
                <th>Nilai Akhir</th><th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->nilaiRapor as $idx => $nilai)
            <tr>
                <td style="text-align:center">{{ $idx + 1 }}</td>
                <td>{{ $nilai->mataPelajaran->nama }}</td>
                <td style="text-align:center">{{ $nilai->mataPelajaran->kkm }}</td>
                <td style="text-align:center">{{ number_format($nilai->rata_rata_harian ?? 0, 1) }}</td>
                <td style="text-align:center">{{ number_format($nilai->nilai_uts ?? 0, 1) }}</td>
                <td style="text-align:center">{{ number_format($nilai->nilai_uas ?? 0, 1) }}</td>
                <td style="text-align:center" class="{{ $nilai->tuntas ? 'lulus' : 'tidak-lulus' }}">{{ number_format($nilai->nilai_akhir ?? 0, 1) }}</td>
                <td style="text-align:center">{{ $nilai->predikat }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center">Belum ada nilai final.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(! $loop->last)
<div class="page-break"></div>
@endif
@endforeach
</body>
</html>
