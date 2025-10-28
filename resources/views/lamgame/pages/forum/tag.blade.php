@extends('layouts.master')

@section('page_title', $tag->name . ' - Forum Tag')
@section('page_description', 'Các bài viết về ' . $tag->name . ' trong cộng đồng game developer Việt Nam')

@section('content')
<div class="forum-page">
    <!-- Tag Header -->
    <div class="forum-header">
        <div class="container">
            <div class="header-content">
                <div class="forum-title">
                    <div class="breadcrumb-nav">
                        <a href="{{ route('forum.index') }}" class="breadcrumb-link">Forum</a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-current">{{ $tag->name }}</span>
                    </div>
                    <h1>🏷️ {{ $tag->name }}</h1>
                    @if($tag->description)
                    <p>{{ $tag->description }}</p>
                    @else
                    <p>Tất cả bài viết có tag {{ $tag->name }}</p>
                    @endif
                </div>
                <div class="forum-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $posts->total() }}</span>
                        <span class="stat-label">Bài viết</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="forum-actions">
        <div class="container">
            <div class="actions-wrapper">
                <!-- Search -->
                <div class="forum-search">
                    <form action="{{ route('forum.search') }}" method="GET" class="search-form">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" placeholder="Tìm kiếm bài viết, thảo luận..." class="search-input">
                    </form>
                </div>
                
                <!-- Action Buttons -->
                <div class="tags-row">
                    <div class="tags-scroll">
                        <a href="{{ route('forum.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            <span class="btn-text">Quay lại</span>
                        </a>
                        
                        <a href="{{ route('forum.posts.create') }}" class="btn btn-primary btn-create">
                            <i class="fas fa-pen"></i>
                            <span class="btn-text">Đăng bài</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="forum-content">
            <!-- Posts Feed -->
            <div class="posts-feed">
                <!-- Tag Info Banner -->
                <div class="tag-info-banner">
                    <div class="tag-badge">
                        <i class="fas fa-tag"></i>
                        <span>{{ $tag->name }}</span>
                    </div>
                    <div class="tag-meta">
                        <span class="meta-item">
                            <i class="fas fa-file-alt"></i>
                            {{ $posts->total() }} bài viết
                        </span>
                    </div>
                </div>

                <!-- Posts List -->
                <div class="posts-list">
                    @forelse($posts as $post)
                        @include('lamgame.pages.forum.partials.post-card', ['post' => $post])
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">🔍</div>
                            <h3>Chưa có bài viết nào</h3>
                            <p>Chưa có bài viết nào với tag <strong>{{ $tag->name }}</strong></p>
                            <a href="{{ route('forum.posts.create') }}" class="btn btn-primary">
                                Tạo bài viết đầu tiên
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="forum-pagination">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.forum-page {
    min-height: 100vh;
    background: #f8fafc;
}

.forum-header {
    background: linear-gradient(135deg, #6a4c93 0%, #9b5de5 100%);
    color: white;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}

.forum-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="80" cy="40" r="0.3" fill="%23ffffff" opacity="0.1"/><circle cx="40" cy="80" r="0.4" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.3;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: white;
}

.breadcrumb-separator {
    color: rgba(255, 255, 255, 0.5);
}

.breadcrumb-current {
    color: white;
    font-weight: 600;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.forum-title h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.forum-title p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0;
}

.forum-stats {
    display: flex;
    gap: 2rem;
}

.stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 12px;
    backdrop-filter: blur(10px);
    min-width: 120px;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.forum-actions {
    background: white;
    padding: 0.875rem 0;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    position: sticky;
    top: 0;
    z-index: 100;
}

.actions-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}

.forum-search {
    width: 100%;
}

.tags-row {
    width: 100%;
    overflow: hidden;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, #6a4c93, #9b5de5);
    color: white;
    box-shadow: 0 4px 12px rgba(106, 76, 147, 0.25);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(106, 76, 147, 0.35);
}

.btn-secondary {
    background: #e2e8f0;
    color: #1a202c;
}

.btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.btn-create {
    flex-shrink: 0;
    margin-left: auto;
}

.btn-create i {
    font-size: 0.95rem;
}

.btn-text {
    font-size: 0.9rem;
}

.search-form {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid #cbd5e0;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.search-form:hover {
    border-color: #94a3b8;
}

.search-form:focus-within {
    border-color: #6a4c93;
    box-shadow: 0 0 0 3px rgba(106, 76, 147, 0.1);
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: #64748b;
    font-size: 1.1rem;
    pointer-events: none;
    transition: color 0.2s ease;
}

.search-form:focus-within .search-icon {
    color: #6a4c93;
}

.forum-page .search-input {
    flex: 1;
    padding: 0.75rem 1rem 0.75rem 2.75rem;
    border: none;
    outline: none;
    font-size: 0.95rem;
    background: transparent;
    color: #1a202c;
    font-weight: 500;
}

.search-input::placeholder {
    color: #64748b;
    font-weight: 400;
}

.forum-content {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 0;
}

.posts-feed {
    width: 100%;
}

.tags-scroll {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    overflow-x: auto;
    padding: 0;
    scrollbar-width: none;
}

.tags-scroll::-webkit-scrollbar {
    display: none;
}

.tag-info-banner {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    background: linear-gradient(135deg, #6a4c93, #9b5de5);
    color: white;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
}

.tag-badge i {
    font-size: 1.25rem;
}

.tag-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    color: #64748b;
    font-size: 0.9rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item i {
    color: #94a3b8;
}

.posts-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #718096;
    margin-bottom: 2rem;
}

.empty-state strong {
    color: #6a4c93;
    font-weight: 700;
}

.forum-pagination {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .breadcrumb-nav {
        justify-content: center;
    }

    .forum-stats {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
    }

    .stat-item {
        min-width: 100px;
        padding: 1rem;
    }

    .forum-actions {
        padding: 0.75rem 0;
    }

    .actions-wrapper {
        gap: 0.5rem;
    }

    .forum-search {
        width: 100%;
    }

    .search-form {
        width: 100%;
    }

    .tags-scroll {
        flex-wrap: wrap;
    }

    .btn-create {
        margin-left: 0;
        order: -1;
        width: 100%;
        justify-content: center;
        padding: 0.75rem 1.25rem;
    }

    .btn-secondary {
        width: 100%;
        justify-content: center;
    }

    .btn-text {
        font-size: 0.95rem;
    }

    .tags-row {
        margin: 0 -1rem;
        padding: 0 1rem;
    }

    .tags-scroll {
        gap: 0.375rem;
    }

    .forum-content {
        padding: 1rem 0;
    }

    .tag-info-banner {
        flex-direction: column;
        align-items: flex-start;
        padding: 1.25rem;
    }

    .tag-badge {
        width: 100%;
        justify-content: center;
    }

    .tag-meta {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto-refresh could be added here if needed
</script>
@endpush
@endsection
