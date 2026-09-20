@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Panel admin</p>
            <h1 class="page-title font-display !text-3xl mt-3">Ringkasan sekolah.</h1>
            <p class="page-subtitle">Kondisi terkini SIAKAD SMA Cihuy dalam satu layar.</p>
        </div>
        <div>
            @if($tahunAktif)
                <span class="badge badge-gold !py-2 !px-4">
                    {{ $tahunAktif->tahun_ajaran }}, Semester {{ $tahunAktif->semester == 'ganjil' ? 'Ganjil' : 'Genap' }}
                </span>
            @else
                <span class="badge badge-danger !py-2 !px-4">
                    Tahun Ajaran Tidak Aktif
                </span>
            @endif
        </div>
    </div>

    {{-- Stat Bento --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Siswa hero --}}
        <div class="lg:col-span-7 rounded-[2rem] bg-navy-800 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 sm:p-8 flex flex-wrap items-end justify-between gap-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Siswa aktif</p>
                    <p class="mt-2 font-display font-extrabold tracking-tight text-white text-5xl sm:text-6xl tabular-nums">{{ $stats['total_siswa'] }}</p>
                    <p class="mt-2 text-sm text-slate-300">Terdaftar di sistem tahun berjalan.</p>
                </div>
                <a href="{{ route('admin.siswa.index') }}" class="group inline-flex items-center gap-3 rounded-full bg-white/10 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white ring-1 ring-white/15 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white/15 active:scale-[0.98]">
                    Kelola siswa
                    <span class="w-8 h-8 rounded-full bg-gold-500 text-navy-900 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </span>
                </a>
            </div>
        </div>

        {{-- Guru --}}
        <div class="lg:col-span-5 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 flex items-center justify-between gap-4 h-full">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Guru aktif</p>
                    <p class="mt-2 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl tabular-nums">{{ $stats['total_guru'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Tenaga pendidik terdaftar.</p>
                </div>
                <a href="{{ route('admin.guru.index') }}" aria-label="Kelola guru" class="group w-12 h-12 shrink-0 rounded-full bg-navy-800 text-white flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                    <svg class="w-5 h-5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
        </div>

        {{-- Kelas --}}
        <div class="lg:col-span-5 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 flex items-center justify-between gap-4 h-full">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Kelas terdaftar</p>
                    <p class="mt-2 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl tabular-nums">{{ $stats['total_kelas'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Rombongan belajar aktif.</p>
                </div>
                <a href="{{ route('admin.kelas.index') }}" aria-label="Kelola kelas" class="group w-12 h-12 shrink-0 rounded-full bg-navy-800 text-white flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                    <svg class="w-5 h-5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
        </div>

        {{-- Nilai menunggu --}}
        <div class="lg:col-span-7 rounded-[2rem] {{ $nilaiMenunggu > 0 ? 'bg-gold-500' : 'bg-white' }} p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 flex flex-wrap items-center justify-between gap-4 {{ $nilaiMenunggu > 0 ? 'shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]' : '' }}">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.2em] {{ $nilaiMenunggu > 0 ? 'text-navy-900/70' : 'text-slate-500' }}">Nilai menunggu finalisasi</p>
                    <p class="mt-2 font-display font-extrabold tracking-tight {{ $nilaiMenunggu > 0 ? 'text-navy-900' : 'text-navy-900' }} text-4xl sm:text-5xl tabular-nums">{{ $nilaiMenunggu }}</p>
                    <p class="mt-2 text-sm {{ $nilaiMenunggu > 0 ? 'text-navy-900/70' : 'text-slate-500' }}">{{ $nilaiMenunggu > 0 ? 'Perlu tindakan sebelum rapor terbit.' : 'Semua nilai sudah final. Rapi.' }}</p>
                </div>
                <a href="{{ route('admin.nilai.laporan') }}" class="group inline-flex items-center gap-3 rounded-full {{ $nilaiMenunggu > 0 ? 'bg-navy-800 text-white hover:bg-navy-700' : 'bg-navy-800 text-white hover:bg-navy-700' }} py-1.5 pl-5 pr-1.5 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] active:scale-[0.98]">
                    Kelola nilai
                    <span class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </span>
                </a>
            </div>
        </div>

    </div>

    {{-- Lower Bento --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Akses cepat --}}
        <div class="lg:col-span-7 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] px-3 py-3">
                <p class="px-4 pt-3 text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Akses cepat</p>
                <ul class="mt-2 divide-y divide-slate-100">
                    <li>
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50 active:scale-[0.99]">
                            <span class="w-11 h-11 rounded-2xl bg-navy-800 text-gold-200 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <span class="flex-1 min-w-0">
                                <span class="block font-bold text-navy-900 text-[15px]">Tahun ajaran</span>
                                <span class="block text-[13px] text-slate-500 mt-0.5">Atur tahun aktif dan semester.</span>
                            </span>
                            <svg class="w-5 h-5 text-slate-300 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:text-navy-800 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.guru.create') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50 active:scale-[0.99]">
                            <span class="w-11 h-11 rounded-2xl bg-gold-100 text-gold-700 flex items-center justify-center shrink-0 ring-1 ring-gold-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            </span>
                            <span class="flex-1 min-w-0">
                                <span class="block font-bold text-navy-900 text-[15px]">Tambah guru</span>
                                <span class="block text-[13px] text-slate-500 mt-0.5">Daftarkan pendidik baru.</span>
                            </span>
                            <svg class="w-5 h-5 text-slate-300 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:text-navy-800 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.siswa.create') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50 active:scale-[0.99]">
                            <span class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 ring-1 ring-emerald-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            </span>
                            <span class="flex-1 min-w-0">
                                <span class="block font-bold text-navy-900 text-[15px]">Tambah siswa</span>
                                <span class="block text-[13px] text-slate-500 mt-0.5">Daftarkan siswa baru.</span>
                            </span>
                            <svg class="w-5 h-5 text-slate-300 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:text-navy-800 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jadwal.index') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50 active:scale-[0.99]">
                            <span class="w-11 h-11 rounded-2xl bg-slate-100 text-navy-800 flex items-center justify-center shrink-0 ring-1 ring-black/5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="flex-1 min-w-0">
                                <span class="block font-bold text-navy-900 text-[15px]">Jadwal pelajaran</span>
                                <span class="block text-[13px] text-slate-500 mt-0.5">Kelola plot jadwal mengajar.</span>
                            </span>
                            <svg class="w-5 h-5 text-slate-300 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:text-navy-800 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Kehadiran --}}
        <div class="lg:col-span-5 rounded-[2rem] bg-navy-800 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 h-full flex flex-col shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Kehadiran pekan ini</p>
                <div class="mt-5 grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-display font-extrabold tracking-tight text-emerald-400 text-4xl tabular-nums">{{ $totalHadir }}</p>
                        <p class="mt-1 text-xs text-slate-400">Hadir</p>
                    </div>
                    <div>
                        <p class="font-display font-extrabold tracking-tight text-rose-400 text-4xl tabular-nums">{{ $totalAlpa }}</p>
                        <p class="mt-1 text-xs text-slate-400">Alpa</p>
                    </div>
                </div>
                @if($totalHadir + $totalAlpa > 0)
                    <div class="mt-6 h-2 rounded-full overflow-hidden flex ring-1 ring-white/15" aria-hidden="true">
                        <div class="bg-emerald-400 h-full" style="width: {{ ($totalHadir / ($totalHadir + $totalAlpa)) * 100 }}%"></div>
                        <div class="bg-rose-400 h-full" style="width: {{ ($totalAlpa / ($totalHadir + $totalAlpa)) * 100 }}%"></div>
                    </div>
                @else
                    <div class="mt-6 h-2 rounded-full ring-1 ring-white/15" aria-hidden="true"></div>
                @endif
                <p class="mt-4 font-display font-bold text-white text-xl">
                    @if($totalHadir + $totalAlpa > 0)
                        {{ round(($totalHadir / ($totalHadir + $totalAlpa)) * 100, 1) }}% hadir
                    @else
                        Belum ada data
                    @endif
                </p>
                <p class="mt-1 text-[13px] text-slate-400">Dari total absensi terisi minggu ini.</p>
                <a href="{{ route('admin.absensi.rekap') }}" class="group mt-auto pt-6 inline-flex items-center gap-3 rounded-full bg-white/10 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white ring-1 ring-white/15 self-start transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white/15 active:scale-[0.98]">
                    Buka rekap absensi
                    <span class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
