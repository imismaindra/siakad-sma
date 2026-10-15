@extends('layouts.admin')

@section('title', 'Tambah Kelas Baru')

@section('breadcrumb-parent', 'Kelas')
@section('breadcrumb-current', 'Tambah')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tambah Kelas Baru</h1>
            <p class="page-subtitle">Daftarkan rombongan belajar baru pada tahun ajaran aktif.</p>
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
            <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" required class="form-select @error('tahun_ajaran_id') error @enderror">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id || $ta->is_aktif ? 'selected' : '' }}>
                                    {{ $ta->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tingkat" class="form-label">Tingkat Kelas</label>
                        <select id="tingkat" name="tingkat" required class="form-select @error('tingkat') error @enderror">
                            <option value="">Pilih Tingkat</option>
                            <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                            <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                            <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>XII (Duabelas)</option>
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
                                <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
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
                        <input type="number" id="nomor" name="nomor" min="1" value="{{ old('nomor', 1) }}" required
                            class="form-input @error('nomor') error @enderror" placeholder="Contoh: 1, 2, 3">
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
                            @php
                                $selectedTaId = old('tahun_ajaran_id', $tahunAjarans->where('is_aktif', true)->first()?->id ?? $tahunAjarans->first()?->id);
                            @endphp
                            @foreach($gurus as $g)
                                @php
                                    $assignedClass = $assignedWaliMap[$selectedTaId][$g->id] ?? null;
                                @endphp
                                <option value="{{ $g->id }}"
                                    data-original-name="{{ $g->name }}"
                                    {{ $assignedClass ? 'disabled' : '' }}
                                    {{ old('wali_kelas_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->name }}{{ $assignedClass ? ' (Sudah Wali Kelas ' . $assignedClass . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('wali_kelas_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kapasitas" class="form-label">Kapasitas Maksimal</label>
                        <input type="number" id="kapasitas" name="kapasitas" min="1" max="50" value="{{ old('kapasitas', 36) }}" required
                            class="form-input @error('kapasitas') error @enderror">
                        @error('kapasitas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.kelas.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const taSelect = document.getElementById('tahun_ajaran_id');
    const waliSelect = document.getElementById('wali_kelas_id');
    const assignedWaliMap = @json($assignedWaliMap ?? []);

    function updateWaliOptions() {
        if (!taSelect || !waliSelect) return;
        const taId = taSelect.value;
        const assignedInTa = assignedWaliMap[taId] || {};

        Array.from(waliSelect.options).forEach(option => {
            if (!option.value) return;

            const guruId = option.value;
            const originalName = option.getAttribute('data-original-name') || option.text.split(' (Sudah Wali')[0];

            if (assignedInTa[guruId]) {
                option.text = `${originalName} (Sudah Wali Kelas ${assignedInTa[guruId]})`;
                option.disabled = true;
            } else {
                option.text = originalName;
                option.disabled = false;
            }
        });
    }

    if (taSelect) {
        taSelect.addEventListener('change', updateWaliOptions);
    }
});
</script>
@endpush
@endsection
