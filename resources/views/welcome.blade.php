<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Cihuy Bandung - Unggul, Berkarakter, Berprestasi</title>
    <meta name="description" content="SMA Cihuy Bandung, sekolah swasta unggulan dengan Kurikulum Merdeka, akreditasi A, dan portal akademik SIAKAD.">
    <meta property="og:title" content="SMA Cihuy Bandung">
    <meta property="og:description" content="Sekolah swasta unggulan di Bandung. Akreditasi A, Kurikulum Merdeka, portal SIAKAD.">
    <meta property="og:image" content="/images/sma_hero.webp">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        body { text-wrap: pretty; background: #F4F5F7; }
        h1, h2 { text-wrap: balance; }
        .rv { opacity: 0; transform: translateY(4rem); filter: blur(12px); transition: opacity .9s cubic-bezier(0.32,0.72,0,1), transform .9s cubic-bezier(0.32,0.72,0,1), filter .9s cubic-bezier(0.32,0.72,0,1); will-change: transform; }
        .rv.on { opacity: 1; transform: none; filter: blur(0); }
        .grain { position: fixed; inset: 0; z-index: 60; pointer-events: none; opacity: .035; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E"); }
        .mask { overflow: hidden; display: block; }
        .mask > span { display: block; transform: translateY(3rem); opacity: 0; transition: transform .7s cubic-bezier(0.32,0.72,0,1), opacity .7s cubic-bezier(0.32,0.72,0,1); }
        .menu-open .mask > span { transform: none; opacity: 1; }
        #mnav { transition: opacity .5s cubic-bezier(0.32,0.72,0,1); }
        @media (prefers-reduced-motion: reduce) {
            .rv { opacity: 1; transform: none; filter: none; transition: none; }
            .mask > span { transform: none; opacity: 1; transition: none; }
            html { scroll-behavior: auto; }
        }
    </style>
</head>
<body class="bg-[#F4F5F7] text-slate-600 font-sans antialiased overflow-x-hidden">
<div class="grain" aria-hidden="true"></div>
<a href="#tentang" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[70] focus:bg-navy-800 focus:text-white focus:px-5 focus:py-2.5 focus:rounded-full focus:text-sm">Lewati ke konten</a>

{{-- FLUID ISLAND NAV --}}
<header class="fixed top-4 inset-x-0 z-50 px-4">
    <nav class="max-w-6xl mx-auto rounded-full bg-white/80 backdrop-blur-xl ring-1 ring-black/5 shadow-[0_24px_60px_-24px_rgba(15,37,87,0.28)] pl-2 pr-2 py-2 flex items-center justify-between gap-4" aria-label="Navigasi utama">
        <a href="/" class="flex items-center gap-3 rounded-full pl-1 pr-4 py-1">
            <span class="block rounded-full bg-navy-800 p-1 ring-1 ring-black/5">
                <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy Bandung" class="w-9 h-9 rounded-full bg-white object-cover">
            </span>
            <span class="leading-tight">
                <span class="block font-display font-extrabold text-navy-900 text-sm tracking-tight">SMA CIHUY</span>
                <span class="block text-[10px] font-medium tracking-[0.2em] text-slate-500">BANDUNG</span>
            </span>
        </a>
        <ul class="hidden lg:flex items-center gap-7 text-[13px] font-semibold text-slate-500">
            <li><a class="hover:text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#tentang">Tentang</a></li>
            <li><a class="hover:text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#berita">Berita</a></li>
            <li><a class="hover:text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#galeri">Galeri</a></li>
            <li><a class="hover:text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#fasilitas">Fasilitas</a></li>
            <li><a class="hover:text-navy-800 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#prestasi">Prestasi</a></li>
        </ul>
        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="group hidden sm:inline-flex items-center gap-3 rounded-full bg-navy-800 pl-6 pr-1.5 py-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">Portal SIAKAD<span class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8"/></svg></span></a>
            @else
                <a href="{{ route('login') }}" class="group hidden sm:inline-flex items-center gap-3 rounded-full bg-navy-800 pl-6 pr-1.5 py-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">Portal SIAKAD<span class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8"/></svg></span></a>
            @endauth
            <button id="burger" class="relative w-11 h-11 rounded-full bg-slate-900/[0.04] ring-1 ring-black/5 flex items-center justify-center" aria-label="Buka menu" aria-expanded="false">
                <span id="bl1" class="absolute w-5 h-[1.5px] bg-navy-900 rounded transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] -translate-y-[4px]"></span>
                <span id="bl2" class="absolute w-5 h-[1.5px] bg-navy-900 rounded transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] translate-y-[4px]"></span>
            </button>
        </div>
    </nav>
</header>

{{-- FULLSCREEN MENU --}}
<div id="mnav" class="fixed inset-0 z-40 bg-white/85 backdrop-blur-3xl opacity-0 pointer-events-none">
    <div class="h-full max-w-6xl mx-auto px-6 pt-32 pb-10 flex flex-col justify-center">
        <ul class="space-y-2 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-6xl">
            <li class="mask"><span style="transition-delay:.05s"><a href="#tentang">Tentang</a></span></li>
            <li class="mask"><span style="transition-delay:.12s"><a href="#berita">Berita</a></span></li>
            <li class="mask"><span style="transition-delay:.19s"><a href="#galeri">Galeri</a></span></li>
            <li class="mask"><span style="transition-delay:.26s"><a href="#fasilitas">Fasilitas</a></span></li>
            <li class="mask"><span style="transition-delay:.33s"><a href="#prestasi">Prestasi</a></span></li>
            <li class="mask"><span style="transition-delay:.40s"><a href="{{ route('login') }}" class="text-gold-700">Portal SIAKAD</a></span></li>
        </ul>
        <p class="mask mt-10"><span style="transition-delay:.48s" class="text-sm text-slate-500">Jl. Cihuy Raya No. 1, Bandung 40212</span></p>
    </div>
</div>

<main>
{{-- HERO: editorial split --}}
<section id="beranda" class="min-h-[100dvh] flex items-center pt-36 pb-24 px-4">
    <div class="max-w-6xl mx-auto w-full grid lg:grid-cols-2 gap-14 items-center">
        <div class="rv">
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-navy-800 text-white ring-1 ring-black/5">Akreditasi A Ban S/M</p>
            <h1 class="mt-6 font-display font-extrabold tracking-tight text-navy-900 text-5xl sm:text-6xl lg:text-7xl leading-[0.95]">Sekolah yang terasa dirancang.</h1>
            <p class="mt-6 text-slate-500 leading-relaxed max-w-[52ch]">Kurikulum Merdeka, guru bersertifikat, dan portal SIAKAD untuk orang tua. Semua dalam satu kampus di Bandung.</p>
            <div class="mt-9 flex flex-wrap items-center gap-3">
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 pl-7 pr-2 py-2 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-700 active:scale-[0.98]">Portal SIAKAD<span class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8"/></svg></span></a>
                <a href="#tentang" class="group inline-flex items-center gap-3 rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-navy-900 ring-1 ring-black/5 shadow-[0_16px_40px_-20px_rgba(15,37,87,0.35)] transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:ring-black/10 active:scale-[0.98]">Profil sekolah</a>
            </div>
            <dl class="mt-12 grid grid-cols-3 max-w-md gap-6 border-t border-slate-900/10 pt-6">
                <div><dd class="font-display font-extrabold text-2xl text-navy-900">1.200+</dd><dt class="text-xs text-slate-500 mt-1">Siswa aktif</dt></div>
                <div><dd class="font-display font-extrabold text-2xl text-navy-900">85+</dd><dt class="text-xs text-slate-500 mt-1">Pendidik</dt></div>
                <div><dd class="font-display font-extrabold text-2xl text-navy-900">20+</dd><dt class="text-xs text-slate-500 mt-1">Tahun berdiri</dt></div>
            </dl>
        </div>
        <div class="relative rv">
            <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_40px_90px_-40px_rgba(15,37,87,0.45)] md:rotate-2 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                <img src="/images/sma_hero.webp" alt="Gedung utama SMA Cihuy Bandung" class="rounded-[calc(2rem-0.5rem)] w-full h-[440px] object-cover shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]" fetchpriority="high">
            </div>
            <div class="rounded-[2rem] bg-white p-1.5 ring-1 ring-black/5 shadow-[0_32px_70px_-30px_rgba(15,37,87,0.5)] absolute -bottom-8 -left-2 sm:left-6 md:-rotate-3 w-[240px] transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                <div class="rounded-[calc(2rem-0.375rem)] bg-navy-900 px-5 py-4 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gold-200 font-medium">OSN 2026</p>
                    <p class="mt-1 font-display font-bold text-white text-lg leading-snug">5 emas, 3 perak, 2 perunggu</p>
                </div>
            </div>
            <div class="rounded-[2rem] bg-white p-1.5 ring-1 ring-black/5 shadow-[0_32px_70px_-30px_rgba(15,37,87,0.4)] absolute -top-6 -right-1 sm:right-4 md:rotate-3 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                <img src="/images/sma_kelas.webp" alt="Suasana belajar di kelas" class="rounded-[calc(2rem-0.375rem)] w-40 h-28 object-cover">
            </div>
        </div>
    </div>
</section>

{{-- TENTANG: asymmetrical bento --}}
<section id="tentang" class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto grid lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.4)] rv">
            <img src="/images/sma_upacara.webp" alt="Upacara bendera siswa SMA Cihuy" class="rounded-[calc(2rem-0.5rem)] w-full h-[420px] lg:h-[520px] object-cover">
        </div>
        <div class="lg:col-span-5 grid gap-6">
            <div class="rounded-[2rem] bg-navy-900 p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.6)] rv">
                <div class="rounded-[calc(2rem-0.5rem)] p-8 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                    <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white/10 text-gold-200">Tentang SMA Cihuy</p>
                    <h2 class="mt-4 font-display font-extrabold tracking-tight text-white text-3xl sm:text-4xl leading-[1.02]">Dua dekade mendidik Bandung.</h2>
                    <p class="mt-4 text-sm text-slate-300 leading-relaxed">Sejak 2004. Pembelajaran berbasis proyek, fokus pada kreativitas dan berpikir kritis.</p>
                </div>
            </div>
            <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv">
                <ul class="rounded-[calc(2rem-0.5rem)] divide-y divide-slate-100 px-7 py-2">
                    <li class="py-5"><h3 class="font-bold text-navy-900 text-sm">Visi</h3><p class="text-sm text-slate-500 mt-1">Lulusan beriman, berprestasi, berbudaya.</p></li>
                    <li class="py-5"><h3 class="font-bold text-navy-900 text-sm">Misi</h3><p class="text-sm text-slate-500 mt-1">Kurikulum inovatif, lingkungan kondusif.</p></li>
                    <li class="py-5"><h3 class="font-bold text-navy-900 text-sm">Nilai</h3><p class="text-sm text-slate-500 mt-1">Integritas, disiplin, inovasi, gotong royong.</p></li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- BERITA: bento 8+4 --}}
