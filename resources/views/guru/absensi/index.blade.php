@extends('layouts.guru')

@section('title', 'Riwayat Sesi Absensi')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Input Absensi')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Riwayat Absensi</h1>
            <p class="page-subtitle">Daftar rekaman absensi kelas yang pernah Anda inputkan sebelumnya.</p>
        </div>
        <div>
            <a href="{{ route('guru.dashboard') }}" class="btn-primary">
                Isi Absensi Hari Ini
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal Sesi</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Materi Bahasan</th>
                        <th>Kehadiran</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $a)
                        @php
                            $details = $a->detailAbsensis;
                            $total = $details->count();
                            $hadir = $details->where('status', 'hadir')->count();
                            $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="font-bold text-slate-800">
                                {{ $a->tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </td>
                            <td>
                                <span class="badge badge-navy font-mono font-bold">{{ $a->kelas->nama_kelas }}</span>
                            </td>
                            <td class="font-semibold text-slate-800">{{ $a->mataPelajaran->nama }}</td>
                            <td class="text-slate-600 truncate max-w-xs" title="{{ $a->materi }}">{{ $a->materi ?? '—' }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold font-mono text-sm {{ $persen >= 90 ? 'text-emerald-600' : ($persen >= 75 ? 'text-amber-500' : 'text-rose-600') }}">
                                        {{ $persen }}%
                                    </span>
                                    <span class="text-xs text-slate-400">({{ $hadir }}/{{ $total }} Siswa)</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('guru.absensi.show', $a) }}" class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                    Lihat Rincian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Belum ada riwayat pengisian absensi kelas terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($absensis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $absensis->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
