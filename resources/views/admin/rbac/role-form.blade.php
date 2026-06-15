@extends('layouts.admin')

@php $isEdit = isset($role); @endphp

@section('title', $isEdit ? 'Edit Role: ' . $role->name : 'Tambah Role Baru')
@section('breadcrumb-parent', 'Manajemen Role')
@section('breadcrumb-current', $isEdit ? 'Edit Role' : 'Tambah Role')

@section('admin-content')
<div class="max-w-4xl space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">{{ $isEdit ? 'Edit Role: ' . $role->name : 'Tambah Role Baru' }}</h1>
            <p class="page-subtitle">{{ $isEdit ? 'Ubah nama role dan atur ulang permission yang dimiliki.' : 'Buat role baru dan tentukan hak akses permission-nya.' }}</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.roles.update', $role) : route('admin.roles.store') }}" class="space-y-6">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- Role Name Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="font-bold text-slate-700 text-sm uppercase tracking-wide">Informasi Role</h2>
            </div>
            <div class="card-body">
                <div class="max-w-sm">
                    <label for="name" class="form-label">Nama Role <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $role->name ?? '') }}"
                        class="form-input @error('name') error @enderror"
                        placeholder="contoh: wali-kelas, orang-tua, kepala-sekolah"
                        required>
                    <p class="text-xs text-slate-400 mt-1.5">Gunakan huruf kecil dan tanda hubung. Contoh: <code class="bg-slate-100 px-1 rounded">wali-kelas</code></p>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Permissions Card --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="font-bold text-slate-700 text-sm uppercase tracking-wide">Hak Akses (Permissions)</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Pilih permission yang dimiliki role ini. Centang semua atau pilih satu per satu.</p>
                </div>
                <button type="button" id="selectAllBtn" class="btn-secondary btn-sm">
                    Pilih Semua
                </button>
            </div>
            <div class="card-body space-y-6">
                @foreach ($permissions as $group => $groupPerms)
                <div>
                    {{-- Group Header --}}
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-navy-600 bg-navy-50 px-3 py-1 rounded-full border border-navy-100">{{ $group }}</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    {{-- Permission Checkboxes --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                        @foreach ($groupPerms as $perm)
                        <label class="permission-checkbox-label flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition-all hover:border-navy-300 hover:bg-navy-50/30 
                            {{ in_array($perm->name, $rolePermissions ?? []) ? 'border-navy-400 bg-navy-50' : 'border-slate-200 bg-white' }}">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                class="perm-checkbox w-4 h-4 rounded text-navy-600 border-slate-300 focus:ring-navy-500/20"
                                {{ in_array($perm->name, $rolePermissions ?? []) ? 'checked' : '' }}>
                            <span class="text-xs font-semibold text-slate-700 leading-tight">{{ $perm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @if($permissions->isEmpty())
                    <div class="text-center py-8 text-slate-400">
                        <p class="text-sm">Belum ada permission tersedia. <a href="{{ route('admin.permissions.index') }}" class="text-navy-600 font-bold hover:underline">Buat permission terlebih dahulu.</a></p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Role' }}
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    const selectAllBtn = document.getElementById('selectAllBtn');
    const checkboxes = document.querySelectorAll('.perm-checkbox');
    let allSelected = false;

    selectAllBtn.addEventListener('click', () => {
        allSelected = !allSelected;
        checkboxes.forEach(cb => {
            cb.checked = allSelected;
            cb.closest('label').classList.toggle('border-navy-400', allSelected);
            cb.closest('label').classList.toggle('bg-navy-50', allSelected);
            cb.closest('label').classList.toggle('border-slate-200', !allSelected);
            cb.closest('label').classList.toggle('bg-white', !allSelected);
        });
        selectAllBtn.textContent = allSelected ? 'Batal Semua' : 'Pilih Semua';
    });

    // Live styling on individual checkbox change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const label = cb.closest('label');
            label.classList.toggle('border-navy-400', cb.checked);
            label.classList.toggle('bg-navy-50', cb.checked);
            label.classList.toggle('border-slate-200', !cb.checked);
            label.classList.toggle('bg-white', !cb.checked);
        });
    });
</script>
@endsection
