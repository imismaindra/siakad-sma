<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD SMA - Sistem Informasi Akademik Sekolah Menengah Atas</title>
    <meta name="description" content="Sistem Informasi Akademik SMA terpadu: Presensi cerdas QR dinamis & geofencing GPS, pengolahan e-Rapor Kurikulum Merdeka otomatis, penjadwalan anti-bentrok, dan portal orang tua real-time.">
    <meta property="og:title" content="SIAKAD SMA - Sistem Informasi Akademik">
    <meta property="og:description" content="Platform operasional akademik sekolah untuk Administrator, Guru, Siswa, dan Orang Tua.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        body { text-wrap: pretty; background-color: #F8F9FA; color: #1E293B; }
        h1, h2, h3, h4 { text-wrap: balance; }
        
        /* High-contrast smooth scroll reveal without blur */
        .rv {
            opacity: 0;
            transform: translateY(1.5rem);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .rv.on {
            opacity: 1;
            transform: none;
        }
        
        .tab-panel {
            display: none;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        .tab-panel.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        @media (prefers-reduced-motion: reduce) {
            .rv { opacity: 1; transform: none; transition: none; }
            html { scroll-behavior: auto; }
        }
    </style>
</head>
<body class="bg-[#F8F9FA] text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-slate-900 selection:text-white">
<a href="#fitur" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[90] focus:bg-slate-950 focus:text-white focus:px-5 focus:py-2.5 focus:rounded-full focus:text-sm shadow-xl font-bold">Lewati ke konten utama</a>

{{-- FLOATING TOP NAVIGATION --}}
<header class="fixed top-4 inset-x-0 z-50 px-4">
    <nav class="max-w-6xl mx-auto rounded-full bg-white/95 backdrop-blur-md border border-slate-300 shadow-md pl-4 pr-2.5 py-2 flex items-center justify-between gap-4" aria-label="Navigasi utama">
        <a href="/" class="flex items-center gap-3 group">
            <span class="w-8 h-8 rounded-full bg-slate-950 flex items-center justify-center text-amber-400 group-hover:scale-105 transition-transform duration-300">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-5.25 6.557c0 1.564.914 2.872 2.25 3.483"/>
                </svg>
            </span>
            <span class="leading-tight">
                <span class="block font-display font-black text-slate-950 text-sm tracking-tight">SIAKAD<span class="text-amber-600">SMA</span></span>
                <span class="block text-[10px] font-mono font-bold tracking-wider text-slate-600 uppercase">SISTEM AKADEMIK</span>
            </span>
        </a>

        <ul class="hidden lg:flex items-center gap-7 text-[13px] font-bold text-slate-700">
            <li><a class="hover:text-slate-950 transition-colors" href="#fitur">Fitur Utama</a></li>
            <li><a class="hover:text-slate-950 transition-colors" href="#orang-tua">Portal Orang Tua</a></li>
            <li><a class="hover:text-slate-950 transition-colors" href="#peran">Matriks Peran</a></li>
            <li><a class="hover:text-slate-950 transition-colors" href="#keamanan">Log Audit</a></li>
            <li><a class="hover:text-slate-950 transition-colors" href="#spesifikasi">Spesifikasi</a></li>
        </ul>

        <div class="flex items-center gap-2">
            @auth
                @php
                    $roleEnum = \App\Enums\UserRole::tryFrom(auth()->user()->role);
                    $dashboardRoute = $roleEnum ? route($roleEnum->dashboardRoute()) : route('home');
                    $roleLabel = $roleEnum ? $roleEnum->label() : auth()->user()->role;
                @endphp
                <a href="{{ $dashboardRoute }}" class="group inline-flex items-center gap-2.5 rounded-full bg-slate-950 pl-5 pr-1.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-slate-800 transition-all duration-300 active:scale-[0.98]">
                    <span>Buka Dashboard ({{ $roleLabel }})</span>
                    <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center transition-transform group-hover:translate-x-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </span>
                </a>
            @else
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-2.5 rounded-full bg-slate-950 pl-5 pr-1.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-slate-800 transition-all duration-300 active:scale-[0.98]">
                    <span>Masuk ke Portal</span>
                    <span class="w-7 h-7 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center font-bold transition-transform group-hover:translate-x-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                    </span>
                </a>
            @endauth

            <button id="burger" class="w-9 h-9 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center text-slate-950 lg:hidden" aria-label="Buka menu navigasi" aria-expanded="false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>
    </nav>
</header>

{{-- MOBILE DRAWER --}}
<div id="mnav" class="fixed inset-0 z-40 bg-slate-950/95 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden">
    <div class="h-full max-w-sm mx-auto px-6 pt-28 pb-10 flex flex-col justify-between text-white">
        <ul class="space-y-4 font-display font-extrabold text-2xl">
            <li><a href="#fitur" class="hover:text-amber-400 block py-1">Fitur Utama</a></li>
            <li><a href="#orang-tua" class="hover:text-amber-400 block py-1">Portal Orang Tua</a></li>
            <li><a href="#peran" class="hover:text-amber-400 block py-1">Matriks Peran</a></li>
            <li><a href="#keamanan" class="hover:text-amber-400 block py-1">Log Audit Data</a></li>
            <li><a href="#spesifikasi" class="hover:text-amber-400 block py-1">Spesifikasi Sistem</a></li>
            <li class="pt-4 border-t border-slate-800">
                @auth
                    <a href="{{ $dashboardRoute }}" class="text-amber-400 font-bold flex items-center gap-2">Buka Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="text-amber-400 font-bold flex items-center gap-2">Masuk ke Portal &rarr;</a>
                @endauth
            </li>
        </ul>
        <div class="border-t border-slate-800 pt-4 text-xs text-slate-400 font-mono">
            SIAKAD SMA v2.0 · Laravel 13 & Spatie RBAC
        </div>
    </div>
</div>

<main>
{{-- HERO SECTION --}}
<section class="min-h-[100dvh] pt-32 sm:pt-36 pb-20 px-4 flex items-center border-b border-slate-300">
    <div class="max-w-6xl mx-auto w-full">
        <div class="grid lg:grid-cols-12 gap-10 items-center">
            
            {{-- Left column: Editorial pitch --}}
            <div class="lg:col-span-6 rv">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono font-bold bg-slate-900 text-slate-100 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    SIAKAD SMA · RILIS v2.0
                </div>

                <h1 class="mt-6 font-display font-black tracking-tight text-slate-950 text-4xl sm:text-5xl lg:text-6xl leading-[1.02]">
                    Satu sistem untuk kelola presensi, jadwal, dan e-Rapor SMA secara akurat.
                </h1>

                <p class="mt-6 text-base sm:text-lg text-slate-700 leading-relaxed font-normal">
                    Menggantikan rekap presensi kertas dan formula lembar kerja terpisah. Dilengkapi verifikasi presensi QR dinamis berpagar GPS, perhitungan nilai otomatis Kurikulum Merdeka, dan portal monitoring harian untuk orang tua murid.
                </p>

                {{-- Action Button Group --}}
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    @auth
                        <a href="{{ $dashboardRoute }}" class="group inline-flex items-center gap-3 rounded-full bg-slate-950 pl-6 pr-2 py-2.5 text-sm font-bold text-white shadow-md hover:bg-slate-800 transition-all duration-300 active:scale-[0.98]">
                            <span>Lanjutkan ke Dashboard</span>
                            <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center transition-transform group-hover:translate-x-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-3 rounded-full bg-slate-950 pl-6 pr-2 py-2.5 text-sm font-bold text-white shadow-md hover:bg-slate-800 transition-all duration-300 active:scale-[0.98]">
                            <span>Masuk ke Portal SIAKAD</span>
                            <span class="w-8 h-8 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center font-bold transition-transform group-hover:translate-x-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </a>
                    @endauth

                    <a href="#fitur" class="inline-flex items-center gap-2 rounded-full bg-white border border-slate-300 px-6 py-3 text-sm font-bold text-slate-900 shadow-sm hover:bg-slate-100 transition-all duration-300">
                        <span>Lihat Fitur Sistem</span>
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </a>
                </div>

                {{-- Stats Counters --}}
                <div class="mt-10 pt-6 border-t border-slate-300 grid grid-cols-3 gap-6">
                    <div>
                        <div class="font-mono font-black text-2xl sm:text-3xl text-slate-950">0</div>
                        <div class="text-xs font-bold text-slate-700 mt-0.5">Bentrok Jadwal</div>
                        <div class="text-[11px] text-slate-600">Validasi ruang & guru</div>
                    </div>
                    <div>
                        <div class="font-mono font-black text-2xl sm:text-3xl text-amber-600">30s</div>
                        <div class="text-xs font-bold text-slate-700 mt-0.5">Rotasi Token QR</div>
                        <div class="text-[11px] text-slate-600">Radius GPS 100m</div>
                    </div>
                    <div>
                        <div class="font-mono font-black text-2xl sm:text-3xl text-slate-950">4</div>
                        <div class="text-xs font-bold text-slate-700 mt-0.5">Hak Akses Peran</div>
                        <div class="text-[11px] text-slate-600">Admin, Guru, Siswa, Ortu</div>
                    </div>
                </div>
            </div>

            {{-- Right column: Interactive Device Showcase --}}
            <div class="lg:col-span-6 rv">
                <div class="rounded-3xl bg-slate-900 p-2 sm:p-3 border border-slate-800 shadow-2xl">
                    <div class="rounded-2xl bg-slate-950 text-white overflow-hidden border border-slate-800">
                        
                        {{-- Device Header --}}
                        <div class="px-5 py-3 bg-slate-900 border-b border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="ml-2 font-mono text-xs text-slate-300 font-semibold">siakad.sma.sch.id</span>
                            </div>
                            <span class="text-xs font-mono font-bold px-2.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Sesi Aktif
                            </span>
                        </div>

                        {{-- Tab Controls --}}
                        <div class="grid grid-cols-4 bg-slate-900/80 border-b border-slate-800 p-1 text-xs font-bold">
                            <button class="hero-tab px-2 py-2 rounded-lg text-center transition-all bg-white text-slate-950 shadow-sm" data-tab="tab-presensi">
                                Presensi QR
                            </button>
                            <button class="hero-tab px-2 py-2 rounded-lg text-center transition-all text-slate-300 hover:text-white" data-tab="tab-rapor">
                                e-Rapor
                            </button>
                            <button class="hero-tab px-2 py-2 rounded-lg text-center transition-all text-slate-300 hover:text-white" data-tab="tab-ortu">
                                Wali Murid
                            </button>
                            <button class="hero-tab px-2 py-2 rounded-lg text-center transition-all text-slate-300 hover:text-white" data-tab="tab-audit">
                                Log Audit
                            </button>
                        </div>

                        {{-- Tab Body --}}
                        <div class="p-5 sm:p-6 min-h-[350px]">
                            
                            {{-- Tab 1: Presensi --}}
                            <div id="tab-presensi" class="tab-panel active space-y-4">
                                <div class="bg-slate-900 p-3.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                    <div>
                                        <p class="text-[11px] font-mono font-bold uppercase tracking-wider text-amber-400">Jam Ke-1 s/d Ke-2</p>
                                        <h3 class="text-sm font-bold text-white mt-0.5">Fisika · Kelas XI MIPA 1</h3>
                                        <p class="text-xs text-slate-300 font-medium">Budi Prasetyo, M.Pd · Ruang Lab Fisika</p>
                                    </div>
                                    <span class="text-xs font-mono font-bold px-2 py-1 rounded bg-emerald-500/20 text-emerald-300">
                                        Sesi Terbuka
                                    </span>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex flex-col items-center text-center">
                                        <div class="w-32 h-32 bg-white p-2.5 rounded-lg flex items-center justify-center">
                                            <svg class="w-full h-full text-slate-950" viewBox="0 0 100 100" fill="currentColor">
                                                <rect x="5" y="5" width="28" height="28" rx="2" fill="none" stroke="currentColor" stroke-width="6"/>
                                                <rect x="12" y="12" width="14" height="14" rx="1"/>
                                                <rect x="67" y="5" width="28" height="28" rx="2" fill="none" stroke="currentColor" stroke-width="6"/>
                                                <rect x="74" y="12" width="14" height="14" rx="1"/>
                                                <rect x="5" y="67" width="28" height="28" rx="2" fill="none" stroke="currentColor" stroke-width="6"/>
                                                <rect x="12" y="74" width="14" height="14" rx="1"/>
                                                <rect x="42" y="15" width="16" height="6"/>
                                                <rect x="42" y="27" width="8" height="10"/>
                                                <rect x="15" y="42" width="10" height="8"/>
                                                <rect x="30" y="42" width="6" height="16"/>
                                                <rect x="42" y="42" width="16" height="16"/>
                                                <rect x="64" y="42" width="10" height="8"/>
                                                <rect x="80" y="42" width="6" height="16"/>
                                                <rect x="42" y="64" width="8" height="10"/>
                                                <rect x="56" y="64" width="14" height="6"/>
                                                <rect x="76" y="64" width="10" height="16"/>
                                                <rect x="42" y="80" width="16" height="6"/>
                                            </svg>
                                        </div>
                                        <div class="mt-3 font-mono text-xs font-bold text-slate-200">
                                            Rotasi Token: <span class="text-amber-400">18 detik lagi</span>
                                        </div>
                                        <div class="font-mono text-xs text-slate-300 mt-1 font-semibold">
                                            OTP Cadangan: <span class="text-white font-bold tracking-wider">842 190</span>
                                        </div>
                                    </div>

                                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex flex-col justify-between text-xs">
                                        <div class="space-y-2.5">
                                            <div class="text-amber-400 font-mono font-bold text-[11px] uppercase">Validasi Geofencing GPS</div>
                                            <div class="flex justify-between py-1 border-b border-slate-800 font-medium text-slate-200">
                                                <span>Radius Sekolah</span>
                                                <span class="font-mono text-white font-bold">100 meter</span>
                                            </div>
                                            <div class="flex justify-between py-1 border-b border-slate-800 font-medium text-slate-200">
                                                <span>Jarak Siswa</span>
                                                <span class="font-mono text-emerald-400 font-bold">38 meter (Valid)</span>
                                            </div>
                                            <div class="flex justify-between py-1 font-medium text-slate-200">
                                                <span>Anti-Fake GPS</span>
                                                <span class="font-mono text-emerald-400 font-bold">Terproteksi</span>
                                            </div>
                                        </div>
                                        <div class="pt-3 border-t border-slate-800 flex justify-between items-center font-bold">
                                            <span class="text-slate-300">Terekam Hadir:</span>
                                            <span class="font-mono text-white text-sm">34 / 36 Siswa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 2: e-Rapor --}}
                            <div id="tab-rapor" class="tab-panel space-y-4">
                                <div class="bg-slate-900 p-3.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                    <div>
                                        <p class="text-[11px] font-mono font-bold uppercase tracking-wider text-amber-400">Kurikulum Merdeka</p>
                                        <h3 class="text-sm font-bold text-white mt-0.5">Ledger Capaian Belajar</h3>
                                        <p class="text-xs text-slate-300">Ahmad Maulana · NISN 0072918231</p>
                                    </div>
                                    <span class="text-xs font-mono font-bold px-2.5 py-1 rounded bg-amber-400 text-slate-950">
                                        Rata-rata 89.8
                                    </span>
                                </div>

                                <div class="rounded-lg border border-slate-800 bg-slate-900 overflow-x-auto">
                                    <table class="w-full text-left text-xs font-mono">
                                        <thead class="bg-slate-800/80 text-slate-300 font-bold uppercase border-b border-slate-700">
                                            <tr>
                                                <th class="py-2.5 px-3 font-sans">Mata Pelajaran</th>
                                                <th class="py-2.5 px-3">TP 1-3</th>
                                                <th class="py-2.5 px-3">Sumatif</th>
                                                <th class="py-2.5 px-3">Nilai Rapor</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-800 text-slate-200 font-medium">
                                            <tr>
                                                <td class="py-2 px-3 font-sans text-white font-bold">Matematika</td>
                                                <td class="py-2 px-3">88, 90, 85</td>
                                                <td class="py-2 px-3">92</td>
                                                <td class="py-2 px-3 text-amber-400 font-bold">89.2 (A)</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3 font-sans text-white font-bold">Fisika</td>
                                                <td class="py-2 px-3">86, 88, 89</td>
                                                <td class="py-2 px-3">90</td>
                                                <td class="py-2 px-3 text-amber-400 font-bold">88.5 (A)</td>
                                            </tr>
                                            <tr>
                                                <td class="py-2 px-3 font-sans text-white font-bold">Bahasa Inggris</td>
                                                <td class="py-2 px-3">92, 94, 90</td>
                                                <td class="py-2 px-3">95</td>
                                                <td class="py-2 px-3 text-amber-400 font-bold">92.8 (A)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex items-center justify-between text-xs text-slate-300 font-semibold pt-1">
                                    <span>Format PDF Standar Kemendikbudristek</span>
                                    <span class="text-white font-mono bg-slate-800 px-2 py-0.5 rounded">Ekspor 1-Klik</span>
                                </div>
                            </div>

                            {{-- Tab 3: Portal Ortu --}}
                            <div id="tab-ortu" class="tab-panel space-y-4">
                                <div class="bg-slate-900 p-3.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                    <div>
                                        <p class="text-[11px] font-mono font-bold uppercase tracking-wider text-emerald-400">Portal Pemantauan</p>
                                        <h3 class="text-sm font-bold text-white mt-0.5">Wali Murid: Ibu Sri Rahayu</h3>
                                        <p class="text-xs text-slate-300">Siswa Aktif: Ahmad Maulana (XI MIPA 1)</p>
                                    </div>
                                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-200 border border-slate-700">
                                        Multi-Anak Aktif
                                    </span>
                                </div>

                                <div class="space-y-2.5 text-xs">
                                    <div class="p-3 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-white">Presensi Masuk Terverifikasi</p>
                                            <p class="text-slate-300 mt-0.5 font-medium">Pukul 06:48 WIB · Gerbang Barat (Radius GPS 38m)</p>
                                        </div>
                                        <span class="font-mono font-bold text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded">Tepat Waktu</span>
                                    </div>
                                    <div class="p-3 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-white">Nilai Sumatif 2 Fisika Terbit</p>
                                            <p class="text-slate-300 mt-0.5 font-medium">Skor: 92/100 · Rata-rata kelas: 83.4</p>
                                        </div>
                                        <span class="font-mono font-bold text-amber-400 bg-amber-500/10 px-2 py-1 rounded">Baru Masuk</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 4: Audit Log --}}
                            <div id="tab-audit" class="tab-panel space-y-4">
                                <div class="bg-slate-900 p-3.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                    <div>
                                        <p class="text-[11px] font-mono font-bold uppercase tracking-wider text-rose-400">Jejak Mutasi Append-Only</p>
                                        <h3 class="text-sm font-bold text-white mt-0.5">Perekaman Audit Aktivitas</h3>
                                        <p class="text-xs text-slate-300">Setiap mutasi nilai & presensi tersimpan permanen</p>
                                    </div>
                                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                        spatie/activitylog
                                    </span>
                                </div>

                                <div class="space-y-2 font-mono text-xs">
                                    <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-200">14:02 · <span class="text-amber-400 font-bold">guru.fisika</span> ubah sumatif #1042 (82 &rarr; 92)</span>
                                        <span class="text-emerald-400 font-bold text-[11px]">Tercatat</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-200">11:15 · <span class="text-blue-400 font-bold">admin.tu</span> simpan matriks jadwal XI MIPA 1</span>
                                        <span class="text-emerald-400 font-bold text-[11px]">0 Bentrok</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-200">06:48 · <span class="text-emerald-400 font-bold">siswa.ahmad</span> scan QR geofence valid</span>
                                        <span class="text-emerald-400 font-bold text-[11px]">Valid GPS</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- SECTION 1: DUA PILAR FITUR UTAMA (SPLIT EDITORIAL SHOWCASE) --}}
