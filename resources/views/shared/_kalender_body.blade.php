<div class="space-y-6 animate-fade-in-up" x-data="{ 
    detailModal: false,
    selectedEvent: null,
    showDetail(ev) {
        this.selectedEvent = ev;
        this.detailModal = true;
    }
}">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Agenda Terpadu</p>
            <h1 class="page-title font-display !text-3xl mt-3">Kalender Akademik.</h1>
            <p class="page-subtitle">Informasi jadwal perkuliahan / persekolahan, rentang waktu ujian, hari libur, dan kegiatan resmi.</p>
        </div>
    </div>

    {{-- Calendar Control Bar --}}
    <div class="rounded-[2rem] bg-white p-4 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5 flex flex-col md:flex-row items-center justify-between gap-4">
        
        {{-- Month Navigator --}}
        <div class="flex items-center gap-2">
            <a href="?bulan={{ $prevDate->month }}&tahun={{ $prevDate->year }}&kategori={{ $kategori }}&mode={{ $viewMode }}"
                class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors" title="Bulan Sebelumnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </a>

            <div class="px-4 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-center min-w-[210px]">
                <span class="text-sm font-bold font-display text-navy-900 capitalize">
                    {{ $currentDate->locale('id')->isoFormat('MMMM YYYY') }}
                </span>
            </div>

            <a href="?bulan={{ $nextDate->month }}&tahun={{ $nextDate->year }}&kategori={{ $kategori }}&mode={{ $viewMode }}"
                class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors" title="Bulan Berikutnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </a>

            <a href="?bulan={{ now()->month }}&tahun={{ now()->year }}&kategori={{ $kategori }}&mode={{ $viewMode }}"
                class="text-xs font-semibold text-slate-600 hover:text-navy-900 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                Bulan Ini
            </a>
        </div>

        {{-- Category Filter Pills & Mode Switcher --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-1 rounded-full bg-slate-100 p-1 border border-slate-200 text-xs font-medium">
                <a href="?bulan={{ $bulan }}&tahun={{ $tahun }}&kategori=&mode={{ $viewMode }}"
                    class="px-3 py-1 rounded-full transition-colors {{ empty($kategori) ? 'bg-white font-bold text-navy-900 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Semua</a>
                <a href="?bulan={{ $bulan }}&tahun={{ $tahun }}&kategori=libur&mode={{ $viewMode }}"
                    class="px-3 py-1 rounded-full transition-colors {{ $kategori === 'libur' ? 'bg-rose-50 font-bold text-rose-700 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Libur</a>
                <a href="?bulan={{ $bulan }}&tahun={{ $tahun }}&kategori=ujian&mode={{ $viewMode }}"
                    class="px-3 py-1 rounded-full transition-colors {{ $kategori === 'ujian' ? 'bg-amber-50 font-bold text-amber-700 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Ujian</a>
                <a href="?bulan={{ $bulan }}&tahun={{ $tahun }}&kategori=kegiatan&mode={{ $viewMode }}"
                    class="px-3 py-1 rounded-full transition-colors {{ $kategori === 'kegiatan' ? 'bg-blue-50 font-bold text-blue-700 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Kegiatan</a>
            </div>

            {{-- Mode Switcher --}}
            <div class="flex items-center gap-1 rounded-full bg-navy-900/5 p-1 text-xs">
                <a href="?bulan={{ $bulan }}&tahun={{ $tahun }}&kategori={{ $kategori }}&mode=grid"
                    class="px-3 py-1 rounded-full transition-all {{ $viewMode === 'grid' ? 'bg-navy-800 text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-navy-900' }}">
                    Grid
                </a>
                <a href="?bulan={{ $bulan }}&tahun={{ $tahun }}&kategori={{ $kategori }}&mode=list"
                    class="px-3 py-1 rounded-full transition-all {{ $viewMode === 'list' ? 'bg-navy-800 text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-navy-900' }}">
                    Daftar
                </a>
            </div>
        </div>
    </div>

    {{-- ================= MODE GRID BULANAN ================= --}}
    @if($viewMode === 'grid' && $calendarData)
    <div class="overflow-hidden rounded-[2rem] bg-white p-3 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        {{-- Week Day Headers --}}
        <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs font-bold font-display uppercase tracking-wider text-slate-500 py-2 border-b border-slate-100">
            <div>Senin</div>
            <div>Selasa</div>
            <div>Rabu</div>
            <div>Kamis</div>
            <div>Jumat</div>
            <div class="text-slate-700">Sabtu</div>
            <div class="text-rose-500">Minggu</div>
        </div>

        {{-- Month Days Grid --}}
        <div class="grid grid-cols-7 gap-1.5 auto-rows-fr">
            @foreach($calendarData as $week)
                @foreach($week as $day)
                    <div class="min-h-[105px] sm:min-h-[125px] p-2 rounded-xl border transition-all flex flex-col justify-between
                        {{ $day['isCurrentMonth'] ? 'bg-white border-slate-100' : 'bg-slate-50/50 border-transparent text-slate-400' }}
                        {{ $day['isToday'] ? 'ring-2 ring-gold-500 bg-gold-50/20' : '' }}
                        {{ $day['isSunday'] ? 'bg-rose-50/20' : '' }}">
                        
                        {{-- Day Top Header --}}
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold {{ $day['isToday'] ? 'w-6 h-6 rounded-full bg-navy-800 text-white flex items-center justify-center font-display' : ($day['isSunday'] ? 'text-rose-500' : ($day['isCurrentMonth'] ? 'text-slate-800' : 'text-slate-400')) }}">
                                {{ $day['dayNumber'] }}
                            </span>
                            @if($day['isToday'])
                                <span class="text-[9px] font-bold uppercase tracking-wider text-gold-600 hidden sm:inline">Hari ini</span>
                            @endif
                        </div>

                        {{-- Event Badges on this day --}}
                        <div class="space-y-1 my-1 flex-1 overflow-y-auto max-h-[75px] scrollbar-none">
                            @foreach($day['events'] as $ev)
                                <button type="button" @click='showDetail(@json($ev))'
                                    class="w-full text-left px-2 py-1 rounded text-[11px] font-medium truncate block cursor-pointer transition-transform hover:scale-[1.02] border {{ $ev->badge_class }}"
                                    title="{{ $ev->judul }}">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full mr-1 {{ $ev->dot_color_class }}"></span>
                                    {{ $ev->judul }}
                                </button>
                            @endforeach
                        </div>

                        @if($day['events']->count() > 2)
                            <p class="text-[9px] text-slate-400 font-semibold text-right">+{{ $day['events']->count() - 2 }} lainnya</p>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================= MODE DAFTAR AGENDA ================= --}}
    @if($viewMode === 'list')
    <div class="overflow-hidden rounded-[2rem] bg-white p-2 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5">
        <div class="overflow-x-auto rounded-[calc(2rem-0.5rem)]">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Agenda Kegiatan</th>
                        <th>Kategori</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $ev)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="whitespace-nowrap font-medium text-xs text-navy-900">
                                @if($ev->tanggal_selesai && $ev->tanggal_mulai != $ev->tanggal_selesai)
                                    <p class="font-bold">{{ $ev->tanggal_mulai->locale('id')->isoFormat('D MMM') }} - {{ $ev->tanggal_selesai->locale('id')->isoFormat('D MMM YYYY') }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $ev->tanggal_mulai->diffInDays($ev->tanggal_selesai) + 1 }} hari</p>
                                @else
                                    <p class="font-bold">{{ $ev->tanggal_mulai->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $ev->tanggal_mulai->locale('id')->isoFormat('dddd') }}</p>
                                @endif
                            </td>
                            <td class="max-w-md">
                                <p class="text-sm font-bold text-navy-900">{{ $ev->judul }}</p>
                                @if($ev->deskripsi)
                                    <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">{{ $ev->deskripsi }}</p>
                                @endif
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $ev->badge_class }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ev->dot_color_class }}"></span>
                                    {{ $ev->label_kategori }}
                                </span>
                            </td>
                            <td>
                                @if($ev->is_libur)
                                    <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">Libur Sekolah</span>
                                @else
                                    <span class="text-xs text-slate-500">Kegiatan Terjadwal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-400">
                                Belum ada agenda kegiatan yang terdaftar untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($events, 'hasPages') && $events->hasPages())
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    @endif
    @endif

    {{-- ================= MODAL DETAIL AGENDA ================= --}}
    <div x-show="detailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <template x-if="selectedEvent">
            <div class="w-full max-w-md rounded-[2rem] bg-white p-6 sm:p-7 shadow-2xl ring-1 ring-black/5 space-y-4"
                @click.outside="detailModal = false">
                
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                        :class="selectedEvent.badge_class">
                        <span class="w-1.5 h-1.5 rounded-full" :class="selectedEvent.dot_color_class"></span>
                        <span x-text="selectedEvent.label_kategori"></span>
                    </span>
                    <button type="button" @click="detailModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div>
                    <h3 class="text-xl font-bold font-display text-navy-900 leading-snug" x-text="selectedEvent.judul"></h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        <span x-text="selectedEvent.tanggal_mulai"></span>
                        <template x-if="selectedEvent.tanggal_selesai && selectedEvent.tanggal_selesai !== selectedEvent.tanggal_mulai">
                            <span>s/d <span x-text="selectedEvent.tanggal_selesai"></span></span>
                        </template>
                    </p>
                </div>

                <div class="rounded-xl bg-slate-50 p-3.5 text-xs text-slate-600 leading-relaxed border border-slate-100"
                    x-show="selectedEvent.deskripsi" x-text="selectedEvent.deskripsi">
                </div>

                <div class="text-[11px] text-slate-400 flex items-center justify-between border-t border-slate-100 pt-3">
                    <span x-text="selectedEvent.is_libur ? '⛔ Hari Libur Resmi Sekolah' : 'Kegiatan Belajar Mengajar Aktif'"></span>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="button" @click="detailModal = false" class="rounded-full bg-navy-800 hover:bg-navy-900 px-5 py-1.5 text-xs font-semibold text-white transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </template>
    </div>

</div>
