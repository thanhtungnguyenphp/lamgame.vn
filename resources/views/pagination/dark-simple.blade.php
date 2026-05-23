@if ($paginator->hasPages())
<nav class="dark-pagi" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="dark-pagi__item dark-pagi__item--disabled">← Trước</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="dark-pagi__item" rel="prev">← Trước</a>
    @endif

    <span class="dark-pagi__item dark-pagi__item--active">{{ $paginator->currentPage() }}</span>

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="dark-pagi__item" rel="next">Tiếp →</a>
    @else
        <span class="dark-pagi__item dark-pagi__item--disabled">Tiếp →</span>
    @endif
</nav>
@endif
