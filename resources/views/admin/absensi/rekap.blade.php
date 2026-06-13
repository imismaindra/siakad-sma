@extends('layouts.admin')

@section('title', 'Rekapitulasi Absensi Kelas')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Absensi')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Rekap Absensi</h1>
            <p class="page-subtitle">Pantau kehadiran siswa per sesi kelas mata pelajaran dan lakukan penyesuaian jika diperlukan.</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.absensi.rekap') }}" method="GET" id="absensi-filter-form" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" onchange="document.getElementById('absensi-filter-form').submit()" class="form-select">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                                {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label for="kelas_id" class="form-label">Kelas</label>
                    <select id="kelas_id" name="kelas_id" onchange="document.getElementById('absensi-filter-form').submit()" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    @if($kelasId)
                        <a href="{{ route('admin.absensi.rekap', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn-secondary w-full justify-center">
                            Reset Filter Kelas
                        </a>
                    @endif
                </div>

            </form>
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
                        <th>Guru Pengisi</th>
                        <th>Rasio Kehadiran</th>
                        <th>Rincian (H / S / I / A)</th>
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
                        @endphp
                        <tr>
                            <td class="font-bold text-slate-800">
                                {{ $a->tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </td>
                            <td>
                                <span class="badge badge-navy font-mono font-bold">{{ $a->kelas->nama_kelas }}</span>
                            </td>
                            <td class="font-semibold text-slate-800">{{ $a->mataPelajaran->nama }}</td>
                            <td>
                                <span class="text-xs text-slate-600 font-medium">{{ $a->guru->nama_lengkap }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold font-mono text-sm {{ $persen >= 90 ? 'text-emerald-600' : ($persen >= 75 ? 'text-amber-500' : 'text-rose-600') }}">
                                        {{ $persen }}%
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap font-mono text-xs text-slate-500">
                                <span class="text-emerald-600 font-bold" title="Hadir">{{ $hadir }} H</span> /
                                <span class="text-blue-500 font-bold" title="Sakit">{{ $sakit }} S</span> /
                                <span class="text-amber-500 font-bold" title="Izin">{{ $izin }} I</span> /
                                <span class="text-rose-500 font-bold" title="Alpa">{{ $alpa }} A</span>
                                <span class="text-slate-400"> (Total: {{ $total }})</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.absensi.detail', $a) }}" class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                    Koreksi &amp; Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Belum ada rekapitulasi data absensi terdaftar untuk kriteria di atas.</td>
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
