@extends('layouts.master')

@section('page_title', $post->meta_title ?: ($post->title . ' - Forum'))
@section('page_description', $post->meta_description ?: $post->excerpt)

@section('content')
<div class="forum-post-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb-nav">
                <a href="{{ route('forum.index') }}">Forum</a>
                <span>›</span>
                <a href="{{ route('forum.category', $post->category->slug) }}">{{ $post->category->name }}</a>
                <span>›</span>
                <span class="current">{{ Str::limit($post->title, 50) }}</span>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="post-layout">
            <!-- Main Content -->
            <div class="post-main">
                <!-- Post Header -->
                <div class="post-header-card">
                    <div class="post-meta">
                        <div class="post-badges">
                            @if($post->type !== 'discussion')
                            <span class="post-type-badge type-{{ $post->type }}">
                                @switch($post->type)
                                    @case('idea') 💡 Ý tưởng @break
                                    @case('question') ❓ Câu hỏi @break
                                    @case('showcase') 🎯 Showcase @break
                                    @case('job') 💼 Tuyển dụng @break
                                    @case('review') 📚 Review @break
                                @endswitch
                            </span>
                            @endif
                            
                            <a href="{{ route('forum.category', $post->category->slug) }}" class="category-badge">
                                {{ $post->category->icon }} {{ $post->category->name }}
                            </a>
                            
                            @if($post->is_featured)
                            <span class="featured-badge">✨ Nổi bật</span>
                            @endif
                            
                            @if($post->is_sticky)
                            <span class="sticky-badge">📌 Quan trọng</span>
                            @endif
                        </div>
                        
                        <div class="post-time">
                            <i class="far fa-clock"></i>
                            <span>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                            @if($post->updated_at != $post->created_at)
                            <span class="updated">• Cập nhật {{ $post->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    <h1 class="post-title">{{ $post->title }}</h1>
                    
                    <!-- Author & Stats -->
                    <div class="post-author-section">
                        <div class="author-info">
                            <div class="author-avatar">
                                {{ strtoupper(substr($post->author_name, 0, 2)) }}
                            </div>
                            <div class="author-details">
                                <h4 class="author-name">{{ $post->author_name }}</h4>
                                <p class="author-meta">Đăng {{ $post->time_ago }}</p>
                            </div>
                        </div>
                        
                        <div class="post-stats">
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>{{ number_format($post->views_count) }} lượt xem</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-comments"></i>
                                <span>{{ number_format($post->comments_count) }} bình luận</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-heart"></i>
                                <span>{{ number_format($post->likes_count) }} thích</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                    <div class="post-tags">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('forum.tag', $tag->slug) }}" 
                           class="post-tag" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Post Content -->
                <div class="post-content-card">
                    <div class="post-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                    
                    <!-- Post Actions -->
                    <div class="post-actions">
                        <button class="action-btn vote-btn" onclick="votePost({{ $post->id }}, 'like')">
                            <i class="far fa-thumbs-up"></i>
                            <span>Thích ({{ $post->likes_count }})</span>
                        </button>
                        <button class="action-btn bookmark-btn" onclick="bookmarkPost({{ $post->id }})">
                            <i class="far fa-bookmark"></i>
                            <span>Lưu</span>
                        </button>
                        <button class="action-btn share-btn" onclick="sharePost()">
                            <i class="far fa-share-alt"></i>
                            <span>Chia sẻ</span>
                        </button>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-header">
                        <h3>💬 Bình luận ({{ $post->comments_count }})</h3>
                        <div class="comments-sort">
                            <select onchange="sortComments(this.value)">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="popular">Phổ biến nhất</option>
                            </select>
                        </div>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="add-comment-form">
                        @auth('customer')
                            <div class="comment-author-info">
                                <div class="author-avatar">
                                    {{ strtoupper(substr(auth('customer')->user()->first_name, 0, 1) . substr(auth('customer')->user()->last_name, 0, 1)) }}
                                </div>
                                <div class="author-details">
                                    <span class="author-name">{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}</span>
                                    <span class="author-status">Thành viên</span>
                                </div>
                            </div>
                            
                            <form action="{{ route('forum.comments.store', $post) }}" method="POST" class="comment-form">
                                @csrf
                                <div class="form-group">
                                    <textarea name="content" 
                                             placeholder="Chia sẻ suy nghĩ của bạn về bài viết này..." 
                                             required 
                                             class="form-textarea {{ $errors->has('content') ? 'error' : '' }}" 
                                             rows="4">{{ old('content') }}</textarea>
                                    @if($errors->has('content'))
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $errors->first('content') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="comment-form-actions">
                                    <div class="form-tips">
                                        <small>💡 Hãy thể hiện quan điểm một cách văn minh và tôn trọng</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i>
                                        Đăng bình luận
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="login-prompt">
                                <div class="login-prompt-icon">🔐</div>
                                <div class="login-prompt-content">
                                    <h4>Tham gia thảo luận</h4>
                                    <p>Bạn cần đăng nhập để có thể bình luận và trao đổi với cộng đồng</p>
                                    <div class="login-prompt-actions">
                                        <a href="{{ route('auth.login') }}" class="btn btn-primary">
                                            <i class="fas fa-sign-in-alt"></i>
                                            Đăng nhập ngay
                                        </a>
                                        <a href="{{ route('auth.register') }}" class="btn btn-outline">
                                            <i class="fas fa-user-plus"></i>
                                            Tạo tài khoản
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- Comments List -->
                    <div class="comments-list">
                        @forelse($post->rootComments as $comment)
                            @include('lamgame.pages.forum.partials.comment', ['comment' => $comment])
                        @empty
                            <div class="no-comments">
                                <div class="no-comments-icon">💭</div>
                                <p>Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ ý kiến!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="post-sidebar">
                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="sidebar-card">
                    <h4 class="sidebar-title">Bài viết liên quan</h4>
                    <div class="related-posts">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="related-post">
                            <h5><a href="{{ route('forum.posts.show', $relatedPost->slug) }}">{{ $relatedPost->title }}</a></h5>
                            <div class="related-meta">
                                <span>{{ $relatedPost->time_ago }}</span>
                                <span>• {{ $relatedPost->comments_count }} bình luận</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Author's Other Posts -->
                @if($authorPosts->count() > 0)
                <div class="sidebar-card">
                    <h4 class="sidebar-title">Bài viết khác của {{ $post->author_name }}</h4>
                    <div class="author-posts">
                        @foreach($authorPosts as $authorPost)
                        <div class="author-post">
                            <h5><a href="{{ route('forum.posts.show', $authorPost->slug) }}">{{ $authorPost->title }}</a></h5>
                            <div class="author-meta">
                                <span>{{ $authorPost->time_ago }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="sidebar-card">
                    <h4 class="sidebar-title">Thao tác nhanh</h4>
                    <div class="quick-actions">
                        <a href="{{ route('forum.posts.create') }}" class="quick-action">
                            <i class="fas fa-plus"></i>
                            Tạo bài viết mới
                        </a>
                        <a href="{{ route('forum.posts.create', ['type' => 'question']) }}" class="quick-action">
                            <i class="fas fa-question-circle"></i>
                            Đặt câu hỏi
                        </a>
                        <a href="{{ route('forum.index') }}" class="quick-action">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại forum
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.forum-post-page {
    background: #f8fafc;
    min-height: 100vh;
}

.breadcrumb-section {
    background: white;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.breadcrumb-nav a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb-nav a:hover {
    text-decoration: underline;
}

.breadcrumb-nav .current {
    color: #4a5568;
    font-weight: 500;
}

.post-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 2rem;
    padding: 2rem 0;
}

.post-header-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.post-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.post-type-badge, .category-badge, .featured-badge, .sticky-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
}

