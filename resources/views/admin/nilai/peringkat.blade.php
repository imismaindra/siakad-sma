@extends('layouts.admin')

@section('title', 'Peringkat Kelas ' . $kelas->nama_kelas)

@section('breadcrumb-parent', 'Nilai & Rapor')
@section('breadcrumb-current', 'Peringkat ' . $kelas->nama_kelas)

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Peringkat</p>
            <h1 class="page-title font-display !text-3xl mt-3">Peringkat {{ $kelas->nama_kelas }}.</h1>
            <p class="page-subtitle mt-1">Tahun ajaran <span class="font-semibold text-navy-900">{{ $tahunAjaran->nama_lengkap }}</span>, rata-rata nilai rapor final.</p>
        </div>
        <a href="{{ route('admin.nilai.laporan', ['tahun_ajaran_id' => $tahunAjaran->id, 'kelas_id' => $kelas->id]) }}" class="group inline-flex items-center gap-3 rounded-full bg-white py-1.5 pl-5 pr-1.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10">
            Kembali
            <span class="flex w-8 h-8 items-center justify-center rounded-full bg-slate-100 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </span>
        </a>
    </div>

    <form action="{{ route('admin.nilai.peringkat') }}" method="GET" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow">
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1 min-w-[220px]">
                <svg class="w-4 h-4 absolute left-5 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..." class="w-full rounded-full bg-slate-50 border-slate-200 pl-12 pr-5 py-2.5 text-sm font-semibold text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 border">
            </div>
            <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.nilai.peringkat', ['kelas_id' => $kelas->id, 'tahun_ajaran_id' => $tahunAjaran->id]) }}" class="rounded-full px-6 py-2.5 text-sm font-semibold text-navy-900 bg-slate-100 text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200">Reset</a>
        </div>
    </form>

    <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow">
        <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-28 text-center">Peringkat</th>
                        <th>NIS / NISN</th>
                        <th>Nama</th>
                        <th class="text-right">Rata-rata</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peringkat as $siswa)
                        <tr>
                            <td class="text-center">
                                @if($siswa->peringkat <= 3)
                                    <span class="inline-flex items-center justify-center min-w-9 h-9 px-3 rounded-full bg-gold-500 text-navy-900 text-sm font-bold tabular-nums">{{ $siswa->peringkat }}</span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-9 h-9 px-3 rounded-full bg-slate-100 text-navy-900 text-sm font-bold tabular-nums">{{ $siswa->peringkat }}</span>
                                @endif
                            </td>
                            <td class="font-mono text-xs text-slate-500">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td>
                            <td class="font-semibold text-navy-900">{{ $siswa->nama_lengkap }}</td>
                            <td class="text-right tabular-nums font-mono font-bold text-navy-900">
                                @if(!is_null($siswa->nilais_avg_nilai_akhir))
                                    {{ round($siswa->nilais_avg_nilai_akhir, 2) }}
                                @else
                                    <span class="text-slate-500 font-normal text-xs">Belum ada nilai final</span>
                                @endif
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    @if(!is_null($siswa->nilais_avg_nilai_akhir))
                                        <a href="{{ route('admin.nilai.rapor.siswa', ['siswa' => $siswa, 'tahun_ajaran_id' => $tahunAjaran->id]) }}" target="_blank" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white" title="Unduh rapor PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 11l5-5 5 5"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.siswa.show', $siswa) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white" title="Detail profil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <p class="font-semibold text-navy-900">Belum ada data siswa.</p>
                                <p class="text-sm text-slate-500 mt-1">Tidak ada siswa cocok untuk filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            <p class="text-xs font-medium text-slate-500">{{ $peringkat->count() }} siswa</p>
        </div>
    </div>

</div>
@endsection
