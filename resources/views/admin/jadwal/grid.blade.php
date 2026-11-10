@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran - Mingguan')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Jadwal Pelajaran')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Top Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Akademik</p>
            <h1 class="page-title font-display !text-3xl mt-3">Jadwal mingguan.</h1>
            <p class="page-subtitle mt-1">Papan interaktif jadwal kelas per minggu. Kelola sesi, jam mengajar, dan ekspor jadwal resmi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Mode Switcher --}}
            <div class="inline-flex rounded-full bg-white p-1 gap-1 ring-1 ring-black/5 shadow-xs" role="tablist" aria-label="Mode tampilan">
                <span class="rounded-full px-5 py-2 text-[13px] font-bold bg-navy-800 text-white shadow-xs">Grid</span>
                <a href="{{ route('admin.jadwal.index', array_merge(request()->query(), ['mode' => 'daftar'])) }}" class="rounded-full px-5 py-2 text-[13px] font-bold text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-navy-800">Daftar</a>
            </div>

            {{-- Export PDF Button --}}
            @if($kelasId)
                <a href="{{ route('admin.jadwal.export-pdf', ['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $kelasId]) }}" class="group inline-flex items-center gap-3 rounded-full bg-white py-1.5 pl-5 pr-1.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 shadow-xs" title="Ekspor Jadwal Kelas ke PDF">
                    Ekspor PDF
                    <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-100 text-gold-700 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-500 group-hover:text-navy-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </span>
                </a>
            @endif

            {{-- Add Schedule Button --}}
            <a href="{{ route('admin.jadwal.create', array_filter(['kelas_id' => $kelasId])) }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900 active:scale-[0.98] shadow-xs">
                Tambah jadwal
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <form method="GET" action="{{ route('admin.jadwal.index') }}" class="rounded-[2rem] bg-white p-2.5 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <input type="hidden" name="mode" value="grid">
        <div class="flex flex-col lg:flex-row gap-3 p-1.5">
            <div class="relative flex-1 min-w-[220px]">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Cari mapel, guru, atau ruangan..."
                    class="w-full rounded-full bg-slate-50 border border-slate-200 pl-12 pr-5 py-2.5 text-sm font-medium text-navy-900 outline-none placeholder:text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10">
            </div>
            <select name="tahun_ajaran_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none transition focus:bg-white focus:border-navy-800" onchange="this.form.submit()">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>{{ $ta->nama_lengkap }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}</option>
                @endforeach
            </select>
            <select name="kelas_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none transition focus:bg-white focus:border-navy-800" onchange="this.form.submit()">
                <option value="">Pilih kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-full bg-navy-800 text-white px-6 py-2.5 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900 active:scale-[0.98]">Terapkan</button>
            <a href="{{ route('admin.jadwal.index') }}" class="rounded-full bg-slate-100 text-navy-800 px-6 py-2.5 text-sm font-semibold text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    {{-- Quick Class Pills Strip (Instant 1-Click Class Switcher) --}}
    @if($kelasList->isNotEmpty())
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none" aria-label="Pilih kelas cepat">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 pl-1 shrink-0">Kelas:</span>
            @foreach($kelasList as $k)
                @php
                    $isAktif = ($k->id == $kelasId);
                    $sesiCount = $k->jadwal_pelajarans_count ?? 0;
                @endphp
                <a href="{{ route('admin.jadwal.index', ['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $k->id, 'mode' => 'grid']) }}" 
                    class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold transition-all duration-300 {{ $isAktif ? 'bg-navy-800 text-white shadow-sm ring-1 ring-navy-800' : 'bg-white text-slate-700 ring-1 ring-slate-200/80 hover:bg-slate-100 hover:text-navy-900' }}">
                    <span>{{ $k->nama_kelas }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] tabular-nums font-mono {{ $isAktif ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $sesiCount }}
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Main Board Island --}}
    @if($selectedKelas)
        @php
            $totalSesi = $jadwals->flatten()->count();
            $wali = $selectedKelas->waliKelas;
        @endphp
        <div class="rounded-[2rem] bg-white p-3 sm:p-4 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] space-y-4">
            
            {{-- Board Header Info Banner --}}
            <div class="rounded-[calc(2rem-0.5rem)] bg-slate-50 p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 border border-slate-200/60">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-navy-800 text-gold-400 flex items-center justify-center font-bold font-display text-lg shrink-0 shadow-xs">
                        {{ $selectedKelas->tingkat }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-display font-black text-navy-950 text-xl tracking-tight">Kelas {{ $selectedKelas->nama_kelas }}</h2>
                            @if($selectedKelas->jurusan)
                                <span class="badge badge-navy text-[11px] font-bold">{{ $selectedKelas->jurusan->nama ?? $selectedKelas->jurusan->kode }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-2 flex-wrap">
                            <span>Wali Kelas: <strong class="text-slate-800">{{ $wali?->name ?? 'Belum ditentukan' }}</strong></span>
                            <span>&bull;</span>
                            <span>Kapasitas: <strong class="text-slate-800">{{ $selectedKelas->kapasitas }} Siswa</strong></span>
                        </p>
                    </div>
                </div>

                {{-- Metrics Summary --}}
                <div class="flex items-center gap-3 self-start md:self-auto">
                    <div class="px-4 py-2 rounded-xl bg-white border border-slate-200/80 shadow-xs text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Sesi</span>
                        <span class="block text-base font-extrabold text-navy-900 font-mono tabular-nums leading-none mt-1">{{ $totalSesi }} Sesi</span>
                    </div>
                    <div class="px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200/80 shadow-xs text-center">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-emerald-700">Status</span>
                        <span class="block text-xs font-bold text-emerald-700 leading-none mt-1">Bebas Bentrok</span>
                    </div>
                </div>
            </div>

            {{-- Weekly Timetable 6-Column Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3.5 items-start">
                @foreach($hariList as $hari)
                    @php
                        $hariJadwal = isset($jadwals[$hari]) ? $jadwals[$hari]->sortBy('jam_mulai') : collect();
                        $adaJadwal = $hariJadwal->isNotEmpty();
                    @endphp
                    <section class="rounded-2xl bg-slate-50 border border-slate-200/70 p-2.5 flex flex-col justify-between transition-all duration-300 {{ $adaJadwal ? 'hover:border-slate-300' : '' }}">
                        {{-- Day Column Header --}}
                        <header class="px-3 py-2 flex items-center justify-between border-b border-slate-200/70 pb-2 mb-2.5">
                            <div class="flex items-center gap-2">
                                <h3 class="font-extrabold text-xs uppercase tracking-wider text-navy-900">{{ $hari }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tabular-nums {{ $adaJadwal ? 'bg-navy-800 text-white' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $hariJadwal->count() }}
                                </span>
                            </div>
                            <a href="{{ route('admin.jadwal.create', ['hari' => $hari, 'kelas_id' => $selectedKelas->id]) }}" 
                               title="Tambah sesi hari {{ $hari }}"
                               class="w-6 h-6 rounded-full bg-white border border-slate-200/80 text-navy-900 flex items-center justify-center hover:bg-navy-800 hover:text-white transition shadow-xs active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </header>

                        {{-- Session Cards List --}}
                        <div class="space-y-2.5">
                            @forelse($hariJadwal as $j)
                                <article class="rounded-xl border border-slate-200/90 bg-white p-3 shadow-xs hover:border-navy-300 hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                                    {{-- Time and Order Row --}}
                                    <div class="flex items-center justify-between gap-1 pb-1.5 border-b border-slate-100">
                                        <span class="inline-flex items-center gap-1 font-mono text-[11px] font-bold text-slate-600 tabular-nums">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-navy-50 text-navy-800 border border-navy-100">
                                            J{{ $j->urutan_jam }}
                                        </span>
                                    </div>

                                    {{-- Subject Title --}}
                                    <h4 class="font-bold text-xs text-navy-950 mt-2 line-clamp-2 leading-snug group-hover:text-navy-800">
                                        {{ $j->mataPelajaran->nama }}
                                    </h4>

                                    {{-- Teacher & Room --}}
                                    <div class="mt-2 space-y-1 text-[11px] text-slate-600">
                                        <p class="truncate flex items-center gap-1" title="{{ $j->guru->nama_lengkap }}">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                            <span class="truncate">{{ $j->guru->nama_lengkap }}</span>
                                        </p>
                                        @if($j->ruangan)
                                            <p class="font-mono text-[10px] text-slate-500 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                                <span>Ruang {{ $j->ruangan }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Actions Footer --}}
                                    <div class="flex items-center justify-end gap-1.5 mt-2.5 pt-2 border-t border-slate-100">
                                        <a href="{{ route('admin.jadwal.edit', $j) }}" 
                                           title="Edit Sesi"
                                           class="inline-flex w-7 h-7 items-center justify-center rounded-lg bg-gold-50 text-gold-700 hover:bg-gold-500 hover:text-navy-900 transition active:scale-95 shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" class="inline" data-confirm="Hapus sesi {{ $j->mataPelajaran->nama }} hari {{ $hari }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Sesi"
                                                    class="inline-flex w-7 h-7 items-center justify-center rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white transition active:scale-95 shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 0c.34.059.68.114 1.022.166m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @empty
                                {{-- Empty Day Interactive Dropzone --}}
                                <a href="{{ route('admin.jadwal.create', ['hari' => $hari, 'kelas_id' => $selectedKelas->id]) }}" 
                                   class="min-h-[110px] rounded-xl border-2 border-dashed border-slate-200 bg-white/60 hover:bg-white hover:border-navy-700 hover:text-navy-900 transition flex flex-col items-center justify-center gap-1.5 p-3 text-center group active:scale-[0.98]">
                                    <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 group-hover:bg-navy-800 group-hover:text-white transition flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    </span>
                                    <span class="text-[11px] font-bold text-slate-600 group-hover:text-navy-900">Tambah Sesi</span>
                                    <span class="text-[10px] text-slate-400">Belum ada jadwal</span>
                                </a>
                            @endforelse

                            {{-- Bottom quick add if column already has items --}}
                            @if($adaJadwal)
                                <a href="{{ route('admin.jadwal.create', ['hari' => $hari, 'kelas_id' => $selectedKelas->id]) }}" 
                                   class="w-full py-2 rounded-xl border border-dashed border-slate-300 hover:border-navy-700 hover:bg-white text-slate-500 hover:text-navy-900 transition text-[11px] font-bold flex items-center justify-center gap-1.5 active:scale-[0.98]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah sesi {{ $hari }}</span>
                                </a>
                            @endif
                        </div>
                    </section>
                @endforeach
            </div>

        </div>
    @else
        {{-- Empty state if no class in year --}}
        <div class="rounded-[2rem] bg-white p-12 text-center ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <span class="mx-auto w-14 h-14 rounded-full bg-navy-800 text-gold-200 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
            <p class="mt-4 font-display font-extrabold text-navy-900 text-xl tracking-tight">Belum ada kelas terdaftar.</p>
            <p class="text-slate-500 text-sm mt-1">Tambahkan kelas terlebih dahulu pada menu Data Kelas untuk mulai menyusun jadwal.</p>
            <a href="{{ route('admin.kelas.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white hover:bg-navy-900 transition">
                Tambah Kelas
            </a>
        </div>
    @endif

</div>
@endsection
