@extends('layouts.admin')

@section('title', 'Manajemen Data Siswa')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Data Siswa')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Data Siswa</p>
            <h1 class="page-title font-display !text-3xl mt-3">Data Siswa.</h1>
            <p class="page-subtitle">Kelola informasi murid, pembagian kelas, riwayat absensi, dan nilai akademik.</p>
        </div>
        <a href="{{ route('admin.siswa.create') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
            Tambah Siswa
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </span>
        </a>
    </div>

    {{-- Toolbar --}}
    <form action="{{ route('admin.siswa.index') }}" method="GET" id="search-filter-form" class="rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="flex flex-col gap-3 p-2 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lengkap, NIS, atau NISN..."
                    class="w-full rounded-full border-slate-200 bg-slate-50 py-2.5 pl-12 pr-5 text-sm font-medium text-navy-900 placeholder:font-normal placeholder:text-slate-500 focus:border-gold-500 focus:ring-gold-500" />
            </div>
            <select name="tahun_ajaran_id" onchange="document.getElementById('search-filter-form').submit()" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="all" {{ $tahunAjaranId === 'all' ? 'selected' : '' }}>Semua Siswa (Semua Tahun Ajaran)</option>
                <option value="tanpa_kelas" {{ $tahunAjaranId === 'tanpa_kelas' ? 'selected' : '' }}>Siswa Tanpa Kelas</option>
                <optgroup label="Berdasarkan Tahun Ajaran">
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ ($ta->id == $tahunAjaranId && $tahunAjaranId !== 'all' && $tahunAjaranId !== 'tanpa_kelas') ? 'selected' : '' }}>
                            {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </optgroup>
            </select>
            <select name="kelas_id" onchange="document.getElementById('search-filter-form').submit()" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} {{ ($tahunAjaranId === 'all' && $k->tahunAjaran) ? '('.$k->tahunAjaran->nama_lengkap.')' : '' }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="pindah" {{ request('status') == 'pindah' ? 'selected' : '' }}>Pindah Sekolah</option>
                <option value="dikeluarkan" {{ request('status') == 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-full bg-navy-800 px-6 py-2.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                    Terapkan
                </button>
                @if(request()->anyFilled(['search', 'kelas_id', 'status']) || request('tahun_ajaran_id') === 'all' || request('tahun_ajaran_id') === 'tanpa_kelas')
                    <a href="{{ route('admin.siswa.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Table Island --}}
    <div class="overflow-hidden rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="overflow-x-auto rounded-[calc(2rem-0.5rem)]">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10 text-center">
                            <input type="checkbox" id="check-all" class="h-4 w-4 rounded border-slate-300 text-gold-600 focus:ring-gold-500 cursor-pointer" title="Pilih Semua di Halaman Ini" />
                        </th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIS / NISN</th>
                        <th>Kelas & Periode</th>
                        <th>L/P</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $s)
                        <tr class="transition-colors hover:bg-slate-50/70" id="row-siswa-{{ $s->id }}">
                            <td class="text-center">
                                <input type="checkbox" value="{{ $s->id }}" class="siswa-checkbox h-4 w-4 rounded border-slate-300 text-gold-600 focus:ring-gold-500 cursor-pointer" />
                            </td>
                            <td>
                                @if($s->foto)
                                    <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto Siswa" class="h-9 w-9 rounded-full object-cover" />
                                @else
                                    <div class="avatar-placeholder h-9 w-9 rounded-full text-xs">
                                        {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.siswa.show', $s) }}" class="font-semibold text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-gold-700">
                                    {{ $s->nama_lengkap }}
                                </a>
                            </td>
                            <td class="font-mono text-xs tabular-nums text-slate-500">
                                {{ $s->nis }} / {{ $s->nisn ?? '-' }}
                            </td>
                            <td>
                                @if($s->kelas)
                                    <div>
                                        <span class="font-mono font-semibold text-navy-900">{{ $s->kelas->nama_kelas }}</span>
                                        @if($s->kelas->tahunAjaran)
                                            <span class="block text-[10px] text-slate-400">TA {{ $s->kelas->tahunAjaran->nama_lengkap }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">
                                        Belum Ada Kelas
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $s->jenis_kelamin == 'L' ? 'badge-info' : 'badge-gray' }}">
                                    {{ $s->jenis_kelamin }}
                                </span>
                            </td>
                            <td>
                                @if($s->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($s->status == 'lulus')
                                    <span class="badge badge-navy">Lulus</span>
                                @elseif($s->status == 'pindah')
                                    <span class="badge badge-warning">Pindah</span>
                                @else
                                    <span class="badge badge-danger">Keluar</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                    {{-- Tombol Mutasi / Pindah Kelas Cepat Individual --}}
                                    <button onclick="openPindahModal({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}', '{{ $s->kelas ? addslashes($s->kelas->nama_kelas . ' (TA ' . ($s->kelas->tahunAjaran->nama_lengkap ?? '') . ')') : 'Belum Ada Kelas' }}', '{{ $s->kelas_id ?? '' }}')" 
                                        title="{{ $s->kelas ? 'Mutasi / Naik Kelas Cepat' : 'Tempatkan ke Kelas' }}"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-inset ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.siswa.show', $s) }}" title="Detail"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-800 hover:text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.siswa.edit', $s) }}" title="Edit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-inset ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus data siswa {{ $s->nama_lengkap }}? Tindakan ini akan menghapus akun login siswa (jika terdaftar).">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-600 hover:text-white">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">
                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-200">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-navy-900">Belum ada siswa yang terdaftar di periode ini.</p>
                                <p class="mx-auto mt-1 max-w-lg text-xs text-slate-500">
                                    Jika Anda baru membuat Tahun Ajaran baru, data siswa lama masih tercatat di kelas tahun ajaran sebelumnya. 
                                    Gunakan tombol di bawah untuk melihat seluruh siswa dan lakukan mutasi/kenaikan kelas.
                                </p>
                                <div class="mt-4 flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.siswa.index', ['tahun_ajaran_id' => 'all']) }}" class="btn-secondary !text-xs">
                                        Lihat Semua Siswa
                                    </a>
                                    <a href="{{ route('admin.siswa.create') }}" class="btn-primary !text-xs">
                                        + Tambah Siswa Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 px-6 py-4 sm:flex-row">
            <p class="text-xs font-medium text-slate-500">Menampilkan {{ $siswas->firstItem() ?? 0 }} sampai {{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} data</p>
            <div class="pagination-wrapper">
                {{ $siswas->links() }}
            </div>
        </div>
    </div>

</div>

{{-- Floating Bulk Action Dock --}}
<div id="bulk-dock" class="fixed bottom-6 left-1/2 z-40 hidden -translate-x-1/2 items-center gap-3 rounded-full bg-navy-950/95 px-5 py-2.5 text-white shadow-[0_20px_50px_rgba(15,23,42,0.4)] backdrop-blur-md ring-1 ring-white/15 transition-all duration-300">
    <div class="flex items-center gap-2 border-r border-white/15 pr-3 text-xs font-semibold">
        <span id="bulk-count-badge" class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-gold-500 px-1.5 text-[11px] font-bold text-navy-950">0</span>
        <span class="whitespace-nowrap">Siswa Dipilih</span>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="openBulkPindahModal()" class="inline-flex items-center gap-1.5 rounded-full bg-gold-500 px-3.5 py-1.5 text-xs font-bold text-navy-950 transition hover:bg-gold-400">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            Mutasi Massal
        </button>
        <button type="button" onclick="openBulkStatusModal()" class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-white/20">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Ubah Status
        </button>
        <button type="button" onclick="openBulkHapusModal()" class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/20 px-3.5 py-1.5 text-xs font-semibold text-rose-300 transition hover:bg-rose-500/30">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
            </svg>
            Hapus
        </button>
        <button type="button" onclick="clearBulkSelection()" title="Batalkan Pilihan" class="rounded-full p-1 text-slate-400 hover:text-white transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

{{-- Modal Mutasi / Penempatan Kelas Cepat Individual --}}
<div id="pindah-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-lg animate-fade-in-up overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-gradient-navy flex items-center justify-between px-6 py-5 text-white">
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-wider">Mutasi Cepat / Penempatan Kelas</h3>
                <p class="mt-0.5 text-xs text-slate-300">Pindahkan atau naikkan siswa ke kelas pada Tahun Ajaran Aktif.</p>
            </div>
            <button onclick="closePindahModal()" class="text-white/80 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="pindah-form" method="POST" class="space-y-4 p-6">
            @csrf
            @method('PATCH')

            {{-- Ringkasan Info Siswa & Status TA --}}
            <div class="space-y-2 rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200/70">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-slate-500">Nama Siswa:</span>
                    <span id="pindah-siswa-nama" class="font-bold text-navy-900"></span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-slate-500">Kelas Saat Ini:</span>
                    <span id="pindah-siswa-kelas-lama" class="font-medium text-slate-700"></span>
                </div>
                @if($tahunAktif)
                    <div class="flex items-center justify-between border-t border-slate-200 pt-1 text-xs">
                        <span class="font-semibold text-emerald-600">Tahun Ajaran Aktif (Tujuan):</span>
                        <span class="rounded bg-emerald-100 px-2 py-0.5 font-bold text-emerald-800">{{ $tahunAktif->nama_lengkap }}</span>
                    </div>
                @endif
            </div>

            <div>
                <label for="pindah_kelas_id" class="form-label">Pilih Kelas Tujuan</label>
                @if($kelasListAktif->count() > 0)
                    <select id="pindah_kelas_id" name="kelas_id" required class="form-select">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        @foreach($kelasListAktif as $k)
                            @php
                                $sisaKapasitas = $k->kapasitas - $k->jumlah_siswa;
                            @endphp
                            <option value="{{ $k->id }}">
                                {{ $k->nama_kelas }} — Sisa: {{ $sisaKapasitas }} dari {{ $k->kapasitas }} siswa
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-[11px] text-slate-500">
                        Menampilkan kelas pada Tahun Ajaran Aktif (<strong>{{ $tahunAktif?->nama_lengkap ?? 'Tidak Ada' }}</strong>).
                    </p>
                @else
                    <div class="rounded-xl bg-rose-50 p-3 text-xs text-rose-700 ring-1 ring-rose-200">
                        Belum ada kelas yang dibuat untuk Tahun Ajaran Aktif ({{ $tahunAktif?->nama_lengkap ?? '-' }}). 
                        Silakan buat kelas terlebih dahulu di menu <strong>Data Master > Kelas</strong> sebelum melakukan penempatan siswa.
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closePindahModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary" {{ $kelasListAktif->count() === 0 ? 'disabled' : '' }}>
                    Simpan Penempatan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal 1: Bulk Mutasi / Naik Kelas Massal --}}
