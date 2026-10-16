@extends('layouts.orang-tua')

@section('title', 'Jadwal Pelajaran Anak')
@section('breadcrumb-parent', 'Monitoring Anak')
@section('breadcrumb-current', 'Jadwal Pelajaran')

@section('orang-tua-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Child Selector & Active Banner --}}
    @include('orang-tua._anak_selector')

    {{-- Header Section --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="page-title font-display">Jadwal Pelajaran Mingguan</h1>
            <p class="page-subtitle">Jadwal aktif kelas {{ $activeSiswa->kelas?->nama_kelas ?? '-' }} • Tahun Ajaran {{ $tahunAktif?->tahun_ajaran ?? '-' }} (Semester {{ $tahunAktif?->semester == '1' ? 'Ganjil' : 'Genap' }})</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge badge-gold px-3 py-1.5 text-xs font-bold uppercase tracking-wider">
                Mode Hanya-Baca
            </span>
        </div>
    </div>

    @if($jadwals->isEmpty())
        <div class="card p-12 text-center bg-white border border-slate-200/80 shadow-xs rounded-2xl">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Belum Ada Jadwal Tersedia</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                Jadwal pelajaran untuk kelas {{ $activeSiswa->kelas?->nama_kelas ?? 'ini' }} pada tahun ajaran aktif belum dikonfigurasi oleh pihak sekolah.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @php
                $orderedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            @endphp

            @foreach($orderedDays as $hari)
                @if(isset($jadwals[$hari]))
                    <div class="card border border-slate-200/80 shadow-xs rounded-2xl bg-white overflow-hidden flex flex-col">
                        {{-- Day Header --}}
                        <div class="px-5 py-3.5 bg-gradient-to-r from-navy-800 to-navy-900 text-white flex items-center justify-between">
                            <h3 class="font-bold font-display text-sm flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                                {{ $hari }}
                            </h3>
                            <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-white/10 text-white/90">
                                {{ $jadwals[$hari]->count() }} Sesi
                            </span>
                        </div>

                        {{-- Day Schedule List --}}
                        <div class="p-4 divide-y divide-slate-100 flex-1 space-y-3">
                            @foreach($jadwals[$hari] as $sesi)
                                <div class="pt-3 first:pt-0 flex items-start gap-3">
                                    <div class="px-2 py-1 rounded bg-sky-50 text-sky-800 text-[11px] font-mono font-bold shrink-0 mt-0.5 border border-sky-100">
                                        {{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-800">{{ $sesi->mataPelajaran->nama_pelajaran }}</h4>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                            <span>{{ $sesi->guru->nama_lengkap ?? 'Guru Pengampu' }}</span>
                                            <span>•</span>
                                            <span class="text-slate-600 font-medium">{{ $sesi->ruangan ?? 'Ruang Kelas' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

</div>
@endsection
