@extends('layouts.admin')

@section('title', 'Manajemen Akun Pengguna')

@section('breadcrumb-parent', 'Sistem')
@section('breadcrumb-current', 'Manajemen Akun')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Manajemen Akun</h1>
            <p class="page-subtitle">Kelola seluruh kredensial pengguna sistem SIAKAD (Admin, Guru, dan Siswa).</p>
        </div>
        <div>
            <a href="{{ route('admin.user.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pengguna
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.user.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <label for="search" class="form-label">Cari Pengguna</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                        class="form-input" placeholder="Cari nama atau email...">
                </div>

                <div>
                    <label for="role" class="form-label">Hak Akses / Role</label>
                    <select id="role" name="role" class="form-select">
                        <option value="">Semua Hak Akses</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary w-full justify-center">
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'role']))
                        <a href="{{ route('admin.user.index') }}" class="btn-secondary">
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
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Role / Hak Akses</th>
                        <th>Status Akun</th>
                        <th>Terdaftar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="font-bold text-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="avatar-placeholder w-8 h-8 text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span>{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="badge badge-navy text-[9px] px-2 py-0.5">Saya</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge badge-gold font-bold uppercase text-[10px]">Admin</span>
                                @elseif($user->role == 'guru')
                                    <span class="badge badge-navy font-bold uppercase text-[10px]">Guru</span>
                                @else
                                    <span class="badge badge-gray font-bold uppercase text-[10px]">Siswa</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-400 font-mono">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-right space-x-1 whitespace-nowrap">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.user.toggle-aktif', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary btn-sm {{ $user->is_active ? 'bg-rose-50 text-rose-700 hover:bg-rose-100 border-rose-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border-emerald-200' }}">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.user.edit', $user) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus akun pengguna {{ $user->name }}? Tindakan ini bersifat permanen.">
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
                            <td colspan="6" class="text-center py-8 text-slate-400">Belum ada akun pengguna terdaftar yang sesuai kriteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
