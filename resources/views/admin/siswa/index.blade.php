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
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" {{ $ta->id == $tahunAjaranId ? 'selected' : '' }}>
                        {{ $ta->nama_lengkap }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
            <select name="kelas_id" onchange="document.getElementById('search-filter-form').submit()" class="rounded-full border-slate-200 bg-slate-50 px-5 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
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
                @if(request()->anyFilled(['search', 'kelas_id', 'status']))
                    <a href="{{ route('admin.siswa.index', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-800 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">
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
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIS / NISN</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $s)
                        <tr>
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
                                    <span class="font-mono font-semibold text-navy-900">{{ $s->kelas->nama_kelas }}</span>
                                @else
                                    <span class="text-xs italic text-slate-500">Belum Ada Kelas</span>
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
                                    @if($s->kelas)
                                        <button onclick="openPindahModal({{ $s->id }}, '{{ $s->nama_lengkap }}', '{{ $s->kelas->id }}')" title="Pindah Kelas"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-inset ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-500 hover:text-navy-900">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                            </svg>
                                        </button>
                                    @endif
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
                            <td colspan="7" class="px-6 py-14 text-center">
                                <p class="text-sm font-semibold text-navy-900">Belum ada data.</p>
                                <p class="mt-1 text-xs text-slate-500">Belum ada data siswa terdaftar yang sesuai filter.</p>
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

{{-- Pindah Kelas Modal --}}
<div id="pindah-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md animate-fade-in-up overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-gradient-navy flex items-center justify-between px-6 py-5 text-white">
            <h3 class="font-display text-sm font-bold uppercase tracking-wider">Mutasi / Pindah Kelas</h3>
            <button onclick="closePindahModal()" class="text-white/80 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="pindah-form" method="POST" class="space-y-4 p-6">
            @csrf
            @method('PATCH')

            <div>
                <span class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Siswa</span>
                <span id="pindah-siswa-nama" class="block text-sm font-bold text-slate-800"></span>
            </div>

            <div>
                <label for="pindah_kelas_id" class="form-label">Pilih Kelas Baru</label>
                <select id="pindah_kelas_id" name="kelas_id" required class="form-select">
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }} (Kapasitas sisa: {{ $k->kapasitas - $k->jumlah_siswa }})</option>
                    @endforeach
                </select>
                <p class="mt-1 text-[10px] italic text-slate-500">Hanya menampilkan daftar kelas pada Tahun Ajaran yang sedang aktif.</p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closePindahModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Pindahkan Siswa</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openPindahModal(siswaId, nama, kelasId) {
        const modal = document.getElementById('pindah-modal');
        const form = document.getElementById('pindah-form');
        const textNama = document.getElementById('pindah-siswa-nama');
        const selectKelas = document.getElementById('pindah_kelas_id');

        form.action = `/admin/siswa/${siswaId}/pindah-kelas`;
        textNama.textContent = nama;
        selectKelas.value = kelasId;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePindahModal() {
        const modal = document.getElementById('pindah-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection
