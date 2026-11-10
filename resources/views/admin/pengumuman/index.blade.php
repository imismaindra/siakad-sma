@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman Sekolah')

@section('breadcrumb-parent', 'Komunikasi & Informasi')
@section('breadcrumb-current', 'Pengumuman')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Komunikasi Resmi</p>
            <h1 class="page-title font-display !text-3xl mt-3">Pengumuman Sekolah.</h1>
            <p class="page-subtitle">Terbitkan maklumat, edaran dinas, dan pengumuman terarah bagi Guru, Siswa, dan Wali Murid.</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
            Buat Pengumuman Baru
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </span>
        </a>
    </div>

    {{-- Quick Metric Pills --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Pengumuman</p>
                <p class="text-2xl font-bold font-display text-navy-900 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Tayang / Aktif</p>
                <p class="text-2xl font-bold font-display text-emerald-700 mt-1">{{ $stats['aktif'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Arsip / Non-Aktif</p>
                <p class="text-2xl font-bold font-display text-slate-600 mt-1">{{ $stats['non_aktif'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="flex flex-col gap-3 p-2 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi pengumuman..."
                    class="w-full rounded-full border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:border-gold-500 focus:ring-gold-500" />
            </div>

            <select name="target" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Target</option>
                <option value="semua" {{ request('target') == 'semua' ? 'selected' : '' }}>Semua Audiens</option>
                <option value="guru" {{ request('target') == 'guru' ? 'selected' : '' }}>Khusus Guru</option>
                <option value="siswa" {{ request('target') == 'siswa' ? 'selected' : '' }}>Khusus Siswa</option>
                <option value="orang_tua" {{ request('target') == 'orang_tua' ? 'selected' : '' }}>Khusus Orang Tua</option>
                <option value="kelas" {{ request('target') == 'kelas' ? 'selected' : '' }}>Siswa Kelas Tertentu</option>
            </select>

            <select name="status" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Tayang Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non-Aktif / Draf</option>
            </select>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                    Filter
                </button>
                @if(request()->filled('search') || request()->filled('target') || request()->filled('status'))
                    <a href="{{ route('admin.pengumuman.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">
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
                        <th>Judul & Ringkasan</th>
                        <th>Target Audiens</th>
                        <th>Pembuat</th>
                        <th>Tanggal Terbit</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumumans as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="max-w-md">
                                <p class="font-semibold text-navy-900 text-sm line-clamp-1">{{ $p->judul }}</p>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">{{ Str::limit(strip_tags($p->konten), 95) }}</p>
                            </td>
                            <td>
                                @if($p->target === 'semua')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                        Semua Audiens
                                    </span>
                                @elseif($p->target === 'guru')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Guru Pengajar
                                    </span>
                                @elseif($p->target === 'siswa')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-200">
                                        Siswa
                                    </span>
                                @elseif($p->target === 'orang_tua')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                        Orang Tua / Wali
                                    </span>
                                @elseif($p->target === 'kelas')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                        Kelas {{ $p->kelas?->nama_kelas ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <p class="text-xs font-medium text-slate-800">{{ $p->pembuat?->name ?? 'Admin TU' }}</p>
                                <p class="text-[10px] text-slate-400 capitalize">{{ $p->pembuat?->role ?? 'admin' }}</p>
                            </td>
                            <td>
                                @if($p->published_at)
                                    <span class="text-xs text-slate-700 font-medium">{{ $p->published_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.pengumuman.toggle-aktif', $p) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-colors {{ $p->is_aktif ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-300' }}" title="Klik untuk beralih status tayang">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $p->is_aktif ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $p->is_aktif ? 'Aktif Tayang' : 'Non-Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.pengumuman.edit', $p) }}" class="p-1.5 text-slate-600 hover:text-navy-900 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Pengumuman">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                    <form action="{{ route('admin.pengumuman.destroy', $p) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini secara permanen?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Pengumuman">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Belum Ada Pengumuman</p>
                                    <p class="text-xs text-slate-500">Tidak ada data pengumuman yang sesuai dengan kriteria penyaringan yang Anda pilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($pengumumans->hasPages())
        <div class="mt-4">
            {{ $pengumumans->links() }}
        </div>
    @endif

</div>
@endsection
