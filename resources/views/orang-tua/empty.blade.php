@extends('layouts.orang-tua')

@section('title', 'Portal Orang Tua')
@section('breadcrumb-parent', 'Utama')
@section('breadcrumb-current', 'Dashboard')

@section('orang-tua-content')
<div class="max-w-2xl mx-auto py-12 px-4 text-center animate-fade-in-up">
    <div class="card p-10 border border-slate-200/80 shadow-md rounded-3xl bg-white">
        <div class="w-20 h-20 rounded-full bg-amber-50 text-amber-500 mx-auto flex items-center justify-center mb-6 ring-8 ring-amber-500/10">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold font-display text-slate-800 mb-3">Data Siswa Belum Ditautkan</h1>
        <p class="text-slate-600 text-sm leading-relaxed mb-6">
            {{ $message ?? 'Akun Anda belum memiliki data anak/siswa yang terhubung ke sistem SIAKAD SMA. Harap menghubungi Bagian Tata Usaha (TU) sekolah untuk memverifikasi dan menghubungkan data putra/putri Anda.' }}
        </p>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/60 text-left text-xs text-slate-500 space-y-2 mb-6">
            <div class="flex justify-between">
                <span>Nama Akun:</span>
                <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span>
            </div>
            <div class="flex justify-between">
                <span>Email Terdaftar:</span>
                <span class="font-semibold text-slate-700 font-mono">{{ Auth::user()->email }}</span>
            </div>
            <div class="flex justify-between">
                <span>Role Pengguna:</span>
                <span class="badge badge-gold uppercase text-[10px] font-bold">Orang Tua / Wali</span>
            </div>
        </div>

        <div class="flex justify-center gap-3">
            <a href="mailto:tatausaha@siakad.sch.id" class="btn btn-secondary text-xs">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Hubungi Tata Usaha
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary text-xs">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
