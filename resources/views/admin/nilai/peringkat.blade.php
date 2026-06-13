@extends('layouts.admin')

@section('title', 'Peringkat Kelas ' . $kelas->nama_kelas)

@section('breadcrumb-parent', 'Nilai & Rapor')
@section('breadcrumb-current', 'Peringkat ' . $kelas->nama_kelas)

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Peringkat Kelas {{ $kelas->nama_kelas }}</h1>
            <p class="page-subtitle">Tahun Ajaran: <span class="font-bold text-slate-700">{{ $tahunAjaran->nama_lengkap }}</span> | Rata-rata prestasi nilai rapor terpublikasi.</p>
        </div>
        <div>
            <a href="{{ route('admin.nilai.laporan', ['tahun_ajaran_id' => $tahunAjaran->id, 'kelas_id' => $kelas->id]) }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Rankings Card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Peringkat Prestasi Siswa</h3>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-20 text-center">Rank</th>
                        <th>NIS / NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Rata-rata Nilai Rapor</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peringkat as $siswa)
                        <tr class="{{ $siswa->peringkat <= 3 ? 'bg-amber-50/10' : '' }}">
                            <td class="text-center font-bold font-mono">
                                @if($siswa->peringkat == 1)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400 text-white font-extrabold shadow-sm" title="Juara 1">1</span>
                                @elseif($siswa->peringkat == 2)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-300 text-slate-700 font-extrabold shadow-sm" title="Juara 2">2</span>
                                @elseif($siswa->peringkat == 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-extrabold shadow-sm" title="Juara 3">3</span>
                                @else
                                    <span class="text-slate-400 font-semibold">{{ $siswa->peringkat }}</span>
                                @endif
                            </td>
                            <td class="font-mono text-slate-500 text-xs">{{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</td>
                            <td class="font-bold text-slate-800">
                                <a href="{{ route('admin.siswa.show', $siswa) }}" class="hover:underline">
                                    {{ $siswa->nama_lengkap }}
                                </a>
                            </td>
                            <td class="font-bold font-mono text-navy-600 text-base">
                                @if(!is_null($siswa->nilais_avg_nilai_akhir))
                                    {{ round($siswa->nilais_avg_nilai_akhir, 2) }}
                                @else
                                    <span class="text-slate-400 italic font-normal text-xs">Belum ada nilai final</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if(!is_null($siswa->nilais_avg_nilai_akhir))
                                    <a href="{{ route('admin.nilai.rapor.siswa', ['siswa' => $siswa, 'tahun_ajaran_id' => $tahunAjaran->id]) }}" target="_blank" 
                                        class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                        Unduh Rapor PDF
                                    </a>
                                @endif
                                <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn-secondary btn-sm">
                                    Detail Profil
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
