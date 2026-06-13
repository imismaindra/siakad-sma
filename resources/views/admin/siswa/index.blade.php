@extends('layouts.admin')

@section('title', 'Manajemen Data Siswa')

@section('breadcrumb-parent', 'Data Master')
@section('breadcrumb-current', 'Data Siswa')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Data Siswa</h1>
            <p class="page-subtitle">Kelola informasi murid, pembagian kelas, riwayat absensi, dan nilai akademik.</p>
        </div>
        <div>
            <a href="{{ route('admin.siswa.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card">
        <div class="p-6">
            <form action="{{ route('admin.siswa.index') }}" method="GET" id="search-filter-form" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    
                    {{-- Search --}}
                    <div class="sm:col-span-2">
                        <label for="search" class="form-label">Cari Siswa</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                            class="form-input" placeholder="Cari nama lengkap, NIS, atau NISN...">
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div>
                        <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" onchange="document.getElementById('search-filter-form').submit()" class="form-select">
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
                        <select id="kelas_id" name="kelas_id" onchange="document.getElementById('search-filter-form').submit()" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-2 border-t border-slate-100 items-end">
                    
                    {{-- Status --}}
                    <div>
                        <label for="status" class="form-label">Status Siswa</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ request('status') == 'pindah' ? 'selected' : '' }}>Pindah Sekolah</option>
                            <option value="dikeluarkan" {{ request('status') == 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                        </select>
                    </div>

                    <div></div>
                    <div></div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary w-full justify-center">
                            Filter Data
                        </button>
                        @if(request()->anyFilled(['search', 'kelas_id', 'status']))
                            <a href="{{ route('admin.siswa.index', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn-secondary">
                                Reset
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
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
                                    <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto Siswa" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="avatar-placeholder w-10 h-10 text-xs bg-slate-300 text-slate-600">
                                        {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="font-bold text-slate-800">
                                <a href="{{ route('admin.siswa.show', $s) }}" class="hover:text-navy-500 transition-colors">
                                    {{ $s->nama_lengkap }}
                                </a>
                            </td>
                            <td class="font-mono text-slate-500 text-xs">
                                {{ $s->nis }} / {{ $s->nisn ?? '—' }}
                            </td>
                            <td>
                                @if($s->kelas)
                                    <span class="text-navy-600 font-bold font-mono">{{ $s->kelas->nama_kelas }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum Ada Kelas</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $s->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
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
                            <td class="text-right space-x-1 whitespace-nowrap">
                                @if($s->kelas)
                                    <button onclick="openPindahModal({{ $s->id }}, '{{ $s->nama_lengkap }}', '{{ $s->kelas->id }}')" 
                                        class="btn-secondary btn-sm bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100">
                                        Pindah Kelas
                                    </button>
                                @endif
                                <a href="{{ route('admin.siswa.show', $s) }}" class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                    Detail
                                </a>
                                <a href="{{ route('admin.siswa.edit', $s) }}" class="btn-secondary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus data siswa {{ $s->nama_lengkap }}? Tindakan ini akan menghapus akun login siswa (jika terdaftar).">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">Belum ada data siswa terdaftar yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($siswas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 pagination-wrapper">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Pindah Kelas Modal --}}
<div id="pindah-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="bg-gradient-navy px-6 py-5 flex items-center justify-between text-white">
            <h3 class="font-bold font-display text-sm uppercase tracking-wider">Mutasi / Pindah Kelas</h3>
            <button onclick="closePindahModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="pindah-form" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PATCH')
            
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-1">Nama Siswa</span>
                <span id="pindah-siswa-nama" class="font-bold text-slate-800 text-sm block"></span>
            </div>

            <div>
                <label for="pindah_kelas_id" class="form-label">Pilih Kelas Baru</label>
                <select id="pindah_kelas_id" name="kelas_id" required class="form-select">
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }} (Kapasitas sisa: {{ $k->kapasitas - $k->jumlah_siswa }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400 mt-1 italic">Hanya menampilkan daftar kelas pada Tahun Ajaran yang sedang aktif.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
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
