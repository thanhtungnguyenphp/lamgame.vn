<div class="comment" data-comment-id="{{ $comment->id }}" style="margin-left: {{ $comment->depth * 2 }}rem;">
    <div class="comment-card">
        <div class="comment-header">
            <div class="comment-author">
                <div class="author-avatar">
                    <img src="{{ $comment->avatar_url }}" alt="{{ $comment->author_name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="avatar-fallback" style="display: none;">
                        {{ strtoupper(substr($comment->author_name, 0, 2)) }}
                    </div>
                </div>
                <div class="author-info">
                    <h5 class="author-name">{{ $comment->author_name }}</h5>
                    <div class="comment-meta">
                        <span class="comment-time">{{ $comment->time_ago }}</span>
                        @if($comment->isReply())
                        <span class="reply-indicator">• Trả lời</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="comment-actions">
                <button class="comment-vote-btn" onclick="voteComment({{ $comment->id }}, 'like')" 
                        title="Thích bình luận">
                    <i class="far fa-thumbs-up"></i>
                    <span>{{ $comment->likes_count }}</span>
                </button>
                
                <button class="comment-reply-btn" onclick="toggleReplyForm({{ $comment->id }})" 
                        title="Trả lời">
                    <i class="far fa-reply"></i>
                    <span>Trả lời</span>
                </button>
                
                <div class="comment-menu">
                    <button class="menu-btn" onclick="toggleCommentMenu({{ $comment->id }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="menu-dropdown" id="menu-{{ $comment->id }}" style="display: none;">
                        <button onclick="reportComment({{ $comment->id }})">
                            <i class="fas fa-flag"></i> Báo cáo
                        </button>
                        <button onclick="copyCommentLink({{ $comment->id }})">
                            <i class="fas fa-link"></i> Sao chép link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="comment-content">
            <p>{{ $comment->content }}</p>
        </div>

        @if($comment->likes_count > 0)
        <div class="comment-likes">
            <i class="fas fa-heart"></i>
            <span>{{ $comment->likes_count }} người thích</span>
        </div>
        @endif
    </div>

    <!-- Reply Form -->
    <div class="reply-form" id="reply-form-{{ $comment->id }}" style="display: none;">
        <form action="{{ route('forum.comments.store', $comment->post) }}" method="POST" class="comment-form">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            
            <div class="form-header">
                <div class="reply-avatar">
                    {{ strtoupper(substr(session('user_name', 'U'), 0, 2)) }}
                </div>
                <div class="form-fields">
                    <div class="form-row">
                        <input type="text" name="author_name" placeholder="Tên của bạn" required 
                               value="{{ session('user_name') }}" class="form-input">
                        <input type="email" name="author_email" placeholder="Email" required 
                               value="{{ session('user_email') }}" class="form-input">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <textarea name="content" placeholder="Viết trả lời của bạn..." required 
                          class="form-textarea" rows="3"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-cancel" onclick="toggleReplyForm({{ $comment->id }})">
                    Hủy
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-reply"></i>
                    Trả lời
                </button>
            </div>
        </form>
    </div>

    <!-- Replies -->
    @if($comment->publishedReplies->count() > 0)
    <div class="comment-replies">
        <div class="replies-header">
            <button class="toggle-replies" onclick="toggleReplies({{ $comment->id }})" id="toggle-{{ $comment->id }}">
                <i class="fas fa-chevron-down"></i>
                <span>{{ $comment->publishedReplies->count() }} trả lời</span>
            </button>
        </div>
        
        <div class="replies-list" id="replies-{{ $comment->id }}">
            @foreach($comment->publishedReplies as $reply)
                @include('lamgame.pages.forum.partials.comment', ['comment' => $reply])
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.comment {
    margin-bottom: 1.5rem;
    position: relative;
}

.comment::before {
    content: '';
    position: absolute;
    left: -1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}

.comment[style*="margin-left: 2rem"]::before {
    background: #cbd5e0;
}

.comment[style*="margin-left: 4rem"]::before {
    background: #a0aec0;
}

.comment-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.2s ease;
}

.comment-card:hover {
    border-color: #cbd5e0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.author-avatar {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
}

.author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-fallback {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
}

.author-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1a202c;
    margin: 0;
}

.comment-meta {
    color: #718096;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.reply-indicator {
    color: #667eea;
    font-weight: 500;
}

