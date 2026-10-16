@extends('layouts.orang-tua')

@section('title', 'Nilai Akademik & e-Rapor Anak')
@section('breadcrumb-parent', 'Monitoring Anak')
@section('breadcrumb-current', 'Nilai & e-Rapor')

@section('orang-tua-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Child Selector & Active Banner --}}
    @include('orang-tua._anak_selector')

    {{-- Header Section --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="page-title font-display">Nilai Akademik & e-Rapor</h1>
            <p class="page-subtitle">Rincian capaian belajar untuk <span class="font-bold text-slate-700">{{ $activeSiswa->nama_lengkap }}</span> • {{ $tahunAjaran?->tahun_ajaran ?? '-' }} (Semester {{ $tahunAjaran?->semester == '1' ? 'Ganjil' : 'Genap' }})</p>
        </div>
        <div class="flex items-center gap-3">
            @if($isRaporPublished)
                <a href="{{ route('orang-tua.nilai.rapor-pdf', $activeSiswa) }}" class="btn btn-primary text-xs shadow-md shadow-sky-600/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh e-Rapor (PDF)
                </a>
            @else
                <button disabled class="btn btn-secondary text-xs opacity-60 cursor-not-allowed" title="Menunggu finalisasi seluruh nilai oleh pihak sekolah">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    e-Rapor Belum Difinalisasi
                </button>
            @endif
        </div>
    </div>

    {{-- Status Banner Rapor --}}
    @if($isRaporPublished)
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-emerald-900">e-Rapor Semester Telah Resmi Dipublikasikan</h4>
                <p class="text-xs text-emerald-700 mt-0.5">
                    Seluruh penilaian semester berjalan telah difinalisasi dan disahkan oleh dewan guru serta kepala sekolah. Anda dapat mengunduh dokumen resmi e-Rapor berformat PDF melalui tombol di atas.
                </p>
            </div>
        </div>
    @else
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-amber-900">Penilaian Semester Masih Berlangsung</h4>
                <p class="text-xs text-amber-700 mt-0.5">
                    Nilai di bawah ini merupakan nilai sementara yang masih dalam proses input/revisi oleh dewan guru mata pelajaran. Unduh dokumen e-Rapor resmi akan aktif otomatis setelah proses finalisasi tuntas.
                </p>
            </div>
        </div>
    @endif

    {{-- Ringkasan Indikator --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-Rata Nilai Akhir</p>
            <p class="text-3xl font-black font-display text-navy-600 mt-2">{{ $avgScore }} <span class="text-xs font-normal text-slate-400">/ 100</span></p>
        </div>
        <div class="card p-5 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Mata Pelajaran</p>
            <p class="text-3xl font-black font-display text-sky-600 mt-2">{{ $nilais->count() }} <span class="text-xs font-normal text-slate-400">Mapel</span></p>
        </div>
        <div class="card p-5 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Finalisasi</p>
            <div class="mt-2">
                @if($isRaporPublished)
                    <span class="badge badge-green text-xs font-bold uppercase">Final / Resmi</span>
                @else
                    <span class="badge badge-gold text-xs font-bold uppercase">Sementara</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabel Nilai Detail --}}
    <div class="card border border-slate-200/80 shadow-xs rounded-2xl bg-white overflow-hidden">
        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th class="text-center">KKM</th>
                        <th class="text-center">Nilai Tugas</th>
                        <th class="text-center">Nilai UH</th>
                        <th class="text-center">Nilai UTS</th>
                        <th class="text-center">Nilai UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Predikat</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $index => $item)
                        @php
                            $kkm = $item->mataPelajaran->kkm ?? 75;
                            $tuntas = ($item->nilai_akhir ?? 0) >= $kkm;
                        @endphp
                        <tr>
                            <td class="text-center font-mono text-xs text-slate-400">{{ $index + 1 }}</td>
                            <td>
                                <div class="font-bold text-xs text-slate-800">{{ $item->mataPelajaran->nama_pelajaran }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $item->mataPelajaran->kode_pelajaran }}</div>
                            </td>
                            <td class="text-xs text-slate-600">
                                {{ $item->guru->nama_lengkap ?? '-' }}
                            </td>
                            <td class="text-center font-mono text-xs text-slate-600 font-semibold">{{ $kkm }}</td>
                            <td class="text-center font-mono text-xs text-slate-700">{{ $item->nilai_tugas ?? '-' }}</td>
                            <td class="text-center font-mono text-xs text-slate-700">{{ $item->nilai_uh ?? '-' }}</td>
                            <td class="text-center font-mono text-xs text-slate-700">{{ $item->nilai_uts ?? '-' }}</td>
                            <td class="text-center font-mono text-xs text-slate-700">{{ $item->nilai_uas ?? '-' }}</td>
                            <td class="text-center">
                                <span class="font-mono text-xs font-black {{ $tuntas ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $item->nilai_akhir ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-bold text-xs text-slate-700">{{ $item->predikat ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $tuntas ? 'badge-green' : 'badge-red' }} text-[10px] font-bold uppercase">
                                    {{ $tuntas ? 'Tuntas' : 'Remedial' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-8 text-center text-xs text-slate-400">
                                Belum ada data nilai akademik yang diinput untuk semester ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
