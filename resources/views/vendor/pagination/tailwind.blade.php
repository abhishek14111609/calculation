@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
        class="flex flex-col md:flex-row items-center justify-between gap-4 py-4 px-6 border-t border-white/5">
        <div class="flex-1 text-sm text-slate-500">
            Showing <span class="font-bold text-white">{{ $paginator->firstItem() ?? 0 }}</span> to
            <span class="font-bold text-white">{{ $paginator->lastItem() ?? 0 }}</span> of
            <span class="font-bold text-white">{{ $paginator->total() }}</span> results
        </div>

        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 rounded-lg bg-white/5 text-slate-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-2 rounded-lg bg-white/10 text-white hover:bg-accent hover:shadow-lg hover:shadow-accent/20 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 text-slate-600">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 rounded-lg bg-accent text-white font-bold shadow-lg shadow-accent/30">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-4 py-2 rounded-lg bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white transition-all">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-2 rounded-lg bg-white/10 text-white hover:bg-accent hover:shadow-lg hover:shadow-accent/20 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="px-3 py-2 rounded-lg bg-white/5 text-slate-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif