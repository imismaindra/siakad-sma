<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD SMA') — {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description', 'Sistem Informasi Akademik SMA')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">

    {{-- Sidebar Overlay (Mobile) --}}
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="sidebar" class="sidebar">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
            <div class="w-10 h-10 rounded-xl bg-gradient-gold flex items-center justify-center shadow-lg flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-tight font-display">SIAKAD SMA</p>
                <p class="text-white/50 text-xs">{{ config('app.name') }}</p>
            </div>
        </div>

        {{-- User Info --}}
        <div class="mx-4 my-3 px-3 py-3 rounded-xl bg-white/8">
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
                    <p class="text-white/50 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-2 space-y-0.5">
            @yield('sidebar-nav')
        </nav>

        {{-- Footer --}}
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left hover:text-red-400">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
            <p class="text-white/20 text-xs mt-3 text-center">v1.0 — SIAKAD SMA</p>
        </div>
    </aside>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="flex items-center gap-4">
                {{-- Mobile menu button --}}
                <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-500" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="hidden md:flex items-center gap-2 text-sm">
                    <span class="text-gray-400">@yield('breadcrumb-parent', 'Beranda')</span>
                    @hasSection('breadcrumb-current')
                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-700 font-medium">@yield('breadcrumb-current')</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Date --}}
                <span class="hidden md:block text-xs text-gray-400 font-medium">
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </span>

                {{-- Profile Dropdown --}}
                <div class="relative inline-block text-left" id="profile-dropdown-container">
                    <button type="button" id="profile-dropdown-btn" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer">
                        @if(auth()->user()->getFotoUrl())
                            <img src="{{ auth()->user()->getFotoUrl() }}" alt="Foto Profile" class="w-8 h-8 rounded-full object-cover flex-shrink-0 select-none">
                        @else
                            <div class="avatar-placeholder w-8 h-8 text-xs select-none">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="hidden md:block text-sm font-medium text-gray-700 select-none">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="profile-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="profile-menu" class="hidden absolute right-0 mt-2 w-56 rounded-xl bg-white border border-gray-100 shadow-xl py-1.5 z-50 origin-top-right transition-all transform opacity-0 scale-95 duration-150">
                        <div class="px-4 py-2.5 border-b border-gray-50">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-gray-800 truncate mt-0.5">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-blue-50 text-blue-700 capitalize">
                                {{ auth()->user()->role }}
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-450" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>

                        <hr class="border-gray-100 my-1">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2.5 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors font-medium cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-6 md:p-8">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="flash-success animate-fade-in-up" data-flash>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error') || $errors->has('error'))
            <div class="flash-error animate-fade-in-up" data-flash>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') ?? $errors->first('error') }}
            </div>
            @endif

            @if(session('info'))
            <div class="flash-info animate-fade-in-up" data-flash>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('info') }}
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="text-center py-4 text-xs text-gray-400 border-t border-gray-100">
            &copy; {{ date('Y') }} SIAKAD SMA — Sistem Informasi Akademik Sekolah Menengah Atas
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