<div id="bulk-pindah-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-lg animate-fade-in-up overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-gradient-navy flex items-center justify-between px-6 py-5 text-white">
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-wider">Mutasi / Penempatan Kelas Massal</h3>
                <p class="mt-0.5 text-xs text-slate-300">Tempatkan seluruh siswa terpilih ke kelas tujuan sekaligus.</p>
            </div>
            <button onclick="closeBulkPindahModal()" class="text-white/80 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.siswa.bulk-action') }}" method="POST" id="bulk-pindah-form" class="space-y-4 p-6">
            @csrf
            <input type="hidden" name="action" value="pindah_kelas">
            <div id="bulk-pindah-inputs"></div>

            <div class="space-y-2 rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200/70">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-slate-500">Jumlah Siswa Terpilih:</span>
                    <span class="rounded bg-navy-100 px-2.5 py-0.5 font-bold text-navy-900" id="bulk-pindah-count">0 Siswa</span>
                </div>
                @if($tahunAktif)
                    <div class="flex items-center justify-between border-t border-slate-200 pt-1 text-xs">
                        <span class="font-semibold text-emerald-600">Tahun Ajaran Aktif (Tujuan):</span>
                        <span class="rounded bg-emerald-100 px-2 py-0.5 font-bold text-emerald-800">{{ $tahunAktif->nama_lengkap }}</span>
                    </div>
                @endif
            </div>

            <div>
                <label for="bulk_pindah_kelas_id" class="form-label">Pilih Kelas Tujuan</label>
                @if($kelasListAktif->count() > 0)
                    <select id="bulk_pindah_kelas_id" name="kelas_id" required class="form-select">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        @foreach($kelasListAktif as $k)
                            @php
                                $sisaKapasitas = $k->kapasitas - $k->jumlah_siswa;
                            @endphp
                            <option value="{{ $k->id }}">
                                {{ $k->nama_kelas }} — Sisa: {{ $sisaKapasitas }} dari {{ $k->kapasitas }} siswa
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-[11px] text-slate-500">
                        Menampilkan kelas pada Tahun Ajaran Aktif (<strong>{{ $tahunAktif?->nama_lengkap ?? 'Tidak Ada' }}</strong>).
                    </p>
                @else
                    <div class="rounded-xl bg-rose-50 p-3 text-xs text-rose-700 ring-1 ring-rose-200">
                        Belum ada kelas yang dibuat untuk Tahun Ajaran Aktif. Silakan buat kelas terlebih dahulu di menu <strong>Data Master > Kelas</strong>.
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeBulkPindahModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary" {{ $kelasListAktif->count() === 0 ? 'disabled' : '' }}>
                    Terapkan Mutasi Massal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal 2: Bulk Ubah Status Massal --}}