.post-time {
    color: #718096;
    font-size: 0.9rem;
    white-space: nowrap;
}

.post-time .updated {
    opacity: 0.7;
}

.post-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1a202c;
    line-height: 1.3;
    margin-bottom: 1.5rem;
}

.post-author-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.author-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.author-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

.author-meta {
    color: #718096;
    font-size: 0.9rem;
    margin: 0;
}

.post-stats {
    display: flex;
    gap: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
    font-size: 0.9rem;
}

.post-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.post-tag {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.post-tag:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.post-content-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
}

.post-content {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #2d3748;
    margin-bottom: 2rem;
}

.post-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
}

.action-btn:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-1px);
}

.comments-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.comments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.comments-header h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

.comments-sort select {
    padding: 0.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    background: white;
}

.add-comment-form {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.comment-author-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.comment-author-info .author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.comment-author-info .author-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.comment-author-info .author-name {
    font-weight: 600;
    color: #1a202c;
    font-size: 0.95rem;
}

.comment-author-info .author-status {
    font-size: 0.8rem;
    color: #718096;
}

.comment-form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.form-tips {
    color: #718096;
}

.login-prompt {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 12px;
    border: 2px dashed #cbd5e0;
}

.login-prompt-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.7;
}

.login-prompt-content h4 {
    color: #1a202c;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.login-prompt-content p {
    color: #4a5568;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.login-prompt-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-outline {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: #667eea;
}

.form-textarea {
    resize: vertical;
    font-family: inherit;
}

.form-textarea.error {
    border-color: #e53e3e;
    background-color: #fed7d7;
}

.error-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: #fed7d7;
    border-radius: 6px;
    border-left: 4px solid #e53e3e;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.no-comments {
    text-align: center;
    padding: 3rem;
    color: #718096;
}

.no-comments-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.post-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.sidebar-card {
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

.related-post, .author-post {
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.related-post:last-child, .author-post:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.related-post h5, .author-post h5 {
    margin: 0 0 0.5rem 0;
    font-size: 0.95rem;
    line-height: 1.4;
}

.related-post h5 a, .author-post h5 a {
    color: #1a202c;
    text-decoration: none;
}

.related-post h5 a:hover, .author-post h5 a:hover {
    color: #667eea;
}

.related-meta, .author-meta {
    color: #718096;
    font-size: 0.8rem;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.quick-action:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .post-layout {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .post-sidebar {
        position: static;
        order: -1;
    }
    
    .post-header-card {
        padding: 1.5rem;
    }
    
    .post-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .post-title {
        font-size: 1.5rem;
    }
    
    .post-author-section {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .post-actions {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .action-btn {
        flex: 1;
        justify-content: center;
        min-width: 0;
    }
    
    .comment-author-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
        text-align: left;
    }
    
    .comment-form-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .login-prompt {
        padding: 1.5rem;
    }
    
    .login-prompt-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .login-prompt-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
function votePost(postId, voteType) {
    console.log('Vote post:', postId, voteType);
    // Implement AJAX voting
}

function bookmarkPost(postId) {
    console.log('Bookmark post:', postId);
    // Implement bookmark functionality
}

function sharePost() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link đã được sao chép!');
        });
    }
}

function sortComments(sortType) {
    console.log('Sort comments:', sortType);
    // Implement comment sorting
}
</script>
@endpush
@endsection
