@extends('layouts.admin')

@section('title', 'Kalender Akademik')

@section('breadcrumb-parent', 'Akademik')
@section('breadcrumb-current', 'Kalender Akademik')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up" x-data="{ 
    showModal: false, 
    isEdit: false,
    detailModal: false,
    selectedEvent: null,
    formAction: '{{ route('admin.kalender.store') }}',
    formMethod: 'POST',
    formData: {
        id: '',
        judul: '',
        kategori: 'kegiatan',
        tanggal_mulai: '',
        tanggal_selesai: '',
        target: 'semua',
        warna: 'blue',
        deskripsi: '',
        is_libur: false,
        is_aktif: true,
        tahun_ajaran_id: '{{ $tahunAjaranAktif?->id }}'
    },
    openCreate() {
        this.isEdit = false;
        this.formAction = '{{ route('admin.kalender.store') }}';
        this.formMethod = 'POST';
        this.formData = {
            id: '',
            judul: '',
            kategori: 'kegiatan',
            tanggal_mulai: '{{ $currentDate->format('Y-m-d') }}',
            tanggal_selesai: '{{ $currentDate->format('Y-m-d') }}',
            target: 'semua',
            warna: 'blue',
            deskripsi: '',
            is_libur: false,
            is_aktif: true,
            tahun_ajaran_id: '{{ $tahunAjaranAktif?->id }}'
        };
        this.showModal = true;
    },
    openEdit(ev) {
        this.isEdit = true;
        this.formAction = '/admin/kalender/' + ev.id;
        this.formMethod = 'POST';
        this.formData = {
            id: ev.id,
            judul: ev.judul,
            kategori: ev.kategori,
            tanggal_mulai: ev.tanggal_mulai,
            tanggal_selesai: ev.tanggal_selesai,
            target: ev.target,
            warna: ev.warna || 'blue',
            deskripsi: ev.deskripsi || '',
            is_libur: Boolean(ev.is_libur),
            is_aktif: Boolean(ev.is_aktif),
            tahun_ajaran_id: ev.tahun_ajaran_id
        };
        this.detailModal = false;
        this.showModal = true;
    },
    showDetail(ev) {
        this.selectedEvent = ev;
        this.detailModal = true;
    }
}">

    {{-- Page Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Agenda Akademik</p>
            <h1 class="page-title font-display !text-3xl mt-3">Kalender Akademik.</h1>
            <p class="page-subtitle">Jadwal semester, pekan asesmen, libur sekolah, serta agenda institusi terpadu.</p>
        </div>
        <button type="button" @click="openCreate()" class="group inline-flex items-center gap-3 rounded-full bg-navy-800 py-1.5 pl-5 pr-1.5 text-sm font-semibold text-white transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-navy-900 cursor-pointer shadow-sm">
            Tambah Agenda Kalender
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-500 text-navy-900 transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:bg-gold-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </span>
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="rounded-2xl bg-white p-3.5 border border-slate-100 shadow-sm flex items-center gap-3">
            <span class="w-2.5 h-8 rounded-full bg-blue-500"></span>
            <div>
                <p class="text-[11px] font-medium text-slate-500">Total Agenda</p>
                <p class="text-xl font-bold font-display text-navy-900">{{ $stats['total_agenda'] }}</p>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-3.5 border border-slate-100 shadow-sm flex items-center gap-3">
            <span class="w-2.5 h-8 rounded-full bg-rose-500"></span>
            <div>
                <p class="text-[11px] font-medium text-slate-500">Hari Libur</p>
                <p class="text-xl font-bold font-display text-rose-600">{{ $stats['total_libur'] }}</p>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-3.5 border border-slate-100 shadow-sm flex items-center gap-3">
            <span class="w-2.5 h-8 rounded-full bg-amber-500"></span>
            <div>
                <p class="text-[11px] font-medium text-slate-500">Pekan Ujian</p>
                <p class="text-xl font-bold font-display text-amber-700">{{ $stats['total_ujian'] }}</p>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-3.5 border border-slate-100 shadow-sm flex items-center gap-3">
            <span class="w-2.5 h-8 rounded-full bg-purple-500"></span>
            <div>
                <p class="text-[11px] font-medium text-slate-500">Bagi Rapor</p>
                <p class="text-xl font-bold font-display text-purple-700">{{ $stats['total_rapor'] }}</p>
            </div>
        </div>
    </div>

    {{-- Calendar Control Bar --}}
    <div class="rounded-[2rem] bg-white p-4 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5 flex flex-col md:flex-row items-center justify-between gap-4">
        
        {{-- Month Navigator --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.kalender.index', ['bulan' => $prevDate->month, 'tahun' => $prevDate->year, 'kategori' => $kategori, 'mode' => $viewMode]) }}"
                class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors" title="Bulan Sebelumnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </a>

            <div class="px-4 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-center min-w-[210px]">
                <span class="text-sm font-bold font-display text-navy-900 capitalize">
                    {{ $currentDate->locale('id')->isoFormat('MMMM YYYY') }}
                </span>
            </div>

            <a href="{{ route('admin.kalender.index', ['bulan' => $nextDate->month, 'tahun' => $nextDate->year, 'kategori' => $kategori, 'mode' => $viewMode]) }}"
                class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors" title="Bulan Berikutnya">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </a>

            <a href="{{ route('admin.kalender.index', ['bulan' => now()->month, 'tahun' => now()->year, 'kategori' => $kategori, 'mode' => $viewMode]) }}"
                class="text-xs font-semibold text-slate-600 hover:text-navy-900 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                Hari Ini
            </a>
        </div>

        {{-- Category Filter Pills & Mode Switcher --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-1 rounded-full bg-slate-100 p-1 border border-slate-200 text-xs font-medium">
                <a href="{{ route('admin.kalender.index', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => '', 'mode' => $viewMode]) }}"
                    class="px-3 py-1 rounded-full transition-colors {{ empty($kategori) ? 'bg-white font-bold text-navy-900 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Semua</a>
                <a href="{{ route('admin.kalender.index', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => 'libur', 'mode' => $viewMode]) }}"
                    class="px-3 py-1 rounded-full transition-colors {{ $kategori === 'libur' ? 'bg-rose-50 font-bold text-rose-700 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Libur</a>
                <a href="{{ route('admin.kalender.index', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => 'ujian', 'mode' => $viewMode]) }}"
                    class="px-3 py-1 rounded-full transition-colors {{ $kategori === 'ujian' ? 'bg-amber-50 font-bold text-amber-700 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Ujian</a>
                <a href="{{ route('admin.kalender.index', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => 'kegiatan', 'mode' => $viewMode]) }}"
                    class="px-3 py-1 rounded-full transition-colors {{ $kategori === 'kegiatan' ? 'bg-blue-50 font-bold text-blue-700 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}">Kegiatan</a>
            </div>

            {{-- Mode Switcher --}}
            <div class="flex items-center gap-1 rounded-full bg-navy-900/5 p-1 text-xs">
                <a href="{{ route('admin.kalender.index', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $kategori, 'mode' => 'grid']) }}"
                    class="px-3 py-1 rounded-full transition-all {{ $viewMode === 'grid' ? 'bg-navy-800 text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-navy-900' }}">
                    Grid
                </a>
                <a href="{{ route('admin.kalender.index', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $kategori, 'mode' => 'list']) }}"
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

                        {{-- Day bottom indicator if multiple events --}}
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
                        <th>Agenda & Deskripsi</th>
                        <th>Kategori</th>
                        <th>Sasaran Audiens</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
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
                                <span class="text-xs font-semibold capitalize text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                    {{ $ev->target }}
                                </span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium {{ $ev->is_aktif ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $ev->is_aktif ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" @click='openEdit(@json($ev))' class="p-1.5 text-slate-600 hover:text-navy-900 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" title="Edit Agenda">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </button>
                                    <form action="{{ route('admin.kalender.destroy', $ev) }}" method="POST" onsubmit="return confirm('Hapus agenda kalender ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Agenda">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                Belum ada agenda kalender terdaftar pada periode ini.
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

    {{-- ================= MODAL TAMBAH / EDIT AGENDA ================= --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 sm:p-8 shadow-2xl ring-1 ring-black/5 space-y-5"
            @click.outside="showModal = false">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h2 class="text-lg font-bold font-display text-navy-900" x-text="isEdit ? 'Edit Agenda Kalender' : 'Tambah Agenda Kalender Baru'"></h2>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="formAction" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Nama / Judul Agenda <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" x-model="formData.judul" required placeholder="Contoh: Penilaian Tengah Semester (PTS) Ganjil"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                        <select name="kategori" x-model="formData.kategori" required
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                            <option value="libur">Libur Sekolah</option>
                            <option value="ujian">Ujian / Asesmen</option>
                            <option value="rapor">Pembagian Rapor</option>
                            <option value="kegiatan">Kegiatan Sekolah</option>
                            <option value="lainnya">Agenda Khusus</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Target Penerima <span class="text-rose-500">*</span></label>
                        <select name="target" x-model="formData.target" required
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-navy-900 focus:border-gold-500 focus:ring-gold-500">
                            <option value="semua">Semua (Guru, Siswa, Ortu)</option>
                            <option value="guru">Khusus Guru</option>
                            <option value="siswa">Khusus Siswa</option>
                            <option value="orang_tua">Khusus Orang Tua</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_mulai" x-model="formData.tanggal_mulai" required
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-navy-900 focus:border-gold-500 focus:ring-gold-500" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" x-model="formData.tanggal_selesai"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-navy-900 focus:border-gold-500 focus:ring-gold-500" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-navy-900 uppercase tracking-wider mb-1.5">Keterangan / Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" x-model="formData.deskripsi" rows="3" placeholder="Informasi tambahan mengenai teknis kegiatan atau catatan libur..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-xs font-medium text-navy-900 focus:border-gold-500 focus:ring-gold-500"></textarea>
                </div>

                <div class="flex items-center gap-4 py-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700">
                        <input type="checkbox" name="is_libur" value="1" x-model="formData.is_libur" class="rounded text-navy-900 focus:ring-gold-500">
                        <span>Ditandai Sebagai Hari Libur Sekolah</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-slate-700">
                        <input type="checkbox" name="is_aktif" value="1" x-model="formData.is_aktif" class="rounded text-navy-900 focus:ring-gold-500">
                        <span>Aktif Ditampilkan</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="rounded-full bg-white px-5 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="rounded-full bg-navy-800 px-6 py-2 text-xs font-semibold text-white hover:bg-navy-900 transition-colors cursor-pointer shadow-sm">
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambahkan ke Kalender'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL DETAIL AGENDA (QUICK VIEW) ================= --}}
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
                    <span>Target: <strong class="text-slate-700 capitalize" x-text="selectedEvent.target"></strong></span>
                    <span x-text="selectedEvent.is_libur ? '⛔ Libur Resmi' : 'Kegiatan Belajar'"></span>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openEdit(selectedEvent)" class="rounded-full bg-slate-100 hover:bg-slate-200 px-4 py-1.5 text-xs font-semibold text-navy-900 transition-colors cursor-pointer">
                        Edit Agenda
                    </button>
                    <button type="button" @click="detailModal = false" class="rounded-full bg-navy-800 hover:bg-navy-900 px-5 py-1.5 text-xs font-semibold text-white transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </template>
    </div>

</div>
@endsection
