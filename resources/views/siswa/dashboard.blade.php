@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('siswa-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display text-slate-900 tracking-tight">Halo, {{ $siswa->nama_lengkap }}</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                <span>NIS: <strong class="font-mono text-slate-700">{{ $siswa->nis }}</strong></span>
                <span class="mx-1.5 text-slate-300">·</span>
                <span>Kelas: <strong class="text-blue-700 font-semibold">{{ $siswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</strong></span>
            </p>
        </div>
        <div>
            @if($tahunAktif)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white shadow-2xs text-xs font-medium text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span>Tahun Ajaran {{ $tahunAktif->tahun_ajaran }} · Semester {{ $tahunAktif->semester == '1' || $tahunAktif->semester == 'ganjil' ? 'Ganjil' : 'Genap' }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Attendance Overview --}}
    @if($rekapAbsensi)
        @php
            $total = $rekapAbsensi['hadir'] + $rekapAbsensi['sakit'] + $rekapAbsensi['izin'] + $rekapAbsensi['alpa'];
            $persen = $total > 0 ? round(($rekapAbsensi['hadir'] / $total) * 100, 1) : 100;
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            {{-- Rasio --}}
            <div class="card p-4 col-span-2 sm:col-span-1 flex flex-col justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tingkat Hadir</p>
                <div class="mt-2">
                    <p class="text-2xl font-extrabold font-display text-blue-700 tabular-nums leading-none">{{ $persen }}%</p>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $rekapAbsensi['hadir'] }}/{{ $total }} sesi</p>
                </div>
            </div>

            {{-- Hadir --}}
            <div class="card p-4 flex flex-col justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Hadir</p>
                <div class="mt-2">
                    <p class="text-2xl font-extrabold font-display text-emerald-700 tabular-nums leading-none">{{ $rekapAbsensi['hadir'] }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">sesi tepat waktu</p>
                </div>
            </div>

            {{-- Sakit --}}
            <div class="card p-4 flex flex-col justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Sakit</p>
                <div class="mt-2">
                    <p class="text-2xl font-extrabold font-display text-blue-600 tabular-nums leading-none">{{ $rekapAbsensi['sakit'] }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">dengan surat</p>
                </div>
            </div>

            {{-- Izin --}}
            <div class="card p-4 flex flex-col justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Izin</p>
                <div class="mt-2">
                    <p class="text-2xl font-extrabold font-display text-amber-600 tabular-nums leading-none">{{ $rekapAbsensi['izin'] }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">dengan izin</p>
                </div>
            </div>

            {{-- Alpa --}}
            <div class="card p-4 flex flex-col justify-between">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Alpa</p>
                <div class="mt-2">
                    <p class="text-2xl font-extrabold font-display text-rose-600 tabular-nums leading-none">{{ $rekapAbsensi['alpa'] }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">tanpa keterangan</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Main 2-Column Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: Today's Schedule (8 cols) --}}
        <div class="lg:col-span-8 card">
            <div class="card-header">
                <div>
                    <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Jadwal Pelajaran Hari Ini</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Sesi tatap muka kelas {{ $siswa->kelas?->nama_kelas ?? '' }}</p>
                </div>
                <a href="{{ route('siswa.jadwal') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Mingguan
                </a>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-20">Jam Ke</th>
                            <th class="w-28">Waktu</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th class="w-28">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalHariIni as $j)
                            <tr>
                                <td>
                                    <span class="badge badge-gray font-mono font-semibold">Ke-{{ $j->urutan_jam }}</span>
                                </td>
                                <td class="font-mono text-xs font-medium text-slate-600">
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="font-semibold text-slate-900">
                                    {{ $j->mataPelajaran->nama }}
                                </td>
                                <td class="text-xs text-slate-600">
                                    {{ $j->guru->nama_lengkap }}
                                </td>
                                <td>
                                    <span class="text-xs font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ $j->ruangan ?? 'Kelas' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400">
                                    <div class="max-w-xs mx-auto text-center">
                                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs font-medium text-slate-600">Tidak ada jadwal belajar hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Recent Grades (4 cols) --}}
        <div class="lg:col-span-4 card flex flex-col justify-between">
            <div>
                <div class="card-header">
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight">Nilai Rapor Terkini</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Penilaian akhir semester aktif</p>
                    </div>
                </div>

                <div class="p-4 space-y-2.5">
                    @forelse($nilaiTerbaru as $n)
                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-slate-300 transition-colors">
                            <div class="min-w-0 pr-2">
                                <p class="font-bold text-xs text-slate-900 truncate" title="{{ $n->mataPelajaran->nama }}">
                                    {{ $n->mataPelajaran->nama }}
                                </p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">KKM: {{ $n->mataPelajaran->kkm }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold font-mono text-sm text-slate-900">{{ $n->nilai_akhir }}</span>
                                <span class="ml-1 text-[11px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $n->predikat }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs italic text-center py-8">Belum ada nilai terpublikasi.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-4 pt-0">
                <a href="{{ route('siswa.nilai') }}" class="btn-secondary w-full justify-center text-xs font-semibold">
                    Buka Rapor Lengkap
                </a>
            </div>
        </div>

    </div>

    {{-- Pengumuman & Agenda Akademik (F5) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Widget Pengumuman Sekolah --}}
        <div class="card p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900">Pengumuman & Informasi Siswa</h2>
                        <p class="text-[11px] text-slate-500">Pemberitahuan resmi sekolah & kelas Anda</p>
                    </div>
                </div>
                <a href="{{ route('siswa.pengumuman.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($pengumumans as $p)
                    <div class="py-2.5 flex items-start justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold {{ $p->target === 'kelas' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ $p->target === 'kelas' ? 'Kelas Anda' : 'Sekolah' }}
                            </span>
                            <p class="text-xs font-bold text-slate-900 mt-1 line-clamp-1">{{ $p->judul }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $p->published_at ? $p->published_at->locale('id')->isoFormat('D MMM YYYY') : '' }}</p>
                        </div>
                        <a href="{{ route('siswa.pengumuman.show', $p) }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 shrink-0">
                            Baca
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Belum ada pengumuman untuk Anda saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Widget Agenda Kalender Akademik Terdekat --}}
        <div class="card p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gold-50 text-gold-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900">Agenda Kalender Terdekat</h2>
                        <p class="text-[11px] text-slate-500">Jadwal ujian, libur, dan kegiatan</p>
                    </div>
                </div>
                <a href="{{ route('siswa.kalender') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Kalender &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($upcomingEvents as $ev)
                    <div class="py-2.5 flex items-start justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold {{ $ev->badge_class }}">
                                {{ $ev->label_kategori }}
                            </span>
                            <p class="text-xs font-bold text-slate-900 mt-1 line-clamp-1">{{ $ev->judul }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{ $ev->tanggal_mulai->locale('id')->isoFormat('D MMMM YYYY') }}
                                @if($ev->tanggal_selesai && $ev->tanggal_selesai != $ev->tanggal_mulai)
                                    - {{ $ev->tanggal_selesai->locale('id')->isoFormat('D MMMM YYYY') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Tidak ada agenda terdekat dalam waktu dekat.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
