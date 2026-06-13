@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')

@section('breadcrumb-parent', 'Manajemen Akun')
@section('breadcrumb-current', 'Tambah')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Tambah Pengguna Baru</h1>
            <p class="page-subtitle">Daftarkan akun login pengguna sistem baru.</p>
        </div>
        <div>
            <a href="{{ route('admin.user.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="form-label">Nama Lengkap Pengguna</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-input @error('name') error @enderror" placeholder="Contoh: Administrator Utama">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="form-input @error('email') error @enderror" placeholder="contoh@siakad.sch.id">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="form-label">Hak Akses / Role</label>
                        <select id="role" name="role" required class="form-select @error('role') error @enderror">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Pengelola Sistem)</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru (Tenaga Pendidik)</option>
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa (Peserta Didik)</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-slate-100 pt-4">
                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" required
                            class="form-input @error('password') error @enderror" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="form-input" placeholder="Masukkan kembali password">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.user.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
