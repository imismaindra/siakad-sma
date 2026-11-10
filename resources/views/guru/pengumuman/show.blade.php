@extends('layouts.guru')

@section('title', $pengumuman->judul)

@section('breadcrumb-parent', 'Pengumuman')
@section('breadcrumb-current', 'Detail')

@section('guru-content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">

    {{-- Back Bar --}}
    <div>
        <a href="{{ route('guru.pengumuman.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Daftar Pengumuman
        </a>
    </div>

    {{-- Announcement Card --}}
    <article class="rounded-[2rem] bg-white p-6 sm:p-10 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5 space-y-6">
        <header class="space-y-3 pb-6 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $pengumuman->target === 'guru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                    {{ $pengumuman->target === 'guru' ? 'Khusus Dewan Guru' : 'Semua Komunitas Sekolah' }}
                </span>
                <span class="text-xs text-slate-400">·</span>
                <span class="text-xs text-slate-500 font-medium">
                    {{ $pengumuman->published_at ? $pengumuman->published_at->locale('id')->isoFormat('dddd, D MMMM YYYY · HH:mm') : '' }} WIB
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold font-display text-navy-900 leading-tight">
                {{ $pengumuman->judul }}
            </h1>

            <div class="flex items-center gap-3 pt-2">
                <div class="w-9 h-9 rounded-full bg-navy-800 text-white font-semibold text-xs flex items-center justify-center">
                    {{ strtoupper(substr($pengumuman->pembuat?->name ?? 'A', 0, 2)) }}
                </div>
                <div>
                    <p class="text-xs font-semibold text-navy-900">{{ $pengumuman->pembuat?->name ?? 'Manajemen Sekolah' }}</p>
                    <p class="text-[10px] text-slate-400">Penerbit Resmi SIAKAD SMA</p>
                </div>
            </div>
        </header>

        {{-- Body --}}
        <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed space-y-4 whitespace-pre-line font-normal">
            {{ $pengumuman->konten }}
        </div>
    </article>

</div>
@endsection
