@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Mata Pelajaran')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Data Master</p>
            <h1 class="page-title font-display !text-3xl mt-3">Mata Pelajaran.</h1>
            <p class="page-subtitle">Kelola kurikulum mata pelajaran, batas nilai KKM, dan pembobotan nilainya.</p>
        </div>
        <div>
            <a href="{{ route('admin.mata-pelajaran.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                Tambah Mapel
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-500 text-navy-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
            </a>
        </div>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.mata-pelajaran.index') }}" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan kode atau nama..."
                    class="w-full rounded-full bg-slate-50 border border-slate-200 pl-12 pr-5 py-2.5 text-sm font-medium text-navy-900 outline-none placeholder:text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800">
            </div>
            <select name="status" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="rounded-full bg-navy-800 text-white px-6 py-2.5 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="rounded-full bg-slate-100 text-navy-800 px-6 py-2.5 text-sm font-semibold text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    {{-- Table island --}}
    <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>KKM</th>
                        <th>Jam/Minggu</th>
                        <th>Pengampu</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapels as $mp)
                        <tr>
                            <td>
                                <span class="badge badge-navy font-mono font-bold">{{ $mp->kode }}</span>
                            </td>
                            <td class="font-semibold text-navy-900">{{ $mp->nama }}</td>
                            <td class="tabular-nums font-semibold text-navy-900">{{ $mp->kkm }} <span class="text-slate-500 font-normal">/ 100</span></td>
                            <td class="tabular-nums font-medium text-navy-900">{{ $mp->jumlah_jam_per_minggu }} <span class="text-slate-500 font-normal">Jam</span></td>
                            <td>
                                <span class="badge badge-gray font-semibold">{{ $mp->gurus_count }} Guru</span>
                                <span class="block text-xs text-slate-500 mt-1 tabular-nums">{{ $mp->jadwal_pelajarans_count }} sesi terjadwal</span>
                            </td>
                            <td>
                                @if($mp->is_aktif ?? true)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.mata-pelajaran.bobot', $mp) }}" title="Bobot Nilai" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l4-4 3 3 5-6"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.mata-pelajaran.edit', $mp) }}" title="Edit" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.mata-pelajaran.destroy', $mp) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus mata pelajaran {{ $mp->nama }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 ring-1 ring-rose-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-600 hover:text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 0c.34.059.68.114 1.022.166m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/>
                                            </svg>
                                        </button>
                                    </form>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <p class="font-semibold text-navy-900">Belum ada mata pelajaran terdaftar.</p>
                                <p class="text-sm text-slate-500 mt-1">Ubah kata kunci atau tambah data baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mapels->total() > 0)
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4">
                <p class="text-xs font-medium text-slate-500 tabular-nums">Menampilkan {{ $mapels->firstItem() }} sampai {{ $mapels->lastItem() }} dari {{ $mapels->total() }} data</p>
                <div class="pagination-wrapper">{{ $mapels->links() }}</div>
            </div>
        @endif
    </div>

</div>
@endsection
