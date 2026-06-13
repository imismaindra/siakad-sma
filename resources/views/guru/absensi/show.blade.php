@extends('layouts.guru')

@section('title', 'Rincian Absensi Pelajaran')

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Rincian Sesi')

@section('guru-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Rincian Absensi Sesi</h1>
            <p class="page-subtitle">Sesi Kelas: <span class="font-bold font-mono text-navy-500">{{ $absensi->kelas->nama_kelas }}</span> | Tanggal: <span class="font-bold text-slate-800">{{ $absensi->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</span></p>
        </div>
        <div>
            <a href="{{ route('guru.absensi.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Details Summary Card --}}
    <div class="card bg-slate-50 border-slate-200">
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6 text-sm border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Mata Pelajaran</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->mataPelajaran->nama }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jam Pelajaran</span>
                <span class="font-bold text-slate-800 block mt-1 font-mono">
                    {{ \Carbon\Carbon::parse($absensi->jadwalPelajaran->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($absensi->jadwalPelajaran->jam_selesai)->format('H:i') }}
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Ruangan</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->jadwalPelajaran->ruangan ?? '—' }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Total Siswa</span>
                <span class="font-bold text-slate-800 block mt-1 font-mono">{{ $absensi->detailAbsensis->count() }} Orang</span>
            </div>
        </div>
        <div class="p-6 text-sm space-y-3">
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Materi Pembahasan</span>
                <p class="font-medium text-slate-700 mt-1 leading-relaxed">{{ $absensi->materi ?? '—' }}</p>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Catatan Guru</span>
                <p class="font-medium text-slate-700 mt-1 leading-relaxed italic">{{ $absensi->catatan ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Attendance list Card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Daftar Kehadiran Siswa</h3>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Status Kehadiran</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensi->detailAbsensis as $idx => $da)
                        <tr>
                            <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="font-mono text-slate-500 text-xs">{{ $da->siswa->nis }}</td>
                            <td class="font-bold text-slate-800">{{ $da->siswa->nama_lengkap }}</td>
                            <td>
                                <span class="badge {{ $da->siswa->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
                                    {{ $da->siswa->jenis_kelamin }}
                                </span>
                            </td>
                            <td>
                                <span class="badge absensi-status-{{ $da->status }}">
                                    {{ ucfirst($da->status) }}
                                </span>
                            </td>
                            <td class="text-xs text-slate-500">{{ $da->keterangan ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
