@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Selamat Datang, {{ $guru->nama_lengkap }}</h1>
            <p class="page-subtitle">Panel Akademik Guru SMA Nusantara.</p>
        </div>
        <div>
            @if($tahunAktif)
                <span class="badge badge-gold px-4 py-2 border border-gold-500/20 text-xs font-bold uppercase tracking-wider">
                    Tahun Ajaran: {{ $tahunAktif->tahun_ajaran }} — Semester {{ $tahunAktif->semester == '1' ? 'Ganjil' : 'Genap' }}
                </span>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Jadwal Hari Ini --}}
        <div class="stat-card navy">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jadwal Mengajar Hari Ini</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ count($jadwalHariIni) }} Kelas</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-navy-50 flex items-center justify-center text-navy-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sesi Mengajar Bulan Ini --}}
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sesi Mengajar Bulan Ini</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ $totalSesiMengajar }} Sesi</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Belum Diabsen --}}
        <div class="stat-card {{ count($belumAbsen) > 0 ? 'red animate-pulse' : 'gold' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tunggakan Absensi</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ count($belumAbsen) }} Sesi</p>
                </div>
                <div class="w-12 h-12 rounded-xl {{ count($belumAbsen) > 0 ? 'bg-rose-50 text-rose-600' : 'bg-gold-50 text-gold-500' }} flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- Timetable / Schedule list --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-5 h-5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Agenda Mengajar Hari Ini — {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </h3>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jam Ke-</th>
                        <th>Waktu</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Ruangan</th>
                        <th>Status Absensi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalHariIni as $j)
                        @php
                            $absenExist = \App\Models\Absensi::where('jadwal_pelajaran_id', $j->id)
                                ->whereDate('tanggal', today())
                                ->first();
                        @endphp
                        <tr>
                            <td>
                                <span class="badge badge-gray font-bold">Jam ke-{{ $j->urutan_jam }}</span>
                            </td>
                            <td class="font-mono text-xs font-semibold text-slate-600">
                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                            </td>
                            <td>
                                <span class="text-navy-600 font-bold font-mono">{{ $j->kelas->nama_kelas }}</span>
                            </td>
                            <td class="font-bold text-slate-800">{{ $j->mataPelajaran->nama }}</td>
                            <td>{{ $j->ruangan ?? '—' }}</td>
                            <td>
                                @if($absenExist)
                                    <span class="badge badge-success">Sudah Diabsen</span>
                                @else
                                    <span class="badge badge-danger">Belum Diabsen</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($absenExist)
                                    <a href="{{ route('guru.absensi.show', $absenExist->id) }}" class="btn-secondary btn-sm">
                                        Lihat Detail
                                    </a>
                                @else
                                    <a href="{{ route('guru.absensi.create', $j->id) }}" class="btn-primary btn-sm">
                                        Input Absensi
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tidak ada jadwal mengajar terdaftar untuk Anda hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