<section id="fitur" class="py-24 px-4 border-b border-slate-300 bg-white">
    <div class="max-w-6xl mx-auto space-y-24">
        
        {{-- Feature 1: Presensi QR Dinamis & Geofencing --}}
        <div class="grid lg:grid-cols-12 gap-10 items-center rv">
            <div class="lg:col-span-6">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-3 py-1 rounded-md border border-amber-300">
                    FITUR 01 · PRESENSI CERDAS
                </span>
                <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-slate-950 tracking-tight leading-tight">
                    Mencegah titip absen dengan token QR dinamis dan pagar lokasi GPS.
                </h2>
                <p class="mt-4 text-slate-700 text-base leading-relaxed">
                    Sistem presensi lama berbasis tanda tangan atau foto kertas rentan dipalsukan. SIAKAD SMA menerapkan enkripsi HMAC SHA-256 pada kode QR yang berotasi otomatis setiap 30 detik. Siswa wajib berada dalam radius 100 meter dari titik koordinat sekolah saat memindai.
                </p>

                <ul class="mt-6 space-y-3 text-sm text-slate-800 font-medium">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                        <span><strong>Rotasi Token 30 Detik:</strong> Tangkapan layar (screenshot) kode QR tidak dapat dibagikan kepada teman karena kedaluwarsa seketika.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                        <span><strong>Formula Geodesik Haversine:</strong> Menghitung kelengkungan koordinat GPS siswa terhadap gerbang sekolah dengan toleransi presisi meter.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                        <span><strong>Cadangan OTP 6 Digit:</strong> Solusi saat kamera gawai bermasalah tanpa mengabaikan verifikasi radius lokasi.</span>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-6">
                <div class="rounded-2xl bg-slate-900 text-white p-6 sm:p-8 border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <span class="font-mono text-xs font-bold text-amber-400">LOGIKA PRESENSI GEOFENCE</span>
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300">Anti-Fake GPS</span>
                    </div>
                    <div class="mt-5 space-y-4 font-mono text-xs">
                        <div class="p-3 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-slate-400 block text-[11px]">Titik Pusat Sekolah</span>
                            <span class="text-white font-bold text-sm block mt-0.5">-6.914744, 107.609810 (SMA Kampus Utama)</span>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-slate-400 block text-[11px]">Hasil Komputasi Jarak Haversine</span>
                            <span class="text-emerald-400 font-bold text-sm block mt-0.5">d = 38.2m &le; 100.0m &rarr; STATUS: VALID DI AREA KAMPUS</span>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-950 border border-slate-800">
                            <span class="text-slate-400 block text-[11px]">Live Polling Guru</span>
                            <span class="text-white font-bold block mt-0.5">Daftar siswa hadir diperbarui otomatis setiap 3 detik di layar guru</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Feature 2: e-Rapor Kurikulum Merdeka --}}
        <div class="grid lg:grid-cols-12 gap-10 items-center rv">
            <div class="lg:col-span-6 order-2 lg:order-1">
                <div class="rounded-2xl bg-white p-6 sm:p-8 border-2 border-slate-300 shadow-lg text-slate-800">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <div>
                            <span class="font-mono text-xs font-bold text-slate-500 uppercase">Ekspor Barryvdh DomPDF</span>
                            <h4 class="font-bold text-base text-slate-950 mt-0.5">Hasil e-Rapor Siap Cetak (A4)</h4>
                        </div>
                        <span class="text-xs font-mono font-bold px-2.5 py-1 rounded bg-slate-900 text-white">Standar Diknas</span>
                    </div>
                    
                    <div class="mt-4 space-y-3 font-mono text-xs">
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <div class="flex justify-between text-slate-900 font-bold">
                                <span>Matematika Tingkat Lanjut</span>
                                <span class="text-amber-700">89.20 (A)</span>
                            </div>
                            <p class="font-sans text-xs text-slate-600 mt-1">Menunjukkan penguasaan sangat baik dalam analisis vektor dan kalkulus diferensial.</p>
                        </div>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <div class="flex justify-between text-slate-900 font-bold">
                                <span>Fisika Terapan</span>
                                <span class="text-amber-700">88.50 (A)</span>
                            </div>
                            <p class="font-sans text-xs text-slate-600 mt-1">Sangat terampil dalam praktikum termodinamika dan pemodelan rangkaian listrik.</p>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-bold text-slate-700">
                        <span>Tanda Tangan Digital Wali Kelas & Kepsek</span>
                        <span class="text-slate-950 font-mono">100% Bebas Kertas</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 order-1 lg:order-2">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-900 bg-slate-200 px-3 py-1 rounded-md border border-slate-300">
                    FITUR 02 · PENILAIAN KURIKULUM MERDEKA
                </span>
                <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-slate-950 tracking-tight leading-tight">
                    Komputasi otomatis nilai formatif, sumatif, dan buku rapor resmi.
                </h2>
                <p class="mt-4 text-slate-700 text-base leading-relaxed">
                    Menghilangkan waktu lembur 1-2 minggu guru di akhir semester. Pendidik cukup memasukkan nilai harian per Tujuan Pembelajaran (TP). Sistem menghitung bobot akhir secara otomatis dan menyusun kalimat deskripsi capaian sesuai format baku Kurikulum Merdeka.
                </p>

                <ul class="mt-6 space-y-3 text-sm text-slate-800 font-medium">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                        <span><strong>Pembobotan Dinamis:</strong> Penggabungan skor formatif harian, PTS, dan PAS dengan kalkulasi otomatis tanpa rumus Excel manual.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                        <span><strong>Ekspor PDF Sekali Klik:</strong> Menggunakan engine Barryvdh DomPDF dengan tata letak dokumen standar Kemendikbudristek.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-white flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✓</span>
                        <span><strong>Pencegah Tabrakan Jadwal:</strong> Guru dan ruangan kelas divalidasi sistem agar tidak ada jadwal mengajar ganda pada jam yang sama.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section>

{{-- SECTION 2: PORTAL ORANG TUA (WIDE SPOTLIGHT BANNER) --}}
<section id="orang-tua" class="py-24 px-4 bg-slate-100 border-b border-slate-300">
    <div class="max-w-6xl mx-auto">
        <div class="rounded-3xl bg-slate-950 text-white p-8 sm:p-12 border border-slate-800 shadow-xl rv">
            <div class="max-w-2xl">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/20 px-3 py-1 rounded-md border border-emerald-500/30">
                    FITUR 03 · MONITORING ORANG TUA REAL-TIME
                </span>
                <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight leading-tight">
                    Wali murid memantau presensi dan nilai anak tanpa menunggu pembagian rapor.
                </h2>
                <p class="mt-3 text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
                    Orang tua diberikan akun khusus terlindungi dengan otorisasi <code>AnakPolicy</code>. Transparansi penuh atas kehadiran anak di sekolah, rekapitulasi izin/sakit, perolehan nilai kuis/tugas, serta pengumuman resmi dari sekolah.
                </p>
            </div>

            <div class="mt-10 grid sm:grid-cols-3 gap-6 pt-8 border-t border-slate-800">
                <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                    <span class="text-amber-400 font-mono text-xs font-bold uppercase block">Kehadiran Hari Ini</span>
                    <h4 class="font-bold text-white text-base mt-1">Notifikasi Jam Presensi</h4>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">Orang tua mengetahui saat anak memindai presensi masuk di sekolah secara real-time.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                    <span class="text-emerald-400 font-mono text-xs font-bold uppercase block">Riwayat Penilaian</span>
                    <h4 class="font-bold text-white text-base mt-1">Progres Capaian Belajar</h4>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">Memeriksa nilai harian tugas dan ulangan untuk evaluasi belajar sejak dini di rumah.</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                    <span class="text-blue-400 font-mono text-xs font-bold uppercase block">Multi-Anak Switcher</span>
                    <h4 class="font-bold text-white text-base mt-1">Satu Akun Banyak Siswa</h4>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">Beralih antar-profil anak dengan satu tombol switcher tanpa perlu keluar masuk akun.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3: MATRIKS HAK AKSES PERAN --}}
<section id="peran" class="py-24 px-4 bg-white border-b border-slate-300">
    <div class="max-w-6xl mx-auto">
        <div class="max-w-xl rv">
            <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-800 bg-slate-200 px-3 py-1 rounded-md border border-slate-300">
                MATRIKS AKSES PENGGUNA
            </span>
            <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-slate-950 tracking-tight leading-tight">
                Hak akses terarah sesuai peran dan tanggung jawab.
            </h2>
            <p class="mt-3 text-slate-700 text-base leading-relaxed">
                Dilindungi oleh Spatie Laravel-Permission v8 dengan pemisahan wewenang yang tegas untuk menjamin keamanan data akademik.
            </p>
        </div>

        {{-- Comparison Matrix Table --}}
        <div class="mt-12 overflow-x-auto rounded-2xl border-2 border-slate-300 shadow-md rv">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900 text-white font-bold text-xs uppercase font-mono">
                    <tr>
                        <th class="py-3.5 px-4 w-1/3">Modul & Tanggung Jawab</th>
                        <th class="py-3.5 px-3 text-center">Admin TU</th>
                        <th class="py-3.5 px-3 text-center">Guru</th>
                        <th class="py-3.5 px-3 text-center">Siswa</th>
                        <th class="py-3.5 px-3 text-center">Orang Tua</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-slate-800">
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Kelola Data Master (Siswa, Guru, Kelas, Mapel)</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Penuh</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Penyusunan Jadwal & Deteksi Bentrok</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Penuh</td>
                        <td class="py-3 px-3 text-center text-slate-700">Lihat</td>
                        <td class="py-3 px-3 text-center text-slate-700">Lihat</td>
                        <td class="py-3 px-3 text-center text-slate-700">Lihat</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Buka Sesi Presensi QR Dinamis & Monitor Kelas</td>
                        <td class="py-3 px-3 text-center text-slate-700">Pantau</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Penuh</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Pindai Presensi QR via Kamera HP (Radius GPS)</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Penuh</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Input Nilai Formatif (TP) & Sumatif</td>
                        <td class="py-3 px-3 text-center text-slate-700">Pantau</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Penuh</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Unduh Salinan e-Rapor PDF Resmi</td>
                        <td class="py-3 px-3 text-center text-slate-700">Semua</td>
                        <td class="py-3 px-3 text-center text-slate-700">Perwalian</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Pribadi</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Data Anak</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-950">Pemeriksaan Log Audit Mutasi Data</td>
                        <td class="py-3 px-3 text-center font-bold text-emerald-700">Penuh</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                        <td class="py-3 px-3 text-center text-slate-400">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- SECTION 4: INTEGRITAS DATA & LOG AUDIT --}}
