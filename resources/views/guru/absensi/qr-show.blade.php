@extends('layouts.guru')

@section('title', 'Layar Absensi QR - ' . $sesi->jadwalPelajaran->mataPelajaran->nama)

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Sesi QR')

@section('guru-content')
<div class="space-y-6 animate-fade-in-up" x-data="qrSessionApp()">
    
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
            <button @click="toggleFullscreen()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition" title="Layar Penuh Proyektor">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
            </button>

            {{-- QR Code Canvas Container --}}
            <div class="my-6 p-4 bg-slate-50 border-2 border-dashed border-indigo-200 rounded-3xl shadow-inner relative group">
                <div id="qrcode-box" class="flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm min-h-[260px] min-w-[260px]">
                    {{-- QR Code injected via JavaScript --}}
                    <div class="text-xs text-slate-400 animate-pulse">Memuat QR Code...</div>
                </div>

                {{-- Countdown Floating Badge --}}
                <div class="mt-4 flex items-center justify-center gap-2">
                    <div class="w-full max-w-[240px] bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-1000 ease-linear" :style="`width: ${(timeLeft / 30) * 100}%`"></div>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-mono mt-2">
                    Berganti otomatis dalam <span class="font-bold text-indigo-600" x-text="timeLeft">30</span> detik
                </p>
            </div>

            <div class="max-w-sm space-y-1">
                <p class="text-xs font-semibold text-slate-700">Petunjuk Siswa:</p>
                <p class="text-[11px] text-slate-500">
                    Buka SIAKAD SMA di browser ponsel, login akun Siswa, pilih menu <strong>Scan Absensi QR</strong>, lalu arahkan kamera ke kode di atas.
                </p>
            </div>
        </div>

        {{-- Right: Live Attendance Monitoring --}}
        <div class="lg:col-span-6 space-y-4">
            
            {{-- Stat Cards --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Siswa</span>
                    <span class="text-2xl font-extrabold text-slate-800" x-text="liveData.total_siswa">0</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm text-center bg-emerald-50/20">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Sudah Hadir</span>
                    <span class="text-2xl font-extrabold text-emerald-600" x-text="liveData.total_hadir">0</span>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-sm text-center bg-amber-50/20">
                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Belum Hadir</span>
                    <span class="text-2xl font-extrabold text-amber-600" x-text="liveData.total_belum">0</span>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                    <span class="text-slate-600">Tingkat Kehadiran Kelas</span>
                    <span class="text-indigo-600 font-bold" x-text="`${percentage}%`">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" :style="`width: ${percentage}%`"></div>
                </div>
            </div>

            {{-- Student Lists with Tabs --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-[340px]">
                <div class="flex items-center border-b border-slate-100 bg-slate-50/60 p-1.5 gap-1 text-xs">
                    <button @click="activeTab = 'hadir'" 
                            :class="activeTab === 'hadir' ? 'bg-white text-emerald-700 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                            class="flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Hadir (<span x-text="liveData.total_hadir">0</span>)</span>
                    </button>
                    <button @click="activeTab = 'belum'" 
                            :class="activeTab === 'belum' ? 'bg-white text-amber-700 font-bold shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                            class="flex-1 py-2 rounded-xl transition text-center flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Belum Hadir (<span x-text="liveData.total_belum">0</span>)</span>
                    </button>
                </div>

                {{-- Tab Siswa Hadir --}}
                <div x-show="activeTab === 'hadir'" class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2">
                    <template x-for="(siswa, idx) in liveData.siswa_hadir" :key="siswa.id">
                        <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[10px]" x-text="idx + 1"></span>
                                <div>
                                    <p class="font-semibold text-slate-800" x-text="siswa.nama"></p>
                                    <p class="text-[10px] text-slate-400 font-mono" x-text="`NIS: ${siswa.nis}`"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-mono bg-emerald-50 text-emerald-700 border border-emerald-200" x-text="siswa.waktu"></span>
                                <span class="block text-[9px] text-slate-400 uppercase mt-0.5" x-text="siswa.metode"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="liveData.siswa_hadir.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-slate-400 text-xs py-10">
                            <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Menunggu siswa melakukan pemindaian...
                        </div>
                    </template>
                </div>

                {{-- Tab Siswa Belum Hadir --}}
                <div x-show="activeTab === 'belum'" class="flex-1 overflow-y-auto divide-y divide-slate-100 p-2" style="display: none;">
                    <template x-for="(siswa, idx) in liveData.siswa_belum" :key="siswa.id">
                        <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 font-semibold flex items-center justify-center text-[10px]" x-text="idx + 1"></span>
                                <div>
                                    <p class="font-medium text-slate-700" x-text="siswa.nama"></p>
                                    <p class="text-[10px] text-slate-400 font-mono" x-text="`NIS: ${siswa.nis}`"></p>
                                </div>
                            </div>
                            <span class="text-[10px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                Belum Scan
                            </span>
                        </div>
                    </template>
                    <template x-if="liveData.siswa_belum.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-emerald-600 text-xs py-10">
                            <svg class="w-8 h-8 text-emerald-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Hebat! Semua siswa kelas ini sudah hadir.
                        </div>
                    </template>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Library QRCode.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function qrSessionApp() {
    return {
        sesiId: {{ $sesi->id }},
        timeLeft: {{ $sesi->getTimeRemaining() }},
        activeTab: 'hadir',
        qrcodeObj: null,
        tokenTimer: null,
        livePollTimer: null,
        liveData: {
            total_siswa: 0,
            total_hadir: 0,
            total_belum: 0,
            siswa_hadir: [],
            siswa_belum: []
        },
        get percentage() {
            if (!this.liveData.total_siswa) return 0;
            return Math.round((this.liveData.total_hadir / this.liveData.total_siswa) * 100);
        },
        init() {
            this.initQr();
            this.startCountdown();
            this.fetchLiveStatus();
            this.livePollTimer = setInterval(() => this.fetchLiveStatus(), 3000);
        },
        initQr() {
            const container = document.getElementById('qrcode-box');
            container.innerHTML = '';
            this.qrcodeObj = new QRCode(container, {
                text: '{!! addslashes($sesi->getQrPayload()) !!}',
                width: 250,
                height: 250,
                colorDark: "#0f172a",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        },
        startCountdown() {
            this.tokenTimer = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    this.fetchNewToken();
                }
            }, 1000);
        },
        fetchNewToken() {
            fetch(`{{ route('guru.absensi.qr.token', $sesi) }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'aktif') {
                        this.timeLeft = data.time_remaining;
                        this.qrcodeObj.clear();
                        this.qrcodeObj.makeCode(data.qr_payload);
                    }
                })
                .catch(err => console.error('Error rotasi token:', err));
        },
        fetchLiveStatus() {
            fetch(`{{ route('guru.absensi.qr.status', $sesi) }}`)
                .then(res => res.json())
                .then(data => {
                    this.liveData = data;
                })
                .catch(err => console.error('Error live status:', err));
        },
        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                document.exitFullscreen().catch(err => console.log(err));
            }
        }
    }
}
</script>
@endpush
