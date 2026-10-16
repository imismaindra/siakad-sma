@extends('layouts.admin')

@section('title', 'Detail Orang Tua - ' . $orangTua->nama_lengkap)

@section('breadcrumb-parent', 'Data Orang Tua')
@section('breadcrumb-current', 'Detail')

@section('admin-content')
<div class="max-w-4xl space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Profil Wali Murid</p>
            <h1 class="page-title font-display !text-3xl mt-3">{{ $orangTua->nama_lengkap }}</h1>
            <p class="page-subtitle">Informasi profil akun dan daftar siswa yang dipantau.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orang-tua.edit', $orangTua) }}" class="rounded-full bg-navy-800 px-5 py-2 text-sm font-semibold text-white hover:bg-navy-900 transition shadow-sm">
                Edit Profil
            </a>
            <a href="{{ route('admin.orang-tua.index') }}" class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50 transition">
                Kembali
            </a>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Profil Singkat --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="text-center pb-4 border-b border-slate-100">
                <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xl flex items-center justify-center mx-auto mb-3 border border-indigo-100 shadow-sm">
                    {{ strtoupper(substr($orangTua->nama_lengkap, 0, 2)) }}
                </div>
                <h3 class="font-bold text-navy-900">{{ $orangTua->nama_lengkap }}</h3>
                <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ $orangTua->jenis_kelamin === 'L' ? 'Laki-laki (Ayah)' : 'Perempuan (Ibu)' }}
                </span>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Email Akun:</span>
                    <span class="font-medium text-slate-800">{{ $orangTua->user?->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">No. Telepon / WhatsApp:</span>
                    <span class="font-medium text-slate-800 font-mono">{{ $orangTua->no_telepon ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Pekerjaan:</span>
                    <span class="font-medium text-slate-800">{{ $orangTua->pekerjaan ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Alamat:</span>
                    <span class="font-medium text-slate-800">{{ $orangTua->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Anak yang Dipantau --}}
        <div class="md:col-span-2 rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-base font-bold text-navy-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                <span>Daftar Siswa yang Dipantau ({{ $orangTua->siswas->count() }} Anak)</span>
            </h3>

            <div class="space-y-3">
                @forelse($orangTua->siswas as $siswa)
                    @php
                        $badgeRelasi = match($siswa->pivot->hubungan) {
                            'ayah' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'ibu' => 'bg-pink-50 text-pink-700 border-pink-200',
                            default => 'bg-amber-50 text-amber-700 border-amber-200',
                        };
                    @endphp
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-navy-900 text-sm">{{ $siswa->nama_lengkap }}</h4>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $badgeRelasi }}">
                                    {{ ucfirst($siswa->pivot->hubungan) }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 mt-1 space-x-2">
                                <span>NIS: <strong class="text-slate-700 font-mono">{{ $siswa->nis }}</strong></span>
                                <span>·</span>
                                <span>Kelas: <strong class="text-slate-700">{{ $siswa->kelas?->nama_kelas ?? 'Belum ada' }}</strong></span>
                                <span>·</span>
                                <span>Wali Kelas: <strong class="text-slate-700">{{ $siswa->kelas?->waliKelas?->nama_lengkap ?? '-' }}</strong></span>
                            </div>
                        </div>
                        <a href="{{ route('admin.siswa.show', $siswa) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 transition shrink-0">
                            Lihat Profil Siswa
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        Belum ada siswa yang ditautkan ke akun orang tua ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
