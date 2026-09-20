@extends('layouts.admin')

@section('title', 'Manajemen Data Guru')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Data Guru')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Data Guru</p>
            <h1 class="page-title font-display !text-3xl mt-3">Data Guru.</h1>
            <p class="page-subtitle">Kelola informasi tenaga pendidik, mata pelajaran diampu, dan kredensial akun mereka.</p>
        </div>
        <a href="{{ route('admin.guru.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
            Tambah Guru
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </span>
        </a>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.guru.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="flex flex-col gap-3 p-2 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau email..."
                    class="w-full rounded-full border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:border-gold-500 focus:ring-gold-500" />
            </div>
            <select name="status" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                    Terapkan
                </button>
                @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.guru.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">
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
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>L/P</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $g)
                        <tr>
                            <td>
                                @if($g->foto)
                                    <img src="{{ asset('storage/' . $g->foto) }}" alt="Foto Guru" class="h-9 w-9 rounded-full object-cover" />
                                @else
                                    <div class="avatar-placeholder h-9 w-9 rounded-full text-xs">
                                        {{ strtoupper(substr($g->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.guru.show', $g) }}" class="font-semibold text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-gold-700">
                                    {{ $g->nama_lengkap }}
                                </a>
                            </td>
                            <td class="font-mono text-xs tabular-nums text-slate-500">{{ $g->nip ?? '-' }}</td>
                            <td class="text-sm text-slate-500">{{ $g->email }}</td>
                            <td>
                                <span class="badge {{ $g->jenis_kelamin == 'L' ? 'badge-info' : 'badge-gray' }}">
                                    {{ $g->jenis_kelamin }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-navy">
                                    {{ $g->jadwal_pelajarans_count }} Jam
                                </span>
                            </td>
                            <td>
                                @if($g->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    <a href="{{ route('admin.guru.show', $g) }}" title="Detail"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.guru.edit', $g) }}" title="Edit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-inset ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $g) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus data guru {{ $g->nama_lengkap }}? Tindakan ini akan menghapus akun login guru tersebut.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-600 hover:text-white">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <p class="text-sm font-semibold text-navy-900">Belum ada data.</p>
                                <p class="mt-1 text-xs text-slate-500">Belum ada data guru terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 px-6 py-4 sm:flex-row">
            <p class="text-xs font-medium text-slate-500">Menampilkan {{ $gurus->firstItem() ?? 0 }} sampai {{ $gurus->lastItem() ?? 0 }} dari {{ $gurus->total() }} data</p>
            <div class="pagination-wrapper">
                {{ $gurus->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
