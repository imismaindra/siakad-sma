@extends('layouts.admin')

@section('title', 'Laporan & Finalisasi Nilai')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Nilai & Rapor')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Nilai &amp; Rapor</h1>
            <p class="page-subtitle">Pantau nilai siswa, lakukan finalisasi (kunci) rapor, dan ekspor dokumen rapor.</p>
        </div>
    </div>

    {{-- Filter & Actions Card --}}
    <div class="card">
        <div class="p-6 space-y-4">
            <form action="{{ route('admin.nilai.laporan') }}" method="GET" id="nilai-filter-form" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" onchange="document.getElementById('nilai-filter-form').submit()" class="form-select">
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
                    <select id="kelas_id" name="kelas_id" onchange="document.getElementById('nilai-filter-form').submit()" class="form-select">
                        <option value="">Pilih kelas...</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    @if($kelasId)
                        <a href="{{ route('admin.nilai.laporan', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn-secondary w-full justify-center">
                            Reset Filter Kelas
                        </a>
                    @endif
                </div>

            </form>

            {{-- Actions if Kelas is Selected --}}
            @if($kelasId)
                <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-100">
                    
                    {{-- Finalisasi Form --}}
                    <form action="{{ route('admin.nilai.finalisasi') }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin mengunci (finalisasi) seluruh nilai untuk kelas ini? Setelah dikunci, guru tidak dapat lagi mengubah nilai.">
                        @csrf
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
                        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Finalisasi &amp; Kunci Nilai
                        </button>
                    </form>

                    {{-- Peringkat --}}
                    <a href="{{ route('admin.nilai.peringkat', ['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $kelasId]) }}" class="btn-gold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Lihat Peringkat Kelas
                    </a>

                    {{-- Export Kelas --}}
                    <a href="{{ route('admin.nilai.rapor.kelas', ['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $kelasId]) }}" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Ekspor Rapor Kelas (PDF)
                    </a>

                </div>
            @endif
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Harian</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $n)
                        <tr>
                            <td class="font-bold text-slate-800">
                                <a href="{{ route('admin.siswa.show', $n->siswa) }}" class="hover:underline">
                                    {{ $n->siswa->nama_lengkap }}
                                </a>
                            </td>
                            <td class="font-mono text-xs text-slate-500">{{ $n->kelas->nama_kelas }}</td>
                            <td class="font-semibold text-navy-500">{{ $n->mataPelajaran->nama }}</td>
                            <td class="font-mono text-slate-600 font-semibold">{{ $n->nilai_harian ?? '—' }}</td>
                            <td class="font-mono text-slate-600 font-semibold">{{ $n->nilai_uts ?? '—' }}</td>
                            <td class="font-mono text-slate-600 font-semibold">{{ $n->nilai_uas ?? '—' }}</td>
                            <td class="font-bold text-slate-800 font-mono text-base">{{ $n->nilai_akhir ?? '—' }}</td>
                            <td>
                                @if($n->predikat)
                                    <span class="grade-{{ strtolower($n->predikat) }} font-bold">{{ $n->predikat }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td>
                                @if($n->is_final)
                                    <span class="badge badge-success">Final / Kunci</span>
                                @else
                                    <span class="badge badge-warning">Draft (Pencatatan)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-400">
                                @if($kelasId)
                                    Belum ada data nilai terisi untuk kelas ini.
                                @else
                                    Pilih kelas terlebih dahulu untuk melihat daftar laporan nilai.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($nilais->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $nilais->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
