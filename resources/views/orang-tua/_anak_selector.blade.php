{{-- Reusable Anak Selector & Status Banner (F4-02 & F4-04) --}}
@if(isset($siswas) && $siswas->isNotEmpty())
    <div class="card p-4 sm:p-5 border border-slate-200/90 bg-white mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

            {{-- Info Anak Aktif --}}
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-lg bg-blue-700 text-white font-display font-bold text-lg flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($activeSiswa->nama_lengkap ?? 'A', 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-base sm:text-lg font-bold font-display text-slate-900">{{ $activeSiswa->nama_lengkap }}</h2>
                        @php
                            $hubungan = $activeSiswa->pivot->hubungan ?? ($activeSiswa->orangTuas->firstWhere('id', Auth::user()->orangTua?->id)?->pivot?->hubungan ?? 'wali');
                            $hubunganLabels = ['ayah' => 'Anak Kandung (Ayah)', 'ibu' => 'Anak Kandung (Ibu)', 'wali' => 'Wali Murid'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $hubunganLabels[$hubungan] ?? ucfirst($hubungan) }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            Mode Pemantauan Aktif
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 mt-1 flex-wrap">
                        <span>NIS: <strong class="text-slate-700 font-mono">{{ $activeSiswa->nis }}</strong></span>
                        <span class="text-slate-300">·</span>
                        <span>Kelas: <strong class="text-slate-700">{{ $activeSiswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</strong></span>
                        @if($activeSiswa->kelas?->waliKelas)
                            <span class="text-slate-300">·</span>
                            <span>Wali Kelas: <span class="text-slate-700 font-medium">{{ $activeSiswa->kelas->waliKelas->nama_lengkap }}</span></span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Switcher Multi-Anak (Jika > 1 Anak) --}}
            @if($siswas->count() > 1)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                    <span class="text-xs font-semibold text-slate-500 px-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                        Pilih Anak:
                    </span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @foreach($siswas as $s)
                            <form method="POST" action="{{ route('orang-tua.switch-anak', $s) }}">
                                @csrf
                                <button type="submit" 
                                    class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $s->id === $activeSiswa->id ? 'bg-blue-700 text-white' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                                    <span>{{ Str::limit($s->nama_lengkap, 16) }}</span>
                                    <span class="text-[10px] opacity-80 font-mono">({{ $s->kelas?->nama_kelas ?? '-' }})</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif
