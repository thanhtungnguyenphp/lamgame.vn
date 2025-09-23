@if ($paginator->hasPages())
<div class="modern-pagination-wrapper">
    <!-- Pagination Info -->
    <div class="pagination-info">
        <p class="results-text">
            @if($paginator->total() > 0)
                Hiển thị <span class="highlight">{{ $paginator->firstItem() }}</span> - 
                <span class="highlight">{{ $paginator->lastItem() }}</span> 
                trong tổng số <span class="highlight">{{ $paginator->total() }}</span> việc làm
            @else
                Không có kết quả
            @endif
        </p>
    </div>

    <!-- Pagination Navigation -->
    <nav class="pagination-nav" role="navigation" aria-label="Phân trang">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link prev-next" aria-label="Trang trước">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span class="sr-only">Trang trước</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link prev-next" href="{{ $paginator->previousPageUrl() }}" 
                       rel="prev" aria-label="Trang trước">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span class="sr-only">Trang trước</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link current">{{ $page }}</span>
                    </li>
                @elseif ($page == 1 || $page == $paginator->lastPage() || ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2))
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}" 
                           aria-label="Đi tới trang {{ $page }}">{{ $page }}</a>
                    </li>
                @elseif ($page == $paginator->currentPage() - 3 || $page == $paginator->currentPage() + 3)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link dots">...</span>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link prev-next" href="{{ $paginator->nextPageUrl() }}" 
                       rel="next" aria-label="Trang tiếp theo">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        <span class="sr-only">Trang tiếp theo</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link prev-next" aria-label="Trang tiếp theo">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                        <span class="sr-only">Trang tiếp theo</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Quick Jump (Optional, chỉ hiện khi có nhiều trang) -->
    @if($paginator->lastPage() > 10)
    <div class="pagination-jump">
        <form method="GET" action="{{ request()->url() }}" class="jump-form">
            @foreach(request()->except('page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <label for="page-jump" class="jump-label">Chuyển đến trang:</label>
            <input type="number" id="page-jump" name="page" min="1" max="{{ $paginator->lastPage() }}" 
                   value="{{ $paginator->currentPage() }}" class="jump-input">
            <button type="submit" class="jump-btn">Đi</button>
        </form>
    </div>
    @endif
</div>

<style>
/* Modern Pagination Styles */
.modern-pagination-wrapper {
    margin-top: 3rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #f0f2f5;
}

.pagination-info {
    text-align: center;
    margin-bottom: 1.5rem;
}

.results-text {
    color: #64748b;
    font-size: 0.95rem;
    margin: 0;
    font-weight: 500;
}

.results-text .highlight {
    color: #667eea;
    font-weight: 700;
}

.pagination-nav {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.pagination-list {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    list-style: none;
    margin: 0;
    padding: 0;
    flex-wrap: wrap;
    justify-content: center;
}

.page-item {
    display: block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 0 12px;
    color: #64748b;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.page-link:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.page-link:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    z-index: 1;
}

.page-item.active .page-link.current {
    background: #667eea;
    color: white;
    border-color: #667eea;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

.page-item.disabled .page-link {
    color: #cbd5e1;
    cursor: not-allowed;
    background: #f8fafc;
    border-color: #e2e8f0;
}

.page-item.disabled .page-link:hover {
    background: #f8fafc;
    color: #cbd5e1;
    transform: none;
    box-shadow: none;
}

.prev-next {
    font-size: 1rem;
    padding: 0;
    width: 42px;
}

.dots {
    background: transparent !important;
    border: none !important;
    color: #94a3b8 !important;
    cursor: default;
    font-weight: 700;
}

/* Quick Jump Styles */
.pagination-jump {
    display: flex;
    justify-content: center;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.jump-form {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: #f8fafc;
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.jump-label {
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 500;
    margin: 0;
}

.jump-input {
    width: 60px;
    padding: 0.4rem 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    text-align: center;
    font-size: 0.85rem;
    background: white;
}

.jump-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

.jump-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    font-size: 0.85rem;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s ease;
}

.jump-btn:hover {
    background: #5a67d8;
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .modern-pagination-wrapper {
        margin-top: 2rem;
        padding: 1rem;
        border-radius: 8px;
    }

    .pagination-info {
        margin-bottom: 1rem;
    }

    .results-text {
        font-size: 0.85rem;
    }

    .pagination-list {
        gap: 0.15rem;
    }

    .page-link {
        min-width: 38px;
        height: 38px;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    .prev-next {
        width: 38px;
    }

    /* Hide some page numbers on very small screens */
    @media (max-width: 480px) {
        .pagination-list .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-last-child(2)):not(:nth-child(2)) {
            display: none;
        }
    }

    .pagination-jump {
        padding-top: 0.75rem;
    }

    .jump-form {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}

/* Dark mode support (nếu cần) */
@media (prefers-color-scheme: dark) {
    .modern-pagination-wrapper {
        background: #1f2937;
        border-color: #374151;
    }

    .results-text {
        color: #d1d5db;
    }

    .page-link {
        background: #374151;
        color: #d1d5db;
        border-color: #4b5563;
    }

    .page-item.disabled .page-link {
        background: #1f2937;
        color: #6b7280;
        border-color: #374151;
    }

    .jump-form {
        background: #374151;
    }

    .jump-input {
        background: #1f2937;
        border-color: #4b5563;
        color: #d1d5db;
    }
}

/* Loading state animation */
.pagination-loading .page-link {
    pointer-events: none;
    opacity: 0.6;
    position: relative;
}

.pagination-loading .page-link::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 2px solid transparent;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Screen reader only */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
@endif