@extends('layouts.guru')

@section('title', 'Layar Absensi QR - ' . $sesi->jadwalPelajaran->mataPelajaran->nama)

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Sesi QR')

@section('guru-content')
@php
    $totalSiswaAwal = $sesi->jadwalPelajaran->kelas->siswas()->aktif()->count();
    $initialPayload = $sesi->getQrPayload();
@endphp
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Header Sesi --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sesi QR Aktif
                </span>
                <span class="text-xs text-slate-400 font-mono">Sesi #{{ $sesi->id }}</span>
            </div>
            <h1 class="text-2xl font-bold font-display text-slate-800">
                {{ $sesi->jadwalPelajaran->mataPelajaran->nama }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Kelas <span class="font-bold text-navy-600">{{ $sesi->jadwalPelajaran->kelas->nama_lengkap }}</span> · 
                {{ $sesi->tanggal->locale('id')->isoFormat('dddd, D MMMM YYYY') }} · 
                Pukul {{ \Carbon\Carbon::parse($sesi->jadwalPelajaran->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jadwalPelajaran->jam_selesai)->format('H:i') }} WIB
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('guru.absensi.index') }}" class="btn-secondary text-xs">
                Kembali ke Daftar
            </a>

            <form method="POST" action="{{ route('guru.absensi.qr.tutup', $sesi) }}" onsubmit="return confirm('Apakah Anda yakin ingin menutup sesi absensi QR ini? Siswa tidak akan dapat memindai lagi.');">
                @csrf
                <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tutup Sesi & Finalisasi
                </button>
            </form>
        </div>
    </div>

    {{-- Main Presentation Stage --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Left: QR Code Display (Proyektor Stage) --}}
        <div class="lg:col-span-6 flex flex-col items-center justify-center bg-white p-8 rounded-2xl border border-slate-100 shadow-sm text-center relative overflow-hidden">
            <div class="absolute top-4 left-4 flex items-center gap-2 text-xs text-slate-400">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span>Pindai Lewat Kamera Browser Ponsel</span>
            </div>

            {{-- Fullscreen Toggle --}}
            <button id="btn-fullscreen" type="button" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition cursor-pointer" title="Layar Penuh Proyektor">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
            </button>

            {{-- QR Code Canvas Container --}}
            <div class="my-6 p-4 bg-slate-50 border-2 border-dashed border-indigo-200 rounded-3xl shadow-inner relative group">
                <div id="qrcode-box" class="flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm min-h-[260px] min-w-[260px]">
                    <div id="qr-loading-text" class="text-xs text-slate-400 animate-pulse">Memuat QR Code...</div>
                </div>

                {{-- Countdown Floating Badge --}}
                <div class="mt-4 flex items-center justify-center gap-2">
                    <div class="w-full max-w-[240px] bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div id="time-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-1000 ease-linear" style="width: 100%;"></div>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-mono mt-2">
                    Berganti otomatis dalam <span id="time-left" class="font-bold text-indigo-600">{{ $sesi->getTimeRemaining() }}</span> detik
                </p>

                {{-- Emergency Rolling OTP Code --}}
                <div class="mt-3 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-indigo-50 border border-indigo-200 text-xs shadow-2xs">
                    <span class="text-slate-500 text-[11px]">Kode Cadangan (OTP):</span>
                    <span id="rolling-code" class="font-mono font-extrabold text-indigo-700 tracking-wider text-sm">{{ $sesi->getRollingCode() }}</span>
                </div>
            </div>

            <div class="max-w-sm space-y-1">
                <p class="text-xs font-semibold text-slate-700">Petunjuk Siswa:</p>
                <p class="text-[11px] text-slate-500">
                    Buka SIAKAD SMA di ponsel, pilih menu <strong>Scan Absensi QR</strong> (atau langsung buka aplikasi Kamera bawaan ponsel Anda), lalu arahkan kamera ke kode di atas.
                </p>
            </div>
        </div>

        {{-- Right: Live Attendance Monitoring --}}
        <div class="lg:col-span-6 space-y-4">
            
            {{-- Stat Cards --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Siswa</span>
                    <span id="stat-total" class="text-2xl font-extrabold text-slate-800">{{ $totalSiswaAwal }}</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm text-center bg-emerald-50/20">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Sudah Hadir</span>
                    <span id="stat-hadir" class="text-2xl font-extrabold text-emerald-600">0</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-sm text-center bg-amber-50/20">
                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Belum Hadir</span>
                    <span id="stat-belum" class="text-2xl font-extrabold text-amber-600">{{ $totalSiswaAwal }}</span>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                    <span class="text-slate-600">Tingkat Kehadiran Kelas</span>
                    <span id="kehadiran-percent" class="text-indigo-600 font-bold">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    <div id="kehadiran-bar" class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%;"></div>
                </div>
            </div>

            {{-- Student Lists with Tabs --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-[340px]">
                <div class="flex items-center border-b border-slate-100 bg-slate-50/60 p-1.5 gap-1 text-xs">
                    <button id="tab-btn-hadir" type="button" class="flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5 bg-white text-emerald-700 font-bold shadow-sm cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Hadir (<span id="tab-count-hadir">0</span>)</span>
                    </button>
                    <button id="tab-btn-belum" type="button" class="flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5 text-slate-500 hover:text-slate-800 cursor-pointer">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Belum Hadir (<span id="tab-count-belum">{{ $totalSiswaAwal }}</span>)</span>
                    </button>
                </div>

                {{-- Tab Siswa Hadir --}}
                <div id="panel-hadir" class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2">
                    <div id="list-hadir-empty" class="h-full flex flex-col items-center justify-center text-slate-400 text-xs py-10">
                        <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Menunggu siswa melakukan pemindaian...
                    </div>
                    <div id="list-hadir-items" class="space-y-1"></div>
                </div>

                {{-- Tab Siswa Belum Hadir --}}
                <div id="panel-belum" class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2 hidden">
                    <div id="list-belum-empty" class="h-full flex flex-col items-center justify-center text-emerald-600 text-xs py-10 hidden">
                        <svg class="w-8 h-8 text-emerald-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Hebat! Semua siswa kelas ini sudah hadir.
                    </div>
                    <div id="list-belum-items" class="space-y-1"></div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Library QRCode.js dari asset lokal (100% offline localhost ready) --}}
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
(function() {
    const sesiId = {{ $sesi->id }};
    let timeLeft = {{ $sesi->getTimeRemaining() }};
    let qrcodeObj = null;
    let tokenTimer = null;
    let livePollTimer = null;
    const initialQrPayload = @json($initialPayload);

    // Inisialisasi QR Code
    function initQrCode() {
        const container = document.getElementById('qrcode-box');
        if (!container) return;

        if (typeof QRCode === 'undefined') {
            console.warn('Menunggu QRCode library...');
            setTimeout(initQrCode, 100);
            return;
        }

        container.innerHTML = '';
        try {
            qrcodeObj = new QRCode(container, {
                text: initialQrPayload,
                width: 250,
                height: 250,
                colorDark: "#0f172a",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
            console.log('QR Code berhasil di-render');
        } catch (err) {
            console.error('Error saat inisialisasi QR:', err);
            container.innerHTML = '<div class="text-xs text-rose-500 p-4">Gagal membuat QR Code. Silakan muat ulang halaman.</div>';
        }
    }

    // Timer Countdown
    function startCountdown() {
        if (tokenTimer) clearInterval(tokenTimer);
        tokenTimer = setInterval(function() {
            timeLeft--;
            if (timeLeft < 0) timeLeft = 0;

            const timeEl = document.getElementById('time-left');
            const barEl = document.getElementById('time-bar');
            if (timeEl) timeEl.textContent = timeLeft;
            if (barEl) {
                const percent = Math.max(0, (timeLeft / 30) * 100);
                barEl.style.width = percent + '%';
            }

            if (timeLeft <= 0) {
                fetchNewToken();
            }
        }, 1000);
    }

    // Rotasi Token berkala
    function fetchNewToken() {
        fetch("{{ route('guru.absensi.qr.token', $sesi) }}")
            .then(res => res.json())
            .then(data => {
                if (data.status === 'aktif') {
                    timeLeft = data.time_remaining;
                    const rollingEl = document.getElementById('rolling-code');
                    if (rollingEl && data.rolling_code) {
                        rollingEl.textContent = data.rolling_code;
                    }
                    if (data.qr_payload && qrcodeObj) {
                        qrcodeObj.clear();
                        qrcodeObj.makeCode(data.qr_payload);
                    }
                }
            })
            .catch(err => console.error('Error rotasi token:', err));
    }

    // Live Monitoring Polling
    function fetchLiveStatus() {
        fetch("{{ route('guru.absensi.qr.status', $sesi) }}")
            .then(res => res.json())
            .then(data => {
                renderLiveStatus(data);
            })
            .catch(err => console.error('Error live status:', err));
    }

    function renderLiveStatus(data) {
        const total = data.total_siswa || 0;
        const hadir = data.total_hadir || 0;
        const belum = data.total_belum || 0;
        const percent = total > 0 ? Math.round((hadir / total) * 100) : 0;

        // Update stats
        const statTotal = document.getElementById('stat-total');
        const statHadir = document.getElementById('stat-hadir');
        const statBelum = document.getElementById('stat-belum');
        const percentEl = document.getElementById('kehadiran-percent');
        const barEl = document.getElementById('kehadiran-bar');
        const tabHadir = document.getElementById('tab-count-hadir');
        const tabBelum = document.getElementById('tab-count-belum');

        if (statTotal) statTotal.textContent = total;
        if (statHadir) statHadir.textContent = hadir;
        if (statBelum) statBelum.textContent = belum;
        if (percentEl) percentEl.textContent = percent + '%';
        if (barEl) barEl.style.width = percent + '%';
        if (tabHadir) tabHadir.textContent = hadir;
        if (tabBelum) tabBelum.textContent = belum;

        // Render List Hadir
        const listHadirEmpty = document.getElementById('list-hadir-empty');
        const listHadirItems = document.getElementById('list-hadir-items');
        if (listHadirItems) {
            listHadirItems.innerHTML = '';
            if (data.siswa_hadir && data.siswa_hadir.length > 0) {
                if (listHadirEmpty) listHadirEmpty.classList.add('hidden');
                data.siswa_hadir.forEach((siswa, idx) => {
                    const row = document.createElement('div');
                    row.className = 'flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition text-xs border border-slate-50';
                    row.innerHTML = `
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[10px]">${idx + 1}</span>
                            <div>
                                <p class="font-semibold text-slate-800">${siswa.nama}</p>
                                <p class="text-[10px] text-slate-400 font-mono">NIS: ${siswa.nis}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-mono bg-emerald-50 text-emerald-700 border border-emerald-200">${siswa.waktu}</span>
                            <span class="block text-[9px] text-slate-400 uppercase mt-0.5">${siswa.metode}</span>
                        </div>
                    `;
                    listHadirItems.appendChild(row);
                });
            } else {
                if (listHadirEmpty) listHadirEmpty.classList.remove('hidden');
            }
        }

        // Render List Belum
        const listBelumEmpty = document.getElementById('list-belum-empty');
        const listBelumItems = document.getElementById('list-belum-items');
        if (listBelumItems) {
            listBelumItems.innerHTML = '';
            if (data.siswa_belum && data.siswa_belum.length > 0) {
                if (listBelumEmpty) listBelumEmpty.classList.add('hidden');
                data.siswa_belum.forEach((siswa, idx) => {
                    const row = document.createElement('div');
                    row.className = 'flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition text-xs border border-slate-50';
                    row.innerHTML = `
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 font-semibold flex items-center justify-center text-[10px]">${idx + 1}</span>
                            <div>
                                <p class="font-medium text-slate-700">${siswa.nama}</p>
                                <p class="text-[10px] text-slate-400 font-mono">NIS: ${siswa.nis}</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            Belum Scan
                        </span>
                    `;
                    listBelumItems.appendChild(row);
                });
            } else {
                if (listBelumEmpty) listBelumEmpty.classList.remove('hidden');
            }
        }
    }

    // Tab Switching
    const tabBtnHadir = document.getElementById('tab-btn-hadir');
    const tabBtnBelum = document.getElementById('tab-btn-belum');
    const panelHadir = document.getElementById('panel-hadir');
    const panelBelum = document.getElementById('panel-belum');

    if (tabBtnHadir && tabBtnBelum) {
        tabBtnHadir.addEventListener('click', function() {
            panelHadir.classList.remove('hidden');
            panelBelum.classList.add('hidden');
            tabBtnHadir.className = 'flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5 bg-white text-emerald-700 font-bold shadow-sm cursor-pointer';
            tabBtnBelum.className = 'flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5 text-slate-500 hover:text-slate-800 cursor-pointer';
        });

        tabBtnBelum.addEventListener('click', function() {
            panelHadir.classList.add('hidden');
            panelBelum.classList.remove('hidden');
            tabBtnBelum.className = 'flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5 bg-white text-amber-700 font-bold shadow-sm cursor-pointer';
            tabBtnHadir.className = 'flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5 text-slate-500 hover:text-slate-800 cursor-pointer';
        });
    }

    // Fullscreen Toggle
    const btnFullscreen = document.getElementById('btn-fullscreen');
    if (btnFullscreen) {
        btnFullscreen.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                document.exitFullscreen().catch(err => console.log(err));
            }
        });
    }

    // Eksekusi Saat Dokumen Siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initQrCode();
            startCountdown();
            fetchLiveStatus();
            livePollTimer = setInterval(fetchLiveStatus, 3000);
        });
    } else {
        initQrCode();
        startCountdown();
        fetchLiveStatus();
        livePollTimer = setInterval(fetchLiveStatus, 3000);
    }
})();
</script>
@endpush
