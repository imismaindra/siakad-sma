@extends('layouts.orang-tua')

@section('title', 'Presensi & Kehadiran Anak')
@section('breadcrumb-parent', 'Monitoring Anak')
@section('breadcrumb-current', 'Presensi & Kehadiran')

@section('orang-tua-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Child Selector & Active Banner --}}
    @include('orang-tua._anak_selector')

    {{-- Header --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="page-title font-display">Presensi & Kehadiran Anak</h1>
            <p class="page-subtitle">Riwayat absensi dan statistik kehadiran kelas untuk <span class="font-bold text-slate-700">{{ $activeSiswa->nama_lengkap }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge badge-gold px-3 py-1.5 text-xs font-bold uppercase tracking-wider">
                Sinkron Realtime
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="stat-card navy col-span-2 md:col-span-1 flex flex-col justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Persentase Hadir</p>
            <p class="text-2xl font-black font-display text-navy-600 mt-2">{{ $stats['persen'] }}%</p>
            <p class="text-[11px] text-slate-400 mt-0.5">{{ $stats['total'] }} total sesi tercatat</p>
        </div>
        <div class="stat-card green flex flex-col justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-black font-display text-emerald-600 mt-2">{{ $stats['hadir'] }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">sesi pertemuan</p>
        </div>
        <div class="stat-card navy flex flex-col justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sakit</p>
            <p class="text-2xl font-black font-display text-blue-600 mt-2">{{ $stats['sakit'] }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">surat dokter/ortu</p>
        </div>
        <div class="stat-card gold flex flex-col justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Izin</p>
            <p class="text-2xl font-black font-display text-amber-600 mt-2">{{ $stats['izin'] }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">keterangan izin</p>
        </div>
        <div class="stat-card red flex flex-col justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alpa</p>
            <p class="text-2xl font-black font-display text-rose-600 mt-2">{{ $stats['alpa'] }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">tanpa keterangan</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card p-4 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
        <form method="GET" action="{{ route('orang-tua.absensi') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-48">
                <select name="status" class="form-input text-xs w-full">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="alpa" {{ request('status') === 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>
            <div class="w-full sm:w-48">
                <select name="bulan" class="form-input text-xs w-full">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn btn-primary text-xs w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['status', 'bulan']))
                    <a href="{{ route('orang-tua.absensi') }}" class="btn btn-secondary text-xs">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Riwayat Presensi --}}
    <div class="card border border-slate-200/80 shadow-xs rounded-2xl bg-white overflow-hidden">
        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Tanggal & Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <th class="text-center">Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatAbsensi as $index => $row)
                        <tr>
                            <td class="text-center font-mono text-xs text-slate-400">
                                {{ $riwayatAbsensi->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="font-bold text-xs text-slate-800">
                                    {{ $row->absensi?->tanggal ? \Carbon\Carbon::parse($row->absensi->tanggal)->translatedFormat('l, d F Y') : '-' }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    {{ $row->absensi?->jadwalPelajaran?->jam_mulai ? \Carbon\Carbon::parse($row->absensi->jadwalPelajaran->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($row->absensi->jadwalPelajaran->jam_selesai)->format('H:i') : '' }}
                                </div>
                            </td>
                            <td>
                                <div class="font-bold text-xs text-slate-700">
                                    {{ $row->absensi?->jadwalPelajaran?->mataPelajaran?->nama_pelajaran ?? 'Presensi Harian' }}
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Ruang: {{ $row->absensi?->jadwalPelajaran?->ruangan ?? 'Kelas' }}
                                </div>
                            </td>
                            <td class="text-xs text-slate-600">
                                {{ $row->absensi?->guru?->nama_lengkap ?? ($row->absensi?->jadwalPelajaran?->guru?->nama_lengkap ?? '-') }}
                            </td>
                            <td class="text-center">
                                @php
                                    $badgeMap = [
                                        'hadir' => 'badge-green',
                                        'sakit' => 'badge-blue',
                                        'izin'  => 'badge-gold',
                                        'alpa'  => 'badge-red',
                                    ];
                                @endphp
                                <span class="badge {{ $badgeMap[$row->status] ?? 'badge-slate' }} uppercase text-[10px] font-bold">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-500">
                                {{ $row->catatan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-slate-400">
                                Tidak ada data presensi yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayatAbsensi->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $riwayatAbsensi->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
