@extends('layouts.guru')

@section('title', 'Jadwal Mengajar Saya')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Jadwal Mengajar')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Jadwal Mengajar Saya</h1>
            <p class="page-subtitle">Rincian pembagian jam mengajar mingguan Anda di sekolah.</p>
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
                                <p class="font-bold text-slate-900">Kelas {{ $j->kelas->nama_kelas }}</p>
                                <p class="font-mono">R. {{ $j->ruangan ?? '—' }}</p>
                            </div>
                            
                            <div class="flex items-center justify-between mt-1 text-[9px] font-bold opacity-90 border-t border-black/5 pt-1.5 font-mono">
                                <span>Jam ke-{{ $j->urutan_jam }}</span>
                                <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="h-32 flex items-center justify-center border-2 border-dashed border-slate-100 rounded-xl">
                            <span class="text-slate-300 text-xs italic">Tidak Ada Jadwal</span>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
