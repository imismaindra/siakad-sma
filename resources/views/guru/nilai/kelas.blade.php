@extends('layouts.guru')

@section('title', 'Nilai Kelas ' . $kelas->nama_kelas)

@section('breadcrumb-parent', 'Kelola Nilai')
@section('breadcrumb-current', 'Kelas ' . $kelas->nama_kelas)

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Nilai Kelas {{ $kelas->nama_kelas }}</h1>
            <p class="page-subtitle">Jurusan: <span class="font-semibold text-slate-700">{{ $kelas->jurusan?->nama ?? 'Umum (Tanpa Jurusan)' }}</span> | Tahun Ajaran: <span class="font-semibold text-slate-700">{{ $tahunAktif?->nama_lengkap }}</span></p>
        </div>
        <div>
            <a href="{{ route('guru.nilai.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    @php
        $mapels = \App\Models\MataPelajaran::whereIn('id', $mapelIds)->get();
    @endphp

    @forelse($mapels as $mp)
        <div class="card">
            <div class="card-header bg-slate-50">
                <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Mata Pelajaran: {{ $mp->nama }} ({{ $mp->kode }}) — KKM: {{ $mp->kkm }}
                </h3>
            </div>
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
                            <th class="text-center">Nilai Harian</th>
                            <th class="text-center">Nilai UTS</th>
                            <th class="text-center">Nilai UAS</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Predikat</th>
                            <th>Status Rapor</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $idx => $siswa)
                            @php
                                $nKey = "{$siswa->id}_{$mp->id}";
                                $n = $nilais->get($nKey);
                            @endphp
                            <tr>
                                <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="font-mono text-slate-500 text-xs">{{ $siswa->nis }}</td>
                                <td class="font-bold text-slate-800">{{ $siswa->nama_lengkap }}</td>
                                <td class="text-center font-mono font-semibold text-slate-600">
                                    {{ $n?->nilai_harian ?? '—' }}
                                </td>
                                <td class="text-center font-mono font-semibold text-slate-600">
                                    {{ $n?->nilai_uts ?? '—' }}
                                </td>
                                <td class="text-center font-mono font-semibold text-slate-600">
                                    {{ $n?->nilai_uas ?? '—' }}
                                </td>
                                <td class="text-center font-mono font-bold text-navy-600 text-base">
                                    {{ $n?->nilai_akhir ?? '—' }}
                                </td>
                                <td class="text-center font-bold">
                                    @if($n?->predikat)
                                        <span class="grade-{{ strtolower($n->predikat) }}">{{ $n->predikat }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($n?->is_final)
                                        <span class="badge badge-success">Dikunci (Final)</span>
                                    @else
                                        <span class="badge badge-warning">Draft</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($n?->is_final)
                                        <span class="text-xs text-slate-400 italic">No Edit</span>
                                    @else
                                        <a href="{{ route('guru.nilai.edit', ['siswa' => $siswa->id, 'mata_pelajaran_id' => $mp->id]) }}" 
                                            class="btn-primary btn-sm">
                                            Input / Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="p-12 text-center card bg-slate-50 border border-dashed border-slate-200">
            <p class="font-bold text-slate-700 text-sm">Tidak ada mata pelajaran diampu</p>
            <p class="text-slate-400 text-xs mt-1">Anda tidak mengajar mata pelajaran apapun di kelas ini pada semester ini.</p>
        </div>
    @endforelse

</div>
@endsection
