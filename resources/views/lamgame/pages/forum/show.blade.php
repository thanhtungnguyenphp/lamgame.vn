@extends('layouts.master')

@section('page_title', $post->meta_title ?: ($post->title . ' - Forum'))
@section('page_description', $post->meta_description ?: $post->excerpt)

@section('content')
<div class="fp-page">
    {{-- Breadcrumb --}}
    <div class="fp-bread">
        <div class="container">
            <nav class="fp-bread-nav">
                <a href="{{ route('forum.index') }}">Forum</a>
                <span>›</span>
                <a href="{{ route('forum.category', $post->category->slug) }}">{{ $post->category->name }}</a>
                <span>›</span>
                <span>{{ Str::limit($post->title, 50) }}</span>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="fp-wrap">
            {{-- Post --}}
            <article class="fp-post">
                {{-- Meta --}}
                <div class="fp-meta">
                    @if($post->type !== 'discussion')
                    <span class="fm-type fm-type-{{ $post->type }}">
                        @switch($post->type)
                            @case('idea') 💡 Ý tưởng @break
                            @case('question') ❓ Câu hỏi @break
                            @case('showcase') 🎯 Showcase @break
                            @case('job') 💼 Tuyển dụng @break
                            @case('review') 📚 Review @break
                        @endswitch
                    </span>
                    @endif
                    <a href="{{ route('forum.category', $post->category->slug) }}" class="fp-cat">{{ $post->category->icon ?? '' }} {{ $post->category->name }}</a>
                    @if($post->is_featured)<span class="fp-badge">✨ Nổi bật</span>@endif
                    @if($post->is_sticky)<span class="fp-badge">📌 Ghim</span>@endif
                </div>

                <h1 class="fp-title">{{ $post->title }}</h1>

                {{-- Author row --}}
                <div class="fp-author-row">
                    <div class="fp-author">
                        <div class="fm-avatar">{{ strtoupper(substr($post->author_name, 0, 1)) }}</div>
                        <div>
                            <span class="fp-author-name">{{ $post->author_name }}</span>
                            <span class="fp-author-time">{{ $post->created_at->format('d/m/Y H:i') }}
                                @if($post->updated_at->gt($post->created_at))
                                · Sửa {{ $post->updated_at->diffForHumans() }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="fp-stats">
                        <span><i class="far fa-eye"></i> {{ number_format($post->views_count) }}</span>
                        <span><i class="far fa-comment"></i> {{ number_format($post->comments_count) }}</span>
                        <span><i class="far fa-heart"></i> {{ number_format($post->likes_count) }}</span>
                    </div>
                </div>

                {{-- Tags --}}
                @if($post->tags->count() > 0)
                <div class="fp-tags">
                    @foreach($post->tags as $tag)
                    <a href="{{ route('forum.tag', $tag->slug) }}" class="fm-tag" style="background:{{ $tag->color }}20;color:{{ $tag->color }};">{{ $tag->name }}</a>
                    @endforeach
                </div>
                @endif

                {{-- Content --}}
                <div class="fp-content">
                    {!! nl2br(e($post->content)) !!}
                </div>

                {{-- Actions --}}
                <div class="fp-actions">
                    <button class="fp-action-btn" onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}else{navigator.clipboard.writeText(location.href).then(()=>alert('Đã sao chép link!'))}">
                        <i class="fas fa-share-alt"></i> Chia sẻ
                    </button>
                    @auth('customer')
                    <button class="fp-action-btn fp-bookmark-btn {{ $post->isBookmarkedBy(auth('customer')->id()) ? 'active' : '' }}" onclick="toggleBookmark(this)" data-post="{{ $post->slug }}">
                        <i class="{{ $post->isBookmarkedBy(auth('customer')->id()) ? 'fas' : 'far' }} fa-bookmark"></i>
                        <span>{{ $post->isBookmarkedBy(auth('customer')->id()) ? 'Đã lưu' : 'Lưu' }}</span>
                    </button>
                    @endauth
                </div>
            </article>

            {{-- Comments --}}
            <section class="fp-comments">
                <h3 class="fp-comments-title">💬 Bình luận ({{ $post->comments_count }})</h3>

                {{-- Comment form --}}
                <div class="fp-comment-form">
                    @auth('customer')
                    <form action="{{ route('forum.comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="fp-form-row">
                            <div class="fm-avatar">{{ strtoupper(substr(auth('customer')->user()->first_name, 0, 1)) }}</div>
                            <div class="fp-form-body">
                                <textarea name="content" placeholder="Chia sẻ suy nghĩ của bạn..." required rows="3" maxlength="2000" class="fp-textarea {{ $errors->has('content') ? 'error' : '' }}">{{ old('content') }}</textarea>
                                @if($errors->has('content'))
                                <p class="fp-form-error">{{ $errors->first('content') }}</p>
                                @endif
                                <div class="fp-form-footer">
                                    <small class="fp-form-hint">Hãy trao đổi văn minh và tôn trọng</small>
                                    <button type="submit" class="fm-btn-create"><i class="fas fa-paper-plane"></i> Đăng</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="fp-login-prompt">
                        <p>🔐 <a href="{{ route('auth.login') }}">Đăng nhập</a> hoặc <a href="{{ route('auth.register') }}">tạo tài khoản</a> để bình luận</p>
                    </div>
                    @endauth
                </div>

                {{-- Comments list --}}
                <div class="fp-comments-list">
                    @forelse($post->rootComments as $comment)
                        @include('lamgame.pages.forum.partials.comment', ['comment' => $comment])
                    @empty
                    <div class="fp-no-comments">
                        <p>💭 Chưa có bình luận. Hãy là người đầu tiên chia sẻ ý kiến!</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- Related posts --}}
            @if($relatedPosts->count() > 0)
            <section class="fp-related">
                <h3 class="fp-related-title">Bài viết liên quan</h3>
                @foreach($relatedPosts as $rp)
                <a href="{{ route('forum.posts.show', $rp->slug) }}" class="fp-related-item">
                    <span class="fp-related-name">{{ $rp->title }}</span>
                    <span class="fp-related-meta">{{ $rp->time_ago }} · {{ $rp->comments_count }} bình luận</span>
                </a>
                @endforeach
            </section>
            @endif

            {{-- Back --}}
            <div class="fp-back">
                <a href="{{ route('forum.index') }}"><i class="fas fa-arrow-left"></i> Quay lại forum</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.fp-page { background: #f8fafc; min-height: 100vh; }

/* Breadcrumb */
.fp-bread { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0.75rem 0; }
.fp-bread-nav { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #94a3b8; }
.fp-bread-nav a { color: #6a4c93; text-decoration: none; }
.fp-bread-nav a:hover { text-decoration: underline; }

/* Wrap */
.fp-wrap { max-width: 800px; margin: 0 auto; padding: 1.5rem 0 3rem; }

/* Post */
.fp-post { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.25rem; }
.fp-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.75rem; }
.fp-cat { font-size: 0.8rem; color: #6a4c93; text-decoration: none; font-weight: 500; }
.fp-cat:hover { text-decoration: underline; }
.fp-badge { font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px; background: #fef3c7; color: #92400e; }

/* Reuse fm-type from forum index */
.fm-type { font-size: 0.7rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 4px; text-transform: uppercase; }
.fm-type-idea { background: #fef3c7; color: #92400e; }
.fm-type-question { background: #fee2e2; color: #991b1b; }
.fm-type-showcase { background: #d1fae5; color: #065f46; }
.fm-type-job { background: #e0e7ff; color: #3730a3; }
.fm-type-review { background: #fce7f3; color: #9d174d; }

.fp-title { font-size: 1.5rem; font-weight: 700; color: #1a202c; line-height: 1.3; margin: 0 0 1rem; }

.fp-author-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9; flex-wrap: wrap; }
.fp-author { display: flex; align-items: center; gap: 0.625rem; }
.fm-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg,#6a4c93,#9b5de5); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; }
.fp-author-name { font-weight: 600; color: #1a202c; font-size: 0.9rem; display: block; }
.fp-author-time { font-size: 0.8rem; color: #94a3b8; display: block; }
.fp-stats { display: flex; gap: 1rem; font-size: 0.8rem; color: #94a3b8; }
.fp-stats span { display: flex; align-items: center; gap: 0.25rem; }

.fp-tags { display: flex; gap: 0.375rem; flex-wrap: wrap; margin-bottom: 1rem; }
.fm-tag { padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; text-decoration: none; }

.fp-content { font-size: 1rem; line-height: 1.75; color: #2d3748; margin-bottom: 1rem; word-break: break-word; }

.fp-actions { display: flex; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
.fp-action-btn { background: #f1f5f9; border: none; padding: 0.5rem 1rem; border-radius: 6px; color: #64748b; font-size: 0.85rem; cursor: pointer; transition: all .15s; display: flex; align-items: center; gap: 0.375rem; }
.fp-action-btn:hover { background: #e2e8f0; color: #1a202c; }
.fp-bookmark-btn.active { background: #fef3c7; color: #d97706; }
.fp-bookmark-btn.active:hover { background: #fde68a; }

/* Best Answer */
.fp-cmt-best { border-left: 3px solid #10b981; background: #f0fdf4; padding-left: 0.75rem; border-radius: 0 8px 8px 0; }
.fp-best-badge { display: inline-flex; align-items: center; gap: 0.25rem; background: #10b981; color: #fff; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 600; }
.fp-pin-btn { background: none; border: 1px dashed #94a3b8; padding: 0.25rem 0.5rem; border-radius: 4px; color: #94a3b8; font-size: 0.75rem; cursor: pointer; transition: all .15s; }
.fp-pin-btn:hover { border-color: #10b981; color: #10b981; }
.fp-pin-btn.pinned { border-style: solid; border-color: #10b981; color: #10b981; background: #f0fdf4; }

/* Comments */
.fp-comments { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.25rem; }
.fp-comments-title { font-size: 1.1rem; font-weight: 700; color: #1a202c; margin: 0 0 1.25rem; }

/* Comment form */
.fp-comment-form { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; }
.fp-form-row { display: flex; gap: 0.75rem; align-items: flex-start; }
.fp-form-body { flex: 1; }
.fp-textarea { width: 100%; padding: 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; font-family: inherit; resize: vertical; min-height: 80px; transition: border-color .2s; }
.fp-textarea:focus { outline: none; border-color: #6a4c93; }
.fp-textarea.error { border-color: #e53e3e; background: #fef5f5; }
.fp-form-error { color: #e53e3e; font-size: 0.8rem; margin: 0.25rem 0 0; }
.fp-form-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; }
.fp-form-hint { color: #94a3b8; font-size: 0.8rem; }
.fm-btn-create { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background: linear-gradient(135deg,#6a4c93,#9b5de5); color: #fff; text-decoration: none; border: none; cursor: pointer; transition: opacity .2s; }
.fm-btn-create:hover { opacity: .85; }

.fp-login-prompt { text-align: center; padding: 1.25rem; background: #f8fafc; border-radius: 8px; border: 1.5px dashed #e2e8f0; }
.fp-login-prompt p { margin: 0; color: #64748b; font-size: 0.9rem; }
.fp-login-prompt a { color: #6a4c93; font-weight: 600; text-decoration: none; }
.fp-login-prompt a:hover { text-decoration: underline; }

/* Comments list */
.fp-comments-list { display: flex; flex-direction: column; gap: 1rem; }
.fp-no-comments { text-align: center; padding: 2rem; color: #94a3b8; }
.fp-no-comments p { margin: 0; }

/* Comment card (used by partial) */
.fp-cmt { padding: 1rem; background: #f8fafc; border-radius: 8px; border: 1px solid #f1f5f9; }
.fp-cmt-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.fp-cmt-author { display: flex; align-items: center; gap: 0.5rem; }
.fp-cmt-name { font-weight: 600; font-size: 0.85rem; color: #1a202c; }
.fp-cmt-time { font-size: 0.75rem; color: #94a3b8; }
.fp-cmt-body { font-size: 0.9rem; line-height: 1.6; color: #2d3748; margin: 0; }
.fp-cmt-footer { display: flex; gap: 0.75rem; margin-top: 0.5rem; }
.fp-cmt-action { background: none; border: none; color: #94a3b8; font-size: 0.8rem; cursor: pointer; padding: 0.25rem 0; display: flex; align-items: center; gap: 0.25rem; }
.fp-cmt-action:hover { color: #6a4c93; }

/* Reply form inline */
.fp-reply-form { margin-top: 0.75rem; padding: 0.75rem; background: #fff; border-radius: 6px; border: 1px solid #e2e8f0; display: none; }
.fp-reply-form.show { display: block; }
.fp-reply-textarea { width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem; font-family: inherit; resize: vertical; min-height: 60px; }
.fp-reply-textarea:focus { outline: none; border-color: #6a4c93; }
.fp-reply-actions { display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.5rem; }
.fp-btn-cancel { background: #f1f5f9; border: none; padding: 0.375rem 0.75rem; border-radius: 6px; color: #64748b; font-size: 0.8rem; cursor: pointer; }
.fp-btn-cancel:hover { background: #e2e8f0; }

/* Nested replies */
.fp-cmt-replies { margin-top: 0.75rem; padding-left: 1.5rem; border-left: 2px solid #e2e8f0; display: flex; flex-direction: column; gap: 0.75rem; }

/* Related */
.fp-related { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.25rem; margin-bottom: 1.25rem; }
.fp-related-title { font-size: 1rem; font-weight: 700; color: #1a202c; margin: 0 0 0.75rem; }
.fp-related-item { display: block; padding: 0.625rem 0; border-bottom: 1px solid #f1f5f9; text-decoration: none; }
.fp-related-item:last-child { border-bottom: none; padding-bottom: 0; }
.fp-related-name { display: block; font-size: 0.9rem; font-weight: 500; color: #1a202c; }
.fp-related-item:hover .fp-related-name { color: #6a4c93; }
.fp-related-meta { font-size: 0.8rem; color: #94a3b8; }

/* Back */
.fp-back { text-align: center; }
.fp-back a { color: #6a4c93; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
.fp-back a:hover { text-decoration: underline; }

/* Mobile */
@media (max-width: 768px) {
    .fp-wrap { padding: 1rem 0 2rem; }
    .fp-post, .fp-comments, .fp-related { padding: 1rem; }
    .fp-title { font-size: 1.25rem; }
    .fp-author-row { flex-direction: column; align-items: flex-start; }
    .fp-form-footer { flex-direction: column; gap: 0.5rem; align-items: stretch; }
    .fm-btn-create { justify-content: center; }
    .fp-cmt-replies { padding-left: 0.75rem; }
}
</style>
@endpush

@push('scripts')
<script>
function toggleBookmark(btn) {
    const slug = btn.dataset.post;
    fetch(`/forum/posts/${slug}/bookmark`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'},
    }).then(r => r.json()).then(data => {
        if (data.success) {
            const icon = btn.querySelector('i');
            const label = btn.querySelector('span');
            btn.classList.toggle('active', data.bookmarked);
            icon.className = data.bookmarked ? 'fas fa-bookmark' : 'far fa-bookmark';
            label.textContent = data.bookmarked ? 'Đã lưu' : 'Lưu';
        }
    });
}

function pinBestAnswer(btn, commentId) {
    const slug = '{{ $post->slug }}';
    fetch(`/forum/posts/${slug}/pin-answer`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'},
        body: JSON.stringify({comment_id: commentId}),
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
        else if (data.message) alert(data.message);
    });
}
</script>
@endpush
@endsection
