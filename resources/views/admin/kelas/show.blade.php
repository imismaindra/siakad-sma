@extends('layouts.admin')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('breadcrumb-parent', 'Kelas')
@section('breadcrumb-current', $kelas->nama_kelas)

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Rombongan belajar</p>
            <h1 class="page-title font-display !text-3xl mt-3">Kelas {{ $kelas->nama_kelas }}.</h1>
            <p class="page-subtitle">Wali kelas, daya tampung, siswa, dan jadwal dalam satu layar.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.kelas.edit', $kelas) }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                Edit kelas
                <span class="w-8 h-8 rounded-full bg-gold-500 text-navy-900 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                </span>
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Identity island --}}
        <div class="lg:col-span-4 rounded-[2rem] bg-navy-800 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-7 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                <p class="font-display font-extrabold tracking-tight text-white text-4xl">{{ $kelas->nama_kelas }}</p>
                <p class="mt-1 text-sm text-slate-400">{{ $kelas->tahunAjaran->nama_lengkap }}</p>

                <dl class="mt-6 divide-y divide-white/10 border-y border-white/10">
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Jurusan</dt>
                        <dd class="mt-1 font-bold text-white text-[15px]">{{ $kelas->jurusan?->nama ?? 'Umum (Tanpa Jurusan)' }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Wali kelas</dt>
                        <dd class="mt-2">
                            @if($kelas->waliKelas)
                                <div class="flex items-center gap-3">
                                    <div class="avatar-placeholder w-10 h-10 text-sm shrink-0">
                                        {{ strtoupper(substr($kelas->waliKelas->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-white text-sm block truncate">{{ $kelas->waliKelas->name }}</span>
                                        <span class="text-xs text-slate-400 block truncate">{{ $kelas->waliKelas->email }}</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400 text-sm">Wali kelas belum ditentukan.</span>
                            @endif
                        </dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-[11px] font-medium uppercase tracking-[0.2em] text-slate-400">Keterisian</dt>
                        <dd class="mt-2 flex items-end justify-between gap-3">
                            <span class="font-display font-extrabold tracking-tight text-white text-3xl tabular-nums">{{ $kelas->jumlah_siswa }}<span class="text-lg text-slate-400">/{{ $kelas->kapasitas }}</span></span>
                            <span class="text-xs text-slate-400 mb-1">siswa</span>
                        </dd>
                        <div class="mt-3 h-2 rounded-full overflow-hidden flex" aria-hidden="true">
                            <div class="bg-gold-500 h-full rounded-full" style="width: {{ min(($kelas->jumlah_siswa / max($kelas->kapasitas, 1)) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Tabs island --}}
        <div class="lg:col-span-8 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)]">
            <div class="rounded-[calc(2rem-0.5rem)] p-3 sm:p-4">
                <div class="inline-flex rounded-full bg-slate-100 p-1 gap-1" role="tablist">
                    <button onclick="switchTab('siswa', this)" role="tab" class="tab-btn rounded-full px-5 py-2 text-[13px] font-bold bg-navy-800 text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                        Siswa ({{ $kelas->siswas->count() }})
                    </button>
                    <button onclick="switchTab('jadwal', this)" role="tab" class="tab-btn rounded-full px-5 py-2 text-[13px] font-bold text-slate-500 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:text-navy-800">
                        Jadwal ({{ $kelas->jadwalPelajarans->count() }})
                    </button>
                </div>

                {{-- Siswa --}}
                <div id="tab-siswa" class="tab-content mt-3">
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>NIS / NISN</th>
                                    <th>L/P</th>
                                    <th>Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas->siswas as $siswa)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                @if($siswa->foto)
                                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama_lengkap }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                                                @else
                                                    <div class="avatar-placeholder w-9 h-9 text-[10px] shrink-0">
                                                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <span class="font-bold text-navy-900">{{ $siswa->nama_lengkap }}</span>
                                            </div>
                                        </td>
                                        <td class="font-mono text-slate-500 text-xs tabular-nums whitespace-nowrap">
                                            {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $siswa->jenis_kelamin == 'L' ? 'badge-info' : 'badge-gray' }}">
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
                                            <a href="{{ route('admin.siswa.show', $siswa) }}" aria-label="Detail {{ $siswa->nama_lengkap }}" class="inline-flex w-9 h-9 rounded-full bg-slate-100 text-navy-800 items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-slate-200 active:scale-[0.98]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.siswa.edit', $siswa) }}" aria-label="Edit {{ $siswa->nama_lengkap }}" class="inline-flex w-9 h-9 rounded-full bg-gold-100 text-gold-700 ring-1 ring-gold-200 items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-200 active:scale-[0.98]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <p class="font-bold text-navy-900">Belum ada siswa di kelas ini.</p>
                                            <p class="text-sm text-slate-500 mt-1">Tambahkan siswa lewat halaman data siswa.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Jadwal --}}
                <div id="tab-jadwal" class="tab-content hidden mt-3">
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas->jadwalPelajarans->sortBy('hari') as $jp)
                                    <tr>
                                        <td><span class="badge badge-navy capitalize">{{ $jp->hari }}</span></td>
                                        <td class="font-mono text-slate-500 text-xs tabular-nums whitespace-nowrap">{{ $jp->jam_mulai }} - {{ $jp->jam_selesai }}</td>
                                        <td class="font-bold text-navy-900">{{ $jp->mataPelajaran->nama }}</td>
                                        <td class="text-slate-500">{{ $jp->guru->name }}</td>
                                        <td class="text-right whitespace-nowrap">
                                            <a href="{{ route('admin.jadwal.edit', $jp) }}" aria-label="Edit jadwal {{ $jp->mataPelajaran->nama }}" class="inline-flex w-9 h-9 rounded-full bg-gold-100 text-gold-700 ring-1 ring-gold-200 items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-200 active:scale-[0.98]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <p class="font-bold text-navy-900">Belum ada jadwal untuk kelas ini.</p>
                                            <p class="text-sm text-slate-500 mt-1">Susun jadwal lewat halaman jadwal pelajaran.</p>
                                        </td>
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
    function switchTab(tabName, btn) {
        document.querySelectorAll('.tab-content').forEach(function (el) { el.classList.add('hidden'); });
        document.querySelectorAll('.tab-btn').forEach(function (el) {
            el.classList.remove('bg-navy-800', 'text-white');
            el.classList.add('text-slate-500');
        });
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        btn.classList.remove('text-slate-500');
        btn.classList.add('bg-navy-800', 'text-white');
    }
</script>
@endpush
@endsection
