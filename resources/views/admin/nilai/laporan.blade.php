@extends('layouts.admin')

@section('title', 'Laporan & Finalisasi Nilai')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Nilai & Rapor')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Akademik</p>
            <h1 class="page-title font-display !text-3xl mt-3">Laporan nilai.</h1>
            <p class="page-subtitle mt-1">Pantau nilai siswa, filter per kelas dan mapel, lalu kunci rapor saat siap.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            @if($kelasId)
                <a href="{{ route('admin.nilai.peringkat', ['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $kelasId]) }}" class="group inline-flex items-center gap-3 rounded-full bg-white py-1.5 pl-5 pr-1.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10">
                    Peringkat kelas
                    <span class="flex w-8 h-8 items-center justify-center rounded-full bg-slate-100 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </span>
                </a>
                <a href="{{ route('admin.nilai.rapor.kelas', ['tahun_ajaran_id' => $tahunAjaranId, 'kelas_id' => $kelasId]) }}" class="group inline-flex items-center gap-3 rounded-full bg-white py-1.5 pl-5 pr-1.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10">
                    Ekspor PDF
                    <span class="flex w-8 h-8 items-center justify-center rounded-full bg-slate-100 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 11l5-5 5 5"/></svg>
                    </span>
                </a>
                <form action="{{ route('admin.nilai.finalisasi') }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin mengunci (finalisasi) seluruh nilai untuk kelas ini? Setelah dikunci, guru tidak dapat lagi mengubah nilai.">
                    @csrf
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                    <button type="submit" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                        Finalisasi nilai
                        <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gold-500 text-navy-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.nilai.laporan') }}" method="GET" id="filter-nilai-form" class="rounded-[2rem] bg-white p-3 sm:p-4 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] space-y-3">
        {{-- Baris 1: Pencarian Cepat + Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama lengkap, NIS, atau nama mata pelajaran..."
                    class="w-full rounded-full border border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 focus:border-navy-800 transition-all duration-300">
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900 active:scale-95 whitespace-nowrap">
                    Terapkan
                </button>
                <a href="{{ route('admin.nilai.laporan', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="rounded-full bg-slate-100 px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200 active:scale-95 whitespace-nowrap">
                    Reset
                </a>
            </div>
        </div>

        {{-- Baris 2: Dropdown Filter Spesifik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 pt-2 border-t border-slate-100">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" onchange="document.getElementById('filter-nilai-form').submit()" class="w-full rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-navy-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 focus:border-navy-800 transition-all">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                            {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3">Kelas</label>
                <select name="kelas_id" onchange="document.getElementById('filter-nilai-form').submit()" class="w-full rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-navy-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 focus:border-navy-800 transition-all">
                    <option value="">Semua kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="w-full rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-navy-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 focus:border-navy-800 transition-all">
                    <option value="">Semua mapel</option>
                    @foreach($mapelList as $m)
                        <option value="{{ $m->id }}" {{ $m->id == $mapelId ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 px-3">Status Rapor</label>
                <select name="is_final" class="w-full rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-navy-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy-800/10 focus:border-navy-800 transition-all">
                    <option value="">Semua status</option>
                    <option value="1" {{ $statusFinal === '1' || $statusFinal === 1 ? 'selected' : '' }}>Final (Terkunci)</option>
                    <option value="0" {{ $statusFinal === '0' || $statusFinal === 0 ? 'selected' : '' }}>Draft (Penilaian)</option>
                </select>
            </div>
        </div>
    </form>

    <div class="overflow-hidden rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th class="text-right">Harian</th>
                        <th class="text-right">UTS</th>
                        <th class="text-right">UAS</th>
                        <th class="text-right">Akhir</th>
                        <th>Predikat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $n)
                        <tr>
                            <td>
                                <p class="font-semibold text-navy-900">{{ $n->siswa->nama_lengkap }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $n->siswa->nis }}</p>
                            </td>
                            <td class="text-sm text-slate-500 font-mono">{{ $n->kelas->nama_kelas }}</td>
                            <td class="font-semibold text-sm text-navy-900">{{ $n->mataPelajaran->nama }}</td>
                            <td class="text-right tabular-nums font-mono text-sm text-slate-500">{{ $n->rata_rata_harian ?? '-' }}</td>
                            <td class="text-right tabular-nums font-mono text-sm text-slate-500">{{ $n->nilai_uts ?? '-' }}</td>
                            <td class="text-right tabular-nums font-mono text-sm text-slate-500">{{ $n->nilai_uas ?? '-' }}</td>
                            <td class="text-right tabular-nums font-mono font-bold text-base grade-{{ strtolower($n->predikat ?? 'e') }}">{{ $n->nilai_akhir ?? '-' }}</td>
                            <td>
                                @if($n->predikat)
                                    <span class="grade-{{ strtolower($n->predikat) }} font-bold">{{ $n->predikat }}</span>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td>
                                @if($n->is_final)
                                    <span class="badge badge-success">Final</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12">
                                <div class="inline-flex w-12 h-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <p class="font-semibold text-navy-900">Belum ada data nilai.</p>
                                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                                    @if($search || $kelasId || $mapelId || ($statusFinal !== null && $statusFinal !== ''))
                                        Tidak ada data yang cocok dengan kriteria filter saat ini. Coba sesuaikan kata kunci atau reset filter.
                                    @else
                                        Belum ada nilai yang diinput pada periode tahun ajaran ini. Silakan pilih tahun ajaran lain pada filter di atas jika ingin melihat arsip nilai sebelumnya.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($nilais->hasPages() || $nilais->total() > 0)
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <p class="text-xs font-medium text-slate-500">Menampilkan {{ $nilais->firstItem() ?? 0 }} sampai {{ $nilais->lastItem() ?? 0 }} dari {{ $nilais->total() }} data</p>
                <div class="pagination-wrapper">{{ $nilais->links() }}</div>
            </div>
        @endif
    </div>

</div>
@endsection
