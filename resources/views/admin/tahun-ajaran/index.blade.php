@extends('layouts.admin')

@section('title', 'Manajemen Tahun Ajaran')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Tahun Ajaran')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tahun Ajaran</h1>
            <p class="page-subtitle">Kelola tahun ajaran, semester aktif, beserta durasi kalender akademiknya.</p>
        </div>
        <div>
            <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tahun Ajaran
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tahunAjarans as $ta)
                        <tr>
                            <td class="font-bold text-slate-800">{{ $ta->nama }}</td>
                            <td>
                                <span class="badge {{ $ta->semester == '1' ? 'badge-navy' : 'badge-gold' }}">
                                    {{ $ta->semester_label }}
                                </span>
                            </td>
                            <td>{{ $ta->tanggal_mulai->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                            <td>{{ $ta->tanggal_selesai->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                            <td>
                                @if($ta->is_aktif)
                                    <span class="badge badge-success animate-pulse">Aktif</span>
                                @else
                                    <span class="badge badge-gray">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-right space-x-1 whitespace-nowrap">
                                @if(!$ta->is_aktif)
                                    <form action="{{ route('admin.tahun-ajaran.set-aktif', $ta) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary btn-sm bg-amber-50 hover:bg-amber-100 text-amber-700 border-amber-200">
                                            Set Aktif
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.tahun-ajaran.edit', $ta) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>

                                @if(!$ta->is_aktif)
                                    <form action="{{ route('admin.tahun-ajaran.destroy', $ta) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus tahun ajaran {{ $ta->nama_lengkap }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada data tahun ajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($tahunAjarans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $tahunAjarans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
