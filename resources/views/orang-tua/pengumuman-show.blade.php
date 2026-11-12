@extends('layouts.orang-tua')

@section('title', $pengumuman->judul)
@section('breadcrumb-parent', 'Pengumuman')
@section('breadcrumb-current', 'Detail')

@section('orang-tua-content')
<div class="space-y-6 max-w-4xl mx-auto animate-fade-in-up">

    {{-- Back Link --}}
    <div>
        <a href="{{ route('orang-tua.pengumuman') }}" class="btn btn-secondary text-xs inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Pengumuman
        </a>
    </div>

    {{-- Main Article Card --}}
    <article class="card p-8 border border-slate-200/80 shadow-sm rounded-3xl bg-white space-y-6">
        
        {{-- Metadata Header --}}
        <div class="space-y-3 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="badge {{ $pengumuman->target === 'semua' ? 'badge-blue' : 'badge-gold' }} text-xs font-bold uppercase">
                    {{ $pengumuman->target === 'semua' ? 'Umum / Seluruh Wali Murid' : 'Khusus Kelas ' . ($pengumuman->kelas?->nama_kelas ?? '') }}
                </span>
                <span class="text-xs text-slate-400 font-mono">
                    {{ $pengumuman->published_at ? $pengumuman->published_at->translatedFormat('l, d F Y - H:i') : '' }} WIB
                </span>
            </div>

            <h1 class="text-2xl font-bold font-display text-slate-900 leading-tight">
                {{ $pengumuman->judul }}
            </h1>

            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span>Diterbitkan oleh: <strong class="text-slate-700">{{ $pengumuman->pembuat?->name ?? 'Admin Sekolah' }}</strong></span>
                @if($pengumuman->target === 'kelas' && $pengumuman->kelas)
                    <span>•</span>
                    <span>Target Kelas: <strong class="text-slate-700">{{ $pengumuman->kelas->nama_kelas }}</strong></span>
                @endif
            </div>
        </div>

        {{-- Featured Image / Foto Pengumuman --}}
        @if($pengumuman->gambar_url)
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 shadow-xs">
                <img src="{{ $pengumuman->gambar_url }}" alt="{{ $pengumuman->judul }}" class="w-full max-h-96 object-cover" />
            </div>
        @endif

        {{-- Content Body --}}
        <div class="prose max-w-none text-sm text-slate-700 leading-relaxed space-y-4">
            {!! nl2br(e($pengumuman->konten)) !!}
        </div>

        {{-- Dokumen Lampiran --}}
        @if($pengumuman->dokumen_url)
            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-navy-800 text-gold-400 flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 truncate">{{ $pengumuman->dokumen_nama ?? 'Dokumen Lampiran Pengumuman' }}</p>
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
