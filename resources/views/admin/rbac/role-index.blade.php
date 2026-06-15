@extends('layouts.admin')

@section('title', 'Manajemen Role')
@section('breadcrumb-parent', 'Sistem')
@section('breadcrumb-current', 'Manajemen Role')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Manajemen Role</h1>
            <p class="page-subtitle">Buat, edit, dan atur permission setiap role secara dinamis tanpa perlu mengubah kode.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.permissions.index') }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Kelola Permission
            </a>
            <a href="{{ route('admin.roles.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Role
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{!! session('success') !!}</span>
        </div>
    @endif
    @if($errors->has('error'))
        <div class="flash-error">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{!! $errors->first('error') !!}</span>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="stat-card navy">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Total Role</div>
            <div class="text-3xl font-black text-navy-700">{{ $roles->count() }}</div>
        </div>
        <div class="stat-card gold">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Total Permission</div>
            <div class="text-3xl font-black text-amber-600">{{ $totalPermissions }}</div>
        </div>
    </div>

    {{-- Roles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($roles as $role)
        <div class="card hover:shadow-md transition-shadow">
            <div class="card-body space-y-4">
                {{-- Role Header --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-navy-600 to-navy-700 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-800 capitalize">{{ $role->name }}</h3>
                            <p class="text-xs text-slate-400">{{ $role->users_count }} pengguna</p>
                        </div>
                    </div>
                    <span class="badge badge-navy text-[11px]">{{ $role->permissions_count }} perm</span>
                </div>

                {{-- Permission List Preview --}}
                <div class="flex flex-wrap gap-1.5 min-h-[40px]">
                    @foreach ($role->permissions->take(6) as $perm)
                        <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $perm->name }}</span>
                    @endforeach
                    @if ($role->permissions_count > 6)
                        <span class="text-[10px] font-bold bg-navy-100 text-navy-600 px-2 py-0.5 rounded-full">+{{ $role->permissions_count - 6 }} lainnya</span>
                    @endif
                    @if ($role->permissions_count === 0)
                        <span class="text-xs text-slate-400 italic">Belum ada permission</span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn-secondary btn-sm flex-1 justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Role
                    </a>
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Hapus role {{ $role->name }}? Tindakan ini tidak dapat diurungkan.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm" @if($role->users_count > 0) disabled title="Role masih digunakan" @endif>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full card">
            <div class="card-body text-center py-12 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="font-semibold">Belum ada role. Buat role pertama.</p>
            </div>
        </div>
        @endforelse
    </div>

</div>
@endsection
