@extends('layouts.master')

@section('page_title', 'Forum - Cộng đồng Game Developer')
@section('page_description', 'Tham gia thảo luận về game development, chia sẻ ý tưởng và tìm kiếm đồng đội.')

@section('content')
<div class="forum-page">
    <!-- Forum Header -->
    <div class="forum-header">
        <div class="container">
            <div class="header-content">
                <div class="forum-title">
                    <h1>🎮 Cộng Đồng Game Developer</h1>
                    <p>Nơi kết nối, chia sẻ và học hỏi của cộng đồng game developer Việt Nam</p>
                </div>
                <div class="forum-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['total_posts'] }}</span>
                        <span class="stat-label">Bài viết</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['total_comments'] }}</span>
                        <span class="stat-label">Bình luận</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['total_members'] }}</span>
                        <span class="stat-label">Thành viên</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="forum-actions">
        <div class="container">
            <div class="actions-wrapper">
                <div class="actions-row">
                    <!-- Search -->
                    <div class="forum-search">
                        <form action="{{ route('forum.search') }}" method="GET" class="search-form">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="q" placeholder="Tìm kiếm bài viết, thảo luận..." value="{{ $search }}" class="search-input">
                            @if($search)
                            <button type="button" onclick="window.location='{{ route('forum.index') }}'" class="clear-btn">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </form>
                    </div>
                    
                    <a href="{{ route('forum.posts.create') }}" class="btn btn-primary btn-create">
                        <i class="fas fa-pen"></i>
                        <span class="btn-text">Viết bài</span>
                    </a>
                </div>
                
                <!-- Popular Tags -->
                @if($popularTags->count() > 0)
                <div class="tags-row">
                    <div class="tags-scroll">
                        @foreach($popularTags as $tag)
                        <a href="{{ route('forum.tag', $tag->slug) }}" class="tag-chip">
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <div class="forum-content">
            <!-- Posts Feed -->
            <div class="posts-feed">
                <!-- Sticky Posts -->
                @if($stickyPosts->count() > 0)
                <div class="sticky-posts">
                    <h3 class="section-title">📌 Bài viết quan trọng</h3>
                    @foreach($stickyPosts as $post)
                        @include('lamgame.pages.forum.partials.post-card', ['post' => $post, 'isSticky' => true])
                    @endforeach
                </div>
                @endif

                <!-- Filter & Sort -->
                <div class="posts-header">
                    <div class="posts-title">
                        <div class="title-wrapper">
                            <h2 class="title-text">
                                @if($category)
                                    {{ $categories->where('slug', $category)->first()->icon ?? '📁' }}
                                    {{ $categories->where('slug', $category)->first()->name ?? 'Danh mục' }}
                                @elseif($search)
                                    🔍 Kết quả: "{{ $search }}"
                                @else
                                    📰 Bài viết mới nhất
                                @endif
                            </h2>
                            <span class="posts-count">{{ number_format($posts->total()) }} bài</span>
                        </div>
                    </div>
                    
                    <div class="posts-filters">
                        <select onchange="updateSort(this.value)" class="sort-select">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Phổ biến</option>
                            <option value="activity" {{ $sort === 'activity' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        </select>
                    </div>
                </div>

                <!-- Posts List -->
                <div class="posts-list">
                    @forelse($posts as $post)
                        @include('lamgame.pages.forum.partials.post-card', ['post' => $post])
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <h3>Chưa có bài viết nào</h3>
                            <p>Hãy là người đầu tiên chia sẻ trong cộng đồng!</p>
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

.actions-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
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

.btn-create i {
    font-size: 1rem;
}

.forum-search {
    flex: 1;
}

.search-form {
    position: relative;
    display: flex;
    align-items: center;
    background: #f7fafc;
    border: 2px solid transparent;
    border-radius: 100px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.search-form:focus-within {
    background: white;
    border-color: #6a4c93;
    box-shadow: 0 4px 16px rgba(106, 76, 147, 0.15);
}

.search-icon {
    position: absolute;
    left: 1.25rem;
    color: #a0aec0;
    font-size: 1rem;
    pointer-events: none;
    transition: color 0.2s ease;
}

.search-form:focus-within .search-icon {
    color: #6a4c93;
}

.forum-page .search-input {
    flex: 1;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: none;
    outline: none;
    font-size: 0.95rem;
    background: transparent;
    color: #2d3748;
}

.search-input::placeholder {
    color: #a0aec0;
    font-weight: 400;
}

.clear-btn {
    background: none;
    border: none;
    color: #a0aec0;
    padding: 0 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.clear-btn:hover {
    color: #6a4c93;
    transform: rotate(90deg);
}

.forum-content {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 0;
}

.posts-feed {
    width: 100%;
}

/* Tags in Forum Actions - Compact Keyword Style */
.tags-scroll {
    display: flex;
    gap: 0.375rem;
    overflow-x: auto;
    padding: 0;
    scrollbar-width: none; /* Hide scrollbar for Firefox */
}

.tags-scroll::-webkit-scrollbar {
    display: none; /* Hide scrollbar for Chrome/Safari */
}

.tag-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.15s ease;
    border: none;
    font-size: 0.8rem;
    line-height: 1;
    background: #e2e8f0;
    color: #1a202c;
}

.tag-chip:hover {
    background: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.posts-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1.25rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid #e2e8f0;
}

.posts-title {
    flex: 1;
}

.title-wrapper {
    display: flex;
    align-items: baseline;
    gap: 0.75rem;
}

.title-text {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a202c;
    margin: 0;
    line-height: 1.2;
}

.posts-count {
    font-size: 0.875rem;
    color: #718096;
    font-weight: 600;
    background: #f7fafc;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    white-space: nowrap;
}

.posts-filters {
    display: flex;
    align-items: center;
}

.sort-select {
    padding: 0.625rem 2.5rem 0.625rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: white;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9rem;
    color: #4a5568;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    appearance: none;
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iOCIgdmlld0JveD0iMCAwIDEyIDgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xIDFMNiA2TDExIDEiIHN0cm9rZT0iIzRBNTU2OCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+');
    background-repeat: no-repeat;
    background-position: right 0.875rem center;
}

.sort-select:focus {
    outline: none;
    border-color: #6a4c93;
    box-shadow: 0 4px 12px rgba(106, 76, 147, 0.15);
}

.sort-select:hover {
    border-color: #cbd5e0;
    background-color: #f7fafc;
}

.sticky-posts {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
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

    .actions-row {
        flex-direction: column;
        gap: 0.75rem;
    }

    .forum-search {
        width: 100%;
    }

    .search-form {
        width: 100%;
    }

    .btn-create {
        width: 100%;
        justify-content: center;
        padding: 0.875rem 1.5rem;
    }

    .btn-text {
        font-size: 1rem;
    }

    .tags-row {
        margin: 0 -1rem;
        padding: 0 1rem;
    }

    .tags-scroll {
        gap: 0.375rem;
    }

    .tag-chip {
        font-size: 0.75rem;
        padding: 0.375rem 0.625rem;
    }

    .forum-content {
        padding: 1rem 0;
    }


    .posts-header {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
        align-items: stretch;
    }

    .title-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .title-text {
        font-size: 1.25rem;
    }

    .posts-count {
        font-size: 0.8rem;
    }

    .posts-filters {
        width: 100%;
    }

    .sort-select {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
function updateSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location = url.toString();
}

// Auto-refresh stats every 5 minutes
setInterval(function() {
    // Could implement AJAX stats refresh here
}, 300000);
</script>
@endpush
@endsection
