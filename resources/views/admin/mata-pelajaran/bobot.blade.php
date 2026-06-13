@extends('layouts.admin')

@section('title', 'Bobot Nilai — ' . $mataPelajaran->nama)

@section('breadcrumb-parent', 'Mata Pelajaran')
@section('breadcrumb-current', 'Bobot Nilai')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Bobot Nilai: {{ $mataPelajaran->nama }}</h1>
            <p class="page-subtitle">Atur proporsi persentase nilai harian, UTS, dan UAS untuk mata pelajaran ini.</p>
        </div>
        <div>
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Error Alert for Bobot Total --}}
    @error('bobot')
        <div class="flash-error">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $message }}
        </div>
    @enderror

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- List Column --}}
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Konfigurasi Bobot Terdaftar</h3>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th>Kelas</th>
                                <th>Harian</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bobotNilais as $bobot)
                                <tr>
                                    <td class="font-bold text-slate-800">{{ $bobot->tahunAjaran->nama_lengkap }}</td>
                                    <td>
                                        @if($bobot->kelas)
                                            <span class="badge badge-navy font-mono">{{ $bobot->kelas->nama_kelas }}</span>
                                        @else
                                            <span class="badge badge-gray">Semua Kelas</span>
                                        @endif
                                    </td>
                                    <td class="font-semibold font-mono text-slate-600">{{ $bobot->bobot_harian }}%</td>
                                    <td class="font-semibold font-mono text-slate-600">{{ $bobot->bobot_uts }}%</td>
                                    <td class="font-semibold font-mono text-slate-600">{{ $bobot->bobot_uas }}%</td>
                                    <td>
                                        <span class="badge badge-success font-bold font-mono">
                                            {{ $bobot->bobot_harian + $bobot->bobot_uts + $bobot->bobot_uas }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-400">Belum ada pengaturan bobot nilai untuk mapel ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Form Column --}}
        <div>
            <div class="card sticky top-20">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Atur Bobot Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mata-pelajaran.bobot.store', $mataPelajaran) }}" method="POST" class="space-y-4">
                        @csrf
                        
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
                            <label for="kelas_id" class="form-label">Kelas (Spesifik)</label>
                            @php
                                $kelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('nomor')->get();
                            @endphp
                            <select id="kelas_id" name="kelas_id" class="form-select @error('kelas_id') error @enderror">
                                <option value="">Berlaku untuk Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }} ({{ $k->tahunAjaran->nama }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-3 border-t border-slate-100 pt-4">
                            <div>
                                <label for="bobot_harian" class="form-label text-[10px]">Harian (%)</label>
                                <input type="number" id="bobot_harian" name="bobot_harian" min="0" max="100" 
                                    value="{{ old('bobot_harian', 40) }}" required class="form-input text-center font-mono">
                                @error('bobot_harian')
                                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bobot_uts" class="form-label text-[10px]">UTS (%)</label>
                                <input type="number" id="bobot_uts" name="bobot_uts" min="0" max="100" 
                                    value="{{ old('bobot_uts', 30) }}" required class="form-input text-center font-mono">
                                @error('bobot_uts')
                                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bobot_uas" class="form-label text-[10px]">UAS (%)</label>
                                <input type="number" id="bobot_uas" name="bobot_uas" min="0" max="100" 
                                    value="{{ old('bobot_uas', 30) }}" required class="form-input text-center font-mono">
                                @error('bobot_uas')
                                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="text-[10px] text-slate-400 italic text-center">Akumulasi bobot (Harian + UTS + UAS) wajib berjumlah tepat 100%.</p>

                        <div class="pt-2">
                            <button type="submit" class="btn-primary w-full justify-center">
                                Simpan Bobot Nilai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
