@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Kelas')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Data Master</p>
            <h1 class="page-title font-display !text-3xl mt-3">Daftar Kelas.</h1>
            <p class="page-subtitle">Kelola pembagian kelas, kapasitas tampung, serta wali kelas pendamping.</p>
        </div>
        <div>
            <a href="{{ route('admin.kelas.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                <span>Tambah Kelas</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.kelas.index') }}" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z" />
                </svg>
                <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Cari nama kelas atau wali..." class="w-full rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 pl-11 text-sm text-navy-900 outline-none focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10" />
            </div>
            <select name="tahun_ajaran_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>Tahun: {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>
            <select name="jurusan_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $jur)
                    <option value="{{ $jur->id }}" {{ ($jurusanId ?? request('jurusan_id')) == $jur->id ? 'selected' : '' }}>{{ $jur->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-full bg-navy-800 text-white text-sm font-semibold px-6 py-2.5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.kelas.index') }}" class="rounded-full ring-1 ring-black/5 px-5 py-2.5 text-sm font-semibold text-slate-500 text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">Atur Ulang</a>
        </div>
    </form>

    <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th>Wali</th>
                        <th>Kapasitas</th>
                        <th>Jml Siswa</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $k)
                        <tr>
                            <td class="font-semibold text-navy-900 tabular-nums">{{ $k->nama_kelas }}</td>
                            <td class="text-slate-500">{{ $k->jurusan?->nama ?? 'Umum' }}</td>
                            <td>
                                @if($k->waliKelas)
                                    <span class="inline-flex items-center gap-2 font-medium text-navy-900">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-navy-800 text-[10px] font-bold text-white">{{ strtoupper(substr($k->waliKelas->name, 0, 2)) }}</span>
                                        <span>{{ $k->waliKelas->name }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-500 italic">Belum Ditentukan</span>
                                @endif
                            </td>
                            <td class="text-slate-500 tabular-nums">{{ $k->kapasitas }} Siswa</td>
                            <td>
                                <span class="badge tabular-nums {{ $k->jumlah_siswa >= $k->kapasitas ? 'badge-danger' : 'badge-info' }}">{{ $k->jumlah_siswa }} / {{ $k->kapasitas }}</span>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <span class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.kelas.show', $k) }}" title="Detail" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.kelas.edit', $k) }}" title="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus kelas {{ $k->nama_kelas }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 ring-1 ring-rose-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916" />
                                            </svg>
                                        </button>
                                    </form>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-12"><p class="font-bold text-navy-900">Belum ada data yang cocok.</p><p class="text-sm text-slate-500 mt-1">Ubah kata kunci atau atur ulang filter.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kelas->hasPages() || $kelas->total() > 0)
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4">
                <p class="text-xs text-slate-500">Menampilkan {{ $kelas->firstItem() ?? 0 }} sampai {{ $kelas->lastItem() ?? 0 }} dari {{ $kelas->total() }} data</p>
                <div class="pagination-wrapper">{{ $kelas->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
