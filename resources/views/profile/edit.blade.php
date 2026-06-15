@extends('layouts.' . auth()->user()->role)

@section('title', 'Profil Saya')

@section('breadcrumb-parent', 'Pengaturan')
@section('breadcrumb-current', 'Profil Saya')

@section(auth()->user()->role . '-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Profil Saya</h1>
            <p class="page-subtitle">Ubah pengaturan nama tampilan, password akun, dan tinjau data pribadi Anda.</p>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Edit Account & Password --}}
        <div class="lg:col-span-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Pengaturan Akun</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Upload Profile Photo --}}
                        <div class="flex items-center gap-4 pb-4 border-b border-slate-100">
                            @if(auth()->user()->getFotoUrl())
                                <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-16 h-16 rounded-full object-cover border-2 border-slate-200 shadow-sm" id="avatar-preview">
                            @else
                                <div class="avatar-placeholder w-16 h-16 text-lg flex items-center justify-center bg-navy-600 text-white font-bold rounded-full border-2 border-slate-200 shadow-sm" id="avatar-preview-placeholder">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <label for="foto" class="form-label mb-1">Ganti Foto Profil</label>
                                <input type="file" id="foto" name="foto" accept="image/*" class="form-input text-xs py-1.5 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-navy-50 file:text-navy-700 hover:file:bg-navy-100">
                                @error('foto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-[10px] text-slate-400 mt-1 italic">Format: JPG, PNG, WEBP. Maks: 2MB. Dioptimalkan otomatis.</p>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="form-label">Nama Tampilan</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="form-input @error('name') error @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Alamat Email (Login)</label>
                            <input type="email" class="form-input bg-slate-50 text-slate-500 cursor-not-allowed" 
                                value="{{ $user->email }}" disabled readonly>
                            <p class="text-[10px] text-slate-400 mt-1 italic">Email login hanya dapat diubah oleh Administrator Sistem.</p>
                        </div>

                        <div class="border-t border-slate-100 pt-4 space-y-4">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Ganti Password</h4>
                            <p class="text-xs text-slate-400">Kosongkan kolom di bawah ini jika Anda tidak berniat merubah password akun saat ini.</p>
                            
                            <div>
                                <label for="password_lama" class="form-label">Password Lama</label>
                                <input type="password" id="password_lama" name="password_lama"
                                    class="form-input @error('password_lama') error @enderror" placeholder="Masukkan password saat ini">
                                @error('password_lama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" id="password" name="password"
                                        class="form-input @error('password') error @enderror" placeholder="Minimal 8 karakter">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-input" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: Biodata Detail based on Role --}}
        <div class="lg:col-span-5">
            @if($user->isGuru())
                @php $guru = $user->guru; @endphp
                @if($guru)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Detail Profil Guru</h3>
                        </div>
                        <div class="card-body space-y-4">
                            @if($guru->foto)
                                <div class="flex justify-center pb-4">
                                    <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru" class="w-24 h-24 rounded-full object-cover border-4 border-slate-100 shadow-md">
                                </div>
                            @endif

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">NIP</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block font-mono">{{ $guru->nip ?? '—' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jenis Kelamin</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    {{ $guru->tempat_lahir ?? '—' }}{{ $guru->tanggal_lahir ? ', ' . $guru->tanggal_lahir->locale('id')->isoFormat('D MMMM YYYY') : '' }}
                                </span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">No. Telepon</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $guru->no_telepon ?? '—' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Alamat Lengkap</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block leading-relaxed">{{ $guru->alamat ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

            @elseif($user->isSiswa())
                @php $siswa = $user->siswa; @endphp
                @if($siswa)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Detail Profil Siswa</h3>
                        </div>
                        <div class="card-body space-y-4">
                            @if($siswa->foto)
                                <div class="flex justify-center pb-4">
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" class="w-24 h-24 rounded-full object-cover border-4 border-slate-100 shadow-md">
                                </div>
                            @endif

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">NIS / NISN</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block font-mono">{{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Kelas &amp; Jurusan</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    @if($siswa->kelas)
                                        Kelas {{ $siswa->kelas->nama_kelas }} <span class="text-xs text-slate-400">({{ $siswa->kelas->jurusan?->nama ?? 'Umum' }})</span>
                                    @else
                                        <span class="text-slate-400 italic font-normal">Belum ditentukan</span>
                                    @endif
                                </span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jenis Kelamin</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Orang Tua / Wali</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $siswa->nama_ortu ?? '—' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Alamat Lengkap</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block leading-relaxed">{{ $siswa->alamat ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Informasi Pengguna</h3>
                    </div>
                    <div class="card-body space-y-4">
                        @if(auth()->user()->getFotoUrl())
                            <div class="flex justify-center pb-4">
                                <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-24 h-24 rounded-full object-cover border-4 border-slate-100 shadow-md">
                            </div>
                        @endif
                        <div class="p-4 bg-navy-50/50 rounded-xl border border-navy-100 text-navy-800 text-xs leading-relaxed space-y-2">
                            <p class="font-bold">Hak Akses: Administrator</p>
                            <p>Akun Administrator memiliki hak akses penuh ke seluruh pengelolaan master data, konfigurasi periode akademik tahun ajaran, plot jadwal pelajaran, finalisasi nilai, manajemen user serta kontrol keaktifan sistem.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
