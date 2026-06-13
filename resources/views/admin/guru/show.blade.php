@extends('layouts.admin')

@section('title', 'Profil Guru ' . $guru->nama_lengkap)

@section('breadcrumb-parent', 'Data Guru')
@section('breadcrumb-current', 'Profil')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Profil Guru</h1>
            <p class="page-subtitle">Informasi lengkap tentang profil, kompetensi mata pelajaran, dan jadwal mengajar guru.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.guru.edit', $guru) }}" class="btn-gold">
                Edit Profil
            </a>
            <a href="{{ route('admin.guru.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Profile Banner Card --}}
    <div class="card bg-gradient-navy text-white overflow-hidden relative">
        <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
        <div class="card-body p-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                @if($guru->foto)
                    <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru" 
                        class="w-24 h-24 rounded-full object-cover border-4 border-white/20 shadow-lg">
                @else
                    <div class="w-24 h-24 rounded-full border-4 border-white/20 shadow-lg flex items-center justify-center bg-gradient-gold text-white text-3xl font-extrabold font-display">
                        {{ strtoupper(substr($guru->nama_lengkap, 0, 2)) }}
                    </div>
                @endif
                
                <div class="space-y-2">
                    <div class="flex flex-col md:flex-row items-center gap-3">
                        <h2 class="text-2xl font-extrabold font-display leading-tight">{{ $guru->nama_lengkap }}</h2>
                        @if($guru->status == 'aktif')
                            <span class="badge badge-success bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Aktif</span>
                        @else
                            <span class="badge badge-danger bg-rose-500/20 text-rose-300 border border-rose-500/30">Tidak Aktif</span>
                        @endif
                    </div>
                    
                    <p class="text-white/75 font-mono text-sm">NIP. {{ $guru->nip ?? '—' }}</p>
                    <p class="text-white/60 text-xs">{{ $guru->email }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Personal Data Card --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Biodata Pribadi</h3>
                </div>
                <div class="card-body space-y-5">
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jenis Kelamin</span>
                        <span class="font-bold text-slate-800 text-sm mt-1 block">
                            {{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                        <span class="font-bold text-slate-800 text-sm mt-1 block">
                            {{ $guru->tempat_lahir ?? '—' }}{{ $guru->tanggal_lahir ? ', ' . $guru->tanggal_lahir->locale('id')->isoFormat('D MMMM YYYY') : '' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">No. Telepon</span>
                        <span class="font-bold text-slate-800 text-sm mt-1 block">
                            {{ $guru->no_telepon ?? '—' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Alamat Lengkap</span>
                        <span class="font-bold text-slate-800 text-sm mt-1 block leading-relaxed">
                            {{ $guru->alamat ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Mapel & Jadwal --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- Mapel Card --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Mata Pelajaran Diampu</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap gap-2">
                        @forelse($guru->mataPelajarans as $mp)
                            <span class="badge badge-navy px-3 py-1.5 font-semibold text-xs border border-navy-100 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $mp->nama }} ({{ $mp->kode }})
                            </span>
                        @empty
                            <span class="text-slate-400 italic text-sm">Belum dikaitkan ke mata pelajaran manapun pada tahun ajaran aktif ini.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Jadwal Card --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Jadwal Mengajar</h3>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru->jadwalPelajarans->sortBy('hari') as $jp)
                                <tr>
                                    <td class="font-bold text-slate-800 capitalize">{{ $jp->hari }}</td>
                                    <td class="font-mono text-slate-600 text-xs">{{ $jp->jam_mulai }} - {{ $jp->jam_selesai }}</td>
                                    <td class="font-bold text-navy-500 font-mono">{{ $jp->kelas->nama_kelas }}</td>
                                    <td>{{ $jp->mataPelajaran->nama }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400">Tidak ada jadwal mengajar terdaftar pada tahun ajaran aktif ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
