@extends('layouts.admin')

@section('title', 'Manajemen Jadwal Pelajaran')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Jadwal Pelajaran')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Jadwal Pelajaran</h1>
            <p class="page-subtitle">Susun jadwal mata pelajaran per kelas tanpa bentrok jadwal mengajar guru.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.jadwal.grid') }}" class="btn-gold shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Lihat Grid Jadwal
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.jadwal.index') }}" method="GET" id="jadwal-filter-form" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" onchange="document.getElementById('jadwal-filter-form').submit()" class="form-select">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                                {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label for="kelas_id" class="form-label">Kelas</label>
                    <select id="kelas_id" name="kelas_id" onchange="document.getElementById('jadwal-filter-form').submit()" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    @if(request()->filled('kelas_id'))
                        <a href="{{ route('admin.jadwal.index', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn-secondary w-full justify-center">
                            Reset Filter Kelas
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
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Jam Ke-</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th>Ruangan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $j)
                        <tr>
                            <td class="font-bold text-slate-800 capitalize">{{ $j->hari }}</td>
                            <td class="font-mono text-xs text-slate-600">
                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                            </td>
                            <td>
                                <span class="badge badge-gray font-bold">Jam ke-{{ $j->urutan_jam }}</span>
                            </td>
                            <td>
                                <span class="text-navy-500 font-bold font-mono">{{ $j->kelas->nama_kelas }}</span>
                            </td>
                            <td class="font-bold text-slate-800">{{ $j->mataPelajaran->nama }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="avatar-placeholder w-6 h-6 text-[10px]">
                                        {{ strtoupper(substr($j->guru->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-slate-700">{{ $j->guru->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td>{{ $j->ruangan ?? '—' }}</td>
                            <td class="text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.jadwal.edit', $j) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus jadwal pelajaran ini?">
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
                            <td colspan="8" class="text-center py-8 text-slate-400">Belum ada jadwal pelajaran terdaftar untuk kriteria di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($jadwals->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
