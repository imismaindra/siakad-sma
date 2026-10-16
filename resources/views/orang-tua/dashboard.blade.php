@extends('layouts.orang-tua')

@section('title', 'Dashboard Monitoring Anak')
@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('orang-tua-content')
<div class="space-y-6 animate-fade-in-up">

    {{-- Child Selector & Active Banner (F4-02 & F4-04) --}}
    @include('orang-tua._anak_selector')

    {{-- Attendance Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        {{-- Rasio Kehadiran --}}
        <div class="stat-card navy col-span-2 md:col-span-1 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tingkat Hadir</p>
                <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black font-display text-navy-600">{{ $attendanceStats['persen'] }}%</p>
                <p class="text-[11px] text-slate-400 mt-0.5">dari {{ $attendanceStats['total'] }} total sesi</p>
            </div>
        </div>

        {{-- Hadir --}}
        <div class="stat-card green flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hadir</p>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black font-display text-emerald-600">{{ $attendanceStats['hadir'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">sesi pertemuan</p>
            </div>
        </div>

        {{-- Sakit --}}
        <div class="stat-card navy flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sakit</p>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black font-display text-blue-600">{{ $attendanceStats['sakit'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">dengan surat</p>
            </div>
        </div>

        {{-- Izin --}}
        <div class="stat-card gold flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Izin</p>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black font-display text-amber-600">{{ $attendanceStats['izin'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">keterangan izin</p>
            </div>
        </div>

        {{-- Alpa --}}
        <div class="stat-card red flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alpa</p>
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            </div>
            <div class="mt-2">
                <p class="text-2xl font-black font-display text-rose-600">{{ $attendanceStats['alpa'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">tanpa keterangan</p>
            </div>
        </div>
    </div>

    {{-- Main Content Bento --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Jadwal Hari Ini & Ringkasan Nilai (2 Kolom) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Jadwal Hari Ini --}}
            <div class="card p-6 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold font-display text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Jadwal Pelajaran Hari Ini ({{ $todayIndo }})
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Jadwal mata pelajaran kelas {{ $activeSiswa->kelas?->nama_kelas ?? '-' }}</p>
                    </div>
                    <a href="{{ route('orang-tua.jadwal') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                        Lihat Mingguan
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                @if($todaySchedule->isEmpty())
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs font-medium text-slate-500">Tidak ada jadwal pelajaran untuk hari ini.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($todaySchedule as $jadwal)
                            <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition-colors flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="px-2.5 py-1.5 rounded-lg bg-sky-100 text-sky-800 text-xs font-mono font-bold shrink-0">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $jadwal->mataPelajaran->nama_pelajaran }}</h4>
                                        <p class="text-xs text-slate-500">Guru: {{ $jadwal->guru->nama_lengkap ?? '-' }} • Ruang: {{ $jadwal->ruangan ?? 'Kelas' }}</p>
                                    </div>
                                </div>
                                <span class="badge badge-blue text-[11px]">Aktif</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Ringkasan Akademik & Nilai --}}
            <div class="card p-6 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold font-display text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Perkembangan Nilai Akademik
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Rata-rata nilai semester berjalan: <span class="font-bold text-slate-700">{{ $avgScore }}</span> / 100</p>
                    </div>
                    <a href="{{ route('orang-tua.nilai') }}" class="btn btn-secondary text-xs">
                        Lihat Nilai & e-Rapor
                    </a>
                </div>

                @if($grades->isEmpty())
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-xs font-medium text-slate-500">Nilai mata pelajaran semester ini belum diinput oleh dewan guru.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($grades->take(6) as $grade)
                            <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/40 flex items-center justify-between">
                                <div class="min-w-0 pr-2">
                                    <p class="text-xs font-bold text-slate-700 truncate">{{ $grade->mataPelajaran->nama_pelajaran }}</p>
                                    <p class="text-[11px] text-slate-400">Predikat: <strong class="text-slate-600">{{ $grade->predikat ?? '-' }}</strong></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-base font-black font-display {{ ($grade->nilai_akhir ?? 0) >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $grade->nilai_akhir ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Kolom Kanan: Presensi Terkini & Pengumuman Sekolah (1 Kolom) --}}
        <div class="space-y-6">

            {{-- Presensi Terkini --}}
            <div class="card p-6 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold font-display text-slate-800">Presensi Terkini</h3>
                    <a href="{{ route('orang-tua.absensi') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Semua</a>
                </div>

                @if($recentAbsensi->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada rekaman presensi.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentAbsensi as $record)
                            <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 text-xs">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-700 truncate">
                                        {{ $record->absensi?->jadwalPelajaran?->mataPelajaran?->nama_pelajaran ?? 'Presensi Kelas' }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $record->absensi?->tanggal ? \Carbon\Carbon::parse($record->absensi->tanggal)->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    @php
                                        $badgeStyles = [
                                            'hadir' => 'badge-green',
                                            'sakit' => 'badge-blue',
                                            'izin'  => 'badge-gold',
                                            'alpa'  => 'badge-red',
                                        ];
                                    @endphp
                                    <span class="badge {{ $badgeStyles[$record->status] ?? 'badge-slate' }} uppercase text-[10px] font-bold">
                                        {{ $record->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Pengumuman Sekolah (F4-05) --}}
            <div class="card p-6 border border-slate-200/80 shadow-xs rounded-2xl bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold font-display text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Pengumuman
                    </h3>
                    <a href="{{ route('orang-tua.pengumuman') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Lihat Semua</a>
                </div>

                @if($pengumumans->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6">Tidak ada pengumuman terbaru.</p>
                @else
                    <div class="space-y-3">
                        @foreach($pengumumans as $info)
                            <a href="{{ route('orang-tua.pengumuman.show', $info) }}" class="block p-3 rounded-xl border border-slate-100 hover:border-sky-300 hover:bg-sky-50/30 transition-all group">
                                <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                                    <span class="font-medium">{{ $info->published_at ? $info->published_at->format('d M Y') : '' }}</span>
                                    <span class="badge {{ $info->target === 'semua' ? 'badge-blue' : 'badge-gold' }} text-[9px] uppercase">
                                        {{ $info->target === 'semua' ? 'Umum' : 'Kelas' }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-sky-600 transition-colors line-clamp-1">
                                    {{ $info->judul }}
                                </h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">
                                    {{ Str::limit(strip_tags($info->konten), 90) }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
