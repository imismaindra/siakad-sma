@extends('layouts.orang-tua')

@section('title', 'Dashboard Monitoring Anak')
@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('orang-tua-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Child Selector & Active Banner (F4-02 & F4-04) --}}
    @include('orang-tua._anak_selector')

    {{-- Attendance Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        {{-- Tingkat Hadir --}}
        <div class="card p-4 col-span-2 sm:col-span-1 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tingkat Hadir</p>
            <div class="mt-2">
                <p class="text-2xl font-extrabold font-display text-blue-700 tabular-nums leading-none">{{ $attendanceStats['persen'] }}%</p>
                <p class="text-[11px] text-slate-400 mt-1">dari {{ $attendanceStats['total'] }} total sesi</p>
            </div>
        </div>

        {{-- Hadir --}}
        <div class="card p-4 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Hadir</p>
            <div class="mt-2">
                <p class="text-2xl font-extrabold font-display text-emerald-700 tabular-nums leading-none">{{ $attendanceStats['hadir'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">sesi tepat waktu</p>
            </div>
        </div>

        {{-- Sakit --}}
        <div class="card p-4 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Sakit</p>
            <div class="mt-2">
                <p class="text-2xl font-extrabold font-display text-blue-600 tabular-nums leading-none">{{ $attendanceStats['sakit'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">dengan surat dokter</p>
            </div>
        </div>

        {{-- Izin --}}
        <div class="card p-4 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Izin</p>
            <div class="mt-2">
                <p class="text-2xl font-extrabold font-display text-amber-600 tabular-nums leading-none">{{ $attendanceStats['izin'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">izin orang tua</p>
            </div>
        </div>

        {{-- Alpa --}}
        <div class="card p-4 flex flex-col justify-between">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Alpa</p>
            <div class="mt-2">
                <p class="text-2xl font-extrabold font-display text-rose-600 tabular-nums leading-none">{{ $attendanceStats['alpa'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">tanpa keterangan</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Kolom Kiri: Jadwal Hari Ini & Ringkasan Nilai (8 Kolom) --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Jadwal Hari Ini --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Jadwal Pelajaran Hari Ini ({{ $todayIndo }})</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Mata pelajaran kelas {{ $activeSiswa->kelas?->nama_kelas ?? '-' }}</p>
                    </div>
                    <a href="{{ route('orang-tua.jadwal') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                        Lihat Mingguan
                    </a>
                </div>

                <div class="p-4">
                    @if($todaySchedule->isEmpty())
                        <div class="p-8 text-center bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs font-medium text-slate-500">Tidak ada jadwal pelajaran untuk hari ini.</p>
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($todaySchedule as $jadwal)
                                <div class="p-3 rounded-lg border border-slate-200/80 bg-slate-50/40 hover:bg-white hover:border-slate-300 transition-colors flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-mono font-bold shrink-0 border border-blue-200">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </div>
                                        <div>
                                            <h3 class="text-xs font-bold text-slate-900">{{ $jadwal->mataPelajaran->nama_pelajaran ?? $jadwal->mataPelajaran->nama }}</h3>
                                            <p class="text-[11px] text-slate-500 mt-0.5">Guru: {{ $jadwal->guru->nama_lengkap ?? '-' }} · Ruang: {{ $jadwal->ruangan ?? 'Kelas' }}</p>
                                        </div>
                                    </div>
                                    <span class="badge badge-gray text-[10px] font-mono">Aktif</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Perkembangan Nilai Akademik --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                            </svg>
                            <span>Perkembangan Nilai Akademik</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Rata-rata semester berjalan: <span class="font-bold text-slate-900">{{ $avgScore }}</span> / 100</p>
                    </div>
                    <a href="{{ route('orang-tua.nilai') }}" class="btn-secondary btn-sm">
                        Buka e-Rapor
                    </a>
                </div>

                <div class="p-4">
                    @if($grades->isEmpty())
                        <div class="p-8 text-center bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-xs font-medium text-slate-500">Nilai mata pelajaran semester ini belum dipublikasikan oleh pihak sekolah.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($grades->take(6) as $grade)
                                <div class="p-3 rounded-lg border border-slate-200/80 bg-slate-50/40 flex items-center justify-between">
                                    <div class="min-w-0 pr-2">
                                        <p class="text-xs font-bold text-slate-900 truncate">{{ $grade->mataPelajaran->nama_pelajaran ?? $grade->mataPelajaran->nama }}</p>
                                        <p class="text-[11px] text-slate-500">Predikat: <strong class="text-slate-800">{{ $grade->predikat ?? '-' }}</strong></p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-base font-bold font-mono {{ ($grade->nilai_akhir ?? 0) >= 75 ? 'text-emerald-700' : 'text-amber-700' }}">
                                            {{ $grade->nilai_akhir ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Presensi Terkini & Pengumuman Sekolah (4 Kolom) --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Presensi Terkini --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight">Presensi Terkini</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Catatan presensi kelas harian</p>
                    </div>
                    <a href="{{ route('orang-tua.absensi') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                        Semua
                    </a>
                </div>

                <div class="p-4">
                    @if($recentAbsensi->isEmpty())
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada rekaman presensi.</p>
                    @else
                        <div class="space-y-2.5">
                            @foreach($recentAbsensi as $record)
                                <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/40 text-xs">
                                    <div class="min-w-0 pr-2">
                                        <p class="font-bold text-slate-900 truncate">
                                            {{ $record->absensi?->jadwalPelajaran?->mataPelajaran?->nama_pelajaran ?? $record->absensi?->jadwalPelajaran?->mataPelajaran?->nama ?? 'Presensi Kelas' }}
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $record->absensi?->tanggal ? \Carbon\Carbon::parse($record->absensi->tanggal)->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        @php
                                            $badgeStyles = [
                                                'hadir' => 'badge-success',
                                                'sakit' => 'badge-info',
                                                'izin'  => 'badge-warning',
                                                'alpa'  => 'badge-danger',
                                            ];
                                        @endphp
                                        <span class="badge {{ $badgeStyles[$record->status] ?? 'badge-gray' }} uppercase text-[10px] font-bold">
                                            {{ $record->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pengumuman Sekolah (F4-05) --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                            <span>Pengumuman Sekolah</span>
                        </h2>
                    </div>
                    <a href="{{ route('orang-tua.pengumuman') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                        Lihat Semua
                    </a>
                </div>

                <div class="p-4">
                    @if($pengumumans->isEmpty())
                        <p class="text-xs text-slate-400 text-center py-6">Tidak ada pengumuman terbaru.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($pengumumans as $info)
                                <a href="{{ route('orang-tua.pengumuman.show', $info) }}" class="block p-3 rounded-lg border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/20 transition-all group">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                                        <span class="font-medium">{{ $info->published_at ? $info->published_at->format('d M Y') : '' }}</span>
                                        <span class="badge {{ $info->target === 'semua' ? 'badge-info' : 'badge-warning' }} text-[9px] uppercase">
                                            {{ $info->target === 'semua' ? 'Umum' : 'Kelas' }}
                                        </span>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-1">
                                        {{ $info->judul }}
                                    </h3>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                        {{ Str::limit(strip_tags($info->konten), 90) }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kalender Akademik Terdekat (F5) --}}
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="text-sm font-bold font-display text-slate-900 tracking-tight flex items-center gap-2">
                            <svg class="w-4 h-4 text-gold-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            <span>Agenda Akademik Terdekat</span>
                        </h2>
                    </div>
                    <a href="{{ route('orang-tua.kalender') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                        Buka Kalender
                    </a>
                </div>

                <div class="p-4 divide-y divide-slate-100">
                    @forelse($upcomingEvents as $ev)
                        <div class="py-2.5 first:pt-0 last:pb-0">
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
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada agenda terdekat yang dijadwalkan.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
