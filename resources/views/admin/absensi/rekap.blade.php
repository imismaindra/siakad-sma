@extends('layouts.admin')

@section('title', 'Rekapitulasi Absensi Kelas')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Absensi')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Akademik</p>
            <h1 class="page-title font-display !text-3xl mt-3">Rekap Absensi.</h1>
            <p class="page-subtitle">Pantau kehadiran siswa per sesi kelas mata pelajaran dan lakukan penyesuaian jika diperlukan.</p>
        </div>
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Lihat Jadwal
            </a>
        </div>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.absensi.rekap') }}" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Cari mapel atau guru..."
                    class="w-full rounded-full bg-slate-50 border border-slate-200 pl-12 pr-5 py-2.5 text-sm font-medium text-navy-900 outline-none placeholder:text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800">
            </div>
            <select name="tahun_ajaran_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>{{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>
            <select name="kelas_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select name="mata_pelajaran_id" class="rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm font-semibold text-navy-900 outline-none">
                <option value="">Semua Mapel</option>
                @foreach($mapelList as $m)
                    <option value="{{ $m->id }}" {{ $m->id == $mapelId ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-full bg-navy-800 text-white px-6 py-2.5 text-sm font-semibold transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.absensi.rekap') }}" class="rounded-full bg-slate-100 text-navy-800 px-6 py-2.5 text-sm font-semibold text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    {{-- Table island --}}
    <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th>Hadir</th>
                        <th>Rasio</th>
                        <th>Rincian H/S/I/A</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $a)
                        @php
                            $details = $a->detailAbsensis;
                            $total = $details->count();
                            $hadir = $details->where('status', 'hadir')->count();
                            $sakit = $details->where('status', 'sakit')->count();
                            $izin = $details->where('status', 'izin')->count();
                            $alpa = $details->where('status', 'alpa')->count();
                            $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
                            $barColor = $persen >= 90 ? 'bg-emerald-500' : ($persen >= 75 ? 'bg-amber-500' : 'bg-rose-500');
                            $textColor = $persen >= 90 ? 'text-emerald-600' : ($persen >= 75 ? 'text-amber-600' : 'text-rose-600');
                        @endphp
                        <tr>
                            <td>
                                <span class="font-semibold text-navy-900">{{ $a->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                <span class="block text-xs text-slate-500 capitalize">{{ $a->tanggal->locale('id')->isoFormat('dddd') }}</span>
                            </td>
                            <td>
                                <span class="badge badge-navy font-mono font-bold">{{ $a->kelas->nama_kelas }}</span>
                                @if($a->kelas->jurusan)
                                    <span class="block text-xs text-slate-500 mt-0.5">{{ $a->kelas->jurusan->nama ?? $a->kelas->jurusan->singkatan ?? '' }}</span>
                                @endif
                            </td>
                            <td class="font-semibold text-navy-900">{{ $a->mataPelajaran->nama }}</td>
                            <td class="text-[13px] font-medium text-slate-500">{{ $a->guru->nama_lengkap }}</td>
                            <td class="tabular-nums font-semibold text-navy-900">{{ $hadir }} <span class="text-slate-500 font-normal">/ {{ $total }}</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold font-mono tabular-nums text-sm {{ $textColor }}">{{ $persen }}%</span>
                                    <span class="w-14 h-1.5 rounded-full bg-slate-100 overflow-hidden inline-block">
                                        <span class="block h-full rounded-full {{ $barColor }}" style="width: {{ $persen }}%"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 font-mono text-[11px] font-bold">
                                    <span class="badge badge-success">{{ $hadir }} H</span>
                                    <span class="badge badge-info">{{ $sakit }} S</span>
                                    <span class="badge badge-warning">{{ $izin }} I</span>
                                    <span class="badge badge-danger">{{ $alpa }} A</span>
                                </span>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.absensi.detail', $a) }}" title="Detail" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <p class="font-semibold text-navy-900">Belum ada rekap absensi untuk kriteria di atas.</p>
                                <p class="text-sm text-slate-500 mt-1">Ubah filter untuk melihat sesi lain.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($absensis->total() > 0)
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4">
                <p class="text-xs font-medium text-slate-500 tabular-nums">Menampilkan {{ $absensis->firstItem() }} sampai {{ $absensis->lastItem() }} dari {{ $absensis->total() }} data</p>
                <div class="pagination-wrapper">{{ $absensis->links() }}</div>
            </div>
        @endif
    </div>

</div>
@endsection
