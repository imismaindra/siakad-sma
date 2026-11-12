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

        {{-- Featured Image / Foto Pengumuman --}}
        @if($pengumuman->gambar_url)
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 shadow-xs">
                <img src="{{ $pengumuman->gambar_url }}" alt="{{ $pengumuman->judul }}" class="w-full max-h-96 object-cover" />
            </div>
        @endif

        {{-- Body --}}
        <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed space-y-4 whitespace-pre-line font-normal">
            {{ $pengumuman->konten }}
        </div>

        {{-- Dokumen Lampiran Card --}}
        @if($pengumuman->dokumen_url)
            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-navy-800 text-gold-400 flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-navy-900 truncate">{{ $pengumuman->dokumen_nama ?? 'Dokumen Lampiran Pengumuman' }}</p>
                        <p class="text-[11px] text-slate-400">Berkas resmi lampiran informasi sekolah</p>
                    </div>
                </div>
                <a href="{{ $pengumuman->dokumen_url }}" target="_blank" download class="inline-flex items-center justify-center gap-2 rounded-full bg-navy-800 px-5 py-2 text-xs font-semibold text-white hover:bg-navy-900 transition-colors shadow-sm shrink-0">
                    <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Unduh Dokumen
                </a>
            </div>
        @endif
    </article>

</div>
@endsection
