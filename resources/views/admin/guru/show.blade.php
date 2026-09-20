@extends('layouts.admin')

@section('title', 'Profil Guru ' . $guru->nama_lengkap)

@section('breadcrumb-parent', 'Data Guru')
@section('breadcrumb-current', 'Profil')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Profil guru</p>
            <h1 class="page-title font-display !text-3xl mt-3">{{ $guru->nama_lengkap }}.</h1>
            <p class="page-subtitle">Biodata, kompetensi, dan jadwal mengajar dalam satu layar.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.guru.edit', $guru) }}" class="inline-flex items-center rounded-full bg-gold-500 px-5 py-2.5 text-sm font-bold text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-600 active:scale-[0.98]">
                Edit profil
            </a>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Identity + biodata island --}}
        <div class="lg:col-span-4 rounded-[2rem] bg-navy-800 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                <div class="flex items-center gap-4">
                    @if($guru->foto)
                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto {{ $guru->nama_lengkap }}"
                            class="w-20 h-20 rounded-full object-cover ring-2 ring-white/20 shrink-0">
                    @else
                        <div class="w-20 h-20 rounded-full ring-2 ring-gold-500/50 bg-gold-500 text-navy-900 text-2xl font-extrabold font-display flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($guru->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        @if($guru->status == 'aktif')
                            <span class="badge bg-emerald-500/15 text-emerald-300 ring-1 ring-emerald-500/30">Aktif</span>
                        @else
                            <span class="badge bg-rose-500/15 text-rose-300 ring-1 ring-rose-500/30">Tidak aktif</span>
                        @endif
                        <p class="mt-2 font-mono text-xs text-slate-400 tabular-nums">NIP {{ $guru->nip ?? '-' }}</p>
                    </div>
                </div>

                <p class="mt-5 text-sm text-slate-300 truncate">{{ $guru->email }}</p>

                <dl class="mt-6 divide-y divide-white/10 border-y border-white/10">
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Jenis kelamin</dt>
                        <dd class="mt-1 font-bold text-white text-sm">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Tempat, tanggal lahir</dt>
                        <dd class="mt-1 font-bold text-white text-sm">{{ $guru->tempat_lahir ?? '-' }}{{ $guru->tanggal_lahir ? ', ' . $guru->tanggal_lahir->locale('id')->isoFormat('D MMMM YYYY') : '' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Telepon</dt>
                        <dd class="mt-1 font-bold text-white text-sm tabular-nums">{{ $guru->no_telepon ?? '-' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Alamat</dt>
                        <dd class="mt-1 font-bold text-white text-sm leading-relaxed">{{ $guru->alamat ?? '-' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Beban mengajar</dt>
                        <dd class="mt-1 font-display font-extrabold tracking-tight text-white text-3xl tabular-nums">{{ $guru->jadwalPelajarans->count() }}<span class="text-sm font-medium text-slate-400"> sesi</span></dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Kompetensi + jadwal island --}}
        <div class="lg:col-span-8 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-5 sm:p-6 space-y-7">
                <div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Mata pelajaran diampu</p>
                        <span class="badge badge-navy">{{ $guru->mataPelajarans->count() }} mapel</span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @forelse($guru->mataPelajarans as $mp)
                            <span class="inline-flex items-center gap-2 rounded-full bg-navy-800 pl-3 pr-4 py-2 text-[13px] font-semibold text-white">
                                <svg class="w-4 h-4 text-gold-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $mp->nama }} <span class="font-mono text-xs text-slate-400">{{ $mp->kode }}</span>
                            </span>
                        @empty
                            <p class="text-sm text-slate-500">Belum dikaitkan ke mapel manapun tahun ini.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Jadwal mengajar</p>
                        <a href="{{ route('admin.jadwal.index') }}" class="group inline-flex items-center gap-2 text-[13px] font-bold text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                            Kelola jadwal
                            <svg class="w-4 h-4 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                        </a>
                    </div>
                    <div class="mt-3 overflow-x-auto">
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
                                        <td><span class="badge badge-navy capitalize">{{ $jp->hari }}</span></td>
                                        <td class="font-mono text-slate-500 text-xs tabular-nums whitespace-nowrap">{{ $jp->jam_mulai }} - {{ $jp->jam_selesai }}</td>
                                        <td><a href="{{ route('admin.kelas.show', $jp->kelas) }}" class="font-bold text-navy-800 font-mono transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-gold-600">{{ $jp->kelas->nama_kelas }}</a></td>
                                        <td class="font-semibold text-slate-500">{{ $jp->mataPelajaran->nama }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-12">
                                            <p class="font-bold text-navy-900">Belum ada jadwal mengajar.</p>
                                            <p class="text-sm text-slate-500 mt-1">Plot jadwal lewat halaman jadwal pelajaran.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
