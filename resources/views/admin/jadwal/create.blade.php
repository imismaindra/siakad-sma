@extends('layouts.admin')

@section('title', 'Tambah Jadwal Pelajaran')

@section('breadcrumb-parent', 'Jadwal Pelajaran')
@section('breadcrumb-current', 'Tambah')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tambah Jadwal Baru</h1>
            <p class="page-subtitle">Daftarkan slot jadwal pelajaran baru pada tahun ajaran aktif.</p>
        </div>
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            @if($tahunAktif)
                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="kelas_id" class="form-label">Kelas Penerima</label>
                            <select id="kelas_id" name="kelas_id" required class="form-select @error('kelas_id') error @enderror">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
                            <select id="mata_pelajaran_id" name="mata_pelajaran_id" required class="form-select @error('mata_pelajaran_id') error @enderror">
                                <option value="">Pilih Mapel</option>
                                @foreach($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>
                                        {{ $mp->nama }} ({{ $mp->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="guru_id" class="form-label">Guru Pengampu</label>
                            <select id="guru_id" name="guru_id" required class="form-select @error('guru_id') error @enderror">
                                <option value="">Pilih Guru Pengampu</option>
                                @foreach($gurus as $g)
                                    <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama_lengkap }} (NIP. {{ $g->nip ?? '—' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hari" class="form-label">Hari</label>
                            <select id="hari" name="hari" required class="form-select @error('hari') error @enderror">
                                <option value="">Pilih Hari</option>
                                <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            </select>
                            @error('hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required
                                class="form-input @error('jam_mulai') error @enderror">
                            @error('jam_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required
                                class="form-input @error('jam_selesai') error @enderror">
                            @error('jam_selesai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="urutan_jam" class="form-label">Urutan Jam Ke-</label>
                            <input type="number" id="urutan_jam" name="urutan_jam" min="1" value="{{ old('urutan_jam', 1) }}" required
                                class="form-input @error('urutan_jam') error @enderror" placeholder="Contoh: 1, 2">
                            @error('urutan_jam')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="ruangan" class="form-label">Ruang Kelas / Lab</label>
                        <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}"
                            class="form-input @error('ruangan') error @enderror" placeholder="Contoh: Ruang XI IPA 1, Lab Biologi (Opsional)">
                        @error('ruangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            @else
                <div class="p-4 bg-red-50 rounded-xl border border-red-100 text-red-700 text-sm text-center">
                    Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu sebelum menjadwalkan mata pelajaran.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
