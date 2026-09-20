@extends('layouts.admin')

@section('title', 'Bobot Nilai ' . $mataPelajaran->nama)

@section('breadcrumb-parent', 'Mata Pelajaran')
@section('breadcrumb-current', 'Bobot Nilai')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Bobot nilai</p>
            <h1 class="page-title font-display !text-3xl mt-3">{{ $mataPelajaran->nama }}.</h1>
            <p class="page-subtitle">Nilai akhir = Harian x persen + UTS x persen + UAS x persen. Total wajib 100%.</p>
        </div>
        <a href="{{ route('admin.mata-pelajaran.index') }}" class="inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">
            Kembali
        </a>
    </div>

    {{-- Error total --}}
    @error('bobot')
        <div class="flash-error !rounded-2xl animate-fade-in-up" data-flash>
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $message }}
        </div>
    @enderror

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Registered weights --}}
        <div class="lg:col-span-7 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-500">Bobot terdaftar</p>
                    <span class="badge badge-navy">{{ $bobotNilais->count() }} konfigurasi</span>
                </div>

                <ul class="mt-5 space-y-4">
                    @forelse($bobotNilais as $bobot)
                        @php $total = $bobot->bobot_harian + $bobot->bobot_uts + $bobot->bobot_uas; @endphp
                        <li class="rounded-2xl bg-slate-50 ring-1 ring-black/5 p-5">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-bold text-navy-900 text-[15px]">
                                    {{ $bobot->tahunAjaran->nama_lengkap }}
                                    @if($bobot->kelas)
                                        <span class="badge badge-navy font-mono ml-2">{{ $bobot->kelas->nama_kelas }}</span>
                                    @else
                                        <span class="badge badge-gray ml-2">Semua kelas</span>
                                    @endif
                                </p>
                                <span class="badge {{ $total == 100 ? 'badge-success' : 'badge-danger' }} font-mono tabular-nums">Total {{ $total }}%</span>
                            </div>
                            <div class="mt-4 h-3 rounded-full overflow-hidden flex" aria-hidden="true">
                                <div class="bg-navy-800 h-full" style="width: {{ $bobot->bobot_harian }}%"></div>
                                <div class="bg-gold-500 h-full" style="width: {{ $bobot->bobot_uts }}%"></div>
                                <div class="bg-emerald-500 h-full" style="width: {{ $bobot->bobot_uas }}%"></div>
                            </div>
                            <div class="mt-3 grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-xl bg-white ring-1 ring-black/5 px-2 py-2">
                                    <p class="font-mono font-bold text-navy-900 tabular-nums">{{ $bobot->bobot_harian }}%</p>
                                    <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-500 mt-0.5">Harian</p>
                                </div>
                                <div class="rounded-xl bg-white ring-1 ring-black/5 px-2 py-2">
                                    <p class="font-mono font-bold text-gold-700 tabular-nums">{{ $bobot->bobot_uts }}%</p>
                                    <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-500 mt-0.5">UTS</p>
                                </div>
                                <div class="rounded-xl bg-white ring-1 ring-black/5 px-2 py-2">
                                    <p class="font-mono font-bold text-emerald-700 tabular-nums">{{ $bobot->bobot_uas }}%</p>
                                    <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-500 mt-0.5">UAS</p>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-12">
                            <p class="font-bold text-navy-900">Belum ada bobot terdaftar.</p>
                            <p class="text-sm text-slate-500 mt-1">Isi formulir di samping, total harus 100%.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Form island --}}
        <div class="lg:col-span-5 rounded-[2rem] bg-navy-800 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)] lg:sticky lg:top-24 self-start">
            <form action="{{ route('admin.mata-pelajaran.bobot.store', $mataPelajaran) }}" method="POST" class="rounded-[calc(2rem-0.5rem)] p-6 sm:p-7 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                @csrf
                <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Atur bobot baru</p>

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="tahun_ajaran_id" class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 mb-2">Tahun ajaran</label>
                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" required class="w-full rounded-2xl bg-white/10 border border-white/15 px-4 py-3 text-sm font-semibold text-white outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:border-gold-500 focus:ring-4 focus:ring-gold-500/15 [&>option]:text-navy-900">
                            <option value="">Pilih tahun ajaran</option>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id || $ta->is_aktif ? 'selected' : '' }}>
                                    {{ $ta->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <p class="text-rose-300 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelas_id" class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-400 mb-2">Berlaku untuk</label>
                        <select id="kelas_id" name="kelas_id" class="w-full rounded-2xl bg-white/10 border border-white/15 px-4 py-3 text-sm font-semibold text-white outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:border-gold-500 focus:ring-4 focus:ring-gold-500/15 [&>option]:text-navy-900">
                            <option value="">Semua kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->nama }} ({{ $k->tahunAjaran->nama }})
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-rose-300 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="bobot_harian" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2">Harian</label>
                            <div class="relative">
                                <input type="number" id="bobot_harian" name="bobot_harian" min="0" max="100" value="{{ old('bobot_harian', 40) }}" required
                                    class="bobot-input w-full rounded-2xl bg-white/10 border border-white/15 pl-4 pr-9 py-3 text-center font-mono font-bold text-white tabular-nums outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:border-gold-500 focus:ring-4 focus:ring-gold-500/15">
                                <span class="absolute right-3 inset-y-0 flex items-center text-xs text-slate-400">%</span>
                            </div>
                        </div>
                        <div>
                            <label for="bobot_uts" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2">UTS</label>
                            <div class="relative">
                                <input type="number" id="bobot_uts" name="bobot_uts" min="0" max="100" value="{{ old('bobot_uts', 30) }}" required
                                    class="bobot-input w-full rounded-2xl bg-white/10 border border-white/15 pl-4 pr-9 py-3 text-center font-mono font-bold text-white tabular-nums outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:border-gold-500 focus:ring-4 focus:ring-gold-500/15">
                                <span class="absolute right-3 inset-y-0 flex items-center text-xs text-slate-400">%</span>
                            </div>
                        </div>
                        <div>
                            <label for="bobot_uas" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-slate-400 mb-2">UAS</label>
                            <div class="relative">
                                <input type="number" id="bobot_uas" name="bobot_uas" min="0" max="100" value="{{ old('bobot_uas', 30) }}" required
                                    class="bobot-input w-full rounded-2xl bg-white/10 border border-white/15 pl-4 pr-9 py-3 text-center font-mono font-bold text-white tabular-nums outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:border-gold-500 focus:ring-4 focus:ring-gold-500/15">
                                <span class="absolute right-3 inset-y-0 flex items-center text-xs text-slate-400">%</span>
                            </div>
                        </div>
                    </div>
                    @error('bobot_harian')<p class="text-rose-300 text-xs font-semibold">{{ $message }}</p>@enderror
                    @error('bobot_uts')<p class="text-rose-300 text-xs font-semibold">{{ $message }}</p>@enderror
                    @error('bobot_uas')<p class="text-rose-300 text-xs font-semibold">{{ $message }}</p>@enderror

                    {{-- Live total --}}
                    <div class="rounded-2xl bg-white/10 ring-1 ring-white/15 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Total</span>
                            <span id="bobot-total" class="font-mono font-extrabold text-2xl text-white tabular-nums">100%</span>
                        </div>
                        <div class="mt-3 h-2.5 rounded-full overflow-hidden flex" aria-hidden="true">
                            <div id="bar-harian" class="bg-white h-full" style="width: 40%"></div>
                            <div id="bar-uts" class="bg-gold-500 h-full" style="width: 30%"></div>
                            <div id="bar-uas" class="bg-emerald-400 h-full" style="width: 30%"></div>
                        </div>
                        <p id="bobot-state" class="mt-3 text-[13px] font-bold text-emerald-300">Pas 100%. Siap simpan.</p>
                    </div>

                    <button type="submit" class="group w-full inline-flex items-center justify-between gap-3 rounded-full bg-gold-500 py-2 pl-7 pr-2 text-sm font-bold text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-600 active:scale-[0.98]">
                        Simpan bobot
                        <span class="w-10 h-10 rounded-full bg-navy-900 text-gold-200 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1">
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
    var h = document.getElementById('bobot_harian');
    var u = document.getElementById('bobot_uts');
    var a = document.getElementById('bobot_uas');
    var total = document.getElementById('bobot-total');
    var state = document.getElementById('bobot-state');
    var bh = document.getElementById('bar-harian');
    var bu = document.getElementById('bar-uts');
    var ba = document.getElementById('bar-uas');
    function num(el) { var v = parseFloat(el.value); return isNaN(v) ? 0 : Math.max(0, v); }
    function update() {
        var hv = num(h), uv = num(u), av = num(a);
        var t = hv + uv + av;
        total.textContent = (Math.round(t * 10) / 10) + '%';
        bh.style.width = hv + '%'; bu.style.width = uv + '%'; ba.style.width = av + '%';
        total.classList.remove('text-white', 'text-emerald-300', 'text-rose-300');
        state.classList.remove('text-emerald-300', 'text-rose-300');
        if (t === 100) {
            total.classList.add('text-white'); state.classList.add('text-emerald-300');
            state.textContent = 'Pas 100%. Siap simpan.';
        } else if (t < 100) {
            total.classList.add('text-rose-300'); state.classList.add('text-rose-300');
            state.textContent = 'Kurang ' + (Math.round((100 - t) * 10) / 10) + '%. Tambah lagi.';
        } else {
            total.classList.add('text-rose-300'); state.classList.add('text-rose-300');
            state.textContent = 'Lebih ' + (Math.round((t - 100) * 10) / 10) + '%. Kurangi lagi.';
        }
    }
    [h, u, a].forEach(function (el) { el.addEventListener('input', update); });
    update();
})();
</script>
@endpush
@endsection
