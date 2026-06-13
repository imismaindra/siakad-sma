<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akademik — SIAKAD SMA</title>
    <meta name="description" content="Masuk ke Sistem Informasi Akademik SMA Nusantara">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-app min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    {{-- Background Blobs --}}
    <div class="absolute w-[400px] h-[400px] rounded-full bg-blue-500/10 blur-3xl top-[-100px] left-[-100px] pointer-events-none"></div>
    <div class="absolute w-[500px] h-[500px] rounded-full bg-amber-500/5 blur-3xl bottom-[-200px] right-[-100px] pointer-events-none"></div>

    <div class="w-full max-w-md z-10 space-y-6 animate-fade-in-up">
        
        {{-- Back to Home --}}
        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-navy-500 transition-colors font-semibold uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-navy px-8 py-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
                <div class="mx-auto w-12 h-12 rounded-xl bg-gradient-gold flex items-center justify-center shadow-lg mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold font-display text-white">SIAKAD SMA</h2>
                <p class="text-white/60 text-xs mt-1">Sistem Informasi Akademik SMA Nusantara</p>
            </div>

            {{-- Form Body --}}
            <div class="p-8">
                
                {{-- Flash Message handled inside layouts but since login isn't in layouts we place it manually --}}
                @if(session('success'))
                    <div class="flash-success mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error') || $errors->has('error'))
                    <div class="flash-error mb-6">
                        {{ session('error') ?? $errors->first('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="form-label">Alamat Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="form-input pl-10 @error('email') error @enderror" placeholder="nama@siakad.sch.id">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="form-input pl-10" placeholder="••••••••">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center text-xs text-slate-500 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-navy-500 focus:ring-navy-500 mr-2">
                            Ingat Saya
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary w-full justify-center py-3 text-sm">
                            Masuk
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Demo Credentials Helper --}}
        <div class="p-4 rounded-xl bg-white/50 backdrop-blur-sm border border-slate-200/50 shadow-sm text-xs space-y-2 text-slate-600">
            <p class="font-bold text-slate-800">Akun Uji Coba (Demo):</p>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <span class="font-semibold block text-slate-700">1. Admin</span>
                    admin@siakad.sch.id
                </div>
                <div>
                    <span class="font-semibold block text-slate-700">2. Guru</span>
                    budi@siakad.sch.id
                </div>
                <div>
                    <span class="font-semibold block text-slate-700">3. Siswa</span>
                    andi@siakad.sch.id
                </div>
            </div>
            <p class="text-slate-400 mt-1 italic text-[10px]">Password semua akun: <span class="font-mono font-bold text-slate-600">password</span></p>
        </div>
        
    </div>

</body>
</html>
