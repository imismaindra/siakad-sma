@extends('layouts.admin')

@section('title', 'Grid Jadwal Pelajaran')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Grid Jadwal')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Akademik</p>
            <h1 class="page-title font-display !text-3xl mt-3">Grid Jadwal Pelajaran.</h1>
            <p class="page-subtitle">Tampilan kalender mingguan terstruktur per kelas.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Tampilan Tabel
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                Tambah Jadwal
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-500 text-navy-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.jadwal.grid') }}" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Cari mapel, guru, atau ruangan..."
                    class="w-full rounded-full bg-slate-50 border border-slate-200 pl-12 pr-5 py-2.5 text-sm font-medium text-navy-900 outline-none placeholder:text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800">
            </div>
            <select name="tahun_ajaran_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>{{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>
            <select name="kelas_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                <option value="">Pilih kelas...</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-full bg-navy-800 text-white px-6 py-2.5 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.jadwal.grid') }}" class="rounded-full bg-slate-100 text-navy-800 px-6 py-2.5 text-sm font-semibold text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    {{-- Board --}}
    @if($kelasId)
        @php
            $colors = ['navy', 'gold', 'green'];
            $totalSesi = $jadwals->flatten()->count();
        @endphp
        <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="flex items-center justify-between px-5 py-4">
                <p class="text-xs font-medium text-slate-500 tabular-nums">{{ $totalSesi }} sesi terjadwal minggu ini</p>
                <p class="text-xs font-semibold text-navy-900">Senin sampai Sabtu</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 px-3 pb-3">
                @foreach($hariList as $hari)
                    @php
                        $hariJadwal = isset($jadwals[$hari]) ? $jadwals[$hari]->sortBy('jam_mulai') : collect();
                    @endphp
                    <div class="rounded-[calc(2rem-0.5rem)] bg-slate-50 ring-1 ring-black/5 overflow-hidden flex flex-col">
                        <div class="px-4 py-3 text-center border-b border-slate-200/70">
                            <h4 class="font-bold text-xs uppercase tracking-[0.15em] text-navy-900 capitalize">{{ $hari }}</h4>
                            <p class="text-[11px] text-slate-500 tabular-nums mt-0.5">{{ $hariJadwal->count() }} sesi</p>
                        </div>
                        <div class="p-3 flex-1 space-y-3">
                            @forelse($hariJadwal as $j)
                                @php $color = $colors[$j->mata_pelajaran_id % count($colors)]; @endphp
                                <div class="schedule-cell schedule-cell-{{ $color }} relative group transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                                    <p class="font-extrabold text-xs leading-snug">{{ $j->mataPelajaran->nama }}</p>
                                    <p class="font-semibold text-[11px] truncate mt-1">{{ $j->guru->nama_lengkap }}</p>
                                    <p class="font-mono text-[11px]">R. {{ $j->ruangan ?? '-' }}</p>
                                    <div class="flex items-center justify-between mt-1.5 text-[10px] font-bold border-t border-black/5 pt-1.5 font-mono tabular-nums">
                                        <span>Jam ke-{{ $j->urutan_jam }}</span>
                                        <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                    </div>
                                    <span class="absolute top-1.5 right-1.5 hidden group-hover:inline-flex items-center gap-1">
                                        <a href="{{ route('admin.jadwal.edit', $j) }}" title="Edit" class="bg-white/90 text-navy-800 p-1 rounded-md shadow-sm transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus jadwal pelajaran ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus" class="bg-white/90 text-rose-700 p-1 rounded-md shadow-sm transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 0c.34.059.68.114 1.022.166m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </span>
                                </div>
                            @empty
                                <div class="h-28 flex items-center justify-center border-2 border-dashed border-slate-200 rounded-xl">
                                    <span class="text-slate-500 text-xs italic">Kosong</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-[2rem] bg-white p-12 text-center ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <svg class="w-12 h-12 mx-auto text-slate-500 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-bold text-navy-900 text-sm">Silakan Pilih Kelas Terlebih Dahulu</p>
            <p class="text-slate-500 text-xs mt-1">Pilih kelas dari form filter di atas untuk menampilkan jadwal kalender mingguan kelas tersebut.</p>
        </div>
    @endif

</div>
@endsection
