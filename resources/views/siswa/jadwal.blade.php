@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran Saya')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Jadwal Pelajaran')

@section('siswa-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Jadwal Pelajaran Saya</h1>
            <p class="page-subtitle">Rincian pembagian jam belajar mingguan berdasarkan kelas Anda.</p>
        </div>
        <div>
            @if($tahunAktif)
                <span class="badge badge-gold px-4 py-2 border border-gold-500/20 text-xs font-bold uppercase tracking-wider">
                    Tahun Ajaran: {{ $tahunAktif->tahun_ajaran }} — Semester {{ $tahunAktif->semester == '1' ? 'Ganjil' : 'Genap' }}
                </span>
            @endif
        </div>
    </div>

    {{-- Grid timetable --}}
    @if($siswa->kelas_id)
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
                        @forelse($hariJadwal as $j)
                            @php
                                $color = $colors[$j->mata_pelajaran_id % count($colors)];
                            @endphp
                            
                            <div class="schedule-cell schedule-cell-{{ $color }} space-y-2 shadow-sm">
                                <p class="font-extrabold text-xs leading-snug">{{ $j->mataPelajaran->nama }}</p>
                                
                                <div class="space-y-0.5 text-[10px] opacity-80">
                                    <p class="font-semibold truncate">{{ $j->guru->nama_lengkap }}</p>
                                    <p class="font-mono">R. {{ $j->ruangan ?? '—' }}</p>
                                </div>
                                
                                <div class="flex items-center justify-between mt-1 text-[9px] font-bold opacity-90 border-t border-black/5 pt-1.5 font-mono">
                                    <span>Jam ke-{{ $j->urutan_jam }}</span>
                                    <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="h-32 flex items-center justify-center border-2 border-dashed border-slate-100 rounded-xl">
                                <span class="text-slate-300 text-xs italic">Libur</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center card bg-slate-50 border border-dashed border-slate-200">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-bold text-slate-700 text-sm">Belum Ada Kelas</p>
            <p class="text-slate-400 text-xs mt-1">Anda belum dimasukkan ke kelas manapun pada semester ini. Silakan hubungi Administrator.</p>
        </div>
    @endif

</div>
@endsection
