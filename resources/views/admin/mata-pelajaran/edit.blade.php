@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')

@section('breadcrumb-parent', 'Mata Pelajaran')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Edit Mata Pelajaran</h1>
            <p class="page-subtitle">Ubah konfigurasi, beban jam, KKM, dan status keaktifan mata pelajaran.</p>
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
            <form action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="kode" class="form-label">Kode Mata Pelajaran</label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode', $mataPelajaran->kode) }}" required
                            class="form-input @error('kode') error @enderror">
                        @error('kode')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $mataPelajaran->nama) }}" required
                            class="form-input @error('nama') error @enderror">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="kkm" class="form-label">KKM (Kriteria Ketuntasan Minimal)</label>
                        <input type="number" id="kkm" name="kkm" min="0" max="100" value="{{ old('kkm', $mataPelajaran->kkm) }}" required
                            class="form-input @error('kkm') error @enderror">
                        @error('kkm')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_jam_per_minggu" class="form-label">Beban Jam (Per Minggu)</label>
                        <input type="number" id="jumlah_jam_per_minggu" name="jumlah_jam_per_minggu" min="1" value="{{ old('jumlah_jam_per_minggu', $mataPelajaran->jumlah_jam_per_minggu) }}" required
                            class="form-input @error('jumlah_jam_per_minggu') error @enderror">
                        @error('jumlah_jam_per_minggu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-textarea @error('deskripsi') error @enderror">{{ old('deskripsi', $mataPelajaran->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_aktif" value="0">
                    <input type="checkbox" id="is_aktif" name="is_aktif" value="1" class="rounded border-slate-300 text-navy-500 focus:ring-navy-500 mr-2"
                        {{ old('is_aktif', $mataPelajaran->is_aktif) ? 'checked' : '' }}>
                    <label for="is_aktif" class="form-label mb-0 cursor-pointer select-none">Mata Pelajaran Aktif</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
