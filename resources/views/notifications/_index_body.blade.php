<div class="space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="inline-flex rounded-full bg-white px-3 py-1 text-[10px] font-medium uppercase tracking-[0.2em] text-navy-800 ring-1 ring-black/5">Kotak Masuk</p>
            <h1 class="page-title font-display !text-3xl mt-3">Pusat Notifikasi.</h1>
            <p class="page-subtitle">Pemberitahuan resmi terkait kehadiran, penilaian semester, dan maklumat sekolah.</p>
        </div>

        <div class="flex items-center gap-2">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors cursor-pointer shadow-sm">
                        Tandai Semua Dibaca ({{ $unreadCount }})
                    </button>
                </form>
            @endif
            <a href="{{ route('notifications.settings') }}" class="inline-flex items-center gap-2 rounded-full bg-navy-800 px-4 py-2 text-xs font-semibold text-white hover:bg-navy-900 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.27.1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.93l-.15.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.27-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.11v-1.093c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.93l.15-.894z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengaturan Channel
            </a>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200/80 pb-3">
        <a href="{{ route('notifications.index') }}"
            class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ empty($filter) || $filter === 'all' && empty($type) ? 'bg-navy-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-navy-900 border border-slate-200' }}">
            Semua
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $filter === 'unread' ? 'bg-navy-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-navy-900 border border-slate-200' }}">
            <span>Belum Dibaca</span>
            @if($unreadCount > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $filter === 'unread' ? 'bg-gold-500 text-navy-950 font-bold' : 'bg-rose-500 text-white' }}">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('notifications.index', ['type' => 'absensi_alpa']) }}"
            class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $type === 'absensi_alpa' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-rose-700 border border-slate-200' }}">
            Presensi / Alpa
        </a>
        <a href="{{ route('notifications.index', ['type' => 'nilai_terbit']) }}"
            class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $type === 'nilai_terbit' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-emerald-700 border border-slate-200' }}">
            Nilai & Rapor
        </a>
        <a href="{{ route('notifications.index', ['type' => 'pengumuman_baru']) }}"
            class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors {{ $type === 'pengumuman_baru' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-blue-700 border border-slate-200' }}">
            Pengumuman
        </a>
    </div>

    {{-- Notification List --}}
    <div class="space-y-3">
        @forelse($notifications as $n)
            @php
                $isUnread = $n->read_at === null;
                $data = $n->data;
                $color = $data['color'] ?? 'blue';
                $icon = $data['icon'] ?? 'bell';
            @endphp
            <div class="group relative rounded-2xl border transition-all duration-200 p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4
                {{ $isUnread ? 'bg-white border-blue-200/80 shadow-sm ring-1 ring-blue-500/10' : 'bg-slate-50/70 border-slate-200/60 opacity-90 hover:opacity-100' }}">
                
                {{-- Left content & Icon --}}
                <div class="flex items-start gap-3.5 min-w-0 flex-1">
                    {{-- Color-coded Icon --}}
                    <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center
                        @if($color === 'rose') bg-rose-50 text-rose-600 border border-rose-200
                        @elseif($color === 'emerald') bg-emerald-50 text-emerald-600 border border-emerald-200
                        @elseif($color === 'indigo') bg-indigo-50 text-indigo-600 border border-indigo-200
                        @else bg-blue-50 text-blue-600 border border-blue-200 @endif">
                        @if($data['type'] === 'absensi_alpa')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        @elseif($data['type'] === 'nilai_terbit')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                        @elseif($data['type'] === 'sesi_qr_dibuka')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 13.5h1.5v1.5h-1.5zM16.5 13.5h1.5v1.5h-1.5zM13.5 16.5h1.5v1.5h-1.5zM16.5 16.5h1.5v1.5h-1.5zM19.5 13.5h.75v3.75H18v-1.5h1.5z"/></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        @endif
                    </div>

                    {{-- Title & Body --}}
                    <div class="min-w-0 flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-navy-900 leading-snug">
                                {{ $data['title'] ?? 'Pemberitahuan Sistem' }}
                            </h3>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse" title="Belum dibaca"></span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium">
                            {{ $n->created_at->locale('id')->diffForHumans() }} · {{ $n->created_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB
                        </p>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                    <a href="{{ route('notifications.read', $n->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-navy-800 hover:bg-navy-900 text-white transition-colors shadow-sm">
                        Buka
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <form action="{{ route('notifications.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Notifikasi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl bg-white border border-slate-200/80 p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">Tidak Ada Pemberitahuan</p>
                <p class="text-xs text-slate-400 mt-1">Anda sudah membaca semua notifikasi terbaru atau belum ada pemberitahuan baru.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
