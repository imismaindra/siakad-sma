<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Portal SIAKAD SMA - Sistem Informasi Akademik</title>
    <meta name="description" content="Halaman masuk resmi Sistem Informasi Akademik SMA (SIAKAD SMA v2.0) untuk Administrator TU, Dewan Guru, Peserta Didik, dan Wali Murid.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        body { text-wrap: pretty; background-color: #F8F9FA; color: #1E293B; }
        h1, h2, h3 { text-wrap: balance; }
        
        /* High-contrast smooth entrance without blur */
        .rv {
            opacity: 0;
            transform: translateY(1.25rem);
            transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .rv.on {
            opacity: 1;
            transform: none;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .rv { opacity: 1; transform: none; transition: none; }
        }
    </style>
</head>
<body class="bg-[#F8F9FA] text-slate-800 font-sans antialiased selection:bg-slate-900 selection:text-white min-h-[100dvh]">
<a href="#login-form" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[90] focus:bg-slate-950 focus:text-white focus:px-5 focus:py-2.5 focus:rounded-full focus:text-sm shadow-xl font-bold">Lewati ke formulir masuk</a>

<div class="min-h-[100dvh] grid lg:grid-cols-12 gap-6 p-4 sm:p-6 lg:p-8">

    {{-- LEFT COLUMN: ARCHITECTURAL SYSTEM SHOWCASE --}}
    <aside class="lg:col-span-6 xl:col-span-7 rounded-[2.5rem] bg-slate-900 p-2 sm:p-3 border border-slate-800 shadow-2xl rv flex flex-col justify-between overflow-hidden" aria-label="Informasi SIAKAD SMA">
        <div class="rounded-[calc(2.5rem-0.625rem)] bg-slate-950 p-7 sm:p-12 text-white h-full flex flex-col justify-between border border-slate-800/80">
            
            {{-- Header info --}}
            <div>
                <div class="flex items-center justify-between gap-4">
                    <a href="/" class="flex items-center gap-3 group">
                        <span class="w-9 h-9 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center text-amber-400 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-5.25 6.557c0 1.564.914 2.872 2.25 3.483"/>
                            </svg>
                        </span>
                        <span class="leading-tight">
                            <span class="block font-display font-black text-white text-base tracking-tight">SIAKAD<span class="text-amber-400">SMA</span></span>
                            <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-400 uppercase">AKADEMIK TERPADU</span>
                        </span>
                    </a>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 font-mono text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Sistem Normal
                    </div>
                </div>

                {{-- Hero Pitch --}}
                <div class="mt-12 max-w-xl">
                    <span class="text-xs font-mono font-bold uppercase tracking-wider text-amber-400 bg-amber-400/20 px-3 py-1 rounded-md border border-amber-400/30">
                        AUTENTIKASI TERPUSAT
                    </span>
                    <h1 class="mt-5 font-display font-black tracking-tight text-white text-3xl sm:text-4xl lg:text-5xl leading-[1.05]">
                        Satu pintu masuk untuk seluruh kebutuhan akademik sekolah.
                    </h1>
                    <p class="mt-4 text-base text-slate-300 leading-relaxed font-normal">
                        Sistem secara otomatis mengidentifikasi hak akses Anda melalui Spatie Dynamic RBAC dan mengarahkan langsung ke dashboard yang relevan: Administrator TU, Dewan Guru, Peserta Didik, atau Wali Murid.
                    </p>
                </div>

                {{-- Pillar Highlights --}}
                <div class="mt-10 grid sm:grid-cols-3 gap-4 max-w-xl">
                    <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-amber-400/10 text-amber-400 flex items-center justify-center font-bold text-sm mb-3">01</div>
                        <h2 class="font-bold text-white text-sm">Presensi GPS</h2>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">Rotasi QR token 30s & validasi radius 100m.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-emerald-400/10 text-emerald-400 flex items-center justify-center font-bold text-sm mb-3">02</div>
                        <h2 class="font-bold text-white text-sm">e-Rapor Merdeka</h2>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">Otomasi kalkulasi TP dan cetak PDF resmi.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-blue-400/10 text-blue-400 flex items-center justify-center font-bold text-sm mb-3">03</div>
                        <h2 class="font-bold text-white text-sm">Log Audit</h2>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">Perekaman append-only setiap mutasi data.</p>
                    </div>
                </div>
            </div>

            {{-- Footer info --}}
            <div class="mt-12 pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs font-mono text-slate-400 font-medium">
                <span>Versi Sistem: SIAKAD SMA v2.0</span>
                <span>Laravel 13 · Spatie RBAC · DomPDF Engine</span>
            </div>

        </div>
    </aside>

    {{-- RIGHT COLUMN: HIGH-CONTRAST LOGIN FORM --}}
    <main class="lg:col-span-6 xl:col-span-5 flex items-center justify-center px-2 sm:px-6 py-6">
        <div class="w-full max-w-md rv">
            
            {{-- Back to Home --}}
            <div class="flex items-center justify-between mb-8">
                <a href="/" class="group inline-flex items-center gap-2.5 rounded-full bg-white border border-slate-300 px-4 py-2 text-xs font-bold text-slate-800 shadow-sm hover:bg-slate-100 transition-all duration-300 active:scale-[0.98]">
                    <svg class="w-3.5 h-3.5 text-slate-600 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Beranda</span>
                </a>

                <span class="text-xs font-mono font-bold text-slate-500">PORTAL LOGIN</span>
            </div>

            {{-- Form Card with Double-Bezel Architecture --}}
            <div class="rounded-3xl bg-white p-2 border-2 border-slate-300 shadow-xl">
                <div class="rounded-[calc(1.5rem-0.125rem)] p-6 sm:p-8 bg-white">
                    
                    {{-- Form Header --}}
                    <div>
                        <h2 id="login-form" class="font-display font-black tracking-tight text-slate-950 text-2xl sm:text-3xl leading-tight">
                            Masuk ke Akun Anda
                        </h2>
                        <p class="mt-1.5 text-slate-700 text-sm font-normal">
                            Gunakan kredensial resmi sekolah yang telah terdaftar di sistem.
                        </p>
                    </div>

                    {{-- Quick-Fill Demo Account Helper --}}
                    <div class="mt-5 p-3 rounded-xl bg-slate-100 border border-slate-300">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-700">Isi Otomatis Akun Uji Coba:</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5">
                            <button type="button" class="demo-fill-btn px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-800 hover:bg-slate-950 hover:text-white transition-all text-center"
                                data-email="admin@siakad.sch.id" data-pass="password">
                                Admin
                            </button>
                            <button type="button" class="demo-fill-btn px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-800 hover:bg-slate-950 hover:text-white transition-all text-center"
                                data-email="guru@siakad.sch.id" data-pass="password">
                                Guru
                            </button>
                            <button type="button" class="demo-fill-btn px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-800 hover:bg-slate-950 hover:text-white transition-all text-center"
                                data-email="siswa@siakad.sch.id" data-pass="password">
                                Siswa
                            </button>
                            <button type="button" class="demo-fill-btn px-2 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-800 hover:bg-slate-950 hover:text-white transition-all text-center"
                                data-email="ortu@siakad.sch.id" data-pass="password">
                                Wali
                            </button>
                        </div>
                    </div>

                    {{-- Session Flash Alerts --}}
                    @if(session('success'))
                        <div class="mt-5 rounded-xl border-2 border-emerald-300 bg-emerald-50 px-4 py-3 flex items-start gap-3" role="status">
                            <svg class="w-5 h-5 text-emerald-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-emerald-900 text-xs font-bold leading-relaxed">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error') || (isset($errors) && $errors->has('error')))
                        <div class="mt-5 rounded-xl border-2 border-rose-300 bg-rose-50 px-4 py-3 flex items-start gap-3" role="alert">
                            <svg class="w-5 h-5 text-rose-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-rose-900 text-xs font-bold leading-relaxed">{{ session('error') ?? ($errors->first('error') ?? '') }}</p>
                        </div>
                    @endif

                    {{-- Login Form --}}
                    <form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-4" novalidate>
                        @csrf
                        
                        {{-- Email Input --}}
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-900 mb-1.5">
                                Alamat Email Resmi
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-600 pointer-events-none" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                                </span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@siakad.sch.id"
                                    class="w-full rounded-xl border-2 @error('email') border-rose-500 bg-rose-50/50 @else border-slate-300 bg-slate-50/80 @enderror pl-11 pr-4 py-3 text-sm font-semibold text-slate-950 placeholder:text-slate-400 outline-none transition-all focus:bg-white focus:border-slate-950 focus:ring-4 focus:ring-slate-950/10">
                            </div>
                            @error('email')
                                <p class="mt-1.5 text-xs font-bold text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Input --}}
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-900 mb-1.5">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-600 pointer-events-none" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi akun"
                                    class="w-full rounded-xl border-2 @error('password') border-rose-500 bg-rose-50/50 @else border-slate-300 bg-slate-50/80 @enderror pl-11 pr-11 py-3 text-sm font-semibold text-slate-950 placeholder:text-slate-400 outline-none transition-all focus:bg-white focus:border-slate-950 focus:ring-4 focus:ring-slate-950/10">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-600 hover:text-slate-950 transition-colors" aria-label="Tampilkan kata sandi">
                                    <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1.5 text-xs font-bold text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="pt-1">
                            <label class="inline-flex items-center gap-2.5 text-xs font-bold text-slate-800 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded-sm border-2 border-slate-400 accent-slate-950">
                                <span>Ingat sesi masuk di perangkat ini</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button type="submit" class="group w-full inline-flex items-center justify-between gap-3 rounded-full bg-slate-950 py-2.5 pl-6 pr-2 text-sm font-bold text-white shadow-md hover:bg-slate-800 transition-all duration-300 active:scale-[0.98]">
                                <span>Masuk ke Portal SIAKAD</span>
                                <span class="w-8 h-8 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center font-bold transition-transform group-hover:translate-x-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Footer Note --}}
            <p class="mt-6 text-center text-xs font-medium text-slate-600">
                Sistem Informasi Akademik SMA v2.0 · Halaman Masuk Resmi
            </p>

        </div>
    </main>

</div>

{{-- SCRIPT: PASSWORD TOGGLE, DEMO AUTO-FILL & INTERSECTION OBSERVER --}}
<script>
(function () {
    // 1. Entrance animation
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                e.target.classList.add('on');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.05 });
    document.querySelectorAll('.rv').forEach(function (el) { io.observe(el); });

    // 2. Password visibility toggle
    var toggle = document.getElementById('togglePassword');
    var passwordInput = document.getElementById('password');
    if (toggle && passwordInput) {
        toggle.addEventListener('click', function () {
            var isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggle.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            var eye = toggle.querySelector('.eye-icon');
            var off = toggle.querySelector('.eye-off-icon');
            if (eye && off) {
                eye.classList.toggle('hidden', isPassword);
                off.classList.toggle('hidden', !isPassword);
            }
        });
    }

    // 3. Demo Quick-Fill Buttons
    var emailInput = document.getElementById('email');
    document.querySelectorAll('.demo-fill-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var email = btn.getAttribute('data-email');
            var pass = btn.getAttribute('data-pass');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = pass;
                emailInput.focus();
            }
        });
    });
})();
</script>
</body>
</html>
