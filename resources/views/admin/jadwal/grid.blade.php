@extends('layouts.admin')

@section('title', 'Grid Jadwal Pelajaran')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Grid Jadwal')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Grid Jadwal Pelajaran</h1>
            <p class="page-subtitle">Tampilan kalender mingguan terstruktur per kelas.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.jadwal.index') }}" class="btn-secondary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Tampilan Tabel List
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.jadwal.grid') }}" method="GET" id="grid-filter-form" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" onchange="document.getElementById('grid-filter-form').submit()" class="form-select">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                                {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label for="kelas_id" class="form-label">Pilih Kelas</label>
                    <select id="kelas_id" name="kelas_id" onchange="document.getElementById('grid-filter-form').submit()" class="form-select">
                        <option value="">Pilih kelas untuk melihat grid...</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    @if($kelasId)
                        <a href="{{ route('admin.jadwal.grid', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn-secondary w-full justify-center">
                            Reset Pilihan Kelas
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Grid timetable --}}
    @if($kelasId)
        @php
            $colors = ['navy', 'gold', 'green', 'purple', 'pink'];
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
            @foreach($hariList as $hari)
                @php
                    $hariJadwal = isset($jadwals[$hari]) 
                        ? $jadwals[$hari]->sortBy('jam_mulai') 
                        : collect();
                @endphp
                
                <div class="card h-full flex flex-col">
                    <div class="card-header bg-slate-50 py-3.5 justify-center border-b border-slate-100">
                        <h4 class="font-bold font-display text-sm uppercase tracking-wider text-slate-700 capitalize">{{ $hari }}</h4>
                    </div>
                    <div class="card-body p-4 flex-1 space-y-4">
                        @forelse($hariJadwal as $idx => $j)
                            @php
                                $color = $colors[$j->mata_pelajaran_id % count($colors)];
                            @endphp
                            
                            <div class="schedule-cell schedule-cell-{{ $color }} space-y-2 relative group shadow-sm">
                                <p class="font-extrabold text-xs leading-snug">{{ $j->mataPelajaran->nama }}</p>
                                
                                <div class="space-y-0.5 text-[10px] opacity-80">
                                    <p class="font-semibold truncate">{{ $j->guru->nama_lengkap }}</p>
                                    <p class="font-mono">R. {{ $j->ruangan ?? '—' }}</p>
                                </div>
                                
                                <div class="flex items-center justify-between mt-1 text-[9px] font-bold opacity-90 border-t border-black/5 pt-1.5 font-mono">
                                    <span>Jam ke-{{ $j->urutan_jam }}</span>
                                    <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                </div>

                                {{-- Edit Button shortcut --}}
                                <a href="{{ route('admin.jadwal.edit', $j) }}" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity bg-white/80 hover:bg-white text-navy-800 p-1 rounded-md shadow-sm text-[8px] font-bold">
                                    Edit
                                </a>
                            </div>
                        @empty
                            <div class="h-32 flex items-center justify-center border-2 border-dashed border-slate-100 rounded-xl">
                                <span class="text-slate-300 text-xs italic">Kosong</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center card bg-slate-50 border border-dashed border-slate-200">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-bold text-slate-700 text-sm">Silakan Pilih Kelas Terlebih Dahulu</p>
            <p class="text-slate-400 text-xs mt-1">Pilih kelas dari form filter di atas untuk menampilkan jadwal kalender mingguan kelas tersebut.</p>
        </div>
    @endif

</div>
@endsection
