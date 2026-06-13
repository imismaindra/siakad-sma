@extends('layouts.admin')

@section('title', 'Profil Siswa ' . $siswa->nama_lengkap)

@section('breadcrumb-parent', 'Data Siswa')
@section('breadcrumb-current', 'Profil')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Profil Siswa</h1>
            <p class="page-subtitle">Rincian profil pribadi, data keluarga, rekap absensi, dan pencapaian nilai akademis.</p>
        </div>
        <div class="flex gap-2">
            @if($siswa->kelas)
                <a href="{{ route('admin.nilai.rapor.siswa', $siswa) }}" target="_blank" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                    Unduh Rapor PDF
                </a>
            @endif
            <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn-gold">
                Edit Profil
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Profile Banner Card --}}
    <div class="card bg-gradient-navy text-white overflow-hidden relative">
        <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
        <div class="card-body p-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                @if($siswa->foto)
                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" 
                        class="w-24 h-24 rounded-full object-cover border-4 border-white/20 shadow-lg">
                @else
                    <div class="w-24 h-24 rounded-full border-4 border-white/20 shadow-lg flex items-center justify-center bg-gradient-gold text-white text-3xl font-extrabold font-display">
                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                    </div>
                @endif
                
                <div class="space-y-2">
                    <div class="flex flex-col md:flex-row items-center gap-3">
                        <h2 class="text-2xl font-extrabold font-display leading-tight">{{ $siswa->nama_lengkap }}</h2>
                        
                        @if($siswa->status == 'aktif')
                            <span class="badge badge-success bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Aktif</span>
                        @elseif($siswa->status == 'lulus')
                            <span class="badge badge-navy bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Lulus</span>
                        @elseif($siswa->status == 'pindah')
                            <span class="badge badge-warning bg-amber-500/20 text-amber-300 border border-amber-500/30">Pindah</span>
                        @else
                            <span class="badge badge-danger bg-rose-500/20 text-rose-300 border border-rose-500/30">Keluar</span>
                        @endif
                    </div>
                    
                    <p class="text-white/75 font-semibold font-mono text-sm">
                        NIS: {{ $siswa->nis }} / NISN: {{ $siswa->nisn ?? '—' }}
                    </p>
                    <p class="text-white/75 text-sm">
                        @if($siswa->kelas)
                            Kelas: <span class="font-bold text-amber-400 font-mono">{{ $siswa->kelas->nama_kelas }}</span> 
                            <span class="text-xs text-white/50">({{ $siswa->kelas->tahunAjaran->nama_lengkap }})</span>
                        @else
                            <span class="text-white/50 italic">Belum terdaftar di kelas manapun</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left: Biodata Card --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Biodata Pribadi --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Biodata Pribadi</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jenis Kelamin</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                            {{ $siswa->tempat_lahir ?? '—' }}{{ $siswa->tanggal_lahir ? ', ' . $siswa->tanggal_lahir->locale('id')->isoFormat('D MMMM YYYY') : '' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">No. Telepon Siswa</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $siswa->no_telepon ?? '—' }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Alamat Lengkap</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block leading-relaxed">{{ $siswa->alamat ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Biodata Orang Tua --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Orang Tua / Wali</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Nama Orang Tua / Wali</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $siswa->nama_ortu ?? '—' }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">No. Telepon Orang Tua / Wali</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $siswa->no_telepon_ortu ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Tabs --}}
        <div class="lg:col-span-8">
            <div class="card">
                {{-- Tabs --}}
                <div class="border-b border-slate-100 flex">
                    <button onclick="switchTab('nilai')" class="tab-btn px-6 py-4 font-display font-bold text-xs uppercase tracking-wider border-b-2 border-navy-500 text-navy-500 transition-all">
                        Nilai Akademik
                    </button>
                    <button onclick="switchTab('absensi')" class="tab-btn px-6 py-4 font-display font-bold text-xs uppercase tracking-wider border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                        Riwayat Kehadiran
                    </button>
                </div>

                {{-- Tab: Nilai --}}
                <div id="tab-nilai" class="tab-content">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai Angka</th>
                                    <th>Nilai Akhir</th>
                                    <th>Predikat</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->nilais as $nilai)
                                    <tr>
                                        <td class="font-bold text-slate-800">{{ $nilai->mataPelajaran->nama }}</td>
                                        <td class="font-semibold text-slate-600 font-mono">
                                            UH: {{ $nilai->nilai_harian ?? '—' }} | UTS: {{ $nilai->nilai_uts ?? '—' }} | UAS: {{ $nilai->nilai_uas ?? '—' }}
                                        </td>
                                        <td class="font-bold text-navy-600 font-mono text-base">{{ $nilai->nilai_akhir ?? '—' }}</td>
                                        <td>
                                            @if($nilai->predikat)
                                                <span class="grade-{{ strtolower($nilai->predikat) }} font-bold text-sm">
                                                    {{ $nilai->predikat }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
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
                                                <span class="badge badge-warning">Draft (Belum Final)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data nilai terekam pada tahun ajaran ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab: Absensi --}}
                <div id="tab-absensi" class="tab-content hidden">
                    
                    {{-- Rekap Box --}}
                    @if($rekapAbsensi)
                        <div class="grid grid-cols-4 gap-4 p-6 bg-slate-50 border-b border-slate-100 text-center">
                            <div>
                                <span class="stats-number text-emerald-500">{{ $rekapAbsensi['hadir'] }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mt-1">Hadir</span>
                            </div>
                            <div>
                                <span class="stats-number text-blue-500">{{ $rekapAbsensi['sakit'] }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mt-1">Sakit</span>
                            </div>
                            <div>
                                <span class="stats-number text-amber-500">{{ $rekapAbsensi['izin'] }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mt-1">Izin</span>
                            </div>
                            <div>
                                <span class="stats-number text-rose-500">{{ $rekapAbsensi['alpa'] }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mt-1">Alpa</span>
                            </div>
                        </div>
                    @endif

                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status Absensi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->detailAbsensis->sortByDesc(fn($da) => $da->absensi->tanggal) as $da)
                                    <tr>
                                        <td class="font-bold text-slate-800">
                                            {{ $da->absensi->tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                        </td>
                                        <td class="font-semibold text-navy-500">
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
                                        <td colspan="4" class="text-center py-8 text-slate-400">Belum ada riwayat absensi mata pelajaran tercatat.</td>
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
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-navy-500', 'text-navy-500');
            el.classList.add('border-transparent', 'text-slate-400', 'hover:text-slate-600');
        });
        
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');
        event.currentTarget.classList.remove('border-transparent', 'text-slate-400', 'hover:text-slate-600');
        event.currentTarget.classList.add('border-navy-500', 'text-navy-500');
    }
</script>
@endpush
@endsection
