@extends('layouts.admin')

@section('title', 'Tambah Permission Baru')
@section('breadcrumb-parent', 'Manajemen Permission')
@section('breadcrumb-current', 'Tambah Permission')

@section('admin-content')
<div class="max-w-lg space-y-6 animate-fade-in-up">
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tambah Permission</h1>
            <p class="page-subtitle">Buat hak akses baru yang dapat ditugaskan ke satu atau lebih role.</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="form-label">Nama Permission <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="form-input @error('name') error @enderror"
                        placeholder="contoh: manage-orang-tua, export-laporan"
                        required>
                    <p class="text-xs text-slate-400 mt-1.5">Gunakan format <code class="bg-slate-100 px-1 rounded">kata-kerja-subjek</code>, huruf kecil, tanda hubung.</p>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Buat Permission
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
