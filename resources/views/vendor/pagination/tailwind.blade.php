@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-3">
        <!-- Results Summary -->
        <div class="flex-1 flex justify-center sm:justify-start">
            <p class="text-sm font-medium text-gray-500">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-bold text-gray-900">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-bold text-gray-900">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-bold text-gray-900">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        <!-- Pagination Links -->
        <div class="flex justify-center sm:justify-end">
            <span class="relative z-0 inline-flex flex-wrap items-center gap-1.5 shadow-none rounded-md">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="relative inline-flex items-center justify-center w-9 h-9 text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg shadow-sm" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-9 h-9 text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-indigo-500 transition-all duration-200" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center justify-center w-9 h-9 text-gray-500 bg-transparent cursor-default">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center justify-center w-9 h-9 text-white bg-indigo-600 border border-indigo-600 rounded-lg shadow-sm font-bold text-sm cursor-default">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-9 h-9 text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm font-medium text-sm hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-300 focus:z-10 focus:ring-2 focus:ring-indigo-500 transition-all duration-200" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-9 h-9 text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-indigo-500 transition-all duration-200" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="relative inline-flex items-center justify-center w-9 h-9 text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-lg shadow-sm" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    </span>
                @endif
            </span>
        </div>
    </nav>
@endif
