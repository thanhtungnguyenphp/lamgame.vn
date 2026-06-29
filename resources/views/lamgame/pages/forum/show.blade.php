@extends('layouts.master')

@section('page_title', $post->meta_title ?: ($post->title . ' - Forum'))
@section('page_description', $post->meta_description ?: $post->excerpt)

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "DiscussionForumPosting",
    "headline": "{{ $post->title }}",
    "datePublished": "{{ $post->created_at->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {"@type": "Person","name": "{{ $post->customer->name ?? $post->author_name ?? 'Thành viên' }}"},
    "interactionStatistic": [
        {"@type": "InteractionCounter","interactionType": "https://schema.org/CommentAction","userInteractionCount": {{ $post->comments_count ?? 0 }}},
        {"@type": "InteractionCounter","interactionType": "https://schema.org/LikeAction","userInteractionCount": {{ $post->likes_count ?? 0 }}}
    ],
    "isPartOf": {"@type": "DiscussionForum","name": "Forum Cộng đồng Game Developer","url": "{{ route('forum.index') }}"}
}
</script>
@endpush

@section('content')
<div class="fp-page">

{{-- THREAD HERO --}}
<section class="fp-hero">
    <div class="fp-hero__bg"></div>
    <div class="fp-container fp-hero__inner">
        <nav class="fp-breadcrumb">
            <a href="{{ route('forum.index') }}">Forum</a><span>›</span>
            <a href="{{ route('forum.category', $post->category->slug) }}">{{ $post->category->name }}</a><span>›</span>
            <span>{{ Str::limit($post->title, 40) }}</span>
        </nav>

        <div class="fp-hero__meta">
            @if($post->type !== 'discussion')
            <span class="fp-type fp-type--{{ $post->type }}">
                @switch($post->type)
                    @case('idea') 💡 Ý tưởng @break
                    @case('question') ❓ Câu hỏi @break
                    @case('showcase') 🎯 Showcase @break
                    @case('job') 💼 Tuyển dụng @break
                    @case('review') 📚 Review @break
                @endswitch
            </span>
            @endif
            @if($post->is_featured)<span class="fp-badge fp-badge--featured">✨ Nổi bật</span>@endif
            @if($post->is_sticky)<span class="fp-badge">📌 Ghim</span>@endif
        </div>

        <h1 class="fp-hero__title">{{ $post->title }}</h1>

        {{-- Discussion Stats --}}
        <div class="fp-stats-bar">
            <div class="fp-stat"><i class="far fa-eye"></i> {{ number_format($post->views_count) }} lượt xem</div>
            <div class="fp-stat"><i class="far fa-comment"></i> {{ number_format($post->comments_count) }} bình luận</div>
            <div class="fp-stat"><i class="far fa-heart"></i> {{ number_format($post->likes_count) }} thích</div>
            <div class="fp-stat">🕐 {{ $post->created_at->diffForHumans() }}</div>
        </div>
    </div>
</section>

