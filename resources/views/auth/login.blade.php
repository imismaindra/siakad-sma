<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Akademik — SIAKAD SMA</title>
    <meta name="description" content="Masuk ke Sistem Informasi Akademik SIAKAD SMA">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom Keyframes & Animations */
        @keyframes smooth-zoom {
            0% { transform: scale(1.02) translate(0px, 0px); }
            50% { transform: scale(1.07) translate(-10px, -5px); }
            100% { transform: scale(1.02) translate(0px, 0px); }
        }
        @keyframes subtle-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes bg-glow {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -40px) scale(1.2); }
        }
        @keyframes bg-glow-reverse {
            0%, 100% { transform: translate(0, 0) scale(1.1); }
            50% { transform: translate(-40px, 30px) scale(0.9); }
        }

        .bg-zoom-slow {
            animation: smooth-zoom 30s ease-in-out infinite;
        }
        .bounce-slow {
            animation: subtle-bounce 4s ease-in-out infinite;
        }
        .glow-circle-1 {
            animation: bg-glow 15s ease-in-out infinite alternate;
        }
        .glow-circle-2 {
            animation: bg-glow-reverse 18s ease-in-out infinite alternate;
        }

        /* Glassmorphism Classes */
        .glass-hero-card {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .glass-login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08);
        }

        /* Focus & Input Polish */
        .floating-label-wrapper:focus-within .input-icon {
            color: #1e3a8a; /* navy-500 */
        }
        .input-glow:focus {
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.08);
        }

        /* Premium Shine Button Effect */
        .btn-shine-effect {
            position: relative;
            overflow: hidden;
        }
        .btn-shine-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 30%;
            height: 200%;
            background: rgba(255, 255, 255, 0.22);
            transform: rotate(30deg);
            transition: all 0.6s ease;
            opacity: 0;
        }
        .btn-shine-effect:hover::after {
            left: 130%;
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col md:flex-row overflow-x-hidden font-sans antialiased selection:bg-navy-500 selection:text-white">

    {{-- ══════════════════════════════════════════════════
         LEFT PANEL: Cinematic School Branding & Pitch (Desktop)
         ══════════════════════════════════════════════════ --}}
    <div class="hidden md:flex md:w-[45%] lg:w-[50%] relative flex-col justify-between p-12 lg:p-16 text-white overflow-hidden min-h-screen">
        <!-- Interactive Zooming Background -->
        <div class="absolute inset-0 bg-cover bg-center bg-zoom-slow pointer-events-none" style="background-image: url('/images/school_hero.webp');"></div>
        <!-- Sophisticated Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-tr from-[#050b18] via-[#081532]/95 to-[#162a5c]/70 pointer-events-none"></div>
        
        <!-- Subtle Glow Lines (Visual Accent) -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Row: Branding -->
        <div class="relative z-10">
            <a href="/" class="inline-flex items-center gap-3.5 group">
                <div class="relative">
                    <div class="absolute inset-0 rounded-full bg-amber-400/20 blur-md group-hover:blur-lg transition-all"></div>
                    <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy" class="relative w-12 h-12 rounded-full border-2 border-amber-400 bg-white transition-all duration-500 group-hover:rotate-12">
                </div>
                <div>
                    <span class="font-display font-black text-lg lg:text-xl tracking-wider uppercase block text-white">SMA Cihuy</span>
                    <span class="text-[10px] text-amber-400 font-extrabold tracking-widest uppercase block -mt-0.5">Kota Bandung</span>
                </div>
            </a>
        </div>
        
        <!-- Floating Glassmorphic Feature Card -->
        <div class="relative z-10 my-auto glass-hero-card rounded-3xl p-8 lg:p-10 shadow-2xl max-w-xl transition-all duration-500 hover:border-white/20 hover:shadow-blue-500/5">
            <div class="space-y-6">
                <!-- Badge -->
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-400/10 text-amber-300 border border-amber-400/20 uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Sistem Akademik Terpadu
                </span>
                
                <h1 class="font-display font-black text-3xl lg:text-4.5xl leading-tight text-white">
                    Pintu Gerbang <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-500 font-extrabold">Prestasi &amp; Informasi</span> Akademik
                </h1>
                
                <p class="text-slate-300 text-sm lg:text-base leading-relaxed">
                    SIAKAD SMA Cihuy Bandung mengintegrasikan data pembelajaran secara real-time untuk mempermudah kolaborasi guru, siswa, dan orang tua.
                </p>

                <!-- Clean Vector Advantage List -->
                <div class="space-y-4 pt-4 border-t border-white/10">
                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5 border border-emerald-500/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white leading-tight">Aksesibilitas Informasi Instan</h4>
                            <p class="text-slate-400 text-xs mt-0.5">Nilai rapor, absensi, dan jadwal belajar langsung dapat dipantau.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5 border border-emerald-500/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white leading-tight">Kurikulum Merdeka Siap Pakai</h4>
                            <p class="text-slate-400 text-xs mt-0.5">Kompatibilitas penuh dengan sistem penilaian formatif dan sumatif terbaru.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5 border border-emerald-500/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white leading-tight">Proteksi Data Terenkripsi</h4>
                            <p class="text-slate-400 text-xs mt-0.5">Keamanan data akademik dan profil siswa terjamin dengan standar proteksi modern.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Row: Footer info -->
        <div class="relative z-10 text-xs text-slate-400/80 flex items-center justify-between border-t border-white/10 pt-5">
            <span>© {{ date('Y') }} SMA Cihuy Bandung. All Rights Reserved.</span>
            <span class="flex items-center gap-1.5 font-semibold text-slate-300">
                <svg class="w-3.5 h-3.5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 0L10 18.9l-4.95-4.85zM10 12a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                Bandung, Indonesia
            </span>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════
         RIGHT PANEL: Interactive Login Form
         ══════════════════════════════════════════════════ --}}
    <div class="flex-1 min-h-screen flex flex-col justify-between p-6 sm:p-10 md:p-14 lg:p-16 relative overflow-hidden bg-gradient-to-br from-slate-50 via-slate-100 to-indigo-50/40">
        
        <!-- Dynamic Fluid Glow Circles (Visual Decoration) -->
        <div class="absolute w-[450px] h-[450px] rounded-full bg-blue-600/5 blur-[100px] top-[-100px] left-[-50px] pointer-events-none glow-circle-1"></div>
        <div class="absolute w-[500px] h-[500px] rounded-full bg-amber-500/5 blur-[120px] bottom-[-200px] right-[-100px] pointer-events-none glow-circle-2"></div>
        <div class="absolute w-[350px] h-[350px] rounded-full bg-indigo-500/5 blur-[90px] top-[40%] right-[-10%] pointer-events-none"></div>

        <!-- Upper Bar: Navigation & Version -->
        <div class="relative z-10 flex justify-between items-center w-full max-w-lg mx-auto">
            <a href="/" class="group inline-flex items-center gap-2 text-xs font-extrabold text-slate-500 hover:text-navy-600 transition-colors uppercase tracking-widest py-2 px-4 rounded-xl hover:bg-slate-200/50">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
            <span class="text-[10px] bg-white text-navy-600 border border-slate-200 font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                Portal v3.0 Stable
            </span>
        </div>

        <!-- Central Container: Responsive Card -->
        <div class="w-full max-w-lg mx-auto my-auto py-8 space-y-6 relative z-10">
            
            <!-- Mobile Branding Header (Only visible on Mobile) -->
            <div class="md:hidden text-center flex flex-col items-center gap-3.5 mb-2">
                <a href="/" class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white border border-slate-100 shadow-sm">
                    <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy" class="w-10 h-10 rounded-full border border-amber-400 bg-white">
                    <div>
                        <span class="font-display font-black text-base tracking-wide text-slate-800 uppercase block leading-none">SMA Cihuy</span>
                        <span class="text-[9px] text-amber-500 font-bold uppercase tracking-widest block text-left">Kota Bandung</span>
                    </div>
                </a>
            </div>

            <!-- Welcome Header -->
            <div class="text-center md:text-left space-y-2.5 px-2">
                <h2 class="font-display font-black text-3.5xl text-slate-800 tracking-tight leading-tight">
                    Selamat Datang <span class="text-navy-600">Kembali</span>
                </h2>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">
                    Akses portal akademik Anda untuk mengelola jadwal kelas, mengunggah materi, dan memeriksa pencapaian nilai rapor.
                </p>
            </div>

            <!-- Authentic Login Form Card -->
            <div class="glass-login-card rounded-[32px] p-8 lg:p-10 shadow-2xl relative">
                
                {{-- Flash Message Success --}}
                @if(session('success'))
                    <div class="flash-success border border-emerald-200 bg-emerald-50/50 rounded-2xl p-4 flex items-start gap-3 mb-6 animate-fade-in">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-emerald-800 text-xs font-semibold leading-relaxed">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{-- Flash Message Error --}}
                @if(session('error') || $errors->has('error'))
                    <div class="flash-error border border-rose-200 bg-rose-50/50 rounded-2xl p-4 flex items-start gap-3 mb-6 animate-fade-in">
                        <div class="w-5 h-5 rounded-full bg-rose-500/10 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <span class="text-rose-800 text-xs font-semibold leading-relaxed">{{ session('error') ?? $errors->first('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field Wrapper -->
                    <div class="floating-label-wrapper space-y-2">
                        <label for="email" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500">Alamat Email Resmi</label>
                        <div class="relative group">
                            <!-- Input -->
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="input-glow w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-navy-600 focus:outline-none transition-all font-semibold text-slate-700 placeholder-slate-400 @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500/5 @enderror" 
                                placeholder="nama@siakad.sch.id">
                            <!-- Left Icon -->
                            <div class="input-icon absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-navy-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1.5 font-bold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field Wrapper -->
                    <div class="floating-label-wrapper space-y-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500">Kata Sandi</label>
                        </div>
                        <div class="relative group">
                            <!-- Input -->
                            <input type="password" id="password" name="password" required
                                class="input-glow w-full pl-12 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-navy-600 focus:outline-none transition-all font-semibold text-slate-700 placeholder-slate-400" 
                                placeholder="••••••••">
                            <!-- Left Icon -->
                            <div class="input-icon absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-navy-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            
                            <!-- Show/Hide Password Toggle -->
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors" aria-label="Toggle Password Visibility">
                                <!-- Eye Icon -->
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <!-- Eye-Off Icon -->
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center text-xs font-bold text-slate-500 cursor-pointer select-none group">
                            <input type="checkbox" name="remember" class="w-4.5 h-4.5 rounded-lg border-slate-300 text-navy-600 focus:ring-navy-500/20 mr-2.5 transition-all group-hover:border-navy-400">
                            Ingat Sesi Saya
                        </label>
                    </div>

                    <!-- Premium Submit Button -->
                    <div>
                        <button type="submit" class="btn-shine-effect w-full py-4 text-sm font-extrabold rounded-2xl text-white bg-gradient-to-r from-navy-700 via-navy-600 to-navy-500 shadow-lg shadow-navy-900/20 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-navy-500/20 transition-all duration-300 flex items-center justify-center gap-2">
                            Masuk ke Portal
                            <svg class="w-4.5 h-4.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>

        <!-- Right Column Footer (Mobile / Desktop Bottom) -->
        <div class="relative z-10 text-center text-[10px] text-slate-400 pt-6 border-t border-slate-200/50 w-full max-w-lg mx-auto">
            <span>© {{ date('Y') }} SMA Cihuy Bandung. Halaman Masuk Resmi Portal SIAKAD.</span>
        </div>
    </div>

    {{-- Script for Toggle Password --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            togglePasswordBtn.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                // Toggle icons
                const eyeIcon = togglePasswordBtn.querySelector('.eye-icon');
                const eyeOffIcon = togglePasswordBtn.querySelector('.eye-off-icon');
                
                if (isPassword) {
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        });
    </script>

</body>
</html>