<section id="berita" class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto">
        <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5 rv">Berita dan kegiatan</p>
        <h2 class="mt-4 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl rv">Kabar terbaru kampus.</h2>
        <div class="mt-10 grid lg:grid-cols-12 gap-6">
            <article class="lg:col-span-8 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.4)] rv group">
                <div class="rounded-[calc(2rem-0.5rem)] overflow-hidden">
                    <img src="/images/school_activity.webp" alt="Siswa peraih medali OSN 2026" class="w-full h-80 object-cover transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-[1.02]">
                    <div class="bg-navy-900 p-8">
                        <p class="text-xs text-slate-400">12 Juni 2026</p>
                        <h3 class="mt-2 font-display font-bold text-white text-2xl sm:text-3xl tracking-tight leading-tight">Juara umum OSN provinsi 2026.</h3>
                        <p class="mt-3 text-sm text-slate-300">Lima emas, tiga perak, dua perunggu untuk sains sekolah.</p>
                    </div>
                </div>
            </article>
            <div class="lg:col-span-4 grid gap-6">
                <a href="#berita" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:-translate-y-1 active:scale-[0.98] block">
                    <span class="rounded-[calc(2rem-0.5rem)] flex gap-4 items-center p-3">
                        <img src="/images/school_hero.webp" alt="Gerbang PPDB SMA Cihuy" class="w-24 h-24 rounded-2xl object-cover shrink-0">
                        <span><span class="block font-bold text-navy-900">PPDB 2026/2027 dibuka</span><span class="block text-xs text-slate-500 mt-1">10 Juni 2026</span></span>
                    </span>
                </a>
                <a href="#berita" class="rounded-[2rem] bg-navy-900 p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.5)] rv transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:-translate-y-1 active:scale-[0.98] block">
                    <span class="rounded-[calc(2rem-0.5rem)] flex gap-4 items-center p-3 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                        <img src="/images/sma_lab.webp" alt="Lab komputer baru" class="w-24 h-24 rounded-2xl object-cover shrink-0">
                        <span><span class="block font-bold text-white">Lab komputer baru</span><span class="block text-xs text-slate-500 mt-1">28 Mei 2026</span></span>
                    </span>
                </a>
                <a href="#berita" class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:-translate-y-1 active:scale-[0.98] block">
                    <span class="rounded-[calc(2rem-0.5rem)] flex gap-4 items-center p-3">
                        <img src="/images/school_lab.webp" alt="Kunjungan industri kelas XI" class="w-24 h-24 rounded-2xl object-cover shrink-0">
                        <span><span class="block font-bold text-navy-900">Kunjungan industri XI</span><span class="block text-xs text-slate-500 mt-1">15 Mei 2026</span></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- GALERI: masonry bento --}}