.comment-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}

.comment-vote-btn, .comment-reply-btn, .menu-btn {
    background: none;
    border: none;
    color: #718096;
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.85rem;
}

.comment-vote-btn:hover, .comment-reply-btn:hover, .menu-btn:hover {
    background: #e2e8f0;
    color: #667eea;
}

.comment-vote-btn.active {
    color: #667eea;
    background: #667eea20;
}

.comment-menu {
    position: relative;
}

.menu-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10;
    min-width: 150px;
    overflow: hidden;
}

.menu-dropdown button {
    width: 100%;
    background: none;
    border: none;
    padding: 0.75rem;
    text-align: left;
    cursor: pointer;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4a5568;
}

.menu-dropdown button:hover {
    background: #f8fafc;
}

.comment-content p {
    color: #2d3748;
    line-height: 1.6;
    margin: 0;
}

.comment-likes {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
    font-size: 0.85rem;
}

.comment-likes i {
    color: #e53e3e;
}

.reply-form {
    margin-top: 1rem;
    padding: 1rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.form-header {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.reply-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.form-fields {
    flex: 1;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.form-group {
    margin-bottom: 0.75rem;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.9rem;
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

.form-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.btn-cancel {
    background: #f8fafc;
    color: #4a5568;
    border: 1px solid #e2e8f0;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.comment-replies {
    margin-top: 1rem;
}

.replies-header {
    margin-bottom: 0.75rem;
}

.toggle-replies {
    background: none;
    border: none;
    color: #667eea;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.toggle-replies:hover {
    background: #667eea10;
}

.toggle-replies i {
    transition: transform 0.2s ease;
}

.toggle-replies.collapsed i {
    transform: rotate(-90deg);
}

.replies-list.collapsed {
    display: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .comment {
        margin-left: 0 !important;
    }
    
    .comment::before {
        display: none;
    }
    
    .comment-header {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
    }
    
    .comment-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        justify-content: stretch;
    }
    
    .btn {
        flex: 1;
        justify-content: center;
    }
}
</style>

<script>
function voteComment(commentId, voteType) {
    console.log('Vote comment:', commentId, voteType);
    // Implement AJAX comment voting
    const btn = event.target.closest('.comment-vote-btn');
    btn.classList.toggle('active');
}

function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    const isVisible = form.style.display !== 'none';
    
    // Hide all other reply forms
    document.querySelectorAll('.reply-form').forEach(f => f.style.display = 'none');
    
    // Toggle current form
    form.style.display = isVisible ? 'none' : 'block';
    
    if (!isVisible) {
        // Focus on textarea when showing form
        const textarea = form.querySelector('textarea');
        setTimeout(() => textarea.focus(), 100);
    }
}

function toggleReplies(commentId) {
    const repliesList = document.getElementById(`replies-${commentId}`);
    const toggleBtn = document.getElementById(`toggle-${commentId}`);
    
    repliesList.classList.toggle('collapsed');
    toggleBtn.classList.toggle('collapsed');
    
    const icon = toggleBtn.querySelector('i');
    const span = toggleBtn.querySelector('span');
    
    if (repliesList.classList.contains('collapsed')) {
        icon.className = 'fas fa-chevron-right';
        span.textContent = span.textContent.replace('Ẩn', 'Hiện');
    } else {
        icon.className = 'fas fa-chevron-down';
        span.textContent = span.textContent.replace('Hiện', 'Ẩn');
    }
}

function toggleCommentMenu(commentId) {
    const menu = document.getElementById(`menu-${commentId}`);
    const isVisible = menu.style.display !== 'none';
    
    // Hide all menus
    document.querySelectorAll('.menu-dropdown').forEach(m => m.style.display = 'none');
    
    // Toggle current menu
    menu.style.display = isVisible ? 'none' : 'block';
}

function reportComment(commentId) {
    console.log('Report comment:', commentId);
    alert('Cảm ơn báo cáo của bạn. Chúng tôi sẽ xem xét sớm nhất.');
    toggleCommentMenu(commentId);
}

function copyCommentLink(commentId) {
    const url = `${window.location.href}#comment-${commentId}`;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link bình luận đã được sao chép!');
    });
    toggleCommentMenu(commentId);
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.comment-menu')) {
        document.querySelectorAll('.menu-dropdown').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});
</script>
