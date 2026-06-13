@extends('layouts.siswa')

@section('title', 'Kehadiran Saya')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Kehadiran')

@section('siswa-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Kehadiran Saya</h1>
            <p class="page-subtitle">Rekam log presensi kehadiran mata pelajaran per sesi kelas.</p>
        </div>
        
        <div>
            <form action="{{ route('siswa.absensi') }}" method="GET" id="absensi-filter-form">
                <select name="tahun_ajaran_id" onchange="document.getElementById('absensi-filter-form').submit()" class="form-select min-w-[200px] text-xs">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                            {{ $ta->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- Rekap Overview --}}
    @if($rekap)
        @php
            $total = $rekap['hadir'] + $rekap['sakit'] + $rekap['izin'] + $rekap['alpa'];
            $persen = $total > 0 ? round(($rekap['hadir'] / $total) * 100, 1) : 100;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            
            {{-- Rasio --}}
            <div class="stat-card navy flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Persentase Hadir</p>
                <p class="text-2xl font-extrabold font-display text-navy-500 mt-2">{{ $persen }}%</p>
            </div>
            
            {{-- Hadir --}}
            <div class="stat-card green flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hadir</p>
                <p class="text-2xl font-extrabold font-display text-emerald-600 mt-2">{{ $rekap['hadir'] }} Sesi</p>
            </div>

            {{-- Sakit --}}
            <div class="stat-card navy flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sakit</p>
                <p class="text-2xl font-extrabold font-display text-blue-500 mt-2">{{ $rekap['sakit'] }} Sesi</p>
            </div>

            {{-- Izin --}}
            <div class="stat-card gold flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Izin</p>
                <p class="text-2xl font-extrabold font-display text-amber-500 mt-2">{{ $rekap['izin'] }} Sesi</p>
            </div>

            {{-- Alpa --}}
            <div class="stat-card red flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alpa</p>
                <p class="text-2xl font-extrabold font-display text-rose-500 mt-2">{{ $rekap['alpa'] }} Sesi</p>
            </div>

        </div>
    @endif

    {{-- Detail Attendance Log Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Log Detail Presensi Kehadiran</h3>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Hari &amp; Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th>Status Kehadiran</th>
                        <th>Keterangan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailAbsensis as $da)
                        <tr>
                            <td class="font-bold text-slate-800">
                                {{ $da->absensi->tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </td>
                            <td class="font-semibold text-navy-600">{{ $da->absensi->mataPelajaran->nama }}</td>
                            <td class="font-medium text-slate-700">{{ $da->absensi->guru->nama_lengkap }}</td>
                            <td>
                                <span class="badge absensi-status-{{ $da->status }}">
                                    {{ ucfirst($da->status) }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-500 italic">{{ $da->keterangan ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Tidak ada log riwayat presensi terekam untuk Anda pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($detailAbsensis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $detailAbsensis->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
