<div class="post-card {{ isset($isSticky) && $isSticky ? 'sticky-post' : '' }}">
    @if(isset($isSticky) && $isSticky)
    <div class="sticky-indicator">
        <i class="fas fa-thumbtack"></i>
        <span>Bài viết quan trọng</span>
    </div>
    @endif

    <div class="post-header">
        <div class="post-meta">
            <!-- Post Type & Category -->
            <div class="post-badges">
                @if($post->type !== 'discussion')
                <span class="post-type-badge type-{{ $post->type }}">
                    @switch($post->type)
                        @case('idea')
                            💡 Ý tưởng
                            @break
                        @case('question') 
                            ❓ Câu hỏi
                            @break
                        @case('showcase')
                            🎯 Showcase
                            @break
                        @case('job')
                            💼 Tuyển dụng
                            @break
                        @case('review')
                            📚 Review
                            @break
                        @default
                            💬 Thảo luận
                    @endswitch
                </span>
                @endif
                
                <a href="{{ route('forum.category', $post->category->slug) }}" class="category-badge">
                    {{ $post->category->icon }} {{ $post->category->name }}
                </a>
                
                @if($post->is_featured)
                <span class="featured-badge">✨ Nổi bật</span>
                @endif
            </div>
            
            <div class="post-time">
                <i class="far fa-clock"></i>
                <span>{{ $post->time_ago }}</span>
            </div>
        </div>
    </div>

    <div class="post-content">
        <h3 class="post-title">
            <a href="{{ route('forum.posts.show', $post->slug) }}">
                {{ $post->title }}
            </a>
        </h3>
        
        <p class="post-excerpt">{{ $post->excerpt }}</p>
        
        <!-- Tags -->
        @if($post->tags->count() > 0)
        <div class="post-tags">
            @foreach($post->tags->take(4) as $tag)
            <a href="{{ route('forum.tag', $tag->slug) }}" 
               class="post-tag" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">
                {{ $tag->name }}
            </a>
            @endforeach
            
            @if($post->tags->count() > 4)
            <span class="more-tags">+{{ $post->tags->count() - 4 }}</span>
            @endif
        </div>
        @endif
    </div>

    <div class="post-footer">
        <div class="post-author">
            <div class="author-avatar">
                {{ strtoupper(substr($post->author_name, 0, 2)) }}
            </div>
            <div class="author-info">
                <span class="author-name">{{ $post->author_name }}</span>
                <span class="author-posts">{{ $post->author_posts_count ?? 1 }} bài viết</span>
            </div>
        </div>
        
        <div class="post-stats">
            <div class="stat-item">
                <i class="far fa-eye"></i>
                <span>{{ number_format($post->views_count) }}</span>
            </div>
            <div class="stat-item">
                <i class="far fa-comments"></i>
                <span>{{ number_format($post->comments_count) }}</span>
            </div>
            <div class="stat-item">
                <i class="far fa-heart"></i>
                <span>{{ number_format($post->likes_count) }}</span>
            </div>
        </div>
        
        <div class="post-actions">
            <button class="action-btn vote-btn" onclick="votePost({{ $post->id }}, 'like')">
                <i class="far fa-thumbs-up"></i>
            </button>
            <a href="{{ route('forum.posts.show', $post->slug) }}" class="action-btn">
                <i class="far fa-comment-alt"></i>
            </a>
            <button class="action-btn bookmark-btn" onclick="bookmarkPost({{ $post->id }})">
                <i class="far fa-bookmark"></i>
            </button>
        </div>
    </div>
</div>

<style>
.post-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid transparent;
    position: relative;
    contain: layout style paint;
}

.post-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    border-left-color: #667eea;
}

.sticky-post {
    border: 2px solid #ffd700;
    background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
}

.sticky-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #b7791f;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding: 0.5rem;
    background: rgba(183, 121, 31, 0.1);
    border-radius: 6px;
}

.post-header {
    margin-bottom: 1rem;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.post-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.post-type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.type-idea {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #744210;
}

.type-question {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
}

.type-showcase {
    background: linear-gradient(135deg, #4ecdc4, #44a08d);
    color: white;
}

.type-job {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.type-review {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

.category-badge {
    background: #f7fafc;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.category-badge:hover {
    background: #667eea;
    color: white;
}

.featured-badge {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { opacity: 1; }
    50% { opacity: 0.8; }
    100% { opacity: 1; }
}

.post-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #a0aec0;
    font-size: 0.9rem;
    white-space: nowrap;
}

.post-title {
    margin: 0 0 0.75rem 0;
}

.post-title a {
    color: #1a202c;
    text-decoration: none;
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.4;
    display: block;
    transition: color 0.2s ease;
}

.post-title a:hover {
    color: #667eea;
}

.post-excerpt {
    color: #4a5568;
    line-height: 1.6;
    margin: 0 0 1rem 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.post-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.post-tag {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.post-tag:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.more-tags {
    background: #e2e8f0;
    color: #718096;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.post-footer {
    display: grid;
    grid-template-columns: 1fr auto auto;
    align-items: center;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.post-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.author-avatar {
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

.author-info {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    color: #1a202c;
    font-size: 0.9rem;
}

.author-posts {
    color: #718096;
    font-size: 0.8rem;
}

.post-stats {
    display: flex;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #718096;
    font-size: 0.9rem;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    background: none;
    border: none;
    color: #a0aec0;
    padding: 0.625rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    min-width: 48px;
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: #f7fafc;
    color: #667eea;
    transform: scale(1.1);
}

.vote-btn.active {
    color: #667eea;
    background: #667eea20;
}

.bookmark-btn.active {
    color: #ffd700;
    background: #ffd70020;
}

/* Tablet & Mobile Responsive */
@media (max-width: 1024px) {
    .post-title a {
        font-size: 1.3rem;
    }
}

@media (max-width: 768px) {
    .post-card {
        padding: 1rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    
    .post-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .post-meta {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
    }
    
    .post-badges {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .post-type-badge,
    .category-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.625rem;
    }
    
    .post-title a {
        font-size: 1.15rem;
    }
    
    .post-excerpt {
        font-size: 0.9rem;
        -webkit-line-clamp: 3;
    }
    
    .post-footer {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .post-author {
        order: 1;
    }
    
    .post-stats {
        order: 2;
        justify-content: flex-start;
        width: 100%;
        gap: 1.5rem;
    }
    
    .stat-item {
        font-size: 0.95rem;
    }
    
    .post-actions {
        order: 3;
        width: 100%;
        justify-content: center;
        gap: 0.75rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .action-btn {
        flex: 1;
        max-width: 120px;
    }
}

@media (max-width: 480px) {
    .post-title a {
        font-size: 1.1rem;
    }
    
    .post-stats {
        gap: 1rem;
    }
    
    .author-avatar {
        width: 36px;
        height: 36px;
        font-size: 0.8rem;
    }
}
</style>

<script>
function votePost(postId, voteType) {
    // AJAX vote functionality - to be implemented
    console.log('Vote:', postId, voteType);
    
    // Show loading state
    event.target.classList.add('loading');
    
    // Simulate API call
    setTimeout(() => {
        event.target.classList.remove('loading');
        event.target.classList.toggle('active');
    }, 500);
}

function bookmarkPost(postId) {
    // AJAX bookmark functionality
    console.log('Bookmark:', postId);
    event.target.classList.toggle('active');
}
</script>
