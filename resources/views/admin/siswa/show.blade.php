@extends('layouts.admin')

@section('title', 'Profil Siswa ' . $siswa->nama_lengkap)

@section('breadcrumb-parent', 'Data Siswa')
@section('breadcrumb-current', 'Profil')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Profil siswa</p>
            <h1 class="page-title font-display !text-3xl mt-3">{{ $siswa->nama_lengkap }}.</h1>
            <p class="page-subtitle">Biodata, keluarga, kehadiran, dan nilai dalam satu layar.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($siswa->kelas)
                <a href="{{ route('admin.nilai.rapor.siswa', $siswa) }}" target="_blank" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                    Unduh rapor
                    <span class="w-8 h-8 rounded-full bg-gold-500 text-navy-900 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    </span>
                </a>
            @endif
            <a href="{{ route('admin.siswa.edit', $siswa) }}" class="inline-flex items-center rounded-full bg-gold-500 px-5 py-2.5 text-sm font-bold text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-600 active:scale-[0.98]">
                Edit profil
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Identity + biodata island --}}
        <div class="lg:col-span-4 rounded-[2rem] bg-navy-800 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                <div class="flex items-center gap-4">
                    @if($siswa->foto)
                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama_lengkap }}"
                            class="w-20 h-20 rounded-full object-cover ring-2 ring-white/20 shrink-0">
                    @else
                        <div class="w-20 h-20 rounded-full ring-2 ring-gold-500/50 bg-gold-500 text-navy-900 text-2xl font-extrabold font-display flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        @if($siswa->status == 'aktif')
                            <span class="badge bg-emerald-500/15 text-emerald-300 ring-1 ring-emerald-500/30">Aktif</span>
                        @elseif($siswa->status == 'lulus')
                            <span class="badge bg-white/10 text-slate-200 ring-1 ring-white/20">Lulus</span>
                        @elseif($siswa->status == 'pindah')
                            <span class="badge bg-gold-500/15 text-gold-200 ring-1 ring-gold-500/30">Pindah</span>
                        @else
                            <span class="badge bg-rose-500/15 text-rose-300 ring-1 ring-rose-500/30">Keluar</span>
                        @endif
                        <p class="mt-2 font-mono text-xs text-slate-400 tabular-nums">NIS {{ $siswa->nis }} / NISN {{ $siswa->nisn ?? '-' }}</p>
                    </div>
                </div>

                <p class="mt-5 text-sm text-slate-300">
                    @if($siswa->kelas)
                        Kelas <span class="font-bold text-gold-200">{{ $siswa->kelas->nama_kelas }}</span>
                        <span class="block text-xs text-slate-400 mt-0.5">{{ $siswa->kelas->tahunAjaran->nama_lengkap }}</span>
                    @else
                        <span class="text-slate-400">Belum terdaftar di kelas manapun.</span>
                    @endif
                </p>

                <dl class="mt-6 divide-y divide-white/10 border-y border-white/10">
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Jenis kelamin</dt>
                        <dd class="mt-1 font-bold text-white text-sm">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Tempat, tanggal lahir</dt>
                        <dd class="mt-1 font-bold text-white text-sm">{{ $siswa->tempat_lahir ?? '-' }}{{ $siswa->tanggal_lahir ? ', ' . $siswa->tanggal_lahir->locale('id')->isoFormat('D MMMM YYYY') : '' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Telepon</dt>
                        <dd class="mt-1 font-bold text-white text-sm tabular-nums">{{ $siswa->no_telepon ?? '-' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Alamat</dt>
                        <dd class="mt-1 font-bold text-white text-sm leading-relaxed">{{ $siswa->alamat ?? '-' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Orang tua / wali</dt>
                        <dd class="mt-1 font-bold text-white text-sm">{{ $siswa->nama_ortu ?? '-' }}</dd>
                        <dd class="mt-0.5 text-xs text-slate-400 tabular-nums">{{ $siswa->no_telepon_ortu ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Tabs island --}}
        <div class="lg:col-span-8 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-3 sm:p-4">
                <div class="inline-flex rounded-full bg-slate-100 p-1 gap-1" role="tablist">
                    <button onclick="switchTab('nilai', this)" role="tab" class="tab-btn rounded-full px-5 py-2 text-[13px] font-bold bg-navy-800 text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                        Nilai ({{ $siswa->nilais->count() }})
                    </button>
                    <button onclick="switchTab('absensi', this)" role="tab" class="tab-btn rounded-full px-5 py-2 text-[13px] font-bold text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-navy-800">
                        Kehadiran
                    </button>
                </div>

                {{-- Nilai --}}
                <div id="tab-nilai" class="tab-content mt-3">
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Harian / UTS / UAS</th>
                                    <th>Akhir</th>
                                    <th>Predikat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->nilais as $nilai)
                                    <tr>
                                        <td class="font-bold text-navy-900">{{ $nilai->mataPelajaran->nama }}</td>
                                        <td class="font-mono text-slate-500 text-xs tabular-nums whitespace-nowrap">
                                            {{ $nilai->rata_rata_harian ?? '-' }} / {{ $nilai->nilai_uts ?? '-' }} / {{ $nilai->nilai_uas ?? '-' }}
                                        </td>
                                        <td class="font-mono font-bold text-base tabular-nums grade-{{ strtolower($nilai->predikat ?? 'e') }}">{{ $nilai->nilai_akhir ?? '-' }}</td>
                                        <td>
                                            @if($nilai->predikat)
                                                <span class="grade-{{ strtolower($nilai->predikat) }} font-bold text-sm">
                                                    {{ $nilai->predikat }}
                                                </span>
                                            @else
                                                <span class="text-slate-500">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($nilai->is_final)
                                                @if(($nilai->nilai_akhir ?? 0) >= ($nilai->mataPelajaran->kkm ?? 75))
                                                    <span class="badge badge-success">Tuntas</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak Tuntas</span>
                                                @endif
                                            @else
                                                <span class="badge badge-warning">Draft</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <p class="font-bold text-navy-900">Belum ada nilai terekam.</p>
                                            <p class="text-sm text-slate-500 mt-1">Nilai muncul setelah guru menginput.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Absensi --}}
                <div id="tab-absensi" class="tab-content hidden mt-3">
                    @if($rekapAbsensi)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 px-1 pb-4">
                            <div class="rounded-2xl bg-emerald-50 ring-1 ring-emerald-100 px-4 py-3 text-center">
                                <p class="font-display font-extrabold text-2xl text-emerald-700 tabular-nums">{{ $rekapAbsensi['hadir'] }}</p>
                                <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-emerald-700/70 mt-0.5">Hadir</p>
                            </div>
                            <div class="rounded-2xl bg-navy-800 px-4 py-3 text-center">
                                <p class="font-display font-extrabold text-2xl text-white tabular-nums">{{ $rekapAbsensi['sakit'] }}</p>
                                <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-400 mt-0.5">Sakit</p>
                            </div>
                            <div class="rounded-2xl bg-gold-100 px-4 py-3 text-center ring-1 ring-gold-200">
                                <p class="font-display font-extrabold text-2xl text-gold-700 tabular-nums">{{ $rekapAbsensi['izin'] }}</p>
                                <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-gold-700/70 mt-0.5">Izin</p>
                            </div>
                            <div class="rounded-2xl bg-rose-50 ring-1 ring-rose-100 px-4 py-3 text-center">
                                <p class="font-display font-extrabold text-2xl text-rose-700 tabular-nums">{{ $rekapAbsensi['alpa'] }}</p>
                                <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-rose-700/70 mt-0.5">Alpa</p>
                            </div>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->detailAbsensis->sortByDesc(fn($da) => $da->absensi->tanggal) as $da)
                                    <tr>
                                        <td class="font-bold text-navy-900 whitespace-nowrap">
                                            {{ $da->absensi->tanggal->locale('id')->isoFormat('D MMM YYYY') }}
                                        </td>
                                        <td class="font-semibold text-slate-500">
                                            {{ $da->absensi->mataPelajaran?->nama ?? 'Mapel Terhapus' }}
                                        </td>
                                        <td>
                                            <span class="badge absensi-status-{{ $da->status }}">
                                                {{ ucfirst($da->status) }}
                                            </span>
                                        </td>
                                        <td class="text-xs text-slate-500">
                                            {{ $da->keterangan ?? 'Tanpa keterangan' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-12">
                                            <p class="font-bold text-navy-900">Belum ada riwayat absensi.</p>
                                            <p class="text-sm text-slate-500 mt-1">Kehadiran tercatat setelah guru mengisi absensi.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function switchTab(tabName, btn) {
        document.querySelectorAll('.tab-content').forEach(function (el) { el.classList.add('hidden'); });
        document.querySelectorAll('.tab-btn').forEach(function (el) {
            el.classList.remove('bg-navy-800', 'text-white');
            el.classList.add('text-slate-500');
        });
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        btn.classList.remove('text-slate-500');
        btn.classList.add('bg-navy-800', 'text-white');
    }
</script>
@endpush
@endsection
