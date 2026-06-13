@extends('layouts.guru')

@section('title', 'Rekap Absensi Siswa')

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Rekap Kelas')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Rekap Absensi Kelas</h1>
            <p class="page-subtitle">Akumulasi rekapitulasi kehadiran siswa pada seluruh pertemuan yang Anda ajar.</p>
        </div>
        
        <div>
            @if($kelasList->isNotEmpty())
                <form action="{{ route('guru.absensi.rekap') }}" method="GET" id="rekap-filter-form">
                    <select name="kelas_id" onchange="document.getElementById('rekap-filter-form').submit()" class="form-select min-w-[180px] text-xs">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        @if($kelasId && $siswaRekap && $siswaRekap->isNotEmpty())
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
                            <th class="text-center text-emerald-600">Hadir</th>
                            <th class="text-center text-blue-500">Sakit</th>
                            <th class="text-center text-amber-500">Izin</th>
                            <th class="text-center text-rose-500">Alpa</th>
                            <th class="text-center">Total Pertemuan</th>
                            <th class="text-right">Rasio Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswaRekap as $idx => $siswa)
                            @php
                                $r = $siswa->rekap;
                                $total = $r['hadir'] + $r['sakit'] + $r['izin'] + $r['alpa'];
                                $persen = $total > 0 ? round(($r['hadir'] / $total) * 100, 1) : 100;
                            @endphp
                            <tr>
                                <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="font-mono text-slate-500 text-xs">{{ $siswa->nis }}</td>
                                <td class="font-bold text-slate-800">{{ $siswa->nama_lengkap }}</td>
                                <td class="text-center font-semibold font-mono text-emerald-600">{{ $r['hadir'] }}</td>
                                <td class="text-center font-semibold font-mono text-blue-500">{{ $r['sakit'] }}</td>
                                <td class="text-center font-semibold font-mono text-amber-500">{{ $r['izin'] }}</td>
                                <td class="text-center font-semibold font-mono text-rose-500">{{ $r['alpa'] }}</td>
                                <td class="text-center font-semibold font-mono text-slate-700">{{ $total }}</td>
                                <td class="text-right">
                                    <span class="font-bold font-mono text-sm {{ $persen >= 90 ? 'text-emerald-600' : ($persen >= 75 ? 'text-amber-500' : 'text-rose-600') }}">
                                        {{ $persen }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center text-slate-400 border border-dashed border-slate-200">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p class="font-bold text-slate-700 text-sm">Tidak ada data untuk ditampilkan</p>
                <p class="text-xs text-slate-400 mt-1">Pastikan kelas yang Anda ajar sudah memiliki siswa terdaftar.</p>
            </div>
        @endif
    </div>

</div>
@endsection
