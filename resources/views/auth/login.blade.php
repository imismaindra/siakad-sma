<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal SIAKAD - SMA Cihuy Bandung</title>
    <meta name="description" content="Masuk ke Sistem Informasi Akademik SMA Cihuy Bandung.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        body { text-wrap: pretty; }
        .rv { opacity: 0; transform: translateY(2rem); filter: blur(8px); transition: opacity .8s cubic-bezier(0.32,0.72,0,1), transform .8s cubic-bezier(0.32,0.72,0,1), filter .8s cubic-bezier(0.32,0.72,0,1); }
        .rv.on { opacity: 1; transform: none; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
            .rv { opacity: 1; transform: none; filter: none; transition: none; }
        }
    </style>
</head>
<body class="bg-[#F4F5F7] text-slate-600 font-sans antialiased">
<a href="#login-form" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[70] focus:bg-navy-800 focus:text-white focus:px-5 focus:py-2.5 focus:rounded-full focus:text-sm">Lewati ke formulir</a>

<div class="min-h-[100dvh] grid lg:grid-cols-12 gap-6 p-4 sm:p-6">

    {{-- LEFT: brand island --}}
    <aside class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-navy-800 ring-1 ring-black/5 min-h-[420px] rv" aria-label="Tentang portal SIAKAD">
        <img src="/images/sma_hero.webp" alt="Gedung utama SMA Cihuy Bandung" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-navy-900/70 to-navy-900/20"></div>
        <div class="relative h-full flex flex-col justify-between p-8 sm:p-12 min-h-[420px] lg:min-h-full">
            <a href="/" class="inline-flex items-center gap-3 self-start rounded-full bg-white/10 py-1.5 pl-1.5 pr-5 ring-1 ring-white/15 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white/15">
                <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy" class="w-9 h-9 rounded-full bg-white object-cover">
                <span class="leading-tight">
                    <span class="block font-display font-extrabold text-white text-sm tracking-tight">SMA CIHUY</span>
                    <span class="block text-[10px] font-medium tracking-[0.2em] text-gold-200">BANDUNG</span>
                </span>
            </a>
            <div class="mt-12">
                <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white/10 text-gold-200 ring-1 ring-white/15">Portal akademik terpadu</p>
                <h1 class="mt-5 font-display font-extrabold tracking-tight text-white text-4xl sm:text-5xl lg:text-6xl leading-[1.0] max-w-[14ch]">Satu pintu untuk nilai, jadwal, absensi.</h1>
                <ul class="mt-8 grid sm:grid-cols-3 gap-4 max-w-2xl">
                    <li class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15"><p class="font-bold text-white text-sm">Nilai real-time</p><p class="text-xs text-slate-300 mt-1">Rapor dan hasil belajar terpantau.</p></li>
                    <li class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15"><p class="font-bold text-white text-sm">Jadwal jelas</p><p class="text-xs text-slate-300 mt-1">Kelas dan ruangan selalu akurat.</p></li>
                    <li class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15"><p class="font-bold text-white text-sm">Data aman</p><p class="text-xs text-slate-300 mt-1">Akses berbasis peran terproteksi.</p></li>
                </ul>
                <p class="mt-8 text-xs text-slate-400">Jl. Cihuy Raya No. 1, Bandung 40212</p>
            </div>
        </div>
    </aside>

    {{-- RIGHT: form --}}
    <main class="lg:col-span-5 flex items-center justify-center px-2 sm:px-6 py-10">
        <div class="w-full max-w-md rv">
            <a href="/" class="group inline-flex items-center gap-3 rounded-full bg-white pl-2 pr-5 py-1.5 text-[13px] font-semibold text-navy-900 ring-1 ring-black/5 shadow-[0_16px_40px_-20px_rgba(15,37,87,0.35)] transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">
                <span class="w-8 h-8 rounded-full bg-navy-800 text-white flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:-translate-x-0.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></span>
                Kembali ke beranda
            </a>

            <p class="mt-8 inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Portal SIAKAD</p>
            <h2 id="login-form" class="mt-4 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl leading-[1.0]">Selamat datang kembali.</h2>
            <p class="mt-3 text-slate-500 text-[15px] leading-relaxed">Masuk untuk mengelola jadwal, nilai, dan absensi Anda.</p>

            <div class="mt-8 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.4)]">
                <div class="rounded-[calc(2rem-0.5rem)] p-7 sm:p-8">
                    @if(session('success'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 flex items-start gap-3 mb-6" role="status">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-emerald-700 text-[13px] font-semibold leading-relaxed">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error') || $errors->has('error'))
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 flex items-start gap-3 mb-6" role="alert">
                            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-rose-700 text-[13px] font-semibold leading-relaxed">{{ session('error') ?? $errors->first('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="space-y-5" novalidate>
                        @csrf
                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Email resmi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                                </span>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@smacihuy.sch.id"
                                    class="w-full rounded-2xl border @error('email') border-rose-400 @else border-slate-200 @enderror bg-slate-50 pl-12 pr-4 py-3.5 text-[15px] font-medium text-navy-900 placeholder:text-slate-400 outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10">
                            </div>
                            @error('email')
                                <p class="mt-2 text-[13px] font-semibold text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Kata sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 pl-12 pr-12 py-3.5 text-[15px] font-medium text-navy-900 placeholder:text-slate-400 outline-none transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:bg-white focus:border-navy-800 focus:ring-4 focus:ring-navy-800/10">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" aria-label="Tampilkan kata sandi">
                                    <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-2.5 text-[13px] font-semibold text-slate-500 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded-md border-slate-300 accent-[#172554]">
                            Ingat sesi saya
                        </label>

                        <button type="submit" class="group w-full inline-flex items-center justify-between gap-3 rounded-full bg-navy-800 py-2 pl-7 pr-2 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">
                            Masuk ke portal
                            <span class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:scale-105"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg></span>
                        </button>
                    </form>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-500">© {{ date('Y') }} SMA Cihuy Bandung. Halaman masuk resmi portal SIAKAD.</p>
        </div>
    </main>
</div>

<script>
(function () {
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('on'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.rv').forEach(function (el) { io.observe(el); });
    var t = document.getElementById('togglePassword');
    var p = document.getElementById('password');
    if (t && p) {
        t.addEventListener('click', function () {
            var show = p.getAttribute('type') === 'password';
            p.setAttribute('type', show ? 'text' : 'password');
            t.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            var eye = t.querySelector('.eye-icon');
            var off = t.querySelector('.eye-off-icon');
            if (eye && off) { eye.classList.toggle('hidden', show); off.classList.toggle('hidden', !show); }
        });
    }
})();
</script>
</body>
</html>
