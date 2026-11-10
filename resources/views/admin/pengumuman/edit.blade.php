@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@section('breadcrumb-parent', 'Pengumuman')
@section('breadcrumb-current', 'Edit Pengumuman')

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up" x-data="{ targetAudiens: '{{ old('target', $pengumuman->target) }}' }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title font-display !text-2xl">Perbarui Pengumuman.</h1>
            <p class="page-subtitle">Ubah materi, sasaran audiens, atau status publikasi pengumuman ini.</p>
        </div>
        <a href="{{ route('admin.pengumuman.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Form Island --}}
    <div class="rounded-[2rem] bg-white p-6 sm:p-8 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <form action="{{ route('admin.pengumuman.update', $pengumuman) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-2">Judul Pengumuman <span class="text-rose-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required
                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-navy-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-gold-500 @error('judul') border-rose-300 ring-1 ring-rose-300 @enderror" />
                @error('judul')
                    <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Target Audiens --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="target" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-2">Target Penerima <span class="text-rose-500">*</span></label>
                    <select id="target" name="target" x-model="targetAudiens" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                        <option value="semua">Semua (Guru, Siswa, Orang Tua)</option>
                        <option value="guru">Khusus Guru / Pendidik</option>
                        <option value="siswa">Seluruh Siswa</option>
                        <option value="orang_tua">Khusus Orang Tua / Wali</option>
                        <option value="kelas">Siswa Kelas Tertentu</option>
                    </select>
                </div>

                {{-- Dynamic Kelas Dropdown --}}
                <div x-show="targetAudiens === 'kelas'" x-cloak x-transition>
                    <label for="kelas_id" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-2">Pilih Kelas Sasaran <span class="text-rose-500">*</span></label>
                    <select id="kelas_id" name="kelas_id"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500 @error('kelas_id') border-rose-300 @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $pengumuman->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} ({{ $kelas->jurusan?->nama ?? 'Umum' }})
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Konten Pengumuman --}}
            <div>
                <label for="konten" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-2">Isi Pengumuman <span class="text-rose-500">*</span></label>
                <textarea id="konten" name="konten" rows="8" required
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-sm font-medium text-navy-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-gold-500 leading-relaxed @error('konten') border-rose-300 ring-1 ring-rose-300 @enderror">{{ old('konten', $pengumuman->konten) }}</textarea>
                @error('konten')
                    <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pengaturan Status & Tanggal --}}
            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', $pengumuman->is_aktif) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500" />
                    <label for="is_aktif" class="text-xs font-medium text-slate-700 cursor-pointer select-none">
                        <span class="font-semibold text-navy-900 block">Status Tayang Aktif</span>
                        Pengumuman dapat dilihat oleh pengguna yang menjadi target sasaran.
                    </label>
                </div>

                <div>
                    <label for="published_at" class="block text-[11px] font-semibold text-slate-600 mb-1">Tanggal & Jam Terbit</label>
                    <input type="datetime-local" id="published_at" name="published_at"
                        value="{{ old('published_at', $pengumuman->published_at ? $pengumuman->published_at->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-lg border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-gold-500 focus:ring-gold-500" />
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.pengumuman.index') }}" class="rounded-full bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="group inline-flex items-center gap-2 rounded-full bg-navy-800 px-6 py-2.5 text-xs font-semibold text-white hover:bg-navy-900 transition-colors shadow-sm cursor-pointer">
                    <span>Simpan Perubahan</span>
                    <svg class="h-4 w-4 text-gold-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
