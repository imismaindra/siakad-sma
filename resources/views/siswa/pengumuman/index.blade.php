@extends('layouts.siswa')

@section('title', 'Pengumuman Sekolah')

@section('breadcrumb-parent', 'Portal Siswa')
@section('breadcrumb-current', 'Pengumuman')

@section('siswa-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Pemberitahuan</p>
            <h1 class="page-title font-display !text-3xl mt-3">Pengumuman & Agenda.</h1>
            <p class="page-subtitle">Informasi akademik, jadwal ujian, dan edaran kegiatan resmi untuk kelas Anda.</p>
        </div>
    </div>

    {{-- Search Toolbar --}}
    <form action="{{ route('siswa.pengumuman.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="flex items-center gap-3 p-2">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul pengumuman..."
                    class="w-full rounded-full border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:text-slate-500 focus:border-gold-500 focus:ring-gold-500" />
            </div>
            <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white hover:bg-navy-900 transition-colors">
                Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('siswa.pengumuman.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 hover:bg-slate-50 transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Grid of Announcements --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($pengumumans as $p)
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between overflow-hidden">
                <div class="space-y-3">
                    @if($p->gambar_url)
                        <div class="rounded-xl overflow-hidden h-36 w-full -mt-1 mb-2 bg-slate-100">
                            <img src="{{ $p->gambar_url }}" alt="{{ $p->judul }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                        </div>
                    @endif

                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $p->target === 'kelas' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                            {{ $p->target === 'kelas' ? 'Khusus Kelas ' . ($p->kelas?->nama_kelas ?? '') : 'Pengumuman Sekolah' }}
                        </span>
                        <span class="text-[11px] text-slate-400">
                            {{ $p->published_at ? $p->published_at->locale('id')->isoFormat('D MMMM YYYY') : '' }}
                        </span>
                    </div>

                    <h2 class="text-base font-bold font-display text-navy-900 line-clamp-2 leading-snug">
                        {{ $p->judul }}
                    </h2>

                    <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                        {{ Str::limit(strip_tags($p->konten), 160) }}
                    </p>

                    @if($p->dokumen_url)
                        <div class="pt-1">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-navy-50 text-navy-800 border border-navy-200">
                                📎 Dokumen Terlampir
                            </span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500">
                        Oleh: {{ $p->pembuat?->name ?? 'Admin Sekolah' }}
                    </span>
                    <a href="{{ route('siswa.pengumuman.show', $p) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-navy-800 hover:text-navy-900 hover:underline">
                        Baca Selengkapnya
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl bg-white border border-slate-200/80 p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">Belum Ada Pengumuman</p>
                <p class="text-xs text-slate-400 mt-1">Tidak ada pengumuman yang ditujukan untuk Anda atau kelas Anda saat ini.</p>
            </div>
        @endforelse
    </div>

    @if($pengumumans->hasPages())
        <div class="mt-4">
            {{ $pengumumans->links() }}
        </div>
    @endif

</div>
@endsection
