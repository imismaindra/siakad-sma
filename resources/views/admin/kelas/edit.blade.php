@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('breadcrumb-parent', 'Kelas')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Edit Kelas — <span class="text-navy-500 font-mono">{{ $kelas->nama_kelas }}</span></h1>
            <p class="page-subtitle">Perbarui wewenang wali kelas, kapasitas, maupun tingkat kelas.</p>
        </div>
        <div>
            <a href="{{ route('admin.kelas.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" class="form-input bg-slate-50 text-slate-500 cursor-not-allowed" 
                            value="{{ $kelas->tahunAjaran->nama_lengkap }}" disabled readonly>
                        <p class="text-[10px] text-slate-400 mt-1 italic">Tahun ajaran tidak dapat dipindahkan setelah kelas dibuat.</p>
                    </div>

                    <div>
                        <label for="tingkat" class="form-label">Tingkat Kelas</label>
                        <select id="tingkat" name="tingkat" required class="form-select @error('tingkat') error @enderror">
                            <option value="X" {{ old('tingkat', $kelas->tingkat) == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                            <option value="XI" {{ old('tingkat', $kelas->tingkat) == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                            <option value="XII" {{ old('tingkat', $kelas->tingkat) == 'XII' ? 'selected' : '' }}>XII (Duabelas)</option>
                        </select>
                        @error('tingkat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="jurusan_id" class="form-label">Jurusan</label>
                        <select id="jurusan_id" name="jurusan_id" class="form-select @error('jurusan_id') error @enderror">
                            <option value="">Umum (Tanpa Jurusan)</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}" {{ old('jurusan_id', $kelas->jurusan_id) == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }} ({{ $j->kode }})
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nomor" class="form-label">Nomor Urut Kelas</label>
                        <input type="number" id="nomor" name="nomor" min="1" value="{{ old('nomor', $kelas->nomor) }}" required
                            class="form-input @error('nomor') error @enderror">
                        @error('nomor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="wali_kelas_id" class="form-label">Wali Kelas</label>
                        <select id="wali_kelas_id" name="wali_kelas_id" class="form-select @error('wali_kelas_id') error @enderror">
                            <option value="">Belum Ditentukan</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $g->id ? 'selected' : '' }}>
                                    {{ $g->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('wali_kelas_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kapasitas" class="form-label">Kapasitas Maksimal</label>
                        <input type="number" id="kapasitas" name="kapasitas" min="1" max="50" value="{{ old('kapasitas', $kelas->kapasitas) }}" required
                            class="form-input @error('kapasitas') error @enderror">
                        @error('kapasitas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.kelas.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
