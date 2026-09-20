<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD SMA') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description', 'Sistem Informasi Akademik SMA')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F4F5F7] text-slate-600 font-sans antialiased">

    {{-- Sidebar Overlay (Mobile) --}}
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    {{-- ===================== SIDEBAR ISLAND ===================== --}}
    <aside id="sidebar" class="sidebar">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 pt-6 pb-5">
            <span class="block rounded-full bg-white/10 p-1 ring-1 ring-white/15">
                <span class="w-9 h-9 rounded-full bg-gold-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </span>
            </span>
            <div>
                <p class="text-white font-bold text-sm leading-tight font-display tracking-tight">SIAKAD SMA</p>
                <p class="text-slate-400 text-[11px]">{{ config('app.name') }}</p>
            </div>
        </div>

        {{-- User Info --}}
        <div class="mx-4 mb-2 px-3 py-3 rounded-2xl bg-white/10 ring-1 ring-white/15">
            <div class="flex items-center gap-3">
                @if(auth()->user()->getFotoUrl())
                    <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                @else
                    <div class="avatar-placeholder w-9 h-9 text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-2 space-y-0.5 overflow-y-auto">
            @yield('sidebar-nav')
        </nav>

        {{-- Footer --}}
        <div class="px-4 py-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="group w-full inline-flex items-center justify-between gap-3 rounded-full bg-white/10 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-slate-200 ring-1 ring-white/15 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-white/15 active:scale-[0.98]">
                    Keluar
                    <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </span>
                </button>
            </form>
            <p class="text-slate-500 text-[11px] mt-3 text-center">SIAKAD SMA - v1.0</p>
        </div>
    </aside>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="flex items-center gap-3">
                {{-- Mobile menu button --}}
                <button id="sidebar-toggle" class="lg:hidden w-10 h-10 rounded-full hover:bg-slate-100 text-slate-500 flex items-center justify-center transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="hidden md:flex items-center gap-2 text-sm">
                    <span class="text-slate-400">@yield('breadcrumb-parent', 'Beranda')</span>
                    @hasSection('breadcrumb-current')
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-navy-900 font-semibold">@yield('breadcrumb-current')</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Date --}}
                <span class="hidden md:block text-xs text-slate-500 font-medium">
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </span>

                {{-- Profile Dropdown --}}
                <div class="relative inline-block text-left" id="profile-dropdown-container">
                    <button type="button" id="profile-dropdown-btn" class="flex items-center gap-2 pl-1.5 pr-3 py-1.5 rounded-full hover:bg-slate-100 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] focus:outline-none cursor-pointer ring-1 ring-transparent hover:ring-black/5">
                        @if(auth()->user()->getFotoUrl())
                            <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-8 h-8 rounded-full object-cover flex-shrink-0 select-none">
                        @else
                            <div class="avatar-placeholder w-8 h-8 text-xs select-none">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="hidden md:block text-sm font-semibold text-navy-900 select-none">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" id="profile-dropdown-arrow" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="profile-menu" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white ring-1 ring-black/5 shadow-[0_24px_60px_-24px_rgba(15,37,87,0.35)] py-1.5 z-50 origin-top-right transition-all transform opacity-0 scale-95 duration-150">
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-[10px] text-slate-500 uppercase font-semibold tracking-[0.14em]">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-navy-900 truncate mt-0.5">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-navy-800 text-gold-200 capitalize">
                                {{ auth()->user()->role }}
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>

                        <hr class="border-slate-100 my-1">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2.5 w-full text-left px-4 py-2 text-sm text-rose-700 hover:bg-rose-50 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] font-semibold cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="px-4 sm:px-6 lg:px-10 py-6 md:py-8 max-w-[1200px] mx-auto w-full">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="flash-success animate-fade-in-up !rounded-2xl" data-flash>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error') || $errors->has('error'))
            <div class="flash-error animate-fade-in-up !rounded-2xl" data-flash>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') ?? $errors->first('error') }}
            </div>
            @endif

            @if(session('info'))
            <div class="flash-info animate-fade-in-up !rounded-2xl" data-flash>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('info') }}
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="text-center py-5 text-xs text-slate-500">
            &copy; {{ date('Y') }} SIAKAD SMA - Sistem Informasi Akademik
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
