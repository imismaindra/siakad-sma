@extends('layouts.siswa')

@section('title', 'Nilai & Rapor Saya')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Nilai & Rapor')

@section('siswa-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Nilai &amp; Rapor</h1>
            <p class="page-subtitle">Daftar evaluasi belajar harian, tengah semester, akhir semester, beserta predikat rapor.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form action="{{ route('siswa.nilai') }}" method="GET" id="nilai-filter-form">
                <select name="tahun_ajaran_id" onchange="document.getElementById('nilai-filter-form').submit()" class="form-select min-w-[200px] text-xs">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                            {{ $ta->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if($raporPublished)
                <a href="{{ route('siswa.rapor.download', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    Unduh Rapor PDF
                </a>
            @endif
        </div>
    </div>

    {{-- Alert if Rapor Not Published --}}
    @if(!$raporPublished)
        <div class="flash-info">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Rapor belum dapat diunduh karena nilai rapor semester ini masih dalam proses penilaian guru atau belum difinalisasi oleh Admin.</span>
        </div>
    @endif

    {{-- Grades Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Hasil Penilaian Akhir</h3>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>KKM</th>
                        <th>Nilai Harian (Rata)</th>
                        <th>Nilai UTS</th>
                        <th>Nilai UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                        <tr>
                            <td class="font-bold text-slate-800">{{ $nilai->mataPelajaran->nama }}</td>
                            <td class="font-mono text-slate-600 font-semibold">{{ $nilai->mataPelajaran->kkm }}</td>
                            <td class="font-mono text-slate-600">{{ $nilai->nilai_harian ?? '—' }}</td>
                            <td class="font-mono text-slate-600">{{ $nilai->nilai_uts ?? '—' }}</td>
                            <td class="font-mono text-slate-600">{{ $nilai->nilai_uas ?? '—' }}</td>
                            <td class="font-bold font-mono text-navy-600 text-base">{{ $nilai->nilai_akhir ?? '—' }}</td>
                            <td>
                                @if($nilai->predikat)
                                    <span class="grade-{{ strtolower($nilai->predikat) }} font-bold text-sm">
                                        {{ $nilai->predikat }}
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td>
                                @if($nilai->is_final)
                                    <span class="badge badge-success">Selesai (Final)</span>
                                @else
                                    <span class="badge badge-warning">Penilaian Guru</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                Tidak ada data nilai rapor untuk Anda pada periode semester ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