<div id="bulk-status-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md animate-fade-in-up overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-gradient-navy flex items-center justify-between px-6 py-5 text-white">
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-wider">Ubah Status Massal</h3>
                <p class="mt-0.5 text-xs text-slate-300">Perbarui status seluruh siswa terpilih sekaligus.</p>
            </div>
            <button onclick="closeBulkStatusModal()" class="text-white/80 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.siswa.bulk-action') }}" method="POST" id="bulk-status-form" class="space-y-4 p-6">
            @csrf
            <input type="hidden" name="action" value="ubah_status">
            <div id="bulk-status-inputs"></div>

            <div class="rounded-xl bg-slate-50 p-3.5 ring-1 ring-slate-200/70">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-slate-500">Jumlah Siswa Terpilih:</span>
                    <span class="rounded bg-navy-100 px-2.5 py-0.5 font-bold text-navy-900" id="bulk-status-count">0 Siswa</span>
                </div>
            </div>

            <div>
                <label for="bulk_status_val" class="form-label">Status Baru</label>
                <select id="bulk_status_val" name="status" required class="form-select">
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif">Aktif</option>
                    <option value="lulus">Lulus</option>
                    <option value="pindah">Pindah Sekolah</option>
                    <option value="dikeluarkan">Dikeluarkan</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeBulkStatusModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Perbarui Status</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal 3: Bulk Hapus Massal --}}
