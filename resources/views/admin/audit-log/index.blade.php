@extends('layouts.admin')

@section('title', 'Audit Log Aktivitas')

@section('admin-content')
<div class="space-y-6" x-data="{
    detailModal: false,
    selectedLog: null,
    loadingDetail: false,
    openDetail(logId) {
        this.loadingDetail = true;
        this.detailModal = true;
        fetch(`{{ url('admin/audit-log') }}/${logId}`)
            .then(res => res.json())
            .then(data => {
                this.selectedLog = data;
                this.loadingDetail = false;
            })
            .catch(err => {
                console.error(err);
                this.loadingDetail = false;
            });
    }
}">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <h1 class="text-xl font-bold text-slate-800">Audit Log & Jejak Aktivitas</h1>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                Pencatatan riwayat perubahan data sensitif secara <em>hanya-tambah (append-only)</em> untuk akuntabilitas sistem.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Integritas Log Terjamin</span>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Catatan Log</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ number_format($stats['total']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Semua aktivitas sejak awal v2.0</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-emerald-600 mt-2">{{ number_format($stats['today']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Perubahan per {{ date('d M Y') }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengguna Terlibat</span>
                <span class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ number_format($stats['users']) }}</p>
            <p class="text-xs text-slate-400 mt-1">Pelaku unik yang melakukan aksi</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.audit-log.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                {{-- Filter Pelaku --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pelaku (Pengguna)</label>
                    <select name="causer_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Pelaku</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Modul --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Modul / Jenis Data</label>
                    <select name="log_name" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Modul</option>
                        @foreach($logNames as $name)
                            <option value="{{ $name }}" {{ request('log_name') == $name ? 'selected' : '' }}>
                                {{ ucfirst($name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Event --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Aksi / Event</label>
                    <select name="event" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Event</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev }}" {{ request('event') == $ev ? 'selected' : '' }}>
                                {{ ucfirst($ev) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tanggal Mulai --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Filter Tanggal Selesai --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="relative flex-1 max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan atau kata kunci..." class="w-full text-xs rounded-xl border-slate-200 pl-8 focus:border-indigo-500 focus:ring-indigo-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-xl transition shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Terapkan Filter
                    </button>
                    @if(request()->hasAny(['causer_id', 'log_name', 'event', 'start_date', 'end_date', 'search']))
                        <a href="{{ route('admin.audit-log.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Audit Log --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Waktu</th>
                        <th class="px-5 py-3.5">Pelaku</th>
                        <th class="px-5 py-3.5">Modul</th>
                        <th class="px-5 py-3.5">Aksi</th>
                        <th class="px-5 py-3.5">Deskripsi / Subjek</th>
                        <th class="px-5 py-3.5 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            {{-- Waktu --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})</div>
                            </td>

                            {{-- Pelaku --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($log->causer)
                                    <div class="font-medium text-slate-800">{{ $log->causer->name }}</div>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-medium rounded-md
                                        {{ $log->causer->role === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                        {{ $log->causer->role === 'guru' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ $log->causer->role === 'siswa' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}">
                                        {{ ucfirst($log->causer->role ?? 'User') }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Sistem / Otomatis</span>
                                @endif
                            </td>

                            {{-- Modul --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php
                                    $modulColor = match($log->log_name) {
                                        'nilai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                        'absensi', 'absensi_detail' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                        'user' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                        'role_permission' => 'bg-fuchsia-50 text-fuchsia-700 border border-fuchsia-200',
                                        default => 'bg-slate-100 text-slate-700 border border-slate-200'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[11px] font-medium rounded-lg {{ $modulColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->log_name ?? 'Umum')) }}
                                </span>
                            </td>

                            {{-- Aksi / Event --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php
                                    $badge = match($log->event) {
                                        'created' => 'bg-green-100 text-green-800',
                                        'updated' => 'bg-amber-100 text-amber-800',
                                        'deleted' => 'bg-red-100 text-red-800',
                                        'override' => 'bg-purple-100 text-purple-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] font-semibold uppercase rounded {{ $badge }}">
                                    {{ $log->event ?? 'action' }}
                                </span>
                            </td>

                            {{-- Deskripsi / Subjek --}}
                            <td class="px-5 py-3.5 max-w-xs sm:max-w-md">
                                <div class="text-slate-800 font-medium truncate">{{ $log->description }}</div>
                                @if($log->subject_type)
                                    <div class="text-[11px] text-slate-400 font-mono">
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </div>
                                @endif
                            </td>

                            {{-- Tombol Detail --}}
                            <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                <button @click="openDetail({{ $log->id }})" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat Diff
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Belum ada riwayat aktivitas yang tercatat atau cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Detail & Diff Nilai Lama vs Nilai Baru --}}
    <div x-show="detailModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="detailModal = false"></div>

            {{-- Dialog Box --}}
            <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl border border-slate-100"
                 @click.away="detailModal = false">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <h3 class="text-base font-bold text-slate-800">Detail Audit Log #<span x-text="selectedLog?.id"></span></h3>
                    </div>
                    <button @click="detailModal = false" class="text-slate-400 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <template x-if="loadingDetail">
                    <div class="py-12 text-center text-slate-400">
                        <svg class="w-8 h-8 mx-auto animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <p class="mt-2 text-xs">Memuat detail log...</p>
                    </div>
                </template>

                <template x-if="!loadingDetail && selectedLog">
                    <div class="mt-4 space-y-4">
                        {{-- Ringkasan Info --}}
                        <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl text-xs">
                            <div>
                                <span class="text-slate-400 block text-[11px]">Waktu:</span>
                                <span class="font-medium text-slate-800" x-text="selectedLog.created_at"></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Pelaku:</span>
                                <span class="font-medium text-slate-800" x-text="selectedLog.causer ? `${selectedLog.causer.name} (${selectedLog.causer.role})` : 'Sistem'"></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Modul & Aksi:</span>
                                <span class="font-medium text-slate-800" x-text="`${selectedLog.log_name} · [${selectedLog.event}]`"></span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[11px]">Subjek:</span>
                                <span class="font-medium text-slate-800" x-text="`${selectedLog.subject_type} #${selectedLog.subject_id}`"></span>
                            </div>
                        </div>

                        {{-- Perbandingan Nilai Lama vs Nilai Baru (Diff) --}}
                        <div>
                            <h4 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Perubahan Atribut Data</h4>
                            
                            {{-- Jika ada properties.attributes atau properties.old --}}
                            <template x-if="selectedLog.properties && (selectedLog.properties.attributes || selectedLog.properties.old)">
                                <div class="overflow-hidden border border-slate-200 rounded-xl">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-50 text-[10px] font-semibold uppercase text-slate-600 border-b border-slate-200">
                                            <tr>
                                                <th class="px-3 py-2">Atribut</th>
                                                <th class="px-3 py-2 text-red-700 bg-red-50/50">Nilai Lama (Sebelum)</th>
                                                <th class="px-3 py-2 text-emerald-700 bg-emerald-50/50">Nilai Baru (Sesudah)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                                            <template x-for="(val, key) in (selectedLog.properties.attributes || selectedLog.properties.old)" :key="key">
                                                <tr>
                                                    <td class="px-3 py-2 font-semibold text-slate-700 font-sans" x-text="key"></td>
                                                    <td class="px-3 py-2 bg-red-50/30 text-red-600 break-all">
                                                        <span x-text="selectedLog.properties.old && (selectedLog.properties.old[key] !== undefined) ? JSON.stringify(selectedLog.properties.old[key]) : '-'"></span>
                                                    </td>
                                                    <td class="px-3 py-2 bg-emerald-50/30 text-emerald-600 break-all">
                                                        <span x-text="selectedLog.properties.attributes && (selectedLog.properties.attributes[key] !== undefined) ? JSON.stringify(selectedLog.properties.attributes[key]) : '-'"></span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>

                            {{-- Jika properti berbentuk custom array/json tanpa attributes/old --}}
                            <template x-if="selectedLog.properties && !selectedLog.properties.attributes && !selectedLog.properties.old && Object.keys(selectedLog.properties).length > 0">
                                <pre class="p-3 bg-slate-900 text-slate-100 text-[11px] font-mono rounded-xl overflow-x-auto max-h-60" x-text="JSON.stringify(selectedLog.properties, null, 2)"></pre>
                            </template>

                            <template x-if="!selectedLog.properties || Object.keys(selectedLog.properties).length === 0">
                                <p class="text-xs text-slate-400 italic">Tidak ada rincian perubahan atribut untuk aksi ini.</p>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="detailModal = false" class="px-4 py-2 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
