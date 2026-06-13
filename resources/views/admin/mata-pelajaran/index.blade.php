@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Mata Pelajaran')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Mata Pelajaran</h1>
            <p class="page-subtitle">Kelola kurikulum mata pelajaran, batas nilai KKM, dan pembobotan nilainya.</p>
        </div>
        <div>
            <a href="{{ route('admin.mata-pelajaran.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mapel
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.mata-pelajaran.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="search" class="form-label">Cari Mata Pelajaran</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                        class="form-input" placeholder="Cari berdasarkan kode atau nama...">
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="btn-primary justify-center flex-1 sm:flex-initial">
                        Cari
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn-secondary">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>KKM</th>
                        <th>Beban Jam</th>
                        <th>Jumlah Pengampu</th>
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
                            <td class="font-bold text-slate-800">{{ $mp->nama }}</td>
                            <td class="font-mono text-slate-600 font-semibold">{{ $mp->kkm }}</td>
                            <td>{{ $mp->jumlah_jam_per_minggu }} Jam/Minggu</td>
                            <td>
                                <span class="badge badge-gray font-semibold">{{ $mp->gurus_count }} Guru</span>
                            </td>
                            <td>
                                @if($mp->is_aktif ?? true)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.mata-pelajaran.bobot', $mp) }}" class="btn-secondary btn-sm bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100">
                                    Bobot Nilai
                                </a>
                                <a href="{{ route('admin.mata-pelajaran.edit', $mp) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.mata-pelajaran.destroy', $mp) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus mata pelajaran {{ $mp->nama }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Belum ada mata pelajaran terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mapels->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $mapels->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
