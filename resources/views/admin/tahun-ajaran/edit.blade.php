@extends('layouts.admin')

@section('title', 'Edit Tahun Ajaran')

@section('breadcrumb-parent', 'Tahun Ajaran')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Edit Tahun Ajaran</h1>
            <p class="page-subtitle">Ubah informasi tahun ajaran dan kalender akademik.</p>
        </div>
        <div>
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="form-label">Tahun Ajaran</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $tahunAjaran->nama) }}" required
                            class="form-input @error('nama') error @enderror" placeholder="Format: YYYY/YYYY (contoh: 2025/2026)">
                        <p class="text-xs text-slate-400 mt-1">Gunakan format empat digit tahun dipisah garis miring.</p>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="form-label">Semester</label>
                        <select id="semester" name="semester" required class="form-select @error('semester') error @enderror">
                            <option value="1" {{ old('semester', $tahunAjaran->semester) == '1' ? 'selected' : '' }}>1 — Ganjil</option>
                            <option value="2" {{ old('semester', $tahunAjaran->semester) == '2' ? 'selected' : '' }}>2 — Genap</option>
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai->format('Y-m-d')) }}" required
                            class="form-input @error('tanggal_mulai') error @enderror">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai->format('Y-m-d')) }}" required
                            class="form-input @error('tanggal_selesai') error @enderror">
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Perbarui Tahun Ajaran
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
