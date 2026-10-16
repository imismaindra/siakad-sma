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

        {{-- Content Body --}}
        <div class="prose max-w-none text-sm text-slate-700 leading-relaxed space-y-4">
            {!! nl2br(e($pengumuman->konten)) !!}
        </div>

    </article>

</div>
@endsection