<section id="keamanan" class="py-24 px-4 bg-slate-900 text-white border-b border-slate-800">
    <div class="max-w-6xl mx-auto">
        <div class="grid lg:grid-cols-12 gap-10 items-center">
            
            <div class="lg:col-span-6 rv">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-rose-400 bg-rose-500/20 px-3 py-1 rounded-md border border-rose-500/30">
                    AKUNTABILITAS & KEPATUHAN
                </span>
                <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight leading-tight">
                    Setiap mutasi nilai dan presensi tercatat dalam log permanen.
                </h2>
                <p class="mt-4 text-slate-300 text-base leading-relaxed">
                    Mencegah manipulasi angka dan sengketa presensi. Setiap kali ada perubahan nilai siswa atau pembukaan sesi presensi, sistem mencatat akun pelaku, alamat IP, timestamp waktu, serta perbandingan nilai lama dan nilai baru secara <em>append-only</em>.
                </p>

                <div class="mt-6 space-y-3 font-medium text-sm text-slate-200">
                    <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">1</span>
                        <div>
                            <strong class="text-white">Pustaka spatie/laravel-activitylog:</strong>
                            <p class="text-xs text-slate-400 mt-0.5">Jejak mutasi data disimpan langsung ke tabel database tanpa bisa diubah ulang melalui antarmuka umum.</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">2</span>
                        <div>
                            <strong class="text-white">Otorisasi Strict AnakPolicy:</strong>
                            <p class="text-xs text-slate-400 mt-0.5">Mencegah kebocoran data nilai antarsiswa. Orang tua hanya dapat melihat data siswa yang sah tertaut di akunnya.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 rv">
                <div class="rounded-2xl bg-slate-950 p-6 border border-slate-800 font-mono text-xs shadow-2xl">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-800 text-slate-400 font-bold">
                        <span>TABEL AUDIT LOG AKTIVITAS</span>
                        <span class="text-emerald-400">Perekaman Aktif</span>
                    </div>

                    <div class="mt-4 space-y-3 text-xs">
                        <div class="p-3 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="text-slate-400">[2026-11-10 13:42:01] EVENT: UPDATE_NILAI</div>
                            <div class="text-white font-bold mt-1">User: guru.fisika@siakad.sch.id (Guru Mapel)</div>
                            <div class="text-slate-300">Target: NilaiSiswa #3409 (Ahmad Maulana)</div>
                            <div class="text-amber-400 font-bold mt-1">Perubahan: {"nilai_sumatif_2": {"old": 82, "new": 92}}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">IP: 192.168.1.45 · Sesi Terotentikasi</div>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-900 border border-slate-800">
                            <div class="text-slate-400">[2026-11-10 07:12:15] EVENT: PRESENSI_VERIFIED</div>
                            <div class="text-white font-bold mt-1">User: siswa.ahmad@siakad.sch.id (Siswa)</div>
                            <div class="text-slate-300">Metode: QR Dinamis (Token HMAC SHA-256)</div>
                            <div class="text-emerald-400 font-bold mt-1">GPS: Jarak 38.2m &le; 100m (Valid Radius Sekolah)</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- SECTION 5: SPESIFIKASI TEKNIS SISTEM --}}
