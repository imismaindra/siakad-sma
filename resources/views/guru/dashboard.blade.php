@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display text-slate-900 tracking-tight">Selamat Datang, {{ $guru->nama_lengkap }}</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Portal Operasional Pengajaran, Presensi Siswa, dan Pengelolaan Nilai.</p>
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

    {{-- 3 Core Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Jadwal Hari Ini --}}
        <div class="card p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Jadwal Mengajar Hari Ini</p>
                    <p class="mt-2 text-3xl font-extrabold font-display text-slate-900 tabular-nums leading-none">
                        {{ count($jadwalHariIni) }} <span class="text-base font-medium text-slate-500">Kelas</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Sesi tatap muka terjadwal hari ini
            </div>
        </div>

        {{-- Sesi Bulan Ini --}}
        <div class="card p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Sesi Mengajar Bulan Ini</p>
                    <p class="mt-2 text-3xl font-extrabold font-display text-slate-900 tabular-nums leading-none">
                        {{ $totalSesiMengajar }} <span class="text-base font-medium text-slate-500">Sesi</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                Total presensi terdata bulan berjalan
            </div>
        </div>

        {{-- Status Presensi --}}
        <div class="card p-5 flex flex-col justify-between {{ count($belumAbsen) > 0 ? 'bg-amber-50/40 border-amber-200/80' : '' }}">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider {{ count($belumAbsen) > 0 ? 'text-amber-800' : 'text-slate-500' }}">Tunggakan Presensi</p>
                    <p class="mt-2 text-3xl font-extrabold font-display {{ count($belumAbsen) > 0 ? 'text-amber-900' : 'text-slate-900' }} tabular-nums leading-none">
                        {{ count($belumAbsen) }} <span class="text-base font-medium {{ count($belumAbsen) > 0 ? 'text-amber-700' : 'text-slate-500' }}">Sesi</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg {{ count($belumAbsen) > 0 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t {{ count($belumAbsen) > 0 ? 'border-amber-200/60' : 'border-slate-100' }} text-xs {{ count($belumAbsen) > 0 ? 'text-amber-700 font-medium' : 'text-slate-500' }}">
                {{ count($belumAbsen) > 0 ? 'Perlu pengisian presensi siswa hari ini' : 'Semua sesi hari ini sudah dipresensi' }}
            </div>
        </div>

    </div>

    {{-- Agenda Mengajar Hari Ini --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span>Agenda Mengajar Hari Ini · {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar kelas dan mata pelajaran yang diampu pada hari ini.</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-24">Jam Ke</th>
                        <th class="w-32">Waktu</th>
                        <th class="w-32">Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th class="w-32">Ruangan</th>
                        <th class="w-40">Status Presensi</th>
                        <th class="text-right w-44">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalHariIni as $j)
                        @php
                            $absenExist = \App\Models\Absensi::where('jadwal_pelajaran_id', $j->id)
                                ->whereDate('tanggal', today())
                                ->first();
                            $sesiQr = \App\Models\SesiAbsensiQr::where('jadwal_pelajaran_id', $j->id)
                                ->whereDate('tanggal', today())
                                ->where('status', 'aktif')
                                ->first();
                        @endphp
                        <tr>
                            <td>
                                <span class="badge badge-gray font-mono font-semibold">Ke-{{ $j->urutan_jam }}</span>
                            </td>
                            <td class="font-mono text-xs font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                            </td>
                            <td>
                                <span class="font-bold text-slate-900 font-mono">{{ $j->kelas->nama_kelas }}</span>
                            </td>
                            <td class="font-semibold text-slate-800">
                                {{ $j->mataPelajaran->nama }}
                            </td>
                            <td class="text-xs text-slate-600">
                                {{ $j->ruangan ?? 'Ruang Kelas' }}
                            </td>
                            <td>
                                @if($sesiQr)
                                    <span class="badge badge-status-aktif font-semibold">
                                        QR Aktif
                                    </span>
                                @elseif($absenExist)
                                    <span class="badge badge-absen-sakit font-semibold">
                                        Sudah Diabsen
                                    </span>
                                @else
                                    <span class="badge badge-absen-alpa font-semibold">
                                        Belum Diabsen
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if($sesiQr)
                                        <a href="{{ route('guru.absensi.qr.show', $sesiQr) }}" class="btn-primary btn-sm !bg-emerald-600 hover:!bg-emerald-700">
                                            Layar QR
                                        </a>
                                    @elseif(!$absenExist)
                                        <form method="POST" action="{{ route('guru.absensi.qr.buka', $j->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-primary btn-sm" title="Buka Presensi QR Dinamis">
                                                Buka QR
                                            </button>
                                        </form>
                                        <a href="{{ route('guru.absensi.create', $j->id) }}" class="btn-secondary btn-sm">
                                            Manual
                                        </a>
                                    @else
                                        <a href="{{ route('guru.absensi.show', $absenExist->id) }}" class="btn-secondary btn-sm">
                                            Lihat Detail
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <div class="max-w-xs mx-auto text-center">
                                    <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                    <p class="text-xs font-medium text-slate-600">Tidak ada jadwal mengajar terdaftar untuk Anda hari ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                        <h2 class="text-sm font-bold font-display text-slate-900">Pengumuman & Warta Guru</h2>
                        <p class="text-[11px] text-slate-500">Informasi kedinasan terbaru</p>
                    </div>
                </div>
                <a href="{{ route('guru.pengumuman.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($pengumumans as $p)
                    <div class="py-2.5 flex items-start justify-between gap-3 first:pt-0 last:pb-0">
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold {{ $p->target === 'guru' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ $p->target === 'guru' ? 'Dewan Guru' : 'Semua' }}
                            </span>
                            <p class="text-xs font-bold text-slate-900 mt-1 line-clamp-1">{{ $p->judul }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $p->published_at ? $p->published_at->locale('id')->isoFormat('D MMM YYYY') : '' }}</p>
                        </div>
                        <a href="{{ route('guru.pengumuman.show', $p) }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 shrink-0">
                            Baca
                        </a>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">
                        Belum ada pengumuman terbaru untuk dewan guru.
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
                        <p class="text-[11px] text-slate-500">Jadwal penting & kalender pendidikan</p>
                    </div>
                </div>
                <a href="{{ route('guru.kalender') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
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
                            <p class="text-xs font-bold text-slate-900 mt-1 line-clamp-1">
                                {{ $ev->judul }}
                                @if($ev->dokumen)
                                    <span class="text-[10px] text-navy-700 ml-1" title="Ada Dokumen">📎</span>
                                @endif
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                {{ $ev->periode_formatted }}
                                @if($ev->jam_mulai)
                                    · {{ $ev->waktu_formatted }}
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
