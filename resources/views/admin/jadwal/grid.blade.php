@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Jadwal Pelajaran')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Akademik</p>
            <h1 class="page-title font-display !text-3xl mt-3">Jadwal mingguan.</h1>
            <p class="page-subtitle">Papan Senin sampai Sabtu per kelas. Geser atau tambah sesi langsung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex rounded-full bg-white p-1 gap-1 ring-1 ring-black/5" role="tablist" aria-label="Mode tampilan">
                <span class="rounded-full px-5 py-2 text-[13px] font-bold bg-navy-800 text-white">Grid</span>
                <a href="{{ route('admin.jadwal.index', array_merge(request()->query(), ['mode' => 'daftar'])) }}" class="rounded-full px-5 py-2 text-[13px] font-bold text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-navy-800">Daftar</a>
            </div>
            <a href="{{ route('admin.jadwal.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                Tambah jadwal
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.jadwal.index') }}" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Cari mapel, guru, atau ruangan..."
                    class="w-full rounded-full bg-slate-50 border border-slate-200 pl-12 pr-5 py-2.5 text-sm font-medium text-navy-900 outline-none placeholder:text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10">
            </div>
            <select name="tahun_ajaran_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none" onchange="this.form.submit()">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>{{ $ta->nama_lengkap }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}</option>
                @endforeach
            </select>
            <select name="kelas_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none" onchange="this.form.submit()">
                <option value="">Pilih kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-full bg-navy-800 text-white px-6 py-2.5 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">Terapkan</button>
            <a href="{{ route('admin.jadwal.index') }}" class="rounded-full bg-slate-100 text-navy-800 px-6 py-2.5 text-sm font-semibold text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    {{-- Board --}}
    @if($kelasId)
        @php
            $colors = ['navy', 'gold', 'green'];
            $totalSesi = $jadwals->flatten()->count();
            $kelasAktif = $kelasList->firstWhere('id', $kelasId);
        @endphp
        <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-4">
                <p class="font-display font-extrabold text-navy-900 text-lg tracking-tight">
                    @if($kelasAktif) Kelas {{ $kelasAktif->nama_kelas }} @endif
                    <span class="ml-2 align-middle badge badge-navy tabular-nums">{{ $totalSesi }} sesi</span>
                </p>
                <p class="text-xs font-medium text-slate-500">Senin sampai Sabtu</p>
            </div>
            <div class="flex lg:grid lg:grid-cols-3 xl:grid-cols-6 gap-4 px-3 pb-3 overflow-x-auto snap-x snap-mandatory lg:overflow-visible pb-4 lg:pb-3 items-start">
                @foreach($hariList as $hari)
                    @php
                        $hariJadwal = isset($jadwals[$hari]) ? $jadwals[$hari]->sortBy('jam_mulai') : collect();
                    @endphp
                    <section class="min-w-[270px] lg:min-w-0 snap-start rounded-[calc(2rem-0.5rem)] bg-slate-50 ring-1 ring-black/5 overflow-hidden flex flex-col">
                        <header class="px-4 py-3 flex items-center justify-between border-b border-slate-200/70">
                            <h2 class="font-bold text-xs uppercase tracking-[0.15em] text-navy-900 capitalize">{{ $hari }}</h2>
                            <span class="badge {{ $hariJadwal->count() > 0 ? 'badge-navy' : 'badge-gray' }} tabular-nums">{{ $hariJadwal->count() }}</span>
                        </header>
                        <div class="p-3 flex-1 space-y-3">
                            @forelse($hariJadwal as $j)
                                @php $color = $colors[$j->mata_pelajaran_id % count($colors)]; @endphp
                                <article class="schedule-cell schedule-cell-{{ $color }} transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:-translate-y-0.5">
                                    <div class="flex items-center justify-between gap-1.5 font-mono text-[11px] font-bold tabular-nums">
                                        <span class="truncate">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                        <span class="shrink-0 opacity-70">J{{ $j->urutan_jam }}</span>
                                    </div>
                                    <p class="font-extrabold text-[13px] leading-snug mt-1.5 break-words">{{ $j->mataPelajaran->nama }}</p>
                                    <p class="font-semibold text-xs truncate mt-0.5">{{ $j->guru->nama_lengkap }}</p>
                                    @if($j->ruangan)
                                        <p class="font-mono text-[11px] mt-0.5 opacity-80 truncate">Ruang {{ $j->ruangan }}</p>
                                    @endif
                                    <div class="flex items-center gap-1.5 mt-2.5 pt-2 border-t border-black/5">
                                        <a href="{{ route('admin.jadwal.edit', $j) }}" aria-label="Edit {{ $j->mataPelajaran->nama }}" class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-white/80 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white active:scale-[0.98]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" class="inline" data-confirm="Hapus sesi {{ $j->mataPelajaran->nama }} hari {{ $hari }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" aria-label="Hapus {{ $j->mataPelajaran->nama }}" class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-white/80 text-rose-700 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-600 hover:text-white active:scale-[0.98]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 0c.34.059.68.114 1.022.166m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @empty
                                <a href="{{ route('admin.jadwal.create') }}" class="min-h-[96px] flex items-center justify-center gap-2 border-2 border-dashed border-slate-200 rounded-xl text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:border-navy-800 hover:text-navy-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    <span class="text-xs font-bold">Tambah</span>
                                </a>
                            @endforelse
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-[2rem] bg-white p-12 text-center ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <span class="mx-auto w-14 h-14 rounded-full bg-navy-800 text-gold-200 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
            <p class="mt-4 font-display font-extrabold text-navy-900 text-xl tracking-tight">Pilih kelas dulu.</p>
            <p class="text-slate-500 text-sm mt-1">Papan mingguan tampil setelah kelas dipilih di filter atas.</p>
        </div>
    @endif

</div>
@endsection
