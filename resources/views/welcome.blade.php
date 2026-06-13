<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di SIAKAD SMA Nusantara</title>
    <meta name="description" content="Portal Sistem Informasi Akademik SMA Nusantara. Akses nilai, absensi, jadwal, dan informasi akademik secara digital.">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white font-sans antialiased overflow-x-hidden">

    {{-- Hero Background Blobs --}}
    <div class="hero-blob bg-blue-600/20 w-[500px] h-[500px] top-[-100px] right-[-100px]"></div>
    <div class="hero-blob bg-amber-500/10 w-[600px] h-[600px] bottom-[-200px] left-[-200px]"></div>

    {{-- Floating Navigation --}}
    <header class="nav-floating">
        <a href="/" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-gradient-gold flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <span class="font-bold font-display text-sm tracking-wide text-white">SMA Nusantara</span>
        </a>
        <nav class="hidden md:flex items-center gap-8">
            <a href="#tentang" class="text-xs font-semibold uppercase tracking-wider text-slate-300 hover:text-white transition-colors">Tentang</a>
            <a href="#fitur" class="text-xs font-semibold uppercase tracking-wider text-slate-300 hover:text-white transition-colors">Fitur</a>
            <a href="#statistik" class="text-xs font-semibold uppercase tracking-wider text-slate-300 hover:text-white transition-colors">Statistik</a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn-gold btn-sm">Dashboard Admin</a>
                @elseif(auth()->user()->role === 'guru')
                    <a href="{{ route('guru.dashboard') }}" class="btn-gold btn-sm">Dashboard Guru</a>
                @else
                    <a href="{{ route('siswa.dashboard') }}" class="btn-gold btn-sm">Dashboard Siswa</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-slate-300 hover:text-white border border-slate-800 hover:bg-slate-900 rounded-lg px-3 py-2 transition-colors cursor-pointer">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-gold btn-sm">Login Akademik</a>
            @endauth
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="hero-section px-6 md:px-12 max-w-7xl mx-auto z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center w-full pt-20">
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left animate-fade-in-up">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-950/60 border border-blue-800 text-xs font-semibold text-blue-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    Portal Akademik Siakad SMA v1.0
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold font-display leading-tight">
                    Membentuk Generasi <br>
                    <span class="text-gradient-gold">Cerdas &amp; Berkarakter</span>
                </h1>
                <p class="text-slate-400 text-base md:text-lg max-w-xl mx-auto lg:mx-0">
                    Selamat datang di Sistem Informasi Akademik SMA Nusantara. Portal digital modern untuk memfasilitasi integrasi data pembelajaran, absensi, jadwal, dan nilai siswa secara real-time.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @auth
                        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn-gold px-8 py-3 text-sm">
                            Masuk ke Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-gold px-8 py-3 text-sm">
                            Mulai Login
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </a>
                        <a href="#fitur" class="btn-secondary px-8 py-3 text-sm bg-slate-900 border-slate-800 text-slate-300 hover:bg-slate-800 hover:text-white">
                            Pelajari Selengkapnya
                        </a>
                    @endauth
                </div>
            </div>
            
            <div class="lg:col-span-5 flex justify-center relative animate-fade-in delay-200">
                {{-- Decorative element --}}
                <div class="absolute w-72 h-72 rounded-full bg-gradient-gold opacity-10 blur-3xl animate-pulse"></div>
                
                {{-- Premium Mockup Card --}}
                <div class="glass p-6 rounded-2xl w-full max-w-sm border-white/10 shadow-2xl relative animate-float">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        </div>
                        <span class="text-[10px] text-white/40 uppercase tracking-widest font-mono">SIAKAD PREVIEW</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                            <p class="text-xs text-white/50">Status Kehadiran</p>
                            <p class="text-lg font-bold text-gradient-gold">98.4% Kehadiran</p>
                        </div>
                        
                        <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                            <p class="text-xs text-white/50">Rata-rata Nilai Rapor</p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-lg font-bold text-white">88.5</span>
                                <span class="badge badge-success btn-sm bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Predikat A</span>
                            </div>
                        </div>

                        <div class="p-3 bg-white/5 rounded-xl border border-white/5 space-y-2">
                            <p class="text-xs text-white/50">Jadwal Hari Ini</p>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-white/80">Matematika Wajib</span>
                                <span class="text-amber-400 font-semibold">07:30 - 09:00</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-white/80">Fisika</span>
                                <span class="text-amber-400 font-semibold">09:15 - 10:45</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Fitur Section --}}
    <section id="fitur" class="py-24 px-6 bg-slate-900/50 relative border-t border-slate-900">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <h2 class="text-xs font-bold uppercase tracking-widest text-amber-500">Unggul &amp; Terintegrasi</h2>
                <p class="text-3xl md:text-4xl font-extrabold font-display">Layanan Akademik Digital Terbaik</p>
                <p class="text-slate-400 text-sm">Menyediakan berbagai modul akademis terpadu yang memfasilitasi proses pembelajaran sekolah untuk semua entitas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pt-6">
                {{-- Fitur 1 --}}
                <div class="feature-card space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-400 border border-blue-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-white">Portal Kelas &amp; Jurusan</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Penyusunan rombongan belajar terkelola yang mendistribusikan siswa ke setiap jenjang kelas secara otomatis dan rapi.</p>
                </div>

                {{-- Fitur 2 --}}
                <div class="feature-card space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 border border-amber-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-white">Kehadiran &amp; Absensi</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Pencatatan data absensi harian yang efisien oleh guru mata pelajaran dengan laporan rekapitulasi persentase otomatis.</p>
                </div>

                {{-- Fitur 3 --}}
                <div class="feature-card space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-white">Input &amp; Bobot Nilai</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Pengisian nilai ujian harian, UTS, dan UAS dengan pembobotan nilai akhir yang disesuaikan kebijakan kurikulum sekolah.</p>
                </div>

                {{-- Fitur 4 --}}
                <div class="feature-card space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 border border-purple-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-white">Penjadwalan Otomatis</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Tata kelola jadwal pelajaran guru dan siswa tanpa terjadi tabrakan jam mengajar antar ruang kelas.</p>
                </div>

                {{-- Fitur 5 --}}
                <div class="feature-card space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center text-pink-400 border border-pink-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-white">E-Rapor Digital PDF</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Ekspor dokumen rapor siswa dan kelengkapan nilai per kelas secara instan ke dalam format cetak PDF berstandar resmi.</p>
                </div>

                {{-- Fitur 6 --}}
                <div class="feature-card space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold font-display text-white">Multi-Role Security</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Sistem otentikasi ketat dengan hak akses yang terpisah jelas antara Admin, Guru, dan Siswa sesuai wewenang.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistik Section --}}
    <section id="statistik" class="py-24 px-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="space-y-2 p-6 glass rounded-2xl border-white/5 bg-slate-900/40">
                <p class="stats-number" data-target="1200">1.200+</p>
                <p class="text-slate-400 text-xs uppercase font-bold tracking-wider">Siswa Aktif</p>
            </div>
            <div class="space-y-2 p-6 glass rounded-2xl border-white/5 bg-slate-900/40">
                <p class="stats-number" data-target="85">85+</p>
                <p class="text-slate-400 text-xs uppercase font-bold tracking-wider">Guru &amp; Staf Pengajar</p>
            </div>
            <div class="space-y-2 p-6 glass rounded-2xl border-white/5 bg-slate-900/40">
                <p class="stats-number" data-target="36">36</p>
                <p class="text-slate-400 text-xs uppercase font-bold tracking-wider">Rombongan Belajar</p>
            </div>
            <div class="space-y-2 p-6 glass rounded-2xl border-white/5 bg-slate-900/40">
                <p class="stats-number" data-target="100">100%</p>
                <p class="text-slate-400 text-xs uppercase font-bold tracking-wider">Akreditasi A</p>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 px-6 max-w-5xl mx-auto text-center relative z-10">
        <div class="p-12 glass rounded-3xl border-white/10 bg-gradient-to-br from-blue-950/40 to-slate-950/80 space-y-6">
            <h2 class="text-3xl md:text-5xl font-extrabold font-display text-white">
                Siap Mengoptimalkan <br> Akademik Anda?
            </h2>
            <p class="text-slate-400 text-sm max-w-xl mx-auto">
                Masuk ke akun Anda sekarang untuk melihat jadwal pelajaran terbaru, menginput kehadiran, mengevaluasi capaian nilai rapor siswa, atau mengelola sistem master.
            </p>
            <div class="pt-4">
                <a href="{{ route('login') }}" class="btn-gold px-10 py-4 text-base font-semibold">
                    Masuk ke Portal Akademik
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-900 py-12 px-6 bg-slate-950 text-slate-500 text-center text-xs">
        <div class="max-w-7xl mx-auto space-y-4">
            <p class="font-bold text-white text-sm font-display">SMA Nusantara</p>
            <p class="max-w-md mx-auto text-slate-400">Jl. Pendidikan No. 45, Kota Utama, Indonesia. Telp: (021) 555-0199 | Email: info@smanusantara.sch.id</p>
            <p class="pt-6 border-t border-slate-900/50">&copy; {{ date('Y') }} SMA Nusantara. All rights reserved. Powered by SIAKAD SMA.</p>
        </div>
    </footer>

</body>
</html>
