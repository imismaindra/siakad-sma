@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('admin-content')
<div class="space-y-8 animate-fade-in-up">
    
    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Dashboard Admin</h1>
            <p class="page-subtitle">Selamat datang kembali di panel administrasi SIAKAD SMA Nusantara.</p>
        </div>
        <div>
            @if($tahunAktif)
                <span class="badge badge-gold px-4 py-2 border border-gold-500/20 text-xs font-bold uppercase tracking-wider">
                    Tahun Ajaran: {{ $tahunAktif->tahun_ajaran }} — Semester {{ $tahunAktif->semester == 'ganjil' ? 'Ganjil' : 'Genap' }}
                </span>
            @else
                <span class="badge badge-danger px-4 py-2 text-xs font-bold uppercase tracking-wider">
                    Tahun Ajaran Tidak Aktif
                </span>
            @endif
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Total Siswa --}}
        <div class="stat-card navy">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Siswa Aktif</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ $stats['total_siswa'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-navy-50 flex items-center justify-center text-navy-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Terdaftar di SIstem</span>
                <a href="{{ route('admin.siswa.index') }}" class="text-navy-500 font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="stat-card gold">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Guru Aktif</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ $stats['total_guru'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Tenaga Pendidik</span>
                <a href="{{ route('admin.guru.index') }}" class="text-gold-600 font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kelas Terdaftar</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ $stats['total_kelas'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Rombongan Belajar</span>
                <a href="{{ route('admin.kelas.index') }}" class="text-emerald-600 font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Pending Grades --}}
        <div class="stat-card red">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Menunggu Finalisasi</p>
                    <p class="text-3xl font-extrabold font-display text-slate-800 mt-2">{{ $nilaiMenunggu }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Ujian &amp; Tugas</span>
                <a href="{{ route('admin.nilai.laporan') }}" class="text-rose-600 font-semibold hover:underline flex items-center gap-1">
                    Kelola Nilai
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Quick Actions --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Akses Cepat Admin
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-navy-100 hover:bg-navy-50/20 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-navy-50 text-navy-500 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Tahun Ajaran</h4>
                                <p class="text-xs text-slate-400 mt-0.5">Atur tahun aktif &amp; semester.</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.guru.create') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-navy-100 hover:bg-navy-50/20 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-gold-50 text-gold-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Tambah Guru</h4>
                                <p class="text-xs text-slate-400 mt-0.5">Pendaftaran pendidik baru.</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.siswa.create') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-navy-100 hover:bg-navy-50/20 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Tambah Siswa</h4>
                                <p class="text-xs text-slate-400 mt-0.5">Pendaftaran siswa baru.</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-navy-100 hover:bg-navy-50/20 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Jadwal Pelajaran</h4>
                                <p class="text-xs text-slate-400 mt-0.5">Kelola plot jadwal mengajar.</p>
                            </div>
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Info --}}
        <div class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Kehadiran Pekan Ini
                    </h3>
                </div>
                <div class="card-body space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-slate-400">Total Status Hadir</p>
                            <p class="text-2xl font-bold font-display text-emerald-600 mt-1">{{ $totalHadir }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 text-right">Total Status Alpa</p>
                            <p class="text-2xl font-bold font-display text-rose-600 mt-1 text-right">{{ $totalAlpa }}</p>
                        </div>
                    </div>
                    
                    {{-- Progress Bar --}}
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-semibold">
                            <span>Rasio Kehadiran</span>
                            <span>
                                @if($totalHadir + $totalAlpa > 0)
                                    {{ round(($totalHadir / ($totalHadir + $totalAlpa)) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden flex">
                            @if($totalHadir + $totalAlpa > 0)
                                <div class="bg-emerald-500 h-full" style="width: {{ ($totalHadir / ($totalHadir + $totalAlpa)) * 100 }}%"></div>
                                <div class="bg-rose-500 h-full" style="width: {{ ($totalAlpa / ($totalHadir + $totalAlpa)) * 100 }}%"></div>
                            @else
                                <div class="bg-slate-300 h-full w-full"></div>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400 mt-3 text-center italic">Rasio kehadiran dihitung berdasarkan total absensi terisi minggu ini.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