<section id="spesifikasi" class="py-24 px-4 bg-white border-b border-slate-300">
    <div class="max-w-6xl mx-auto">
        <div class="max-w-xl rv">
            <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-800 bg-slate-200 px-3 py-1 rounded-md border border-slate-300">
                SPESIFIKASI ARSITEKTUR
            </span>
            <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-slate-950 tracking-tight leading-tight">
                Spesifikasi perangkat lunak sistem.
            </h2>
            <p class="mt-3 text-slate-700 text-base leading-relaxed">
                Dibangun dengan framework PHP modern berkinerja tinggi, struktur MVC bersih, dan pustaka terstandarisasi.
            </p>
        </div>

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 font-mono text-xs">
            
            <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-300 shadow-sm rv">
                <span class="text-slate-500 font-bold uppercase block text-[11px]">Backend Core</span>
                <h3 class="font-sans font-bold text-base text-slate-950 mt-1">Laravel 13 & PHP 8.3</h3>
                <p class="font-sans text-xs text-slate-700 mt-2 leading-relaxed font-medium">
                    Struktur kode rapi, type-hinting ketat, Eloquent ORM, dan migrasi skema database terversi.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-300 shadow-sm rv">
                <span class="text-slate-500 font-bold uppercase block text-[11px]">Hak Akses (RBAC)</span>
                <h3 class="font-sans font-bold text-base text-slate-950 mt-1">Spatie Permission v8.0</h3>
                <p class="font-sans text-xs text-slate-700 mt-2 leading-relaxed font-medium">
                    Dynamic Role-Based Access Control terintegrasi dengan middleware guard dan Laravel policy.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-300 shadow-sm rv">
                <span class="text-slate-500 font-bold uppercase block text-[11px]">Dokumen e-Rapor</span>
                <h3 class="font-sans font-bold text-base text-slate-950 mt-1">Barryvdh DomPDF v3.1</h3>
                <p class="font-sans text-xs text-slate-700 mt-2 leading-relaxed font-medium">
                    Mesin render cetak berkas e-Rapor A4 resmi dengan format baku Kemendikbudristek secara instan.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-300 shadow-sm rv">
                <span class="text-slate-500 font-bold uppercase block text-[11px]">Frontend & Antarmuka</span>
                <h3 class="font-sans font-bold text-base text-slate-950 mt-1">Tailwind CSS v4 & Vite</h3>
                <p class="font-sans text-xs text-slate-700 mt-2 leading-relaxed font-medium">
                    Kompilasi kilat tanpa overhead framework berat, responsif di gawai smartphone hingga desktop.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-300 shadow-sm rv">
                <span class="text-slate-500 font-bold uppercase block text-[11px]">Validasi Lokasi</span>
                <h3 class="font-sans font-bold text-base text-slate-950 mt-1">Formula Haversine GPS</h3>
                <p class="font-sans text-xs text-slate-700 mt-2 leading-relaxed font-medium">
                    Perhitungan jarak geodesik kelengkungan bumi untuk validasi presensi siswa dalam radius 100m.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border-2 border-slate-300 shadow-sm rv">
                <span class="text-slate-500 font-bold uppercase block text-[11px]">Database Support</span>
                <h3 class="font-sans font-bold text-base text-slate-950 mt-1">SQLite & MySQL</h3>
                <p class="font-sans text-xs text-slate-700 mt-2 leading-relaxed font-medium">
                    Mendukung SQLite untuk pengujian lokal cepat dan MySQL/MariaDB untuk server produksi kampus.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- CALL TO ACTION & AKUN DEMO --}}
