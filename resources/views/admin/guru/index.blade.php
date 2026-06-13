@extends('layouts.admin')

@section('title', 'Manajemen Data Guru')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Data Guru')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Data Guru</h1>
            <p class="page-subtitle">Kelola informasi tenaga pendidik, mata pelajaran diampu, dan kredensial akun mereka.</p>
        </div>
        <div>
            <a href="{{ route('admin.guru.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Guru
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.guru.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <label for="search" class="form-label">Cari Guru</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                        class="form-input" placeholder="Cari nama, NIP, atau email...">
                </div>

                <div>
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full justify-center">
                        Cari
                    </button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('admin.guru.index') }}" class="btn-secondary">
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
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>L/P</th>
                        <th>Jadwal Mengajar</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $g)
                        <tr>
                            <td>
                                @if($g->foto)
                                    <img src="{{ asset('storage/' . $g->foto) }}" alt="Foto Guru" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="avatar-placeholder w-10 h-10 text-xs">
                                        {{ strtoupper(substr($g->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="font-bold text-slate-800">
                                <a href="{{ route('admin.guru.show', $g) }}" class="hover:text-navy-500 transition-colors">
                                    {{ $g->nama_lengkap }}
                                </a>
                            </td>
                            <td class="font-mono text-slate-500 text-xs">{{ $g->nip ?? '-' }}</td>
                            <td>{{ $g->email }}</td>
                            <td>
                                <span class="badge {{ $g->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
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
                            <td class="text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.guru.show', $g) }}" class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                    Detail
                                </a>
                                <a href="{{ route('admin.guru.edit', $g) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.guru.destroy', $g) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus data guru {{ $g->nama_lengkap }}? Tindakan ini akan menghapus akun login guru tersebut.">
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
                            <td colspan="8" class="text-center py-8 text-slate-400">Belum ada data guru terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($gurus->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
