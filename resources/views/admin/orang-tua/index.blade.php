@extends('layouts.admin')

@section('title', 'Manajemen Data Orang Tua / Wali')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Data Orang Tua')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Portal Monitoring (F4)</p>
            <h1 class="page-title font-display !text-3xl mt-3">Data Orang Tua & Wali.</h1>
            <p class="page-subtitle">Kelola akun wali murid, kredensial login, dan tautan anak/siswa yang dipantau.</p>
        </div>
        <a href="{{ route('admin.orang-tua.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900 shadow-sm">
            Tambah Orang Tua
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </span>
        </a>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Akun Orang Tua</span>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ number_format($stats['total']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Terdaftar dalam sistem</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tautan Ayah</span>
            <p class="text-2xl font-bold text-indigo-600 mt-2">{{ number_format($stats['ayah']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Relasi orang tua laki-laki</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tautan Ibu</span>
            <p class="text-2xl font-bold text-pink-600 mt-2">{{ number_format($stats['ibu']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Relasi orang tua perempuan</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tautan Wali</span>
            <p class="text-2xl font-bold text-amber-600 mt-2">{{ number_format($stats['wali']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Wali asuh / kerabat siswa</p>
        </div>
    </div>

    {{-- Toolbar & Search --}}
    <form action="{{ route('admin.orang-tua.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="flex flex-col gap-3 p-2 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama orang tua, email, telepon, atau nama siswa..."
                    class="w-full rounded-full border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:border-gold-500 focus:ring-gold-500" />
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                    Cari
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.orang-tua.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Table Island --}}
    <div class="overflow-hidden rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="overflow-x-auto rounded-[calc(2rem-0.5rem)]">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Orang Tua / Wali</th>
                        <th>Kontak & Akun</th>
                        <th>Pekerjaan</th>
                        <th>Siswa Tertaut (Anak)</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orangTuas as $ot)
                        <tr>
                            <td>
                                <div class="font-bold text-navy-900">{{ $ot->nama_lengkap }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $ot->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium text-slate-700">{{ $ot->user?->email ?? '-' }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $ot->no_telepon ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="text-xs text-slate-600">{{ $ot->pekerjaan ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @forelse($ot->siswas as $siswa)
                                        @php
                                            $hubunganBadge = match($siswa->pivot->hubungan) {
                                                'ayah' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'ibu' => 'bg-pink-50 text-pink-700 border-pink-200',
                                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg border {{ $hubunganBadge }}">
                                            <span class="font-semibold">{{ $siswa->nama_lengkap }}</span>
                                            <span class="text-[10px] opacity-75">({{ ucfirst($siswa->pivot->hubungan) }} - {{ $siswa->kelas?->nama_kelas ?? 'No Kelas' }})</span>
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Belum ada anak ditautkan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.orang-tua.show', $ot) }}" class="p-1.5 text-slate-400 hover:text-navy-900 rounded-lg hover:bg-slate-100 transition" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.orang-tua.edit', $ot) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.orang-tua.destroy', $ot) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun orang tua {{ $ot->nama_lengkap }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                Belum ada data akun orang tua yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orangTuas->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orangTuas->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