<section class="py-20 px-4 bg-[#F8F9FA]">
    <div class="max-w-6xl mx-auto rounded-3xl bg-slate-950 text-white p-8 sm:p-14 border border-slate-800 shadow-2xl rv">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="max-w-xl">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-amber-400 bg-amber-400/20 px-3 py-1 rounded-md border border-amber-400/30">
                    PORTAL MASUK SIAKAD
                </span>
                <h2 class="mt-4 font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight leading-tight">
                    Masuk ke portal akademik sekolah.
                </h2>
                <p class="mt-3 text-slate-300 text-sm sm:text-base leading-relaxed">
                    Satu gerbang masuk terpadu untuk seluruh sivitas sekolah. Sistem otomatis mengarahkan ke dashboard yang sesuai dengan peran Anda.
                </p>

                <div class="mt-6 p-4 rounded-xl bg-slate-900 border border-slate-800 font-mono text-xs">
                    <p class="text-amber-400 font-bold uppercase text-[11px] mb-2">Akun Uji Coba (Demo Kredensial):</p>
                    <div class="grid sm:grid-cols-2 gap-2 text-slate-200">
                        <div>Admin: <strong class="text-white">admin@siakad.sch.id</strong></div>
                        <div>Guru: <strong class="text-white">guru@siakad.sch.id</strong></div>
                        <div>Siswa: <strong class="text-white">siswa@siakad.sch.id</strong></div>
                        <div>Orang Tua: <strong class="text-white">ortu@siakad.sch.id</strong></div>
                    </div>
                    <p class="text-slate-400 text-[11px] mt-2">Password default untuk seluruh akun: <strong class="text-white">password</strong></p>
                </div>
            </div>

            <div class="shrink-0">
                @auth
                    <a href="{{ $dashboardRoute }}" class="inline-flex items-center gap-3 rounded-full bg-amber-400 px-8 py-4 text-sm font-bold text-slate-950 shadow-xl hover:bg-amber-300 transition-all duration-300 active:scale-[0.98]">
                        <span>Buka Dashboard Aktif</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-3 rounded-full bg-amber-400 px-8 py-4 text-sm font-bold text-slate-950 shadow-xl hover:bg-amber-300 transition-all duration-300 active:scale-[0.98]">
                        <span>Masuk ke Portal SIAKAD</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
