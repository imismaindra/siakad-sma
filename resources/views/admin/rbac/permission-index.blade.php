@extends('layouts.admin')

@section('title', 'Manajemen Permission')
@section('breadcrumb-parent', 'Sistem')
@section('breadcrumb-current', 'Manajemen Permission')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Manajemen Permission</h1>
            <p class="page-subtitle">Daftar seluruh hak akses granular yang tersedia di sistem. Permission dapat diterapkan ke satu atau lebih role.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.roles.index') }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Role
            </a>
            <a href="{{ route('admin.permissions.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Permission
            </a>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flash-success">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{!! session('success') !!}</span>
        </div>
    @endif

    {{-- Permission Groups --}}
    <div class="space-y-6">
        @forelse ($permissions as $group => $groupPerms)
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-navy-600 bg-navy-50 px-3 py-1 rounded-full border border-navy-100">{{ $group }}</span>
                    <span class="badge badge-gray">{{ $groupPerms->count() }} permission</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama Permission</th>
                                <th>Digunakan oleh Role</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupPerms as $perm)
                            <tr>
                                <td>
                                    <code class="text-sm font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded">{{ $perm->name }}</code>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($perm->roles as $role)
                                            <span class="badge badge-navy text-[10px]">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('admin.permissions.destroy', $perm) }}"
                                        onsubmit="return confirm('Hapus permission {{ $perm->name }}? Permission ini akan dicabut dari semua role.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-12 text-slate-400">
                <p class="font-semibold">Belum ada permission. Buat permission pertama.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
