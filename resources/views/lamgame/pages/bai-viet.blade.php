@extends('layouts.master')

@section('page_title', $page_title ?? (isset($bai_viet) ? $bai_viet['title'] . ' - Cộng đồng Làm Game' : $post['title'] . ' - Cộng đồng Làm Game'))

@section('page_description', $page_description ?? (isset($bai_viet) ? 'Thảo luận về ' . $bai_viet['title'] : 'Thảo luận về ' . $post['title']))

@section('content')
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ route('shop.home.index') }}">Trang chủ</a>
                <span class="separator">›</span>
                <a href="{{ route('forum.index') }}">Forum</a>
                <span class="separator">›</span>
                <span class="current">{{ $post['title'] }}</span>
            </nav>
        </div>
    </section>

    <!-- Post Content -->
    <section class="post-detail-section">
        <div class="container">
            <div class="post-layout">
                <!-- Main Content -->
                <div class="main-content">
                    <div class="post-header">
                        <div class="post-meta">
                            <span class="category">{{ $post['category'] }}</span>
                            <span class="date">{{ \Carbon\Carbon::parse($post['created_at'])->format('d/m/Y H:i') }}</span>
                            <span class="views">👁️ {{ $post['views'] }} lượt xem</span>
                        </div>
                        <h1 class="post-title">{{ $post['title'] }}</h1>
                        <div class="author-info">
                            <div class="author-avatar">👤</div>
                            <div class="author-details">
                                <div class="author-name">{{ $post['author'] }}</div>
                                <div class="author-role">Game Developer</div>
                            </div>
                        </div>
                    </div>

                    <div class="post-content">
                        <p>{{ $post['content'] ?? 'Nội dung chi tiết của bài viết sẽ được hiển thị ở đây. Đây là nơi tác giả chia sẻ kinh nghiệm, đặt câu hỏi hoặc thảo luận về các vấn đề liên quan đến game development.' }}</p>
                        
                        <h3>Chi tiết vấn đề</h3>
                        <p>Trong quá trình phát triển game Unity, việc tối ưu performance là một trong những thách thức lớn nhất mà developer phải đối mặt. Đặc biệt khi phát triển game mobile, mọi frame rate đều quan trọng.</p>
                        
                        <h3>Một số kỹ thuật tối ưu phổ biến</h3>
                        <ul>
                            <li><strong>Object Pooling:</strong> Tái sử dụng objects thay vì destroy/instantiate liên tục</li>
                            <li><strong>Texture Compression:</strong> Sử dụng định dạng texture phù hợp với từng platform</li>
                            <li><strong>LOD (Level of Detail):</strong> Giảm chi tiết của objects ở khoảng cách xa</li>
                            <li><strong>Culling:</strong> Loại bỏ các objects không cần thiết khỏi quá trình render</li>
                        </ul>
                        
                        <p>Các bạn có kinh nghiệm gì khác về vấn đề này không? Rất mong được nghe chia sẻ!</p>
                    </div>

                    <!-- Post Actions -->
                    <div class="post-actions">
                        <button class="action-btn like-btn" onclick="likePost({{ $post['id'] }})">
                            👍 Thích (15)
                        </button>
                        <button class="action-btn share-btn" onclick="sharePost({{ $post['id'] }})">
                            📤 Chia sẻ
                        </button>
                        <button class="action-btn bookmark-btn" onclick="bookmarkPost({{ $post['id'] }})">
                            🔖 Lưu
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section">
                        <h3 class="comments-title">💬 Bình luận ({{ count($comments) }})</h3>
                        
                        <!-- Add Comment Form -->
                        <div class="add-comment">
                            <form class="comment-form" onsubmit="submitComment(event)">
                                <div class="comment-input">
                                    <div class="commenter-avatar">👤</div>
                                    <textarea name="content" placeholder="Chia sẻ ý kiến của bạn..." rows="4" required></textarea>
                                </div>
                                <div class="comment-actions">
                                    <div class="comment-options">
                                        <label class="option">
                                            <input type="checkbox" name="anonymous">
                                            <span>Đăng ẩn danh</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                                </div>
                                <input type="hidden" name="post_id" value="{{ $post['id'] }}">
                                <input type="hidden" name="author" value="Người dùng">
                            </form>
                        </div>

                        <!-- Comments List -->
                        <div class="comments-list">
                            @foreach($comments as $comment)
                            <div class="comment-item">
                                <div class="comment-header">
                                    <div class="commenter-avatar">👤</div>
                                    <div class="commenter-info">
                                        <div class="commenter-name">{{ $comment['author'] }}</div>
                                        <div class="comment-time">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>{{ $comment['content'] }}</p>
                                </div>
                                <div class="comment-actions">
                                    <button class="comment-action" onclick="likeComment({{ $loop->index }})">
                                        👍 Thích ({{ rand(1, 8) }})
                                    </button>
                                    <button class="comment-action" onclick="replyComment({{ $loop->index }})">
                                        💬 Trả lời
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Load More Comments -->
                        <div class="load-more-comments">
                            <button class="btn btn-outline" onclick="loadMoreComments()">
                                Xem thêm bình luận
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Author Card -->
                    <div class="author-card">
                        <h4>👤 Tác giả</h4>
                        <div class="author-profile">
                            <div class="author-avatar-large">👤</div>
                            <div class="author-info">
                                <div class="author-name">{{ $post['author'] }}</div>
                                <div class="author-title">Unity Developer</div>
                                <div class="author-stats">
                                    <span>📝 {{ rand(15, 50) }} bài viết</span>
                                    <span>⭐ {{ rand(100, 500) }} điểm</span>
                                </div>
                            </div>
                        </div>
                        <div class="author-actions">
                            <button class="btn btn-primary btn-small">Theo dõi</button>
                            <button class="btn btn-outline btn-small">Nhắn tin</button>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <div class="related-posts">
                        <h4>📖 Bài viết liên quan</h4>
                        <div class="related-list">
                            <div class="related-item">
                                <h5><a href="#">Tối ưu rendering trong Unity 2023</a></h5>
                                <div class="related-meta">
                                    <span>👤 TechExpert</span>
                                    <span>👁️ 245 views</span>
                                </div>
                            </div>
                            <div class="related-item">
                                <h5><a href="#">Best practices cho mobile game performance</a></h5>
                                <div class="related-meta">
                                    <span>👤 MobileDev</span>
                                    <span>👁️ 189 views</span>
                                </div>
                            </div>
                            <div class="related-item">
                                <h5><a href="#">Unity Profiler: Cách sử dụng hiệu quả</a></h5>
                                <div class="related-meta">
                                    <span>👤 UnityMaster</span>
                                    <span>👁️ 156 views</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Community Guidelines -->
                    <div class="guidelines-widget">
                        <h4>📋 Quy tắc thảo luận</h4>
                        <ul>
                            <li>Tôn trọng ý kiến khác biệt</li>
                            <li>Đưa ra lời khuyên xây dựng</li>
                            <li>Không spam hay quảng cáo</li>
                            <li>Chia sẻ kiến thức hữu ích</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .breadcrumb-section {
            background: #f8f9fa;
            padding: 20px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .separator {
            color: #a0aec0;
        }
        
        .current {
            color: #4a5568;
            font-weight: 500;
        }
        
        .post-detail-section {
            padding: 40px 0;
        }
        
        .post-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
        }
        
        .post-header {
            margin-bottom: 30px;
        }
        
        .post-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .post-meta .category {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 500;
        }
        
        .post-meta .date, .post-meta .views {
            color: #666;
        }
        
        .post-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .author-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .author-name {
            font-weight: 600;
            color: #333;
        }
        
        .author-role {
            color: #666;
            font-size: 0.9rem;
        }
        
        .post-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .post-content h3 {
            color: #333;
            margin: 30px 0 15px;
            font-size: 1.4rem;
        }
        
        .post-content ul {
            margin: 20px 0;
        }
        
        .post-content li {
            margin-bottom: 10px;
        }
        
        .post-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .action-btn {
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .action-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }
        
        .like-btn:hover {
            background: #e6f3ff;
        }
        
        .comments-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .comments-title {
            margin-bottom: 25px;
            color: #333;
        }
        
        .add-comment {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        .comment-input {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .commenter-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .comment-input textarea {
            flex: 1;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
        }
        
        .comment-input textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .comment-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .comment-options .option {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            cursor: pointer;
        }
        
        .comments-list {
            margin-bottom: 30px;
        }
        
        .comment-item {
            padding: 20px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .comment-header {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .commenter-info .commenter-name {
            font-weight: 600;
            color: #333;
        }
        
        .comment-time {
            color: #666;
            font-size: 0.85rem;
        }
        
        .comment-content {
            margin-left: 55px;
            margin-bottom: 10px;
        }
        
        .comment-item .comment-actions {
            margin-left: 55px;
            display: flex;
            gap: 15px;
        }
        
        .comment-action {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .comment-action:hover {
            background: #f1f5f9;
            color: #667eea;
        }
        
        .load-more-comments {
            text-align: center;
        }
        
        /* Sidebar Styles */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 30px;
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        
        .author-card, .related-posts, .guidelines-widget {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .author-card h4, .related-posts h4, .guidelines-widget h4 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .author-profile {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .author-avatar-large {
            width: 60px;
            height: 60px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .author-card .author-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .author-title {
            color: #667eea;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .author-stats {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .author-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            padding: 8px 16px;
            font-size: 0.85rem;
        }
        
        .related-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .related-item h5 {
            margin-bottom: 8px;
        }
        
        .related-item a {
            color: #333;
            text-decoration: none;
            line-height: 1.4;
        }
        
        .related-item a:hover {
            color: #667eea;
        }
        
        .related-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #666;
        }
        
        .guidelines-widget ul {
            list-style: none;
            padding: 0;
        }
        
        .guidelines-widget li {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            color: #666;
        }
        
        .guidelines-widget li:before {
            content: '✓';
            color: #667eea;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-outline:hover {
            background: #667eea;
            color: white;
        }
        
        @media (max-width: 768px) {
            .post-layout {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .sidebar {
                position: static;
                order: 2;
            }
            
            .post-title {
                font-size: 2rem;
            }
            
            .post-actions {
                flex-wrap: wrap;
            }
            
            .comment-input {
                flex-direction: column;
            }
            
            .comment-actions {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .author-actions {
                flex-direction: column;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function likePost(postId) {
            console.log('Like post:', postId);
            alert('Đã thích bài viết này!');
        }
        
        function sharePost(postId) {
            console.log('Share post:', postId);
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Đã copy link bài viết!');
                });
            }
        }
        
        function bookmarkPost(postId) {
            console.log('Bookmark post:', postId);
            alert('Đã lưu bài viết!');
        }
        
        function submitComment(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());
            
            console.log('Submit comment:', data);
            
            // Here you would send to server via AJAX
            alert('Bình luận đã được gửi! Cảm ơn bạn đã chia sẻ.');
            
            // Clear form
            event.target.reset();
        }
        
        function likeComment(commentIndex) {
            console.log('Like comment:', commentIndex);
            alert('Đã thích bình luận này!');
        }
        
        function replyComment(commentIndex) {
            console.log('Reply to comment:', commentIndex);
            alert('Tính năng trả lời bình luận đang được phát triển!');
        }
        
        function loadMoreComments() {
            console.log('Load more comments');
            alert('Đang tải thêm bình luận...');
        }
        
        // Auto-resize textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });
        });
    </script>
    @endpush
@endsection