<section id="galeri" class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto">
        <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5 rv">Galeri</p>
        <h2 class="mt-4 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl rv">Kampus dalam bingkai.</h2>
        <div class="mt-10 grid md:grid-cols-12 gap-6">
            <figure class="md:col-span-7 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.4)] rv">
                <img src="/images/sma_hero.webp" alt="Gedung utama SMA Cihuy" class="rounded-[calc(2rem-0.5rem)] w-full h-[380px] object-cover">
            </figure>
            <figure class="md:col-span-5 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_32px_80px_-40px_rgba(15,37,87,0.4)] rv md:rotate-1">
                <img src="/images/sma_kelas.webp" alt="Belajar di kelas" class="rounded-[calc(2rem-0.5rem)] w-full h-[380px] object-cover">
            </figure>
            <figure class="md:col-span-4 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv">
                <img src="/images/sma_upacara.webp" alt="Upacara bendera" class="rounded-[calc(2rem-0.5rem)] w-full h-64 object-cover">
            </figure>
            <figure class="md:col-span-4 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv md:-rotate-1">
                <img src="/images/sma_lab.webp" alt="Praktikum laboratorium" class="rounded-[calc(2rem-0.5rem)] w-full h-64 object-cover">
            </figure>
            <figure class="md:col-span-4 rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv">
                <img src="/images/sma_ekskul.webp" alt="Latihan ekstrakurikuler" class="rounded-[calc(2rem-0.5rem)] w-full h-64 object-cover">
            </figure>
        </div>
    </div>
