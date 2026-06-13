@extends('layouts.guru')

@section('title', 'Kelola Nilai Akademik')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Kelola Nilai')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Kelola Nilai &amp; Rapor</h1>
            <p class="page-subtitle">Pilih kelas yang Anda ajar untuk melakukan penginputan, pembobotan, dan pengelolaan nilai siswa.</p>
        </div>
    </div>

    {{-- Grid Classes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelasList as $k)
            <div class="card hover:shadow-lg transition-all group flex flex-col justify-between">
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-xl bg-navy-50 text-navy-500 flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform font-mono">
                            {{ $k->tingkat }}
                        </div>
                        <span class="badge badge-navy font-mono font-bold">{{ $k->jurusan?->kode ?? 'Umum' }}</span>
                    </div>

                    <div>
                        <h4 class="text-xl font-bold font-display text-slate-800">Kelas {{ $k->nama_kelas }}</h4>
                        <p class="text-xs text-slate-400 mt-1">Jurusan: {{ $k->jurusan?->nama ?? 'Umum (Tanpa Jurusan)' }}</p>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-slate-500 pt-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $k->siswas()->aktif()->count() }} Siswa Aktif</span>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                    <a href="{{ route('guru.nilai.kelas', $k) }}" class="btn-primary btn-sm flex-1 justify-center">
                        Kelola Nilai
                    </a>
                    <a href="{{ route('guru.nilai.rapor.kelas', $k) }}" target="_blank" class="btn-secondary btn-sm" title="Unduh Rapor PDF Kelas">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center card bg-slate-50 border border-dashed border-slate-200">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-bold text-slate-700 text-sm">Tidak ada kelas terdaftar</p>
                <p class="text-slate-400 text-xs mt-1">Anda belum memiliki jadwal mengajar di kelas manapun pada tahun ajaran aktif ini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
