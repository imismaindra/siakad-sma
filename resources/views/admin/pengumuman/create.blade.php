@extends('layouts.admin')

@section('title', 'Buat Pengumuman Baru')

@section('breadcrumb-parent', 'Pengumuman')
@section('breadcrumb-current', 'Buat Pengumuman')

@section('admin-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up" x-data="{ 
    targetAudiens: '{{ old('target', 'semua') }}',
    imagePreview: null,
    previewImage(e) {
        const file = e.target.files[0];
        if (file) {
            this.imagePreview = URL.createObjectURL(file);
        }
    }
}">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title font-display !text-2xl">Buat Pengumuman Baru.</h1>
            <p class="page-subtitle">Terbitkan informasi resmi, lampirkan berkas/gambar, dan kirimkan notifikasi serta email ke penerima.</p>
        </div>
        <a href="{{ route('admin.pengumuman.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Form Island --}}
    <div class="rounded-[2rem] bg-white p-6 sm:p-8 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-2">Judul Pengumuman <span class="text-rose-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Pengumuman Pelaksanaan Penilaian Akhir Semester (PAS) Ganjil"
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
                    <p class="text-[11px] text-slate-500 mt-1">Notifikasi akan otomatis dikirimkan ke target yang dipilih.</p>
                </div>

                {{-- Dynamic Kelas Dropdown --}}
                <div x-show="targetAudiens === 'kelas'" x-cloak x-transition>
                    <label for="kelas_id" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-2">Pilih Kelas Sasaran <span class="text-rose-500">*</span></label>
                    <select id="kelas_id" name="kelas_id"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500 @error('kelas_id') border-rose-300 @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
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
                <textarea id="konten" name="konten" rows="8" required placeholder="Tuliskan isi pengumuman secara lengkap, jelas, dan santun..."
                    class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-sm font-medium text-navy-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-gold-500 leading-relaxed @error('konten') border-rose-300 ring-1 ring-rose-300 @enderror">{{ old('konten') }}</textarea>
                @error('konten')
                    <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Media: Gambar & Dokumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Upload Gambar / Foto Banner --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 space-y-3">
                    <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider">
                        Foto / Banner Pengumuman (Opsional)
                    </label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp" @change="previewImage($event)"
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-navy-800 file:text-white hover:file:bg-navy-900 cursor-pointer" />
                    <p class="text-[11px] text-slate-400">Format: JPG, PNG, WEBP (Maks. 5 MB)</p>

                    <template x-if="imagePreview">
                        <div class="mt-2 rounded-xl overflow-hidden border border-slate-200 bg-white p-1">
                            <img :src="imagePreview" alt="Pratinjau Gambar" class="w-full max-h-48 object-cover rounded-lg" />
                        </div>
                    </template>
                    @error('gambar')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Dokumen Lampiran --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 space-y-3">
                    <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider">
                        Lampiran Dokumen / Surat Edaran (Opsional)
                    </label>
                    <input type="file" name="dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-navy-800 file:text-white hover:file:bg-navy-900 cursor-pointer" />
                    <p class="text-[11px] text-slate-400">Format: PDF, Word, Excel, ZIP (Maks. 10 MB)</p>
                    @error('dokumen')
                        <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pengaturan Status & Notifikasi Email --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', true) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500" />
                            <label for="is_aktif" class="text-xs text-slate-700 cursor-pointer select-none">
                                <span class="font-semibold text-navy-900 block">Langsung Tayangkan</span>
                                Pengumuman dapat langsung dilihat oleh target sasaran saat disimpan.
                            </label>
                        </div>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="kirim_email" name="kirim_email" value="1" {{ old('kirim_email', true) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500" />
                            <label for="kirim_email" class="text-xs text-slate-700 cursor-pointer select-none">
                                <span class="font-semibold text-navy-900 block">Kirimkan Notifikasi ke Email Penerima</span>
                                Kirimkan ringkasan warta dan lampiran berkas langsung ke email seluruh pengguna target.
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="published_at" class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Jadwal Tanggal & Jam Terbit (Opsional)</label>
                        <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-gold-500 focus:ring-gold-500" />
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.pengumuman.index') }}" class="rounded-full bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="group inline-flex items-center gap-2 rounded-full bg-navy-800 px-6 py-2.5 text-xs font-semibold text-white hover:bg-navy-900 transition-colors shadow-sm cursor-pointer">
                    <span>Terbitkan Pengumuman</span>
                    <svg class="h-4 w-4 text-gold-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