</section>

{{-- EKSKUL --}}
<section id="ekskul" class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto rounded-[2.5rem] bg-white p-3 sm:p-4 ring-1 ring-black/5 shadow-[0_40px_90px_-45px_rgba(15,37,87,0.4)]">
        <div class="rounded-[calc(2.5rem-1rem)] bg-slate-100 px-6 py-12 sm:p-12">
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-navy-800 text-white rv">Ekstrakurikuler</p>
            <h2 class="mt-4 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl rv">Bakat tumbuh di luar kelas.</h2>
            <div class="mt-10 grid md:grid-cols-12 gap-6">
                <div class="md:col-span-7 relative overflow-hidden rounded-[2rem] min-h-[300px] ring-1 ring-black/5 rv group">
                    <img src="/images/sma_ekskul.webp" alt="Marching band SMA Cihuy" class="absolute inset-0 w-full h-full object-cover transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-[1.03]">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-900/90 to-transparent"></div>
                    <div class="absolute bottom-0 p-7"><h3 class="text-white font-bold text-xl tracking-tight">Marching Band</h3><p class="text-slate-300 text-sm mt-1">Juara nasional sejak 2015.</p></div>
                </div>
                <div class="md:col-span-5 rounded-[2rem] bg-navy-900 p-8 ring-1 ring-black/5 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)] rv">
                    <h3 class="font-bold text-white text-lg tracking-tight">Robotik dan Coding</h3><p class="text-sm text-slate-300 mt-2">Kompetisi teknologi nasional.</p>
                    <h3 class="font-bold text-white text-lg tracking-tight mt-8">Karya Ilmiah</h3><p class="text-sm text-slate-300 mt-2">Riset dan LKTI provinsi.</p>
                </div>
                <div class="md:col-span-4 rounded-[2rem] bg-gold-500 p-8 ring-1 ring-black/5 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] rv"><h3 class="font-bold text-navy-900 tracking-tight">Olahraga</h3><p class="text-sm text-navy-900/70 mt-2">Bola, basket, voli, atletik.</p></div>
                <div class="md:col-span-4 rounded-[2rem] bg-white p-8 ring-1 ring-black/5 rv"><h3 class="font-bold text-navy-900 tracking-tight">Seni</h3><p class="text-sm text-slate-500 mt-2">Teater, tari, paduan suara.</p></div>
                <div class="md:col-span-4 rounded-[2rem] bg-white p-8 ring-1 ring-black/5 rv"><h3 class="font-bold text-navy-900 tracking-tight">Pramuka dan PMR</h3><p class="text-sm text-slate-500 mt-2">Wajib untuk kelas X.</p></div>
            </div>
        </div>
    </div>