<div id="bulk-hapus-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md animate-fade-in-up overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-rose-700 flex items-center justify-between px-6 py-5 text-white">
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-wider">Konfirmasi Hapus Massal</h3>
                <p class="mt-0.5 text-xs text-rose-100">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            </div>
            <button onclick="closeBulkHapusModal()" class="text-white/80 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.siswa.bulk-action') }}" method="POST" id="bulk-hapus-form" class="space-y-4 p-6">
            @csrf
            <input type="hidden" name="action" value="hapus">
            <div id="bulk-hapus-inputs"></div>

            <div class="rounded-xl bg-rose-50 p-4 text-xs text-rose-800 ring-1 ring-rose-200">
                <p class="font-bold">Peringatan Penghapusan:</p>
                <p class="mt-1">
                    Anda akan menghapus data <strong id="bulk-hapus-count">0</strong> siswa terpilih beserta akun login mereka. Seluruh riwayat absensi dan nilai siswa bersangkutan akan ikut terhapus.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeBulkHapusModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-danger">Ya, Hapus Terpilih</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // State Siswa Terpilih
    let selectedSiswaIds = new Set();

    function updateBulkDock() {
        const dock = document.getElementById('bulk-dock');
        const countBadge = document.getElementById('bulk-count-badge');
        const checkAll = document.getElementById('check-all');
        const rowCheckboxes = document.querySelectorAll('.siswa-checkbox');

        selectedSiswaIds.clear();
        rowCheckboxes.forEach(cb => {
            const row = document.getElementById(`row-siswa-${cb.value}`);
            if (cb.checked) {
                selectedSiswaIds.add(cb.value);
                if (row) row.classList.add('bg-gold-50/50');
            } else {
                if (row) row.classList.remove('bg-gold-50/50');
            }
        });

        const count = selectedSiswaIds.size;
        if (countBadge) countBadge.textContent = count;

        if (count > 0) {
            dock.classList.remove('hidden');
            dock.classList.add('flex');
        } else {
            dock.classList.remove('flex');
            dock.classList.add('hidden');
        }

        if (checkAll && rowCheckboxes.length > 0) {
            checkAll.checked = (count === rowCheckboxes.length);
            checkAll.indeterminate = (count > 0 && count < rowCheckboxes.length);
        }
    }

    function populateHiddenInputs(containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        selectedSiswaIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'siswa_ids[]';
            input.value = id;
            container.appendChild(input);
        });
    }

    function openBulkPindahModal() {
        if (selectedSiswaIds.size === 0) return;
        document.getElementById('bulk-pindah-count').textContent = selectedSiswaIds.size + ' Siswa';
        populateHiddenInputs('bulk-pindah-inputs');
        const modal = document.getElementById('bulk-pindah-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBulkPindahModal() {
        const modal = document.getElementById('bulk-pindah-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function openBulkStatusModal() {
        if (selectedSiswaIds.size === 0) return;
        document.getElementById('bulk-status-count').textContent = selectedSiswaIds.size + ' Siswa';
        populateHiddenInputs('bulk-status-inputs');
        const modal = document.getElementById('bulk-status-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBulkStatusModal() {
        const modal = document.getElementById('bulk-status-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function openBulkHapusModal() {
        if (selectedSiswaIds.size === 0) return;
        document.getElementById('bulk-hapus-count').textContent = selectedSiswaIds.size;
        populateHiddenInputs('bulk-hapus-inputs');
        const modal = document.getElementById('bulk-hapus-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBulkHapusModal() {
        const modal = document.getElementById('bulk-hapus-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function clearBulkSelection() {
        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            cb.checked = false;
        });
        const checkAll = document.getElementById('check-all');
        if (checkAll) {
            checkAll.checked = false;
            checkAll.indeterminate = false;
        }
        updateBulkDock();
    }

    // Modal Pindah Individual
    function openPindahModal(siswaId, nama, kelasLama, kelasId) {
        const modal = document.getElementById('pindah-modal');
        const form = document.getElementById('pindah-form');
        const textNama = document.getElementById('pindah-siswa-nama');
        const textKelasLama = document.getElementById('pindah-siswa-kelas-lama');
        const selectKelas = document.getElementById('pindah_kelas_id');

        form.action = `/admin/siswa/${siswaId}/pindah-kelas`;
        textNama.textContent = nama;
        if (textKelasLama) {
            textKelasLama.textContent = kelasLama;
        }
        if (selectKelas) {
            selectKelas.value = kelasId || '';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePindahModal() {
        const modal = document.getElementById('pindah-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Init Event Listeners
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('check-all');
        if (checkAll) {
            checkAll.addEventListener('change', function () {
                document.querySelectorAll('.siswa-checkbox').forEach(cb => {
                    cb.checked = checkAll.checked;
                });
                updateBulkDock();
            });
        }

        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkDock);
        });
    });
</script>
@endpush
@endsection