{{-- MAIN CONTENT --}}
<div class="fp-container">
    <div class="fp-layout">
        {{-- ARTICLE --}}
        <div class="fp-main">
            {{-- Author --}}
            <div class="fp-author-card">
                <div class="fp-avatar">{{ strtoupper(substr($post->author_name, 0, 1)) }}</div>
                <div>
                    <span class="fp-author__name">{{ $post->author_name }}</span>
                    <span class="fp-author__time">{{ $post->created_at->format('d/m/Y H:i') }}
                        @if($post->updated_at->gt($post->created_at)) · Sửa {{ $post->updated_at->diffForHumans() }}@endif
                    </span>
                </div>
            </div>

            {{-- Tags --}}
            @if($post->tags->count() > 0)
            <div class="fp-tags">
                @foreach($post->tags as $tag)
                <a href="{{ route('forum.tag', $tag->slug) }}" class="fp-tag">{{ $tag->name }}</a>
                @endforeach
            </div>
            @endif

            {{-- Content --}}
            <div class="fp-content">
                {!! strip_tags($post->content, '<p><br><strong><b><em><i><ul><ol><li><a><h1><h2><h3><h4><h5><h6><img><blockquote><pre><code><span><div><hr><table><tr><td><th>') !!}
            </div>

            {{-- Poll --}}
            @if($post->poll)
            <div class="fp-poll">
                <h4>📊 {{ $post->poll->question }}</h4>
                <div id="poll-{{ $post->poll->id }}">
                    @php $userVoted = $post->poll->votes->where('customer_id', auth('customer')->id())->isNotEmpty(); @endphp
                    @foreach($post->poll->options as $opt)
                    @php $pct = $post->poll->total_votes > 0 ? round($opt->votes_count / $post->poll->total_votes * 100) : 0; @endphp
                    <div class="fp-poll__opt">
                        @if($userVoted || ($post->poll->expires_at && $post->poll->expires_at->isPast()))
                        <div class="fp-poll__result"><span>{{ $opt->text }}</span><span>{{ $pct }}%</span></div>
                        <div class="fp-poll__bar"><div style="width:{{ $pct }}%"></div></div>
                        @else
                        <button type="button" onclick="votePoll({{ $post->poll->id }}, [{{ $opt->id }}])" class="fp-poll__btn">{{ $opt->text }}</button>
                        @endif
                    </div>
                    @endforeach
                    <div class="fp-poll__info">{{ $post->poll->total_votes }} phiếu{{ $post->poll->expires_at ? ' · Hết hạn '.$post->poll->expires_at->format('d/m/Y') : '' }}</div>
                </div>
            </div>
            @endif

            {{-- Reactions --}}
            <div class="fp-reactions">
                <div class="fp-reactions__btns">
                    @php $rc = $post->reactions_count; @endphp
                    <button class="fp-react" onclick="reactPost('like', this)">👍 <span class="fp-react__count" data-type="like">{{ ($rc['like'] ?? 0) ?: '' }}</span></button>
                    <button class="fp-react" onclick="reactPost('love', this)">❤️ <span class="fp-react__count" data-type="love">{{ ($rc['love'] ?? 0) ?: '' }}</span></button>
                    <button class="fp-react" onclick="reactPost('fire', this)">🔥 <span class="fp-react__count" data-type="fire">{{ ($rc['fire'] ?? 0) ?: '' }}</span></button>
                    <button class="fp-react" onclick="reactPost('think', this)">💡 <span class="fp-react__count" data-type="think">{{ ($rc['think'] ?? 0) ?: '' }}</span></button>
                    <button class="fp-react" onclick="reactPost('game', this)">🎮 <span class="fp-react__count" data-type="game">{{ ($rc['game'] ?? 0) ?: '' }}</span></button>
                </div>
                <div class="fp-reactions__actions">
                    <button class="fp-action-btn" onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}else{navigator.clipboard.writeText(location.href).then(()=>alert('Đã copy link!'))}">
                        <i class="fas fa-share-alt"></i> Chia sẻ
                    </button>
                    @auth('customer')
                    <button class="fp-action-btn {{ $post->isBookmarkedBy(auth('customer')->id()) ? 'active' : '' }}" onclick="toggleBookmark(this)" data-post="{{ $post->slug }}">
                        <i class="{{ $post->isBookmarkedBy(auth('customer')->id()) ? 'fas' : 'far' }} fa-bookmark"></i>
                        <span>{{ $post->isBookmarkedBy(auth('customer')->id()) ? 'Đã lưu' : 'Lưu' }}</span>
                    </button>
                    @endauth
                </div>
            </div>

            {{-- COMMENTS --}}
            <section class="fp-comments">
                <h3 class="fp-comments__title">💬 Bình luận ({{ $post->comments_count }})</h3>

                {{-- Comment form --}}
                @auth('customer')
                <form action="{{ route('forum.comments.store', $post) }}" method="POST" class="fp-comment-form">
                    @csrf
                    <div class="fp-avatar">{{ strtoupper(substr(auth('customer')->user()->first_name, 0, 1)) }}</div>
                    <div class="fp-comment-form__body">
                        <textarea name="content" placeholder="Chia sẻ suy nghĩ của bạn..." required rows="3" maxlength="2000" class="fp-textarea">{{ old('content') }}</textarea>
                        <div class="fp-comment-form__footer">
                            <small>Trao đổi văn minh và tôn trọng</small>
                            <button type="submit" class="fp-btn fp-btn--primary"><i class="fas fa-paper-plane"></i> Đăng</button>
                        </div>
                    </div>
                </form>
                @else
                <div class="fp-login-prompt">
                    <p>🔐 <a href="{{ route('auth.login') }}">Đăng nhập</a> hoặc <a href="{{ route('auth.register') }}">tạo tài khoản</a> để bình luận</p>
                </div>
                @endauth

                {{-- Comments list --}}
                <div class="fp-comments__list">
                    @forelse($post->rootComments as $comment)
                        @include('lamgame.pages.forum.partials.comment', ['comment' => $comment])
                    @empty
                    <div class="fp-empty">💭 Chưa có bình luận. Hãy là người đầu tiên!</div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- SIDEBAR --}}
        <aside class="fp-sidebar">
            {{-- Related threads --}}
            @if($relatedPosts->count() > 0)
            <div class="fp-widget">
                <h3 class="fp-widget__title">🔗 Thảo luận liên quan</h3>
                @foreach($relatedPosts as $rp)
                <a href="{{ route('forum.posts.show', $rp->slug) }}" class="fp-related-item">
                    <span class="fp-related-item__title">{{ Str::limit($rp->title, 50) }}</span>
                    <span class="fp-related-item__meta">{{ $rp->comments_count }} bình luận · {{ $rp->time_ago }}</span>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Community CTA --}}
            <div class="fp-widget fp-widget--cta">
                <h3 class="fp-widget__title">🚀 Tham gia thảo luận</h3>
                <p>Chia sẻ kiến thức, hỏi đáp và kết nối với 12.000+ developers</p>
                <a href="{{ route('forum.posts.create') }}" class="fp-btn fp-btn--primary fp-btn--sm">Đăng bài mới →</a>
            </div>

            {{-- Back --}}
            <a href="{{ route('forum.index') }}" class="fp-back-link">← Quay lại forum</a>
        </aside>
    </div>
</div>

</div>
@endsection

@push('styles')
<style>.fp-page{background:#070B14;min-height:100vh}</style>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet"></noscript>
<link rel="stylesheet" href="{{ asset('css/forum-detail.css') }}">
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

function votePoll(pollId, optionIds) {
    fetch('/api/v1/forum/polls/' + pollId + '/vote', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body: JSON.stringify({option_ids: optionIds})
    }).then(r => r.json()).then(d => { if(d.success || !d.error) location.reload(); else alert(d.error||'Lỗi'); });
}

function reactPost(type, btn) {
    fetch('/forum/react', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
        body: JSON.stringify({
            reactable_type: 'post',
            reactable_id: {{ $post->id }},
            type: type
        })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            document.querySelectorAll('.fp-react__count').forEach(el => {
                const t = el.dataset.type;
                const c = d.counts[t] || 0;
                el.textContent = c > 0 ? c : '';
            });
            // Toggle active state
            document.querySelectorAll('.fp-react').forEach(b => b.classList.remove('active'));
            if (d.action !== 'removed') btn.classList.add('active');
        }
    }).catch(() => alert('Vui lòng đăng nhập để react'));
}
</script>
@endpush