</section>

{{-- FASILITAS: cascade --}}
<section id="fasilitas" class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-10 items-center">
        <div class="rv">
            <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5">Fasilitas</p>
            <h2 class="mt-4 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl leading-[1.0]">Ruang yang membuat betah.</h2>
            <div class="mt-8 grid sm:grid-cols-2 gap-5">
                <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] md:-rotate-1">
                    <div class="rounded-[calc(2rem-0.5rem)] p-6"><h3 class="text-xs font-medium uppercase tracking-[0.2em] text-slate-500">Akademik</h3><ul class="mt-3 space-y-2.5 text-sm text-slate-600"><li>Lab Fisika, Kimia, Biologi</li><li>Perpustakaan 15.000 buku</li><li>36 kelas pintar ber AC</li></ul></div>
                </div>
                <div class="rounded-[2rem] bg-navy-900 p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.5)] md:rotate-1 md:mt-8">
                    <div class="rounded-[calc(2rem-0.5rem)] p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]"><h3 class="text-xs font-medium uppercase tracking-[0.2em] text-slate-400">Penunjang</h3><ul class="mt-3 space-y-2.5 text-sm text-slate-200"><li>Lapangan dan aula</li><li>WiFi seluruh area</li><li>UKS dan kantin sehat</li></ul></div>
                </div>
            </div>
        </div>
        <div class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_40px_90px_-45px_rgba(15,37,87,0.45)] rv">
            <img src="/images/school_lab.webp" alt="Laboratorium SMA Cihuy" class="rounded-[calc(2rem-0.5rem)] w-full h-[480px] object-cover">
        </div>
    </div>
</section>

