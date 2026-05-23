@if ($paginator->hasPages())
<nav class="dark-pagi" aria-label="Pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="dark-pagi__item dark-pagi__item--disabled">← Trước</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="dark-pagi__item" rel="prev">← Trước</a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="dark-pagi__item dark-pagi__item--dots">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="dark-pagi__item dark-pagi__item--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="dark-pagi__item">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="dark-pagi__item" rel="next">Tiếp →</a>
    @else
        <span class="dark-pagi__item dark-pagi__item--disabled">Tiếp →</span>
    @endif
</nav>
@endif
