<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD SMA') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description', 'Sistem Informasi Akademik SMA')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased min-h-screen flex flex-col">

    {{-- Sidebar Overlay (Mobile) --}}
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    {{-- ===================== INSTITUTIONAL SIDEBAR ===================== --}}
    <aside id="sidebar" class="sidebar">
        {{-- School Branding Header --}}
        <div class="sidebar-brand">
            <span class="w-9 h-9 rounded-lg bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-white font-bold text-sm tracking-tight font-display leading-none">SIAKAD SMA</p>
                <p class="text-slate-400 text-[11px] truncate mt-1">Portal Akademik Terpadu</p>
            </div>
        </div>

        {{-- Active User Snapshot --}}
        <div class="mx-3 mt-3 mb-1 px-3 py-2.5 rounded-lg bg-white/5 border border-white/8">
            <div class="flex items-center gap-2.5">
                @if(auth()->user()->getFotoUrl())
                    <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-white/10">
                @else
                    <div class="avatar-placeholder w-8 h-8 text-xs shrink-0 ring-1 ring-white/10">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="text-white text-xs font-semibold truncate leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-blue-300 text-[10.5px] font-medium capitalize mt-0.5">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="sidebar-nav">
            @yield('sidebar-nav')
        </nav>

        {{-- Sidebar Footer & Logout --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/8 transition-colors duration-150">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        Keluar
                    </span>
                    <span class="text-[10px] text-slate-500 font-mono">ESC</span>
                </button>
            </form>
            <p class="text-slate-500 text-[10px] mt-2.5 text-center">SIAKAD SMA · Versi 2.0</p>
        </div>
    </aside>

    {{-- ===================== MAIN CONTENT WRAPPER ===================== --}}
    <div class="main-content">

        {{-- Anchored Topbar --}}
        <header class="topbar">
            <div class="flex items-center gap-3">
                {{-- Mobile menu button --}}
                <button id="sidebar-toggle" class="lg:hidden w-9 h-9 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center justify-center transition-colors" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                {{-- Breadcrumb Hierarchy --}}
                <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
                    <span class="text-slate-500 font-medium">@yield('breadcrumb-parent', 'Beranda')</span>
                    @hasSection('breadcrumb-current')
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                    <span class="text-slate-900 font-semibold">@yield('breadcrumb-current')</span>
                    @endif
                </nav>
            </div>

            <div class="flex items-center gap-3">
                {{-- Date indicator --}}
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50/70 text-xs text-slate-600 font-medium">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>

                {{-- Notification Bell Dropdown (F6) --}}
                <div class="relative inline-block text-left" x-data="{
                    open: false,
                    unreadCount: {{ auth()->check() ? auth()->user()->unreadNotifications()->count() : 0 }},
                    notifications: [],
                    async fetchNotifications() {
                        try {
                            const res = await fetch('{{ route('notifications.unread-count') }}');
                            const data = await res.json();
                            this.unreadCount = data.unread_count;
                            this.notifications = data.notifications;
                        } catch (e) {
                            console.error(e);
                        }
                    }
                }" x-init="fetchNotifications()">
                    <button type="button" @click="open = !open; if(open) fetchNotifications();"
                        class="relative w-9 h-9 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                        aria-label="Lihat Notifikasi">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-white"
                                x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </template>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open" @click.outside="open = false" x-cloak
                        class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white border border-slate-200/80 shadow-xl py-2 z-50 origin-top-right transition-all transform"
                        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                        
                        <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold font-display text-navy-900">Pemberitahuan</span>
                                <template x-if="unreadCount > 0">
                                    <span class="text-[10px] font-bold bg-rose-50 text-rose-600 px-2 py-0.5 rounded-full border border-rose-200" x-text="unreadCount + ' Baru'"></span>
                                </template>
                            </div>
                            <a href="{{ route('notifications.settings') }}" class="text-[11px] font-medium text-slate-500 hover:text-navy-900 transition-colors">
                                Pengaturan
                            </a>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            <template x-for="item in notifications" :key="item.id">
                                <a :href="item.url" class="p-3.5 flex items-start gap-3 hover:bg-slate-50/80 transition-colors block">
                                    <span class="w-2 h-2 rounded-full mt-1.5 shrink-0" :class="item.read ? 'bg-transparent' : 'bg-blue-600'"></span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-navy-900 leading-snug line-clamp-1" x-text="item.title"></p>
                                        <p class="text-[11px] text-slate-600 line-clamp-2 mt-0.5" x-text="item.message"></p>
                                        <p class="text-[10px] text-slate-400 mt-1" x-text="item.time_ago"></p>
                                    </div>
                                </a>
                            </template>
                            <template x-if="notifications.length === 0">
                                <div class="py-8 text-center text-xs text-slate-400">
                                    Tidak ada notifikasi baru
                                </div>
                            </template>
                        </div>

                        <div class="px-3 py-2 border-t border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-b-xl">
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[11px] font-semibold text-slate-600 hover:text-navy-900 cursor-pointer">
                                    Tandai Semua Dibaca
                                </button>
                            </form>
                            <a href="{{ route('notifications.index') }}" class="text-[11px] font-bold text-navy-800 hover:text-navy-900">
                                Lihat Semua &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User Profile Dropdown --}}
                <div class="relative inline-block text-left" id="profile-dropdown-container">
                    <button type="button" id="profile-dropdown-btn" class="flex items-center gap-2 pl-1.5 pr-2.5 py-1 rounded-lg border border-transparent hover:border-slate-200 hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer">
                        @if(auth()->user()->getFotoUrl())
                            <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-7 h-7 rounded-full object-cover shrink-0 select-none ring-1 ring-slate-200">
                        @else
                            <div class="avatar-placeholder w-7 h-7 text-[11px] select-none">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="hidden md:block text-xs font-semibold text-slate-800 select-none max-w-[130px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-150" id="profile-dropdown-arrow" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div id="profile-menu" class="hidden absolute right-0 mt-1.5 w-56 rounded-xl bg-white border border-slate-200 shadow-lg py-1 z-50 origin-top-right transition-all transform opacity-0 scale-95 duration-150">
                        <div class="px-3.5 py-2.5 border-b border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-semibold tracking-wider">Pengguna Aktif</p>
                            <p class="text-xs font-semibold text-slate-900 truncate mt-0.5">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 capitalize">
                                {{ auth()->user()->role }}
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            Pengaturan Profil
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2.5 w-full text-left px-3.5 py-2 text-xs text-rose-600 hover:bg-rose-50 transition-colors font-medium cursor-pointer">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                </svg>
                                Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content Container --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 max-w-7xl w-full mx-auto">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="flash-success animate-fade-in-up" data-flash>
                <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error') || $errors->has('error'))
            <div class="flash-error animate-fade-in-up" data-flash>
                <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <span>{{ session('error') ?? $errors->first('error') }}</span>
            </div>
            @endif

            @if(session('info'))
            <div class="flash-info animate-fade-in-up" data-flash>
                <svg class="w-4 h-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-200/80 bg-white/50 text-center py-4 text-xs text-slate-500">
            &copy; {{ date('Y') }} SIAKAD SMA · Sistem Informasi Akademik Sekolah Menengah Atas
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
