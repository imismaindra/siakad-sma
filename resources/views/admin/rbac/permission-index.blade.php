@extends('layouts.admin')

@section('title', 'Manajemen Permission')
@section('breadcrumb-parent', 'Sistem')
@section('breadcrumb-current', 'Manajemen Permission')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Sistem</p>
            <h1 class="page-title font-display !text-3xl mt-3">Manajemen permission.</h1>
            <p class="page-subtitle mt-1">Daftar hak akses granular, dikelompokkan per modul dan siap dipasang ke role.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.roles.index') }}" class="group inline-flex items-center gap-3 rounded-full bg-white py-1.5 pl-5 pr-1.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10">
                Kembali ke role
                <span class="flex w-8 h-8 items-center justify-center rounded-full bg-slate-100 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </span>
            </a>
            <a href="{{ route('admin.permissions.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                Tambah permission
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

    <form action="{{ route('admin.permissions.index') }}" method="GET" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1 min-w-[220px]">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama permission..." class="w-full rounded-full bg-slate-50 border-slate-200 pl-12 pr-5 py-2.5 text-sm font-semibold text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 border">
            </div>
            <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.permissions.index') }}" class="rounded-full px-6 py-2.5 text-sm font-semibold text-navy-900 bg-slate-100 text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    <div class="space-y-6">
        @forelse($permissions as $group => $groupPerms)
            <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow">
                <div class="flex flex-wrap items-center gap-3 px-5 pt-4 pb-3">
                    <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-navy-900">{{ $group }}</h2>
                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-navy-900">{{ $groupPerms->count() }} permission</span>
                </div>
                <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                <th>Digunakan role</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupPerms as $perm)
                                <tr>
                                    <td><code class="text-xs font-mono bg-slate-100 text-navy-900 px-2.5 py-1 rounded-full">{{ $perm->name }}</code></td>
                                    <td>
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($perm->roles as $role)
                                                <span class="badge badge-navy text-[10px]">{{ $role->name }}</span>
                                            @empty
                                                <span class="text-xs text-slate-500">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $perm) }}" class="inline" data-confirm="Hapus permission {{ $perm->name }}? Permission ini akan dicabut dari semua role.">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-700 hover:text-white" title="Hapus {{ $perm->name }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="rounded-[2rem] bg-white p-12 ring-1 ring-black/5 shadow text-center">
                <p class="font-semibold text-navy-900">Belum ada permission.</p>
                <p class="text-sm text-slate-500 mt-1">Buat permission pertama untuk mulai mengatur akses.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
