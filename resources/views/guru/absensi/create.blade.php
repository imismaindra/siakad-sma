@extends('layouts.guru')

@section('title', 'Input Absensi Siswa')

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Input Baru')

@section('guru-content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Isi Absensi Pelajaran</h1>
            <p class="page-subtitle">Kelas: <span class="font-bold font-mono text-navy-500">{{ $jadwal->kelas->nama_kelas }}</span> | Mata Pelajaran: <span class="font-bold text-slate-800">{{ $jadwal->mataPelajaran->nama }}</span></p>
        </div>
        <div>
            <a href="{{ route('guru.dashboard') }}" class="btn-secondary">
                Batal
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('guru.absensi.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="jadwal_pelajaran_id" value="{{ $jadwal->id }}">

        {{-- Sesi Info Card --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Detail Sesi Belajar</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="tanggal" class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="form-input @error('tanggal') error @enderror">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="materi" class="form-label">Materi / Bahasan Pembelajaran</label>
                        <input type="text" id="materi" name="materi" value="{{ old('materi') }}" required
                            class="form-input @error('materi') error @enderror" placeholder="Contoh: Bab 2 - Persamaan Kuadrat">
                        @error('materi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="catatan" class="form-label">Catatan Sesi Kelas</label>
                    <textarea id="catatan" name="catatan" class="form-textarea @error('catatan') error @enderror" placeholder="Contoh: Kelas kondusif, siswa antusias. (Opsional)">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Student List Card --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Presensi Kehadiran Siswa</h3>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th class="w-64 text-center">Status Kehadiran</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal->kelas->siswas as $idx => $s)
                            <tr>
                                <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="font-mono text-slate-500 text-xs">
                                    {{ $s->nis }}
                                    <input type="hidden" name="siswa[{{ $idx }}][id]" value="{{ $s->id }}">
                                </td>
                                <td class="font-bold text-slate-800">{{ $s->nama_lengkap }}</td>
                                <td>
                                    <span class="badge {{ $s->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
                                        {{ $s->jenis_kelamin }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-4 text-xs font-semibold">
                                        <label class="flex items-center gap-1.5 cursor-pointer text-emerald-600 select-none">
                                            <input type="radio" name="siswa[{{ $idx }}][status]" value="hadir" checked
                                                class="rounded-full border-slate-300 text-emerald-500 focus:ring-emerald-500">
                                            H
                                        </label>
                                        
                                        <label class="flex items-center gap-1.5 cursor-pointer text-blue-600 select-none">
                                            <input type="radio" name="siswa[{{ $idx }}][status]" value="sakit"
                                                class="rounded-full border-slate-300 text-blue-500 focus:ring-blue-500">
                                            S
                                        </label>

                                        <label class="flex items-center gap-1.5 cursor-pointer text-amber-600 select-none">
                                            <input type="radio" name="siswa[{{ $idx }}][status]" value="izin"
                                                class="rounded-full border-slate-300 text-amber-500 focus:ring-amber-500">
                                            I
                                        </label>

                                        <label class="flex items-center gap-1.5 cursor-pointer text-rose-600 select-none">
                                            <input type="radio" name="siswa[{{ $idx }}][status]" value="alpa"
                                                class="rounded-full border-slate-300 text-rose-500 focus:ring-rose-500">
                                            A
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="siswa[{{ $idx }}][keterangan]" 
                                        class="form-input py-1 text-xs" placeholder="Catatan opsional...">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">Tidak ada siswa terdaftar pada kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('guru.dashboard') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    Simpan Absensi
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
