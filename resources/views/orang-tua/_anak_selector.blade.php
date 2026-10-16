{{-- Reusable Anak Selector & Status Banner (F4-02 & F4-04) --}}
@if(isset($siswas) && $siswas->isNotEmpty())
    <div class="card p-5 border border-slate-200/80 bg-gradient-to-r from-slate-50 via-white to-sky-50/40 shadow-sm rounded-2xl mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Info Anak Aktif --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-navy-700 to-sky-500 text-white font-display font-bold text-xl flex items-center justify-center shadow-md shadow-sky-500/20 shrink-0">
                    {{ strtoupper(substr($activeSiswa->nama_lengkap ?? 'A', 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg font-bold font-display text-slate-800">{{ $activeSiswa->nama_lengkap }}</h2>
                        @php
                            $hubungan = $activeSiswa->pivot->hubungan ?? ($activeSiswa->orangTuas->firstWhere('id', Auth::user()->orangTua?->id)?->pivot?->hubungan ?? 'wali');
                            $hubunganLabels = ['ayah' => 'Anak Kandung (Ayah)', 'ibu' => 'Anak Kandung (Ibu)', 'wali' => 'Wali Murid'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                            {{ $hubunganLabels[$hubungan] ?? ucfirst($hubungan) }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            Mode Pemantauan Aktif
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-1 flex-wrap">
                        <span>NIS: <strong class="text-slate-700 font-mono">{{ $activeSiswa->nis }}</strong></span>
                        <span>•</span>
                        <span>Kelas: <strong class="text-slate-700">{{ $activeSiswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</strong></span>
                        @if($activeSiswa->kelas?->waliKelas)
                            <span>•</span>
                            <span>Wali Kelas: <span class="text-slate-700 font-medium">{{ $activeSiswa->kelas->waliKelas->nama_lengkap }}</span></span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Switcher Multi-Anak (Jika > 1 Anak) --}}
            @if($siswas->count() > 1)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 bg-white/90 p-2 rounded-xl border border-slate-200 shadow-xs">
                    <span class="text-xs font-semibold text-slate-500 px-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        Pilih Anak:
                    </span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @foreach($siswas as $s)
                            <form method="POST" action="{{ route('orang-tua.switch-anak', $s) }}">
                                @csrf
                                <button type="submit" 
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 {{ $s->id === $activeSiswa->id ? 'bg-navy-600 text-white shadow-sm ring-2 ring-navy-600/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    <span>{{ Str::limit($s->nama_lengkap, 16) }}</span>
                                    <span class="text-[10px] opacity-75 font-mono">({{ $s->kelas?->nama_kelas ?? '-' }})</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif
