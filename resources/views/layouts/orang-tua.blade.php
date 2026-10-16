{{-- Orang Tua Layout --}}
@extends('layouts.app')

@section('sidebar-nav')
    <p class="sidebar-section-label">Utama</p>
    <a href="{{ route('orang-tua.dashboard') }}" class="sidebar-link {{ request()->routeIs('orang-tua.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
        Dashboard
    </a>

    <p class="sidebar-section-label">Monitoring Anak</p>
    <a href="{{ route('orang-tua.jadwal') }}" class="sidebar-link {{ request()->routeIs('orang-tua.jadwal*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Jadwal Pelajaran
    </a>
    <a href="{{ route('orang-tua.absensi') }}" class="sidebar-link {{ request()->routeIs('orang-tua.absensi*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Presensi & Kehadiran
    </a>
    <a href="{{ route('orang-tua.nilai') }}" class="sidebar-link {{ request()->routeIs('orang-tua.nilai*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
        Nilai & e-Rapor
    </a>

    <p class="sidebar-section-label">Informasi Sekolah</p>
    <a href="{{ route('orang-tua.pengumuman') }}" class="sidebar-link {{ request()->routeIs('orang-tua.pengumuman*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        Pengumuman
    </a>

    <p class="sidebar-section-label">Pengaturan</p>
    <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Profil Saya
    </a>
@endsection

@section('content')
    @yield('orang-tua-content')
@endsection
