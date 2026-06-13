@extends('layouts.admin')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('breadcrumb-parent', 'Kelas')
@section('breadcrumb-current', $kelas->nama_kelas)

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Kelas {{ $kelas->nama_kelas }}</h1>
            <p class="page-subtitle">Detail informasi rombongan belajar, wali kelas, siswa, dan jadwal pelajaran.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.kelas.edit', $kelas) }}" class="btn-gold">
                Edit Kelas
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left Column: Info Card --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Informasi Kelas</h3>
                </div>
                <div class="card-body space-y-5">
                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Tahun Ajaran</span>
                        <span class="font-bold text-slate-800 text-sm mt-1 block">{{ $kelas->tahunAjaran->nama_lengkap }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jurusan</span>
                        <span class="font-bold text-slate-800 text-sm mt-1 block">{{ $kelas->jurusan?->nama ?? 'Umum (Tanpa Jurusan)' }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Wali Kelas</span>
                        @if($kelas->waliKelas)
                            <div class="flex items-center gap-3 mt-2">
                                <div class="avatar-placeholder w-10 h-10 text-sm">
                                    {{ strtoupper(substr($kelas->waliKelas->name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">{{ $kelas->waliKelas->name }}</span>
                                    <span class="text-xs text-slate-400 block">{{ $kelas->waliKelas->email }}</span>
                                </div>
                            </div>
                        @else
                            <span class="text-slate-400 italic text-sm mt-1 block">Wali kelas belum ditentukan.</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider mb-2">Kapasitas Kelas</span>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-1">
                            <span>Keterisian Kelas</span>
                            <span>{{ $kelas->jumlah_siswa }} / {{ $kelas->kapasitas }} Siswa</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="bg-navy-500 h-full rounded-full" style="width: {{ min(($kelas->jumlah_siswa / $kelas->kapasitas) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Tabs (Siswa / Jadwal) --}}
        <div class="lg:col-span-8">
            <div class="card">
                
                {{-- Tabs Navigation --}}
                <div class="border-b border-slate-100 flex">
                    <button onclick="switchTab('siswa')" class="tab-btn px-6 py-4 font-display font-bold text-xs uppercase tracking-wider border-b-2 border-navy-500 text-navy-500 transition-all">
                        Daftar Siswa ({{ $kelas->siswas->count() }})
                    </button>
                    <button onclick="switchTab('jadwal')" class="tab-btn px-6 py-4 font-display font-bold text-xs uppercase tracking-wider border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">
                        Jadwal Pelajaran ({{ $kelas->jadwalPelajarans->count() }})
                    </button>
                </div>

                {{-- Tab Content: Siswa --}}
                <div id="tab-siswa" class="tab-content">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>NIS / NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>L/P</th>
                                    <th>Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas->siswas as $siswa)
                                    <tr>
                                        <td class="font-mono text-slate-500 text-xs">
                                            {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}
                                        </td>
                                        <td class="font-bold text-slate-800">
                                            <div class="flex items-center gap-2">
                                                @if($siswa->foto)
                                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto" class="w-6 h-6 rounded-full object-cover">
                                                @else
                                                    <div class="avatar-placeholder w-6 h-6 text-[9px] bg-slate-300">
                                                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <span>{{ $siswa->nama_lengkap }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $siswa->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
                                                {{ $siswa->jenis_kelamin }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($siswa->status == 'aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-gray capitalize">{{ $siswa->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right whitespace-nowrap">
                                            <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn-secondary btn-sm bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn-secondary btn-sm">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                	@empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada siswa terdaftar di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Content: Jadwal --}}
                <div id="tab-jadwal" class="tab-content hidden">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru Pengampu</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas->jadwalPelajarans->sortBy('hari') as $jp)
                                    <tr>
                                        <td class="font-bold text-slate-800 capitalize">{{ $jp->hari }}</td>
                                        <td class="font-mono text-slate-600 text-xs">{{ $jp->jam_mulai }} - {{ $jp->jam_selesai }}</td>
                                        <td class="font-semibold text-navy-500">{{ $jp->mataPelajaran->nama }}</td>
                                        <td class="font-medium text-slate-700">{{ $jp->guru->name }}</td>
                                        <td class="text-right whitespace-nowrap">
                                            <a href="{{ route('admin.jadwal.edit', $jp) }}" class="btn-secondary btn-sm">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada jadwal pelajaran untuk kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function switchTab(tabName) {
        // Hide all content blocks
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Reset all tab button styles
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-navy-500', 'text-navy-500');
            el.classList.add('border-transparent', 'text-slate-400', 'hover:text-slate-600');
        });
        
        // Show selected tab content and active state
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');
        event.currentTarget.classList.remove('border-transparent', 'text-slate-400', 'hover:text-slate-600');
        event.currentTarget.classList.add('border-navy-500', 'text-navy-500');
    }
</script>
@endpush
@endsection
