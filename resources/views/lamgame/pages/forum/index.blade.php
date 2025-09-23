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
            <div class="actions-row">
                <div class="action-buttons">
                    <a href="{{ route('forum.posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Tạo bài viết mới
                    </a>
                    <a href="{{ route('forum.posts.create', ['type' => 'idea']) }}" class="btn btn-outline">
                        <i class="fas fa-lightbulb"></i>
                        Chia sẻ ý tưởng
                    </a>
                    <a href="{{ route('forum.posts.create', ['type' => 'question']) }}" class="btn btn-outline">
                        <i class="fas fa-question-circle"></i>
                        Đặt câu hỏi
                    </a>
                </div>
                
                <!-- Search -->
                <div class="forum-search">
                    <form action="{{ route('forum.search') }}" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Tìm kiếm bài viết..." value="{{ $search }}" class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="forum-content">
            <!-- Categories Sidebar -->
            <div class="categories-sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Danh mục</h3>
                    <div class="categories-list">
                        <a href="{{ route('forum.index') }}" class="category-item {{ !$category ? 'active' : '' }}">
                            <span class="category-icon">📋</span>
                            <div class="category-info">
                                <div class="category-name">Tất cả</div>
                                <div class="category-count">{{ $stats['total_posts'] }}</div>
                            </div>
                        </a>
                        
                        @foreach($categories as $cat)
                        <a href="{{ route('forum.category', $cat->slug) }}" 
                           class="category-item {{ $category === $cat->slug ? 'active' : '' }} {{ $cat->is_featured ? 'featured' : '' }}">
                            <span class="category-icon">{{ $cat->icon }}</span>
                            <div class="category-info">
                                <div class="category-name">{{ $cat->name }}</div>
                                <div class="category-count">{{ $cat->posts_count }}</div>
                            </div>
                            @if($cat->is_featured)
                            <span class="featured-badge">Hot</span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Popular Tags -->
                @if($popularTags->count() > 0)
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Tags phổ biến</h3>
                    <div class="popular-tags">
                        @foreach($popularTags as $tag)
                        <a href="{{ route('forum.tag', $tag->slug) }}" 
                           class="tag-item" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">
                            {{ $tag->name }}
                            <span class="tag-count">{{ $tag->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

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
                        <h2>
                            @if($category)
                                {{ $categories->where('slug', $category)->first()->name ?? 'Danh mục' }}
                            @elseif($search)
                                Kết quả tìm kiếm: "{{ $search }}"
                            @else
                                Bài viết mới nhất
                            @endif
                        </h2>
                        <span class="posts-count">{{ $posts->total() }} bài viết</span>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
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
    padding: 2rem 0;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.actions-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-outline {
    background: white;
    color: #667eea;
    border-color: #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

.search-form {
    display: flex;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    min-width: 300px;
}

.search-input {
    flex: 1;
    padding: 0.875rem 1rem;
    border: none;
    outline: none;
    font-size: 1rem;
}

.search-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 0 1.5rem;
    cursor: pointer;
    transition: background 0.2s ease;
}

.search-btn:hover {
    background: #5a67d8;
}

.forum-content {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    padding: 2rem 0;
}

.categories-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.sidebar-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}

.sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
}

.categories-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    text-decoration: none;
    color: #4a5568;
    transition: all 0.2s ease;
    position: relative;
}

.category-item:hover {
    background: #f7fafc;
    color: #667eea;
}

.category-item.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.category-item.featured {
    border: 2px solid #ffd700;
    background: linear-gradient(135deg, #fff9e6, #fff3cd);
}

.category-icon {
    font-size: 1.2rem;
}

.category-info {
    flex: 1;
}

.category-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.category-count {
    font-size: 0.8rem;
    opacity: 0.7;
}

.featured-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff6b35;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
}

.popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag-item {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.tag-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.tag-count {
    background: rgba(0,0,0,0.1);
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.7rem;
}

.posts-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.posts-title h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

.posts-count {
    font-size: 0.9rem;
    color: #718096;
    margin-left: 0.5rem;
}

.sort-select {
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    font-weight: 500;
    cursor: pointer;
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

    .actions-row {
        flex-direction: column;
        gap: 1rem;
    }

    .action-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }

    .forum-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .categories-sidebar {
        position: static;
        order: 2;
    }

    .posts-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
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
