@extends('layouts.guru')

@section('title', 'Input Nilai — ' . $siswa->nama_lengkap)

@section('breadcrumb-parent', 'Kelola Nilai')
@section('breadcrumb-current', 'Input Nilai')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Input Nilai Siswa</h1>
            <p class="page-subtitle">Siswa: <span class="font-bold text-slate-800">{{ $siswa->nama_lengkap }}</span> (NIS: {{ $siswa->nis }}) | Mapel: <span class="font-bold text-navy-500">{{ $nilai->mataPelajaran->nama }}</span></p>
        </div>
        <div>
            <a href="{{ $siswa->kelas_id ? route('guru.nilai.kelas', $siswa->kelas_id) : route('guru.nilai.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Main Grades & Harian Log --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- UTS/UAS Form --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Nilai Utama (UTS, UAS, &amp; Deskripsi)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.nilai.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $nilai->mata_pelajaran_id }}">
                        <input type="hidden" name="kelas_id" value="{{ $siswa->kelas_id }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="nilai_uts" class="form-label">Nilai UTS (Ujian Tengah Semester)</label>
                                <input type="number" id="nilai_uts" name="nilai_uts" min="0" max="100" step="0.1" 
                                    value="{{ old('nilai_uts', $nilai->nilai_uts) }}" 
                                    class="form-input @error('nilai_uts') error @enderror font-mono font-semibold" placeholder="Belum diisi">
                                @error('nilai_uts')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nilai_uas" class="form-label">Nilai UAS (Ujian Akhir Semester)</label>
                                <input type="number" id="nilai_uas" name="nilai_uas" min="0" max="100" step="0.1" 
                                    value="{{ old('nilai_uas', $nilai->nilai_uas) }}" 
                                    class="form-input @error('nilai_uas') error @enderror font-mono font-semibold" placeholder="Belum diisi">
                                @error('nilai_uas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="deskripsi" class="form-label">Deskripsi Capaian Kompetensi</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-textarea @error('deskripsi') error @enderror" 
                                placeholder="Tuliskan deskripsi pencapaian kompetensi siswa (misal: Menunjukkan penguasaan yang sangat baik dalam memahami trigonometri)...">{{ old('deskripsi', $nilai->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                @if($nilai->exists)
                                    <span class="text-xs text-slate-400">Rata Harian: <span class="font-bold font-mono text-slate-700">{{ $nilai->rata_rata_harian ?? '0' }}</span> | Nilai Akhir: <span class="font-extrabold font-mono text-navy-600 text-sm">{{ $nilai->nilai_akhir ?? '0' }}</span></span>
                                @endif
                            </div>
                            <button type="submit" class="btn-primary">
                                Simpan Nilai Utama
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daily Tasks list --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Daftar Nilai Harian (Rata-rata: {{ $nilai->rata_rata_harian ?? '0' }})</h3>
                </div>
                
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Judul Penilaian</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nilai->detailNilais ?? [] as $detail)
                                <tr>
                                    <td class="font-mono text-xs text-slate-500">{{ $detail->tanggal->locale('id')->isoFormat('D/MM/Y') }}</td>
                                    <td>
                                        <span class="badge badge-gray font-semibold capitalize">{{ str_replace('_', ' ', $detail->jenis) }}</span>
                                    </td>
                                    <td class="font-bold text-slate-800">{{ $detail->judul }}</td>
                                    <td class="font-bold font-mono text-navy-600 text-sm">{{ $detail->nilai }}</td>
                                    <td class="text-xs text-slate-400 max-w-xs truncate" title="{{ $detail->keterangan }}">{{ $detail->keterangan ?? '—' }}</td>
                                    <td class="text-right">
                                        <form action="{{ route('guru.nilai.detail.destroy', $detail) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus nilai harian ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-400">Belum ada rincian nilai harian diinputkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Right: Add Harian Form --}}
        <div class="lg:col-span-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Tambah Nilai Harian</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.nilai.detail.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $nilai->mata_pelajaran_id }}">
                        <input type="hidden" name="kelas_id" value="{{ $siswa->kelas_id }}">

                        <div>
                            <label for="tanggal" class="form-label">Tanggal Penilaian</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                class="form-input @error('tanggal') error @enderror">
                            @error('tanggal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis" class="form-label">Jenis Penilaian</label>
                            <select id="jenis" name="jenis" required class="form-select @error('jenis') error @enderror">
                                <option value="tugas" {{ old('jenis') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                                <option value="kuis" {{ old('jenis') == 'kuis' ? 'selected' : '' }}>Kuis</option>
                                <option value="ulangan_harian" {{ old('jenis') == 'ulangan_harian' ? 'selected' : '' }}>Ulangan Harian</option>
                                <option value="praktik" {{ old('jenis') == 'praktik' ? 'selected' : '' }}>Praktik</option>
                                <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="judul" class="form-label">Judul / Materi Penilaian</label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                                class="form-input @error('judul') error @enderror" placeholder="Contoh: Tugas 1 Aljabar">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nilai" class="form-label">Nilai (0-100)</label>
                            <input type="number" id="nilai" name="nilai" min="0" max="100" step="0.1" value="{{ old('nilai') }}" required
                                class="form-input @error('nilai') error @enderror font-mono font-semibold" placeholder="Nilai">
                            @error('nilai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keterangan" class="form-label">Keterangan Singkat</label>
                            <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" 
                                class="form-input @error('keterangan') error @enderror" placeholder="Keterangan opsional...">
                            @error('keterangan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn-gold w-full justify-center">
                                Tambah Nilai Harian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