{{-- PRESTASI --}}
<section id="prestasi" class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto">
        <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white text-navy-800 ring-1 ring-black/5 rv">Prestasi</p>
        <h2 class="mt-4 font-display font-extrabold tracking-tight text-navy-900 text-4xl sm:text-5xl rv">Jejak kebanggaan.</h2>
        <ol class="mt-10 grid md:grid-cols-2 gap-6">
            <li class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv"><div class="rounded-[calc(2rem-0.5rem)] p-7"><p class="font-display font-extrabold text-4xl text-gold-600">01</p><h3 class="mt-3 font-bold text-navy-900">Emas Olimpiade Matematika</h3><p class="text-sm text-slate-500 mt-1">Nasional 2024, KSN.</p></div></li>
            <li class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv md:mt-8"><div class="rounded-[calc(2rem-0.5rem)] p-7"><p class="font-display font-extrabold text-4xl text-gold-600">02</p><h3 class="mt-3 font-bold text-navy-900">Juara Marching Band</h3><p class="text-sm text-slate-500 mt-1">Provinsi 2024, Jawa Barat Open.</p></div></li>
            <li class="rounded-[2rem] bg-navy-900 p-2 ring-1 ring-black/5 rv"><div class="rounded-[calc(2rem-0.5rem)] p-7 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]"><p class="font-display font-extrabold text-4xl text-gold-200">03</p><h3 class="mt-3 font-bold text-white">Sekolah Penggerak</h3><p class="text-sm text-slate-300 mt-1">Kemendikbudristek 2023.</p></div></li>
            <li class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv md:mt-8"><div class="rounded-[calc(2rem-0.5rem)] p-7"><p class="font-display font-extrabold text-4xl text-gold-600">04</p><h3 class="mt-3 font-bold text-navy-900">Juara Umum O2SN</h3><p class="text-sm text-slate-500 mt-1">Kota Bandung 2023.</p></div></li>
        </ol>
    </div>
</section>

{{-- TESTIMONI --}}
<section class="px-4 py-24 lg:py-32">
    <div class="max-w-6xl mx-auto grid lg:grid-cols-12 gap-6">
        <figure class="lg:col-span-7 rounded-[2rem] bg-navy-900 p-2 ring-1 ring-black/5 shadow-[0_40px_90px_-45px_rgba(15,37,87,0.6)] rv">
            <blockquote class="rounded-[calc(2rem-0.5rem)] p-9 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                <p class="font-display text-white text-2xl sm:text-3xl tracking-tight leading-tight">"Guru Cihuy memotivasi kami bermimpi besar. Saya lolos SNBP ke ITB."</p>
                <figcaption class="mt-7 text-sm"><span class="block font-bold text-gold-200">Andi Ramadhan</span><span class="block text-slate-400 text-xs mt-1">Alumni 2023</span></figcaption>
            </blockquote>
        </figure>
        <div class="lg:col-span-5 grid gap-6">
            <figure class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv"><blockquote class="rounded-[calc(2rem-0.5rem)] p-7 text-sm text-slate-600">"Nilai anak saya naik pesat dan jadi mandiri."<figcaption class="mt-4 font-bold text-navy-900">Siti Wahyuni <span class="block text-xs font-normal text-slate-500">Orang tua kelas XII</span></figcaption></blockquote></figure>
            <figure class="rounded-[2rem] bg-white p-2 ring-1 ring-black/5 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] rv"><blockquote class="rounded-[calc(2rem-0.5rem)] p-7 text-sm text-slate-600">"Fasilitas memadai, siswa sangat semangat."<figcaption class="mt-4 font-bold text-navy-900">Budi Prasetyo <span class="block text-xs font-normal text-slate-500">Guru Matematika</span></figcaption></blockquote></figure>
        </div>
    </div>
</section>

{{-- PPDB ISLAND --}}
<section id="kontak" class="px-4 pb-24 lg:pb-32">
    <div class="max-w-6xl mx-auto rounded-[2.5rem] bg-navy-800 p-3 ring-1 ring-black/5 shadow-[0_50px_100px_-50px_rgba(15,37,87,0.7)] rv">
        <div class="rounded-[calc(2.5rem-0.75rem)] px-8 py-14 sm:p-14 flex flex-col lg:flex-row lg:items-center gap-10 justify-between shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
            <div>
                <p class="inline-flex rounded-full px-3 py-1 text-[10px] uppercase tracking-[0.2em] font-medium bg-white/10 text-gold-200">PPDB 2026/2027</p>
                <h2 class="mt-4 font-display font-extrabold tracking-tight text-white text-3xl sm:text-5xl leading-[1.0]">Amankan kursi putra putri Anda.</h2>
                <p class="mt-4 text-sm text-slate-300 max-w-[55ch]">Beasiswa prestasi dan ekonomi tersedia. Hubungi (022) 222-0001 atau info@smacihuy.sch.id.</p>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-3 rounded-full bg-gold-500 pl-7 pr-2 py-2 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-gold-600 active:scale-[0.98]">Portal SIAKAD<span class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8"/></svg></span></a>
                <a href="#tentang" class="inline-flex items-center rounded-full border border-white/20 px-7 py-3.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:border-white/50 active:scale-[0.98]">Info sekolah</a>
            </div>
        </div>
    </div>
