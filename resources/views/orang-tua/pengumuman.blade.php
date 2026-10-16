@extends('layouts.orang-tua')

@section('title', 'Pengumuman Sekolah')
@section('breadcrumb-parent', 'Informasi Sekolah')
@section('breadcrumb-current', 'Pengumuman')

@section('orang-tua-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Child Selector & Active Banner --}}
    @include('orang-tua._anak_selector')

    {{-- Header --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="page-title font-display">Pengumuman & Informasi Sekolah</h1>
            <p class="page-subtitle">Informasi akademik resmi sekolah untuk wali murid dan kelas {{ $activeSiswa->kelas?->nama_kelas ?? '-' }}</p>
        </div>
        <div>
            <span class="badge badge-gold px-3 py-1.5 text-xs font-bold uppercase tracking-wider">
                Informasi Terverifikasi
            </span>
        </div>
    </div>

    {{-- Daftar Pengumuman --}}
    <div class="space-y-4">
        @forelse($pengumumans as $info)
            <div class="card p-6 border border-slate-200/80 shadow-xs rounded-2xl bg-white hover:border-sky-300 hover:shadow-md transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="badge {{ $info->target === 'semua' ? 'badge-blue' : 'badge-gold' }} text-[10px] font-bold uppercase">
                            {{ $info->target === 'semua' ? 'Umum / Seluruh Wali' : 'Khusus Kelas ' . ($info->kelas?->nama_kelas ?? '') }}
                        </span>
                        <span class="text-xs text-slate-400 font-mono">
                            {{ $info->published_at ? $info->published_at->translatedFormat('d F Y, H:i') : '' }}
                        </span>
                    </div>
                    <span class="text-xs text-slate-400">
                        Dipublikasikan oleh: <strong class="text-slate-600">{{ $info->pembuat?->name ?? 'Admin Sekolah' }}</strong>
                    </span>
                </div>

                <h3 class="text-base font-bold font-display text-slate-800 mb-2">
                    <a href="{{ route('orang-tua.pengumuman.show', $info) }}" class="hover:text-sky-600 transition-colors">
                        {{ $info->judul }}
                    </a>
                </h3>

                <p class="text-xs text-slate-600 leading-relaxed line-clamp-3 mb-4">
                    {{ strip_tags($info->konten) }}
                </p>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <a href="{{ route('orang-tua.pengumuman.show', $info) }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                        Baca Selengkapnya
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center bg-white border border-slate-200/80 shadow-xs rounded-2xl">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Pengumuman</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                    Saat ini belum ada pengumuman umum sekolah maupun pengumuman khusus kelas yang diterbitkan.
                </p>
            </div>
        @endforelse
    </div>

    @if($pengumumans->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            {{ $pengumumans->links() }}
        </div>
    @endif

</div>
@endsection
