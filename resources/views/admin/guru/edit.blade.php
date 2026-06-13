@extends('layouts.admin')

@section('title', 'Edit Data Guru')

@section('breadcrumb-parent', 'Data Guru')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Edit Guru: {{ $guru->nama_lengkap }}</h1>
            <p class="page-subtitle">Perbarui wewenang mengajar, status kepegawaian, maupun kredensial login.</p>
        </div>
        <div>
            <a href="{{ route('admin.guru.index') }}" class="btn-secondary">
                Batal
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.guru.update', $guru) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Personal Data Card --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Data Pribadi</h3>
                    </div>
                    <div class="card-body space-y-5">
                        <div>
                            <label for="nama_lengkap" class="form-label">Nama Lengkap (dengan gelar)</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required
                                class="form-input @error('nama_lengkap') error @enderror">
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nip" class="form-label">NIP (Nomor Induk Pegawai)</label>
                                <input type="text" id="nip" name="nip" value="{{ old('nip', $guru->nip) }}"
                                    class="form-input @error('nip') error @enderror">
                                @error('nip')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required class="form-select @error('jenis_kelamin') error @enderror">
                                    <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                                    class="form-input @error('tempat_lahir') error @enderror">
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                                    value="{{ old('tanggal_lahir', $guru->tanggal_lahir ? $guru->tanggal_lahir->format('Y-m-d') : '') }}"
                                    class="form-input @error('tanggal_lahir') error @enderror">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" class="form-textarea @error('alamat') error @enderror">{{ old('alamat', $guru->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="no_telepon" class="form-label">No. Telepon / WhatsApp</label>
                                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $guru->no_telepon) }}"
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
            </div>

            {{-- Credentials, Status, & Mapel Card --}}
            <div class="space-y-6">
                
                {{-- Account Login Card --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Akun Login &amp; Status</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div>
                            <label for="email" class="form-label">Email Resmi</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $guru->email) }}" required
                                class="form-input @error('email') error @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" id="password" name="password"
                                class="form-input @error('password') error @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            <p class="text-[10px] text-slate-400 mt-1 italic">Kosongkan kolom ini jika password default tidak ingin diganti.</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="form-label">Status Kepegawaian</label>
                            <select id="status" name="status" required class="form-select @error('status') error @enderror">
                                <option value="aktif" {{ old('status', $guru->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status', $guru->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Teaching Subject Card --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Mata Pelajaran</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-xs text-slate-400 mb-4">Pilih mata pelajaran yang diampu oleh guru ini pada tahun ajaran aktif.</p>
                        @if($tahunAktif)
                            <div class="space-y-2.5 max-h-56 overflow-y-auto pr-2">
                                @forelse($mataPelajarans as $mp)
                                    <label class="flex items-start text-sm text-slate-600 cursor-pointer select-none">
                                        <input type="checkbox" name="mata_pelajaran_ids[]" value="{{ $mp->id }}" 
                                            class="rounded border-slate-300 text-navy-500 focus:ring-navy-500 mt-1 mr-2"
                                            {{ (is_array(old('mata_pelajaran_ids')) && in_array($mp->id, old('mata_pelajaran_ids'))) || (!is_array(old('mata_pelajaran_ids')) && $guru->mataPelajarans->contains($mp->id)) ? 'checked' : '' }}>
                                        <span>{{ $mp->nama }} <span class="text-xs text-slate-400">({{ $mp->kode }})</span></span>
                                    </label>
                                @empty
                                    <p class="text-slate-400 text-xs italic">Belum ada mata pelajaran aktif.</p>
                                @endforelse
                            </div>
                        @else
                            <div class="p-3 bg-red-50 rounded-lg border border-red-100 text-red-700 text-xs">
                                Tidak ada tahun ajaran aktif untuk sinkronisasi mapel.
                            </div>
                        @endif
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
