@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display text-slate-900 tracking-tight">Ringkasan Operasional Sekolah</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Ikhtisar data pokok pendidikan, rombongan belajar, presensi, dan status penilaian.</p>
        </div>
        <div class="flex items-center gap-2.5">
            @if($tahunAktif)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white shadow-2xs text-xs font-medium text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span>Tahun Ajaran {{ $tahunAktif->tahun_ajaran }} · Semester {{ $tahunAktif->semester == 'ganjil' ? 'Ganjil' : 'Genap' }}</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-xs font-semibold text-rose-700">
                    <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                    <span>Tahun Ajaran Tidak Aktif</span>
                </div>
            @endif
        </div>
    </div>

    {{-- 4 Core KPI Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Siswa Aktif --}}
        <div class="card p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Siswa Aktif</p>
                    <p class="mt-2 text-3xl font-extrabold font-display text-slate-900 tabular-nums leading-none">
                        {{ number_format($stats['total_siswa'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Terdaftar di rombel</span>
                <a href="{{ route('admin.siswa.index') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1">
                    Kelola
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>

        {{-- Guru Aktif --}}
        <div class="card p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tenaga Pendidik</p>
                    <p class="mt-2 text-3xl font-extrabold font-display text-slate-900 tabular-nums leading-none">
                        {{ number_format($stats['total_guru'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Guru pengampu aktif</span>
                <a href="{{ route('admin.guru.index') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1">
                    Kelola
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>

        {{-- Rombongan Belajar / Kelas --}}
        <div class="card p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Rombongan Belajar</p>
                    <p class="mt-2 text-3xl font-extrabold font-display text-slate-900 tabular-nums leading-none">
                        {{ number_format($stats['total_kelas'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Kelas aktif semester ini</span>
                <a href="{{ route('admin.kelas.index') }}" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1">
                    Kelola
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>

        {{-- Status Penilaian --}}
        <div class="card p-5 flex flex-col justify-between {{ $nilaiMenunggu > 0 ? 'bg-amber-50/40 border-amber-200/80' : '' }}">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider {{ $nilaiMenunggu > 0 ? 'text-amber-800' : 'text-slate-500' }}">Status Nilai Rapor</p>
                    <p class="mt-2 text-3xl font-extrabold font-display {{ $nilaiMenunggu > 0 ? 'text-amber-900' : 'text-slate-900' }} tabular-nums leading-none">
                        {{ $nilaiMenunggu }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg {{ $nilaiMenunggu > 0 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t {{ $nilaiMenunggu > 0 ? 'border-amber-200/60' : 'border-slate-100' }} flex items-center justify-between text-xs">
                <span class="{{ $nilaiMenunggu > 0 ? 'text-amber-700' : 'text-slate-500' }}">
                    {{ $nilaiMenunggu > 0 ? 'Menunggu finalisasi' : 'Semua nilai final' }}
                </span>
                <a href="{{ route('admin.nilai.laporan') }}" class="font-semibold {{ $nilaiMenunggu > 0 ? 'text-amber-800 hover:text-amber-950' : 'text-blue-600 hover:text-blue-800' }} transition-colors inline-flex items-center gap-1">
                    Laporan
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>

    </div>

    {{-- Operational Shortcuts & Attendance Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Pintasan Administrasi (7 cols) --}}
        <div class="lg:col-span-7 card">
            <div class="card-header">
                <div>
                    <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight">Pintasan Menu Administrasi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Akses cepat ke konfigurasi dan pendataan utama sekolah</p>
                </div>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('admin.tahun-ajaran.index') }}" class="flex items-start gap-3 p-3 rounded-lg border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/30 transition-all group">
                    <div class="w-9 h-9 rounded-md bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Tahun Ajaran</p>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Pengaturan semester aktif dan tanggal penting.</p>
                    </div>
                </a>

                <a href="{{ route('admin.siswa.create') }}" class="flex items-start gap-3 p-3 rounded-lg border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/30 transition-all group">
                    <div class="w-9 h-9 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Tambah Siswa Baru</p>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Registrasi peserta didik ke database sekolah.</p>
                    </div>
                </a>

                <a href="{{ route('admin.guru.create') }}" class="flex items-start gap-3 p-3 rounded-lg border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/30 transition-all group">
                    <div class="w-9 h-9 rounded-md bg-amber-50 text-amber-700 border border-amber-100 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-4.5-2.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM3.75 18a5.25 5.25 0 0110.5 0v.75H3.75V18z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Tambah Pendidik</p>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Daftarkan akun dan profil guru pengampu.</p>
                    </div>
                </a>

                <a href="{{ route('admin.jadwal.index') }}" class="flex items-start gap-3 p-3 rounded-lg border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/30 transition-all group">
                    <div class="w-9 h-9 rounded-md bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Jadwal Pelajaran</p>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Plot jam tatap muka dan ruang kelas.</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Kehadiran Pekan Ini (5 cols) --}}
        <div class="lg:col-span-5 card">
            <div class="card-header">
                <div>
                    <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight">Presensi Siswa Pekan Ini</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Statistik kehadiran terakumulasi minggu berjalan</p>
                </div>
                <a href="{{ route('admin.absensi.rekap') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Rekap
                </a>
            </div>
            <div class="card-body">
                @php
                    $totalCatatan = $totalHadir + $totalAlpa;
                    $persenHadir = $totalCatatan > 0 ? round(($totalHadir / $totalCatatan) * 100, 1) : 0;
                @endphp

                <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-100">
                    <div class="p-3 rounded-lg bg-emerald-50/50 border border-emerald-100">
                        <p class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Hadir Terdata</p>
                        <p class="text-2xl font-bold font-display text-emerald-700 tabular-nums mt-1">{{ number_format($totalHadir, 0, ',', '.') }}</p>
                        <p class="text-[11px] text-emerald-600 mt-0.5">Sesi pertemuan</p>
                    </div>
                    <div class="p-3 rounded-lg bg-rose-50/50 border border-rose-100">
                        <p class="text-[11px] font-semibold text-rose-800 uppercase tracking-wider">Alpa (Tanpa Keterangan)</p>
                        <p class="text-2xl font-bold font-display text-rose-700 tabular-nums mt-1">{{ number_format($totalAlpa, 0, ',', '.') }}</p>
                        <p class="text-[11px] text-rose-600 mt-0.5">Sesi tidak hadir</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs font-medium text-slate-700 mb-1.5">
                        <span>Rasio Kehadiran</span>
                        <span class="font-bold font-mono">{{ $totalCatatan > 0 ? $persenHadir . '%' : 'Belum Ada Data' }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden flex" aria-hidden="true">
                        @if($totalCatatan > 0)
                            <div class="bg-emerald-600 h-full transition-all duration-300" style="width: {{ $persenHadir }}%"></div>
                            <div class="bg-rose-500 h-full transition-all duration-300" style="width: {{ 100 - $persenHadir }}%"></div>
                        @else
                            <div class="bg-slate-200 h-full w-full"></div>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">
                        @if($totalCatatan > 0)
                            Dihitung dari {{ number_format($totalCatatan, 0, ',', '.') }} total log presensi kelas minggu ini.
                        @else
                            Belum ada rekap presensi terinput pada pekan ini.
                        @endif
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Pengumuman & Agenda Akademik (F5) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Widget Pengumuman Sekolah --}}
        <div class="card p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900">Pengumuman Sekolah</h2>
                        <p class="text-[11px] text-slate-500">Maklumat dan informasi resmi aktif</p>
                    </div>
                </div>
                <a href="{{ route('admin.pengumuman.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Kelola Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($pengumumans as $p)
                    <div class="py-2.5 flex items-start justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $p->is_aktif ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $p->is_aktif ? 'Aktif' : 'Draf' }}
                                </span>
                                <span class="text-[10px] text-slate-400">Target: <strong class="text-slate-600 capitalize">{{ $p->target }}</strong></span>
                            </div>
                            <p class="text-xs font-bold text-slate-900 mt-1 line-clamp-1">{{ $p->judul }}</p>
                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ Str::limit(strip_tags($p->konten), 80) }}</p>
                        </div>
                        <a href="{{ route('admin.pengumuman.edit', $p) }}" class="text-[11px] font-semibold text-slate-500 hover:text-navy-900 shrink-0">
                            Edit
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Belum ada pengumuman yang diterbitkan.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Widget Agenda Kalender Akademik Terdekat --}}
        <div class="card p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gold-50 text-gold-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900">Agenda Kalender Terdekat</h2>
                        <p class="text-[11px] text-slate-500">Jadwal penting dan libur mendatang</p>
                    </div>
                </div>
                <a href="{{ route('admin.kalender.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Kalender &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($upcomingEvents as $ev)
                    <div class="py-2.5 flex items-start justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold {{ $ev->badge_class }}">
                                {{ $ev->label_kategori }}
                            </span>
                            <p class="text-xs font-bold text-slate-900 mt-1 line-clamp-1">
                                {{ $ev->judul }}
                                @if($ev->dokumen)
                                    <span class="text-[10px] text-navy-700 ml-1" title="Ada Dokumen">📎</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{ $ev->periode_formatted }}
                                @if($ev->jam_mulai)
                                    · {{ $ev->waktu_formatted }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Tidak ada agenda terdekat yang dijadwalkan.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
