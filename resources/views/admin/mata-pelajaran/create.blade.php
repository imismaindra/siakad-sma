@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('breadcrumb-parent', 'Mata Pelajaran')
@section('breadcrumb-current', 'Tambah')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tambah Mata Pelajaran</h1>
            <p class="page-subtitle">Daftarkan mata pelajaran baru ke dalam kurikulum sekolah.</p>
        </div>
        <div>
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.mata-pelajaran.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="kode" class="form-label">Kode Mata Pelajaran</label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode') }}" required
                            class="form-input @error('kode') error @enderror" placeholder="Contoh: MP-BIN-X">
                        @error('kode')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                            class="form-input @error('nama') error @enderror" placeholder="Contoh: Bahasa Indonesia">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="kkm" class="form-label">KKM (Kriteria Ketuntasan Minimal)</label>
                        <input type="number" id="kkm" name="kkm" min="0" max="100" value="{{ old('kkm', 75) }}" required
                            class="form-input @error('kkm') error @enderror">
                        @error('kkm')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_jam_per_minggu" class="form-label">Beban Jam (Per Minggu)</label>
                        <input type="number" id="jumlah_jam_per_minggu" name="jumlah_jam_per_minggu" min="1" value="{{ old('jumlah_jam_per_minggu', 2) }}" required
                            class="form-input @error('jumlah_jam_per_minggu') error @enderror" placeholder="Jam pelajaran per minggu">
                        @error('jumlah_jam_per_minggu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-textarea @error('deskripsi') error @enderror" placeholder="Penjelasan silabus singkat... (Opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Mapel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
