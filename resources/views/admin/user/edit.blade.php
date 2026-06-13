@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('breadcrumb-parent', 'Manajemen Akun')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Edit Pengguna: {{ $user->name }}</h1>
            <p class="page-subtitle">Ubah informasi akun login, password, hak akses, dan status keaktifan.</p>
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
            <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="form-label">Nama Lengkap Pengguna</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="form-input @error('name') error @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="form-input @error('email') error @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="form-label">Hak Akses / Role</label>
                        <select id="role" name="role" required class="form-select @error('role') error @enderror">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Pengelola Sistem)</option>
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru (Tenaga Pendidik)</option>
                            <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa (Peserta Didik)</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-slate-100 pt-4">
                    <div>
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" id="password" name="password"
                            class="form-input @error('password') error @enderror" placeholder="Biarkan kosong jika tidak diubah">
                        <p class="text-[10px] text-slate-400 mt-1 italic">Kosongkan jika password login tidak ingin diganti.</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-input" placeholder="Masukkan kembali password baru">
                    </div>
                </div>

                @if($user->id !== auth()->id())
                    <div class="flex items-center gap-3 pt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded border-slate-300 text-navy-500 focus:ring-navy-500 mr-2"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-label mb-0 cursor-pointer select-none">Akun Pengguna Aktif</label>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.user.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
