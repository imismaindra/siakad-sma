@extends('layouts.admin')

@section('title', 'Tambah Siswa Baru')

@section('breadcrumb-parent', 'Data Siswa')
@section('breadcrumb-current', 'Tambah')

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tambah Siswa Baru</h1>
            <p class="page-subtitle">Daftarkan peserta didik baru beserta akun portal akademik mereka.</p>
        </div>
        <div>
            <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
                Batal
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required
                                    class="form-input @error('nis') error @enderror" placeholder="Wajib diisi">
                                @error('nis')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nisn" class="form-label">NISN (Nasional)</label>
                                <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}"
                                    class="form-input @error('nisn') error @enderror" placeholder="Opsional">
                                @error('nisn')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="nama_lengkap" class="form-label">Nama Lengkap Siswa</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                class="form-input @error('nama_lengkap') error @enderror" placeholder="Contoh: Andi Wijaya">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required class="form-select @error('jenis_kelamin') error @enderror">
                                    <option value="">Pilih Gender</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kelas_id" class="form-label">Kelas Awal</label>
                                <select id="kelas_id" name="kelas_id" class="form-select @error('kelas_id') error @enderror">
                                    <option value="">Belum Memiliki Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            Kelas {{ $k->nama_kelas }} (Sisa kapasitas: {{ $k->kapasitas - $k->jumlah_siswa }})
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
                                <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                    class="form-input @error('tempat_lahir') error @enderror" placeholder="Contoh: Bandung">
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                    class="form-input @error('tanggal_lahir') error @enderror">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="alamat" class="form-label">Alamat Tinggal</label>
                            <textarea id="alamat" name="alamat" class="form-textarea @error('alamat') error @enderror" placeholder="Contoh: Jl. Diponegoro No. 45">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="no_telepon" class="form-label">No. Telepon Siswa</label>
                                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                                    class="form-input @error('no_telepon') error @enderror" placeholder="Contoh: 08123456789">
                                @error('no_telepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="foto" class="form-label">Foto Profil</label>
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
                            <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu') }}"
                                class="form-input @error('nama_ortu') error @enderror" placeholder="Contoh: Hermawan Wijaya">
                            @error('nama_ortu')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="no_telepon_ortu" class="form-label">No. Telepon Orang Tua / Wali</label>
                            <input type="text" id="no_telepon_ortu" name="no_telepon_ortu" value="{{ old('no_telepon_ortu') }}"
                                class="form-input @error('no_telepon_ortu') error @enderror" placeholder="Contoh: 08987654321">
                            @error('no_telepon_ortu')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Login Card --}}
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Akses Portal Siswa</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <p class="text-xs text-slate-400">Bagian ini diisi jika siswa ingin diberikan akses masuk ke portal akademis SIAKAD.</p>
                        
                        <div>
                            <label for="email" class="form-label">Email Siswa</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-input @error('email') error @enderror" placeholder="siswa@siakad.sch.id (Opsional)">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="form-label">Password Login</label>
                            <input type="password" id="password" name="password"
                                class="form-input @error('password') error @enderror" placeholder="Kosongkan = Default NIS">
                            <p class="text-[10px] text-slate-400 mt-1 italic">Jika kolom password dikosongkan, password login default adalah nilai NIS siswa.</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full justify-center py-3">
                        Simpan Data Siswa
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
