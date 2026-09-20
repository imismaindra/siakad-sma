@extends('layouts.admin')

@section('title', 'Edit Jadwal Pelajaran')

@section('breadcrumb-parent', 'Jadwal Pelajaran')
@section('breadcrumb-current', 'Edit')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Ubah jadwal</p>
            <h1 class="page-title font-display !text-3xl mt-3">Edit jadwal.</h1>
            <p class="page-subtitle">{{ $jadwal->mataPelajaran->nama }}, kelas {{ $jadwal->kelas->nama_kelas }}. Bentrok guru dicek otomatis.</p>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">
            Kembali
        </a>
    </div>

    {{-- Form island --}}
    <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="rounded-[calc(2rem-0.5rem)] p-6 sm:p-8">
            <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST" data-loading class="space-y-7" novalidate>
                @csrf
                @method('PUT')

                {{-- Kelas dan Mapel --}}
                <fieldset>
                    <legend class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Kelas dan mapel</legend>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kelas_id" class="form-label">Kelas penerima</label>
                            <select id="kelas_id" name="kelas_id" required class="form-select @error('kelas_id') error @enderror">
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id', $jadwal->kelas_id) == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mata_pelajaran_id" class="form-label">Mata pelajaran</label>
                            <select id="mata_pelajaran_id" name="mata_pelajaran_id" required class="form-select @error('mata_pelajaran_id') error @enderror">
                                @foreach($mataPelajarans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id', $jadwal->mata_pelajaran_id) == $mp->id ? 'selected' : '' }}>
                                        {{ $mp->nama }} ({{ $mp->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- Guru --}}
                <fieldset>
                    <legend class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Guru pengampu</legend>
                    <div class="mt-3">
                        <select id="guru_id" name="guru_id" required class="form-select @error('guru_id') error @enderror">
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}" {{ old('guru_id', $jadwal->guru_id) == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama_lengkap }} (NIP {{ $g->nip ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </fieldset>

                {{-- Hari --}}
                <fieldset>
                    <legend class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Hari</legend>
                    <div class="mt-3 grid grid-cols-3 sm:grid-cols-6 gap-2" role="radiogroup" aria-label="Hari">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                            <label class="cursor-pointer">
                                <input type="radio" name="hari" value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'checked' : '' }} required class="peer sr-only">
                                <span class="block text-center rounded-full px-3 py-2.5 text-[13px] font-bold text-slate-500 bg-slate-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] peer-checked:bg-navy-800 peer-checked:text-white peer-checked:shadow-[0_8px_20px_-8px_rgba(23,37,84,0.6)] hover:bg-slate-200 peer-checked:hover:bg-navy-800">{{ $h }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('hari')
                        <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- Waktu --}}
                <fieldset>
                    <legend class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Waktu</legend>
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="jam_mulai" class="form-label">Jam mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai"
                                value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" required
                                class="form-input tabular-nums @error('jam_mulai') error @enderror">
                            @error('jam_mulai')
                                <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="jam_selesai" class="form-label">Jam selesai</label>
                            <input type="time" id="jam_selesai" name="jam_selesai"
                                value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" required
                                class="form-input tabular-nums @error('jam_selesai') error @enderror">
                            @error('jam_selesai')
                                <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="urutan_jam" class="form-label">Jam ke</label>
                            <input type="number" id="urutan_jam" name="urutan_jam" min="1" value="{{ old('urutan_jam', $jadwal->urutan_jam) }}" required
                                class="form-input tabular-nums @error('urutan_jam') error @enderror">
                            @error('urutan_jam')
                                <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p id="durasi-hint" class="mt-2.5 text-[13px] font-semibold text-slate-500" aria-live="polite"></p>
                </fieldset>

                {{-- Ruangan --}}
                <fieldset>
                    <legend class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Ruangan <span class="normal-case font-semibold text-slate-400">(opsional)</span></legend>
                    <div class="mt-3">
                        <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan', $jadwal->ruangan) }}"
                            class="form-input @error('ruangan') error @enderror" placeholder="Contoh: Lab Biologi" maxlength="50">
                        @error('ruangan')
                            <p class="text-rose-700 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </fieldset>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center rounded-full px-5 py-3 text-sm font-semibold text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-navy-900">Batal</a>
                    <button type="submit" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-6 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                        Perbarui jadwal
                        <span class="w-9 h-9 rounded-full bg-gold-500 text-navy-900 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    var mulai = document.getElementById('jam_mulai');
    var selesai = document.getElementById('jam_selesai');
    var hint = document.getElementById('durasi-hint');
    if (!mulai || !selesai || !hint) return;
    function toMin(v) { var p = v.split(':'); return parseInt(p[0], 10) * 60 + parseInt(p[1], 10); }
    function update() {
        if (!mulai.value || !selesai.value) { hint.textContent = ''; return; }
        var d = toMin(selesai.value) - toMin(mulai.value);
        hint.classList.remove('text-slate-500', 'text-rose-700', 'text-emerald-700');
        if (d <= 0) {
            hint.classList.add('text-rose-700');
            hint.textContent = 'Jam selesai harus setelah jam mulai.';
        } else {
            hint.classList.add('text-emerald-700');
            hint.textContent = 'Durasi ' + d + ' menit.';
        }
    }
    mulai.addEventListener('change', update);
    selesai.addEventListener('change', update);
    update();
})();
</script>
@endpush
@endsection
