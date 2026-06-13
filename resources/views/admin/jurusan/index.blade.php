@extends('layouts.admin')

@section('title', 'Manajemen Jurusan')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Jurusan')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Jurusan</h1>
            <p class="page-subtitle">Kelola konsentrasi keahlian / jurusan yang diselenggarakan di sekolah.</p>
        </div>
        <div>
            <a href="{{ route('admin.kelas.index') }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Lihat Data Kelas
            </a>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- List Column --}}
        <div class="lg:col-span-2">
            <div class="card">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Jurusan</th>
                                <th>Jumlah Kelas</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jurusans as $j)
                                <tr>
                                    <td>
                                        <span class="badge badge-navy font-mono font-bold">{{ $j->kode }}</span>
                                    </td>
                                    <td class="font-bold text-slate-800">{{ $j->nama }}</td>
                                    <td>
                                        <span class="badge badge-gray font-semibold">{{ $j->kelas_count }} Kelas</span>
                                    </td>
                                    <td class="text-right space-x-1 whitespace-nowrap">
                                        <button onclick="openEditModal({{ $j->id }}, '{{ $j->nama }}', '{{ $j->kode }}')" 
                                            class="btn-secondary btn-sm">
                                            Edit
                                        </button>

                                        @if($j->kelas_count == 0)
                                            <form action="{{ route('admin.jurusan.destroy', $j) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus jurusan {{ $j->nama }} ({{ $j->kode }})?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-slate-400">Belum ada data jurusan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($jurusans->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                        {{ $jurusans->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Form Column --}}
        <div>
            <div class="card sticky top-20">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">
                        Tambah Jurusan
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jurusan.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="kode" class="form-label">Kode Jurusan</label>
                            <input type="text" id="kode" name="kode" value="{{ old('kode') }}" required
                                class="form-input @error('kode') error @enderror" placeholder="Contoh: IPA, IPS">
                            @error('kode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama" class="form-label">Nama Jurusan</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="form-input @error('nama') error @enderror" placeholder="Contoh: Ilmu Pengetahuan Alam">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn-primary w-full justify-center">
                                Simpan Jurusan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="bg-gradient-navy px-6 py-5 flex items-center justify-between text-white">
            <h3 class="font-bold font-display text-sm uppercase tracking-wider">Edit Jurusan</h3>
            <button onclick="closeEditModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="edit-form" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="edit_kode" class="form-label">Kode Jurusan</label>
                <input type="text" id="edit_kode" name="kode" required class="form-input">
            </div>

            <div>
                <label for="edit_nama" class="form-label">Nama Jurusan</label>
                <input type="text" id="edit_nama" name="nama" required class="form-input">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
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
