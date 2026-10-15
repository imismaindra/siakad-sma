@extends('layouts.siswa')

@section('title', 'Scan Absensi QR')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Scan QR')

@push('styles')
<style>
    /* Animasi Laser Scanning */
    @keyframes scanLaser {
        0% { top: 0%; opacity: 0.8; }
        50% { opacity: 1; }
        100% { top: 96%; opacity: 0.8; }
    }
    .scanner-laser {
        position: absolute;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, rgba(99, 102, 241, 0) 0%, rgba(99, 102, 241, 1) 50%, rgba(99, 102, 241, 0) 100%);
        box-shadow: 0 0 12px #6366f1;
        animation: scanLaser 2s infinite ease-in-out alternate;
    }
</style>
@endpush

@section('siswa-content')
<div class="space-y-6 max-w-2xl mx-auto animate-fade-in-up" x-data="siswaScannerApp()">
    
    {{-- Header --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                Presensi Digital Siswa
            </div>
            <h1 class="text-xl font-bold font-display text-slate-800">Pemindai Absensi QR</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Arahkan kamera ke QR Code yang ditampilkan oleh guru di layar proyektor kelas.
            </p>
        </div>
        <div class="text-xs text-slate-500 bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200 text-center">
            <span class="block text-[10px] uppercase font-bold text-slate-400">Kelas Anda</span>
            <span class="font-bold text-navy-600 font-mono">{{ $siswa->kelas->nama_lengkap }}</span>
        </div>
    </div>

    {{-- Scanner Viewport Card --}}
    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm overflow-hidden relative">
        
        {{-- Mode Switch (Kamera vs Input Manual) --}}
        <div class="flex items-center justify-center p-1 bg-slate-100 rounded-xl mb-4 max-w-xs mx-auto text-xs font-semibold">
            <button type="button" @click="mode = 'camera'; startScanner();" 
                    :class="mode === 'camera' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 py-1.5 rounded-lg transition text-center">
                Kamera Browser
            </button>
            <button type="button" @click="mode = 'manual'; stopScanner();" 
                    :class="mode === 'manual' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                    class="flex-1 py-1.5 rounded-lg transition text-center">
                Kode Cadangan
            </button>
        </div>

        {{-- CAMERA SCANNER VIEW --}}
        <div x-show="mode === 'camera'" class="relative flex flex-col items-center">
            
            {{-- Camera Viewfinder Box --}}
            <div class="relative w-full max-w-[340px] aspect-square bg-slate-900 rounded-2xl overflow-hidden shadow-inner flex items-center justify-center border-4 border-slate-800">
                
                {{-- html5-qrcode target div --}}
                <div id="reader" class="w-full h-full"></div>

                {{-- Overlay Scanning Reticle --}}
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center p-8">
                    <div class="relative w-full h-full border-2 border-indigo-400/80 rounded-2xl overflow-hidden">
                        {{-- Corner Brackets --}}
                        <div class="absolute top-0 left-0 w-4 h-4 border-t-4 border-l-4 border-indigo-500"></div>
                        <div class="absolute top-0 right-0 w-4 h-4 border-t-4 border-r-4 border-indigo-500"></div>
                        <div class="absolute bottom-0 left-0 w-4 h-4 border-b-4 border-l-4 border-indigo-500"></div>
                        <div class="absolute bottom-0 right-0 w-4 h-4 border-b-4 border-r-4 border-indigo-500"></div>
                        {{-- Animated Laser --}}
                        <div class="scanner-laser"></div>
                    </div>
                </div>

                {{-- Loading / Processing Overlay --}}
                <div x-show="isProcessing" class="absolute inset-0 bg-slate-900/80 backdrop-blur-xs flex flex-col items-center justify-center text-white text-xs z-20">
                    <svg class="w-8 h-8 animate-spin text-indigo-400 mb-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memverifikasi presensi ke server...</span>
                </div>
            </div>

            <p class="text-[11px] text-slate-400 mt-3 text-center">
                Posisikan QR code guru tepat di dalam bingkai kotak di atas.
            </p>
        </div>

        {{-- MANUAL TOKEN INPUT (FALLBACK) --}}
        <div x-show="mode === 'manual'" class="p-4 space-y-4 max-w-sm mx-auto text-left" style="display: none;">
            <p class="text-xs text-slate-500 text-center">
                Gunakan formulir ini jika kamera ponsel mengalami kendala.
            </p>

            <form @submit.prevent="submitManual()">
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Sesi Pelajaran</label>
                        <select x-model="manualForm.sesi_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Sesi Aktif Hari Ini --</option>
                            @foreach($sesiAktifList as $sa)
                                <option value="{{ $sa->id }}">
                                    {{ $sa->jadwalPelajaran->mataPelajaran->nama }} ({{ $sa->guru->nama_lengkap }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Token Rahasia</label>
                        <input type="text" x-model="manualForm.token" placeholder="Masukkan 64 karakter token atau paste dari guru" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-mono" required>
                    </div>

                    <button type="submit" :disabled="isProcessing" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                        Kirim Presensi Manual
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- Scan Result Modal --}}
    <div x-show="resultModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="closeResultModal()"></div>

            <div class="relative inline-block w-full max-w-sm p-6 my-8 overflow-hidden text-center align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-100"
                 @click.away="closeResultModal()">
                
                {{-- Success State --}}
                <template x-if="resultData?.success">
                    <div class="space-y-4">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800" x-text="resultData.already_present ? 'Sudah Tercatat Hadir' : 'Presensi Berhasil!'"></h3>
                            <p class="text-xs text-slate-500 mt-1" x-text="resultData.message"></p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl text-xs space-y-1.5 text-left border border-slate-100">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Mata Pelajaran:</span>
                                <span class="font-bold text-slate-800" x-text="resultData.data?.mata_pelajaran"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Kelas:</span>
                                <span class="font-medium text-slate-800" x-text="resultData.data?.kelas"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Waktu Presensi:</span>
                                <span class="font-mono text-emerald-600 font-bold" x-text="`${resultData.data?.waktu} WIB`"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Status Kehadiran:</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-800">HADIR</span>
                            </div>
                        </div>
                        <button type="button" @click="closeResultModal()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Selesai
                        </button>
                    </div>
                </template>

                {{-- Error State --}}
                <template x-if="!resultData?.success">
                    <div class="space-y-4">
                        <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Presensi Ditolak</h3>
                            <p class="text-xs text-rose-600 mt-1 font-medium" x-text="resultData?.message || 'Terjadi kesalahan saat memproses data.'"></p>
                        </div>
                        <p class="text-[11px] text-slate-400">
                            Pastikan Anda memindai kode QR yang masih aktif dan berada pada jam pelajaran yang sesuai.
                        </p>
                        <button type="button" @click="closeResultModal()" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Coba Pindai Lagi
                        </button>
                    </div>
                </template>

            </div>
        </div>
    </div>

    {{-- Riwayat Absensi Hari Ini --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Riwayat Presensi Anda Hari Ini
        </h3>
        
        <div class="divide-y divide-slate-100">
            @forelse($absensiHariIni as $ah)
                <div class="flex items-center justify-between py-2.5 text-xs">
                    <div>
                        <p class="font-bold text-slate-800">{{ $ah->absensi->mataPelajaran->nama }}</p>
                        <p class="text-[11px] text-slate-400">{{ $ah->absensi->guru->nama_lengkap }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold
                            {{ $ah->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($ah->status) }}
                        </span>
                        <span class="block text-[10px] text-slate-400 font-mono mt-0.5">
                            {{ $ah->waktu_absen ? $ah->waktu_absen->format('H:i') . ' WIB' : $ah->updated_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">Belum ada absensi yang tercatat untuk hari ini.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Library html5-qrcode --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function siswaScannerApp() {
    return {
        mode: 'camera',
        isProcessing: false,
        resultModal: false,
        resultData: null,
        html5QrCode: null,
        manualForm: {
            sesi_id: '',
            token: ''
        },
        init() {
            this.startScanner();
        },
        startScanner() {
            this.$nextTick(() => {
                if (!this.html5QrCode) {
                    this.html5QrCode = new Html5Qrcode("reader");
                }
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                this.html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText) => this.onScanSuccess(decodedText)
                ).catch(err => {
                    console.warn("Gagal membuka kamera:", err);
                });
            });
        },
        stopScanner() {
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                this.html5QrCode.stop().catch(err => console.error(err));
            }
        },
        onScanSuccess(decodedText) {
            if (this.isProcessing) return;
            this.isProcessing = true;
            this.playBeep();
            if (navigator.vibrate) navigator.vibrate(100);

            try {
                const payload = JSON.parse(decodedText);
                if (payload.sesi_id && payload.token) {
                    this.submitScan(payload.sesi_id, payload.token);
                } else {
                    throw new Error("Format QR tidak valid");
                }
            } catch (e) {
                this.isProcessing = false;
                this.resultData = {
                    success: false,
                    message: "Kode QR tidak dikenali sebagai format absensi SIAKAD."
                };
                this.resultModal = true;
            }
        },
        submitScan(sesiId, token) {
            fetch(`{{ route('siswa.absensi.scan.post') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sesi_id: sesiId,
                    token: token
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                this.isProcessing = false;
                this.resultData = body;
                this.resultModal = true;
                if (body.success) {
                    this.stopScanner();
                }
            })
            .catch(err => {
                this.isProcessing = false;
                this.resultData = {
                    success: false,
                    message: "Terjadi gangguan koneksi saat mengirim presensi."
                };
                this.resultModal = true;
            });
        },
        submitManual() {
            if (!this.manualForm.sesi_id || !this.manualForm.token) return;
            this.isProcessing = true;
            this.submitScan(this.manualForm.sesi_id, this.manualForm.token);
        },
        closeResultModal() {
            this.resultModal = false;
            if (this.mode === 'camera' && !this.html5QrCode?.isScanning) {
                this.startScanner();
            }
        },
        playBeep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = "sine";
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.2, ctx.currentTime);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } catch (e) {}
        }
    }
}
</script>
@endpush