</main>

{{-- SYSTEM FOOTER --}}
<footer class="bg-white border-t border-slate-300 text-slate-700">
    <div class="max-w-6xl mx-auto px-6 py-14 grid md:grid-cols-4 gap-10">
        <div class="md:col-span-1">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-slate-950 flex items-center justify-center text-amber-400 font-bold">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-5.25 6.557c0 1.564.914 2.872 2.25 3.483"/>
                    </svg>
                </span>
                <p class="font-display font-black text-slate-950 tracking-tight text-base">SIAKAD<span class="text-amber-600">SMA</span></p>
            </div>
            <p class="mt-4 text-xs text-slate-700 leading-relaxed font-medium">
                Sistem Informasi Akademik Sekolah Menengah Atas v2.0. Berbasis Laravel 13, Tailwind CSS v4, dan Spatie Dynamic RBAC.
            </p>
            <div class="mt-4 inline-flex items-center gap-1.5 text-slate-900 font-mono text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Layanan Normal</span>
            </div>
        </div>

        <div>
            <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-950 mb-4">Modul Fungsional</h4>
            <ul class="space-y-2.5 text-xs text-slate-700 font-medium">
                <li><a class="hover:text-slate-950 transition-colors" href="#fitur">Presensi QR & Geofencing GPS</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="#fitur">e-Rapor Kurikulum Merdeka</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="#fitur">Pencegah Bentrok Jadwal</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="#orang-tua">Portal Wali Murid</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-950 mb-4">Akses Masuk</h4>
            <ul class="space-y-2.5 text-xs text-slate-700 font-medium">
                <li><a class="hover:text-slate-950 transition-colors" href="{{ route('login') }}">Masuk Administrator TU</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="{{ route('login') }}">Masuk Dewan Guru</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="{{ route('login') }}">Masuk Peserta Didik</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="{{ route('login') }}">Masuk Orang Tua / Wali</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-950 mb-4">Integritas Data</h4>
            <ul class="space-y-2.5 text-xs text-slate-700 font-medium">
                <li><a class="hover:text-slate-950 transition-colors" href="#keamanan">Log Audit Append-Only</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="#peran">Dynamic RBAC Spatie</a></li>
                <li><a class="hover:text-slate-950 transition-colors" href="#spesifikasi">Formula Geodesik Haversine</a></li>
                @auth
                <li class="pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-rose-600 font-bold hover:text-rose-800 transition-colors">Keluar dari Sesi</button>
                    </form>
                </li>
                @endauth
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-300 py-6 px-6">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600 font-mono font-semibold">
            <p>© {{ date('Y') }} SIAKAD SMA. Seluruh hak cipta dilindungi undang-undang.</p>
            <p>Evolusi Tata Kelola Digital Pendidikan Menengah.</p>
        </div>
    </div>
</footer>

{{-- INTERACTIVE JAVASCRIPT --}}
<script>
(function () {
    // 1. Mobile Menu Toggle
    var burger = document.getElementById('burger');
    var mnav = document.getElementById('mnav');
    var open = false;

    if (burger && mnav) {
        function setMenu(v) {
            open = v;
            document.body.classList.toggle('overflow-hidden', v);
            burger.setAttribute('aria-expanded', String(v));
            mnav.classList.toggle('opacity-0', !v);
            mnav.classList.toggle('pointer-events-none', !v);
        }
        burger.addEventListener('click', function () { setMenu(!open); });
        mnav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () { setMenu(false); });
        });
    }

    // 2. Interactive Product Preview Tab Switcher
    var tabs = document.querySelectorAll('.hero-tab');
    var panels = document.querySelectorAll('.tab-panel');

    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-tab');

            // Reset tab button states
            tabs.forEach(function (t) {
                t.classList.remove('bg-white', 'text-slate-950', 'shadow-sm');
                t.classList.add('text-slate-300');
            });
            btn.classList.add('bg-white', 'text-slate-950', 'shadow-sm');
            btn.classList.remove('text-slate-300');

            // Toggle active panel
            panels.forEach(function (p) {
                if (p.id === targetId) {
                    p.classList.add('active');
                } else {
                    p.classList.remove('active');
                }
            });
        });
    });

    // 3. Scroll Reveal via Intersection Observer (Clean, zero blur)
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                e.target.classList.add('on');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.05 });

    document.querySelectorAll('.rv').forEach(function (el) {
        io.observe(el);
    });
})();
</script>
</body>
</html>
