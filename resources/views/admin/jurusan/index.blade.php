@extends('layouts.admin')

@section('title', 'Manajemen Jurusan')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Jurusan')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Data Master</p>
            <h1 class="page-title font-display !text-3xl mt-3">Jurusan.</h1>
            <p class="page-subtitle">Kelola konsentrasi keahlian yang diselenggarakan di sekolah.</p>
        </div>
        <div>
            <a href="#tambah" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">
                <span>Tambah Jurusan</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.jurusan.index') }}" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
        <div class="flex flex-col lg:flex-row gap-3 p-2">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama jurusan..." class="w-full rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 pl-11 text-sm text-navy-900 outline-none focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10" />
            </div>
            <button type="submit" class="rounded-full bg-navy-800 text-white text-sm font-semibold px-6 py-2.5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Terapkan</button>
            <a href="{{ route('admin.jurusan.index') }}" class="rounded-full ring-1 ring-black/5 px-5 py-2.5 text-sm font-semibold text-slate-500 text-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-50">Atur Ulang</a>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
                <div class="rounded-[calc(2rem-0.5rem)] overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jml Kelas</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jurusans as $j)
                                <tr>
                                    <td><span class="badge badge-info font-mono">{{ $j->kode }}</span></td>
                                    <td class="font-semibold text-navy-900">{{ $j->nama }}</td>
                                    <td><span class="badge badge-success tabular-nums">{{ $j->kelas_count }} Kelas</span></td>
                                    <td class="text-right whitespace-nowrap">
                                        <span class="inline-flex items-center gap-2">
                                            <button onclick="openEditModal({{ $j->id }}, '{{ $j->nama }}', '{{ $j->kode }}')" title="Edit" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700 ring-1 ring-gold-200 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                </svg>
                                            </button>
                                            @if($j->kelas_count == 0)
                                                <form action="{{ route('admin.jurusan.destroy', $j) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus jurusan {{ $j->nama }} ({{ $j->kode }})?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-700 ring-1 ring-rose-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-rose-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-12"><p class="font-bold text-navy-900">Belum ada data yang cocok.</p><p class="text-sm text-slate-500 mt-1">Ubah kata kunci atau atur ulang filter.</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($jurusans->hasPages() || $jurusans->total() > 0)
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4">
                        <p class="text-xs text-slate-500">Menampilkan {{ $jurusans->firstItem() ?? 0 }} sampai {{ $jurusans->lastItem() ?? 0 }} dari {{ $jurusans->total() }} data</p>
                        <div class="pagination-wrapper">{{ $jurusans->links() }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div id="tambah" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] h-fit lg:sticky lg:top-20 scroll-mt-24">
            <div class="rounded-[calc(2rem-0.5rem)] bg-slate-50 px-6 py-5">
                <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Form Baru</p>
                <h3 class="font-display text-xl text-navy-900 mt-2">Tambah Jurusan.</h3>
                <p class="text-sm text-slate-500 mt-1">Isi kode dan nama, lalu simpan.</p>
            </div>
            <form action="{{ route('admin.jurusan.store') }}" method="POST" class="space-y-4 px-4 py-5">
                @csrf
                <div>
                    <label for="kode" class="text-xs font-semibold text-navy-900">Kode Jurusan</label>
                    <input type="text" id="kode" name="kode" value="{{ old('kode') }}" required placeholder="Contoh: IPA, IPS" class="mt-1.5 w-full rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm text-navy-900 outline-none focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10 @error('kode') border-rose-300 @enderror" />
                    @error('kode')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nama" class="text-xs font-semibold text-navy-900">Nama Jurusan</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Ilmu Pengetahuan Alam" class="mt-1.5 w-full rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm text-navy-900 outline-none focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10 @error('nama') border-rose-300 @enderror" />
                    @error('nama')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full rounded-full bg-navy-800 text-white text-sm font-semibold px-6 py-2.5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900">Simpan Jurusan</button>
            </form>
        </div>
    </div>
</div>

<div id="edit-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-[2rem] max-w-md w-full shadow-2xl overflow-hidden p-2">
        <div class="rounded-[calc(2rem-0.5rem)] bg-navy-800 px-6 py-5 flex items-center justify-between text-white">
            <h3 class="font-bold text-sm uppercase tracking-wider">Edit Jurusan</h3>
            <button onclick="closeEditModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 hover:bg-white/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="edit-form" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_kode" class="text-xs font-semibold text-navy-900">Kode Jurusan</label>
                <input type="text" id="edit_kode" name="kode" required class="mt-1.5 w-full rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm text-navy-900 outline-none focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10" />
            </div>
            <div>
                <label for="edit_nama" class="text-xs font-semibold text-navy-900">Nama Jurusan</label>
                <input type="text" id="edit_nama" name="nama" required class="mt-1.5 w-full rounded-full bg-slate-50 border border-slate-200 px-5 py-2.5 text-sm text-navy-900 outline-none focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10" />
            </div>
            <div class="flex items-center justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditModal()" class="rounded-full ring-1 ring-black/5 px-5 py-2.5 text-sm font-semibold text-slate-500">Batal</button>
                <button type="submit" class="rounded-full bg-navy-800 text-white text-sm font-semibold px-6 py-2.5">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, nama, kode) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        const inputNama = document.getElementById('edit_nama');
        const inputKode = document.getElementById('edit_kode');
        form.action = `/admin/jurusan/${id}`;
        inputNama.value = nama;
        inputKode.value = kode;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection
