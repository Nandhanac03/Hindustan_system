@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        
        {{-- Mobile View --}}
        <div class="flex items-center justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-[10px] font-extrabold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl uppercase tracking-wider">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-[10px] font-extrabold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors uppercase tracking-wider shadow-sm">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-[10px] font-extrabold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors uppercase tracking-wider shadow-sm">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-[10px] font-extrabold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl uppercase tracking-wider">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-extrabold text-slate-800">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-extrabold text-slate-800">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-extrabold text-slate-800">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex gap-1.5 shadow-sm rounded-xl p-1.5 bg-slate-50/80 border border-slate-200/80 backdrop-blur-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400 bg-transparent cursor-not-allowed rounded-lg" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 text-slate-600 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-lg transition-colors border border-slate-200 shadow-sm" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-extrabold text-slate-400 bg-transparent cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-black text-white bg-[#a38c29] border border-[#a38c29] rounded-lg shadow-sm shadow-[#a38c29]/40">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 text-xs font-extrabold text-slate-500 bg-transparent hover:bg-white hover:text-[#a38c29] border border-transparent hover:border-slate-200 rounded-lg transition-all hover:shadow-sm" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 text-slate-600 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-lg transition-colors border border-slate-200 shadow-sm" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400 bg-transparent cursor-not-allowed rounded-lg" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
