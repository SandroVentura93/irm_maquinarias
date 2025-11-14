@if ($paginator->hasPages())
    <nav class="modern-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="pagination-wrapper">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-item disabled">
                    <i class="fas fa-chevron-left"></i>
                    <span class="sr-only">{{ __('pagination.previous') }}</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-item" rel="prev" aria-label="{{ __('pagination.previous') }}">
                    <i class="fas fa-chevron-left"></i>
                    <span class="sr-only">{{ __('pagination.previous') }}</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-item dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-item active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-item" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item" rel="next" aria-label="{{ __('pagination.next') }}">
                    <i class="fas fa-chevron-right"></i>
                    <span class="sr-only">{{ __('pagination.next') }}</span>
                </a>
            @else
                <span class="pagination-item disabled">
                    <i class="fas fa-chevron-right"></i>
                    <span class="sr-only">{{ __('pagination.next') }}</span>
                </span>
            @endif
        </div>

        {{-- Results Info --}}
        <div class="pagination-info">
            <p class="text-sm text-gray-600">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>
    </nav>
@endif