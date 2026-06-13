@extends('layouts.admin')

@section('title', 'Edit Data Siswa')

@section('breadcrumb-parent', 'Data Siswa')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Edit Siswa: {{ $siswa->nama_lengkap }}</h1>
            <p class="page-subtitle">Perbarui data diri siswa, wali murid, status, dan password login.</p>
        </div>
        <div>
            <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
                Batal
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Personal Data Card --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Data Pribadi Murid</h3>
                    </div>
                    <div class="card-body space-y-5">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                                <input type="text" id="nis" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                                    class="form-input @error('nis') error @enderror">
                                @error('nis')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nisn" class="form-label">NISN (Nasional)</label>
                                <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}"
                                    class="form-input @error('nisn') error @enderror">
                                @error('nisn')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="nama_lengkap" class="form-label">Nama Lengkap Siswa</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required
                                class="form-input @error('nama_lengkap') error @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required class="form-select @error('jenis_kelamin') error @enderror">
                                    <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kelas_id" class="form-label">Kelas Aktif</label>
                                <select id="kelas_id" name="kelas_id" class="form-select @error('kelas_id') error @enderror">
                                    <option value="">Belum Memiliki Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                                            Kelas {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                                    class="form-input @error('tempat_lahir') error @enderror">
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                                    value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}"
                                    class="form-input @error('tanggal_lahir') error @enderror">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="alamat" class="form-label">Alamat Tinggal</label>
                            <textarea id="alamat" name="alamat" class="form-textarea @error('alamat') error @enderror">{{ old('alamat', $siswa->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="no_telepon" class="form-label">No. Telepon Siswa</label>
                                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}"
                                    class="form-input @error('no_telepon') error @enderror">
                                @error('no_telepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="foto" class="form-label">Foto Profil (Ganti Baru)</label>
                                <input type="file" id="foto" name="foto" accept="image/*"
                                    class="form-input @error('foto') error @enderror py-1.5 text-xs">
                                @error('foto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Parent / Wali Card --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Data Orang Tua / Wali</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div>
                            <label for="nama_ortu" class="form-label">Nama Orang Tua / Wali</label>
                            <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $siswa->nama_ortu) }}"
                                class="form-input @error('nama_ortu') error @enderror">
                            @error('nama_ortu')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="no_telepon_ortu" class="form-label">No. Telepon Orang Tua / Wali</label>
                            <input type="text" id="no_telepon_ortu" name="no_telepon_ortu" value="{{ old('no_telepon_ortu', $siswa->no_telepon_ortu) }}"
                                class="form-input @error('no_telepon_ortu') error @enderror">
                            @error('no_telepon_ortu')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account, Status & Submit Card --}}
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Akses &amp; Status</h3>
                    </div>
                    <div class="card-body space-y-4">
                        
                        <div>
                            <label class="form-label">Email Siswa (Akun Login)</label>
                            @if($siswa->user)
                                <input type="email" class="form-input bg-slate-50 text-slate-500 cursor-not-allowed" 
                                    value="{{ $siswa->user->email }}" disabled readonly>
                            @else
                                <span class="text-slate-400 italic text-xs">Siswa ini belum memiliki akun portal login. Silakan edit via Menu Akun Sistem jika ingin mendaftarkan email login.</span>
                            @endif
                        </div>

                        @if($siswa->user)
                            <div>
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" id="password" name="password"
                                    class="form-input @error('password') error @enderror" placeholder="Biarkan kosong jika tidak diubah">
                                <p class="text-[10px] text-slate-400 mt-1 italic">Kosongkan jika password portal tidak ingin diganti.</p>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="status" class="form-label">Status Siswa</label>
                            <select id="status" name="status" required class="form-select @error('status') error @enderror">
                                <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status', $siswa->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="pindah" {{ old('status', $siswa->status) == 'pindah' ? 'selected' : '' }}>Pindah Sekolah</option>
                                <option value="dikeluarkan" {{ old('status', $siswa->status) == 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full justify-center py-3">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
