@extends('layouts.admin')

@section('title', 'Atur Role Pengguna: ' . $user->name)
@section('breadcrumb-parent', 'Manajemen Akun')
@section('breadcrumb-current', 'Atur Role')

@section('admin-content')
<div class="max-w-2xl space-y-6 animate-fade-in-up">

    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Atur Role Pengguna</h1>
            <p class="page-subtitle">Tentukan role yang dimiliki pengguna ini. Role menentukan hak akses di seluruh sistem.</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    {{-- User Info Card --}}
    <div class="card">
        <div class="card-body flex items-center gap-4">
            <div class="avatar-placeholder w-14 h-14 text-lg flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <div class="font-extrabold text-slate-800 text-base">{{ $user->name }}</div>
                <div class="text-sm text-slate-500">{{ $user->email }}</div>
                <div class="flex gap-2 mt-1.5">
                    @foreach ($user->roles as $r)
                        <span class="badge badge-navy text-[11px]">{{ $r->name }}</span>
                    @endforeach
                    @if($user->roles->isEmpty())
                        <span class="badge badge-gray text-[11px]">Belum ada role</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Role Selection --}}
    <div class="card">
        <div class="card-header">
            <h2 class="font-bold text-slate-700 text-sm uppercase tracking-wide">Pilih Role</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.user.roles.update', $user) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach ($roles as $role)
                    <label class="role-label flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all
                        {{ in_array($role->name, $userRoles) ? 'border-navy-500 bg-navy-50' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            class="role-checkbox w-4 h-4 rounded text-navy-600 border-slate-300 focus:ring-navy-500/20"
                            {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                        <div class="flex-1">
                            <div class="font-bold text-slate-800 capitalize">{{ $role->name }}</div>
                            <div class="text-xs text-slate-400">{{ $role->permissions_count ?? $role->permissions->count() }} permission</div>
                        </div>
                        @if(in_array($role->name, $userRoles))
                            <span class="badge badge-navy text-[10px]">Aktif</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2.5 mb-4">
                        <strong>Catatan:</strong> Jika user memiliki lebih dari satu role, role pertama yang dipilih akan menjadi role utama yang menentukan arah redirect setelah login.
                    </p>
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Simpan Role
                        </button>
                        <a href="{{ route('admin.user.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.role-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const label = cb.closest('label');
            label.classList.toggle('border-navy-500', cb.checked);
            label.classList.toggle('bg-navy-50', cb.checked);
            label.classList.toggle('border-slate-200', !cb.checked);
            label.classList.toggle('bg-white', !cb.checked);
        });
    });
</script>
@endsection
