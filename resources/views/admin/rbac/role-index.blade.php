@extends('layouts.admin')

@section('title', 'Manajemen Role')
@section('breadcrumb-parent', 'Sistem')
@section('breadcrumb-current', 'Manajemen Role')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Sistem</p>
            <h1 class="page-title font-display !text-3xl mt-3">Manajemen role.</h1>
            <p class="page-subtitle mt-1">Atur role dan permission dinamis, total {{ $totalPermissions }} permission tersedia.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.permissions.index') }}" class="group inline-flex items-center gap-3 rounded-full bg-white py-1.5 pl-5 pr-1.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10">
                Kelola permission
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-slate-100 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
            </a>
            <a href="{{ route('admin.roles.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                Tambah role
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-500 text-navy-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flash-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{!! session('success') !!}</span>
        </div>
    @endif
    @if($errors->has('error'))
        <div class="flash-error">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{!! $errors->first('error') !!}</span>
        </div>
    @endif

    <form action="{{ route('admin.roles.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1 min-w-[220px]">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama role..." class="w-full rounded-full bg-slate-50 border-slate-200 pl-12 pr-5 py-2.5 text-sm font-semibold text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 border">
            </div>
            <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.roles.index') }}" class="rounded-full px-6 py-2.5 text-sm font-semibold text-navy-900 bg-slate-100 text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow">
        <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th class="text-right">Izin</th>
                        <th>Preview permission</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>
                                <p class="font-semibold text-navy-900 capitalize">{{ $role->name }}</p>
                                <p class="text-xs text-slate-500">{{ $role->users_count }} pengguna</p>
                            </td>
                            <td class="text-right tabular-nums font-mono font-bold text-navy-900">{{ $role->permissions_count }}</td>
                            <td>
                                <div class="flex flex-wrap gap-1.5 max-w-md">
                                    @foreach($role->permissions->take(6) as $perm)
                                        <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $perm->name }}</span>
                                    @endforeach
                                    @if($role->permissions_count > 6)
                                        <span class="text-[10px] font-bold bg-navy-800 text-white px-2 py-0.5 rounded-full">+{{ $role->permissions_count - 6 }} lainnya</span>
                                    @endif
                                    @if($role->permissions_count === 0)
                                        <span class="text-xs text-slate-500">Belum ada permission</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900" title="Edit role">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline" data-confirm="Hapus role {{ $role->name }}? Tindakan ini tidak dapat diurungkan.">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-700 hover:text-white disabled:opacity-40" @if($role->users_count > 0) disabled title="Role masih digunakan oleh {{ $role->users_count }} pengguna" @endif>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12">
                                <p class="font-semibold text-navy-900">Belum ada role.</p>
                                <p class="text-sm text-slate-500 mt-1">Buat role pertama untuk mulai mengatur akses.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($roles->hasPages() || $roles->total() > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <p class="text-xs font-medium text-slate-500">Menampilkan {{ $roles->firstItem() ?? 0 }} sampai {{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }} role</p>
                <div class="pagination-wrapper">{{ $roles->links() }}</div>
            </div>
        @endif
    </div>

</div>
@endsection
