@extends('layouts.admin')

@section('title', 'Manajemen Akun Pengguna')

@section('breadcrumb-parent', 'Sistem')
@section('breadcrumb-current', 'Manajemen Akun')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Manajemen Akun</p>
            <h1 class="page-title font-display !text-3xl mt-3">Manajemen Akun.</h1>
            <p class="page-subtitle">Kelola seluruh kredensial pengguna sistem SIAKAD (Admin, Guru, dan Siswa).</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
            Tambah Pengguna
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </span>
        </a>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.user.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="flex flex-col gap-3 p-2 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="w-full rounded-full border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:border-gold-500 focus:ring-gold-500" />
            </div>
            <select name="role" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Hak Akses</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                    Terapkan
                </button>
                @if(request()->anyFilled(['search', 'role']))
                    <a href="{{ route('admin.user.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-placeholder h-9 w-9 rounded-full text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-navy-900">{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="badge badge-navy px-2 py-0.5 text-[9px]">Saya</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-sm text-slate-500">{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge badge-gold text-[10px] font-bold uppercase">Admin</span>
                                @elseif($user->role == 'guru')
                                    <span class="badge badge-navy text-[10px] font-bold uppercase">Guru</span>
                                @else
                                    <span class="badge badge-gray text-[10px] font-bold uppercase">Siswa</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs tabular-nums text-slate-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.user.toggle-aktif', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] {{ $user->is_active ? 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100 hover:bg-rose-600 hover:text-white' : 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-100 hover:bg-emerald-600 hover:text-white' }}">
                                                @if($user->is_active)
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L3 3m18 18L3 3"/>
                                                    </svg>
                                                @else
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.user.roles', $user) }}" title="Atur Role"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM5 21v-1a7 7 0 0113.2-3.2"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.user.edit', $user) }}" title="Edit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-inset ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus akun pengguna {{ $user->name }}? Tindakan ini bersifat permanen.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-600 hover:text-white">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <p class="text-sm font-semibold text-navy-900">Belum ada data.</p>
                                <p class="mt-1 text-xs text-slate-500">Belum ada akun pengguna terdaftar yang sesuai kriteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 px-6 py-4 sm:flex-row">
            <p class="text-xs font-medium text-slate-500">Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data</p>
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