</section>
</main>

<footer class="bg-navy-900 rounded-t-[2.5rem]">
    <div class="max-w-6xl mx-auto px-6 py-14 grid md:grid-cols-3 gap-10">
        <div>
            <div class="flex items-center gap-3">
                <img src="/images/sma_logo.webp" alt="Logo SMA Cihuy" class="w-11 h-11 rounded-full bg-white object-cover ring-1 ring-white/20">
                <p class="font-display font-extrabold text-white tracking-tight">SMA CIHUY <span class="block text-[10px] font-medium tracking-[0.2em] text-slate-400">BANDUNG</span></p>
            </div>
            <p class="mt-4 text-sm text-slate-400">Swasta unggulan sejak 2004. NPSN 20219876.</p>
            <p class="mt-4 text-sm text-slate-400">Jl. Cihuy Raya No. 1, Bandung 40212<br>(022) 222-0001<br>info@smacihuy.sch.id</p>
        </div>
        <nav aria-label="Tautan sekolah">
            <h3 class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-400 mb-4">Sekolah</h3>
            <ul class="space-y-2.5 text-sm text-slate-300">
                <li><a class="hover:text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#tentang">Tentang kami</a></li>
                <li><a class="hover:text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#berita">Berita terbaru</a></li>
                <li><a class="hover:text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#fasilitas">Fasilitas</a></li>
                <li><a class="hover:text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#prestasi">Prestasi</a></li>
            </ul>
        </nav>
        <nav aria-label="Tautan portal">
            <h3 class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-400 mb-4">Portal</h3>
            <ul class="space-y-2.5 text-sm text-slate-300">
                <li><a class="hover:text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="{{ route('login') }}">Portal SIAKAD</a></li>
                @auth
                <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="hover:text-white" type="submit">Keluar</button></form></li>
                @endauth
                <li><a class="hover:text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" href="#kontak">Kontak PPDB</a></li>
            </ul>
        </nav>
    </div>
    <div class="border-t border-white/10">
        <div class="max-w-6xl mx-auto px-6 py-5 flex flex-col sm:flex-row gap-2 justify-between text-xs text-slate-400">
            <p>© {{ date('Y') }} SMA Cihuy Bandung.</p>
            <p><a class="hover:text-white" href="#">Kebijakan privasi</a> <span class="mx-2">-</span> <a class="hover:text-white" href="#">Syarat layanan</a></p>
        </div>
    </div>
</footer>

<script>
(function () {
    var burger = document.getElementById('burger');
    var mnav = document.getElementById('mnav');
    var b1 = document.getElementById('bl1');
    var b2 = document.getElementById('bl2');
    var open = false;
    function setMenu(v) {
        open = v;
        document.body.classList.toggle('menu-open', v);
        burger.setAttribute('aria-expanded', String(v));
        mnav.classList.toggle('opacity-0', !v);
        mnav.classList.toggle('pointer-events-none', !v);
        b1.style.transform = v ? 'translateY(0) rotate(45deg)' : '';
        b2.style.transform = v ? 'translateY(0) rotate(-45deg)' : '';
        if (!v) { b1.classList.add('-translate-y-[4px]'); b2.classList.add('translate-y-[4px]'); }
        else { b1.classList.remove('-translate-y-[4px]'); b2.classList.remove('translate-y-[4px]'); }
    }
    burger.addEventListener('click', function () { setMenu(!open); });
    mnav.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('on'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    document.querySelectorAll('.rv').forEach(function (el) { io.observe(el); });
})();
</script>
</body>
</html>
