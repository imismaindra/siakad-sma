@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('siswa-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Halo, {{ $siswa->nama_lengkap }}</h1>
            <p class="page-subtitle">NIS: {{ $siswa->nis }} | Kelas: <span class="font-bold font-mono text-navy-500">{{ $siswa->kelas?->nama_kelas ?? 'Belum ada Kelas' }}</span></p>
        </div>
        <div>
            @if($tahunAktif)
                <span class="badge badge-gold px-4 py-2 border border-gold-500/20 text-xs font-bold uppercase tracking-wider">
                    Tahun Ajaran: {{ $tahunAktif->tahun_ajaran }} — Semester {{ $tahunAktif->semester == '1' ? 'Ganjil' : 'Genap' }}
                </span>
            @endif
        </div>
    </div>

    {{-- Attendance Overview (Mini Stat Cards) --}}
    @if($rekapAbsensi)
        @php
            $total = $rekapAbsensi['hadir'] + $rekapAbsensi['sakit'] + $rekapAbsensi['izin'] + $rekapAbsensi['alpa'];
            $persen = $total > 0 ? round(($rekapAbsensi['hadir'] / $total) * 100, 1) : 100;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            {{-- Rasio --}}
            <div class="stat-card navy col-span-2 md:col-span-1 flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kehadiran</p>
                <p class="text-2xl font-extrabold font-display text-navy-500 mt-2">{{ $persen }}%</p>
            </div>
            
            {{-- Hadir --}}
            <div class="stat-card green flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hadir</p>
                <p class="text-2xl font-extrabold font-display text-emerald-600 mt-2">{{ $rekapAbsensi['hadir'] }}</p>
            </div>

            {{-- Sakit --}}
            <div class="stat-card navy flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sakit</p>
                <p class="text-2xl font-extrabold font-display text-blue-500 mt-2">{{ $rekapAbsensi['sakit'] }}</p>
            </div>

            {{-- Izin --}}
            <div class="stat-card gold flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Izin</p>
                <p class="text-2xl font-extrabold font-display text-amber-500 mt-2">{{ $rekapAbsensi['izin'] }}</p>
            </div>

            {{-- Alpa --}}
            <div class="stat-card red flex flex-col justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alpa</p>
                <p class="text-2xl font-extrabold font-display text-rose-500 mt-2">{{ $rekapAbsensi['alpa'] }}</p>
            </div>
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Today's Schedule --}}
        <div class="lg:col-span-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Jadwal Belajar Hari Ini
                    </h3>
                </div>
                
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Jam Ke-</th>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru Pengampu</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni as $j)
                                <tr>
                                    <td>
                                        <span class="badge badge-gray font-bold">Jam ke-{{ $j->urutan_jam }}</span>
                                    </td>
                                    <td class="font-mono text-xs font-semibold text-slate-600">
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td class="font-bold text-slate-800">{{ $j->mataPelajaran->nama }}</td>
                                    <td class="font-medium text-slate-700">{{ $j->guru->nama_lengkap }}</td>
                                    <td>
                                        <span class="badge badge-navy font-mono">{{ $j->ruangan ?? '—' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400">
                                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Tidak ada jadwal belajar hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Recent Grades --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Nilai Rapor Terbaru</h3>
                </div>
                <div class="card-body p-4 space-y-4">
                    @forelse($nilaiTerbaru as $n)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-navy-100 transition-colors">
                            <div>
                                <p class="font-bold text-xs text-slate-800 truncate max-w-[150px]" title="{{ $n->mataPelajaran->nama }}">{{ $n->mataPelajaran->nama }}</p>
                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">KKM: {{ $n->mataPelajaran->kkm }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold font-mono text-navy-600 text-sm">{{ $n->nilai_akhir }}</p>
                                <span class="grade-{{ strtolower($n->predikat) }} font-bold text-[10px]">{{ $n->predikat }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs italic text-center py-6">Belum ada nilai terpublikasi.</p>
                    @endforelse

                    <div class="pt-2">
                        <a href="{{ route('siswa.nilai') }}" class="btn-secondary w-full justify-center text-xs">
                            Lihat Semua Nilai Rapor
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
