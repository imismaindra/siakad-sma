@extends('layouts.admin')

@section('title', 'Tambah Akun Orang Tua')

@section('breadcrumb-parent', 'Data Orang Tua')
@section('breadcrumb-current', 'Tambah Akun')

@section('admin-content')
<div class="max-w-4xl space-y-6 animate-fade-in-up" x-data="{
    anakList: [{ siswa_id: '', hubungan: 'ayah' }],
    addAnak() {
        this.anakList.push({ siswa_id: '', hubungan: 'ayah' });
    },
    removeAnak(index) {
        if (this.anakList.length > 1) {
            this.anakList.splice(index, 1);
        } else {
            this.anakList[0] = { siswa_id: '', hubungan: 'ayah' };
        }
    }
}">

    {{-- Header --}}
    <div>
        <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Portal Monitoring (F4)</p>
        <h1 class="page-title font-display !text-3xl mt-3">Tambah Akun Orang Tua.</h1>
        <p class="page-subtitle">Daftarkan akun wali murid baru dan tautkan ke siswa yang ingin dipantau.</p>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <p class="font-bold mb-1">Terjadi kesalahan pada input:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orang-tua.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Section 1: Kredensial Akun --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-base font-bold text-navy-900 border-b border-slate-100 pb-3">1. Kredensial Login Akun</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Hermawan Wijaya, S.E."
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email Login <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: hermawan@gmail.com"
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                </div>
            </div>
        </div>

        {{-- Section 2: Biodata Orang Tua --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-base font-bold text-navy-900 border-b border-slate-100 pb-3">2. Biodata & Kontak</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (Ayah / Wali)</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (Ibu / Wali)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890"
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="Contoh: Pegawai Swasta / Wiraswasta"
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Tempat Tinggal</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap..."
                    class="w-full rounded-xl border-slate-200 text-sm focus:border-gold-500 focus:ring-gold-500">{{ old('alamat') }}</textarea>
            </div>
        </div>

        {{-- Section 3: Tautan Siswa (Anak) --}}
        <div class="rounded-[2rem] bg-white p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-navy-900">3. Tautan Siswa yang Dipantau</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Satu akun orang tua dapat menautkan lebih dari satu siswa (misal memiliki 2 anak kandung).</p>
                </div>
                <button type="button" @click="addAnak()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-xl hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Siswa
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in anakList" :key="index">
                    <div class="flex flex-col sm:flex-row items-center gap-3 p-3 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex-1 w-full">
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Pilih Siswa</label>
                            <select :name="`anak[${index}][siswa_id]`" x-model="item.siswa_id" required class="w-full rounded-xl border-slate-200 text-xs focus:border-gold-500 focus:ring-gold-500">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswas as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (NIS: {{ $s->nis }} - {{ $s->kelas?->nama_kelas ?? 'Belum ada kelas' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-44">
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Hubungan</label>
                            <select :name="`anak[${index}][hubungan]`" x-model="item.hubungan" required class="w-full rounded-xl border-slate-200 text-xs focus:border-gold-500 focus:ring-gold-500">
                                <option value="ayah">Ayah</option>
                                <option value="ibu">Ibu</option>
                                <option value="wali">Wali</option>
                            </select>
                        </div>
                        <div class="pt-5 sm:pt-4">
                            <button type="button" @click="removeAnak(index)" class="p-2 text-slate-400 hover:text-red-600 rounded-xl hover:bg-red-50 transition" title="Hapus Tautan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.orang-tua.index') }}" class="px-5 py-2.5 rounded-full border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-7 py-2.5 rounded-full bg-navy-800 text-sm font-semibold text-white hover:bg-navy-900 transition shadow-sm">
                Simpan Akun Orang Tua
            </button>
        </div>
    </form>

</div>
@endsection
