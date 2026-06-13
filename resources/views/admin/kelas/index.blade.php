@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Kelas')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Daftar Kelas</h1>
            <p class="page-subtitle">Kelola pembagian kelas, kapasitas tampung, serta wali kelas pendamping.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <form action="{{ route('admin.kelas.index') }}" method="GET" id="filter-form" class="flex-1 sm:flex-initial">
                <select name="tahun_ajaran_id" onchange="document.getElementById('filter-form').submit()" class="form-select min-w-[200px] text-xs">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                            Tahun Ajaran: {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
            
            <a href="{{ route('admin.kelas.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kelas
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Kapasitas</th>
                        <th>Jumlah Siswa</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $k)
                        <tr>
                            <td class="font-bold text-slate-800">
                                <span class="text-navy-500 font-mono">{{ $k->nama_kelas }}</span>
                            </td>
                            <td>{{ $k->jurusan?->nama ?? 'Umum' }}</td>
                            <td class="font-medium">
                                @if($k->waliKelas)
                                    <div class="flex items-center gap-2">
                                        <div class="avatar-placeholder w-6 h-6 text-[10px]">
                                            {{ strtoupper(substr($k->waliKelas->name, 0, 2)) }}
                                        </div>
                                        <span>{{ $k->waliKelas->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Belum Ditentukan</span>
                                @endif
                            </td>
                            <td>{{ $k->kapasitas }} Siswa</td>
                            <td>
                                <span class="badge {{ $k->jumlah_siswa >= $k->kapasitas ? 'badge-danger' : 'badge-navy' }}">
                                    {{ $k->jumlah_siswa }} / {{ $k->kapasitas }}
                                </span>
                            </td>
                            <td class="text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.kelas.show', $k) }}" class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                    Detail
                                </a>
                                <a href="{{ route('admin.kelas.edit', $k) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus kelas {{ $k->nama_kelas }}?">
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
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada kelas yang terdaftar pada tahun ajaran ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kelas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $kelas->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
