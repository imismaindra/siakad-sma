@if ($paginator->total() > 0)
    <nav role="navigation" aria-label="Navigasi Halaman" class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 py-3 px-4 sm:px-6 border-t border-slate-100 bg-slate-50/60 rounded-b-[inherit]">
        {{-- Info Counter --}}
        <div class="text-xs text-slate-500 font-medium">
            <span>Menampilkan</span>
            <span class="font-bold text-navy-900 tabular-nums">{{ $paginator->firstItem() ?? 1 }}</span>
            <span>sampai</span>
            <span class="font-bold text-navy-900 tabular-nums">{{ $paginator->lastItem() ?? $paginator->total() }}</span>
            <span>dari</span>
            <span class="font-bold text-navy-900 tabular-nums">{{ $paginator->total() }}</span>
            <span>data</span>
        </div>

        {{-- Mobile Controls (Simple Prev / Counter / Next) --}}
        <div class="flex items-center justify-between gap-2 sm:hidden w-full">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200/80 bg-white/60 text-slate-400 text-xs font-semibold cursor-not-allowed select-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-navy-900 text-xs font-semibold hover:bg-slate-100 active:scale-95 transition shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </a>
            @endif

            <span class="text-xs font-bold text-navy-900 font-mono tabular-nums px-2">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-navy-900 text-xs font-semibold hover:bg-slate-100 active:scale-95 transition shadow-xs">
                    Next
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200/80 bg-white/60 text-slate-400 text-xs font-semibold cursor-not-allowed select-none">
                    Next
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>

        {{-- Desktop Controls --}}
        <div class="hidden sm:flex sm:items-center sm:gap-1.5">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200/70 bg-white/60 text-slate-400 text-xs font-semibold cursor-not-allowed select-none" aria-disabled="true">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:text-navy-900 hover:bg-slate-100 text-xs font-semibold active:scale-95 transition shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @if (isset($elements) && is_array($elements))
                <div class="inline-flex items-center gap-1 mx-1">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="h-8 px-2 flex items-center justify-center text-slate-400 text-xs font-bold select-none" aria-disabled="true">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="h-8 min-w-[2rem] px-2.5 rounded-xl bg-navy-800 text-white font-bold text-xs ring-1 ring-navy-800 flex items-center justify-center tabular-nums shadow-xs select-none">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="h-8 min-w-[2rem] px-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:text-navy-900 hover:bg-slate-100 text-xs font-semibold flex items-center justify-center tabular-nums transition active:scale-95 shadow-xs">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @else
                {{-- Fallback jika hanya 1 page tanpa elements array --}}
                <span aria-current="page" class="h-8 min-w-[2rem] px-2.5 rounded-xl bg-navy-800 text-white font-bold text-xs ring-1 ring-navy-800 flex items-center justify-center tabular-nums shadow-xs select-none mx-1">
                    {{ $paginator->currentPage() }}
                </span>
            @endif

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:text-navy-900 hover:bg-slate-100 text-xs font-semibold active:scale-95 transition shadow-xs">
                    <span>Selanjutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200/70 bg-white/60 text-slate-400 text-xs font-semibold cursor-not-allowed select-none" aria-disabled="true">
                    <span>Selanjutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
