<div class="fp-cmt" id="comment-{{ $comment->id }}">
    <div class="fp-cmt-hdr">
        <div class="fp-cmt-author">
            <div class="fm-avatar" style="width:28px;height:28px;font-size:0.65rem;">{{ strtoupper(substr($comment->author_name, 0, 1)) }}</div>
            <span class="fp-cmt-name">{{ $comment->author_name }}</span>
            <span class="fp-cmt-time">{{ $comment->time_ago }}</span>
        </div>
        @if($comment->likes_count > 0)
        <span class="fp-cmt-action"><i class="far fa-heart"></i> {{ $comment->likes_count }}</span>
        @endif
    </div>

    <p class="fp-cmt-body">{{ $comment->content }}</p>

    <div class="fp-cmt-footer">
        @auth('customer')
        <button class="fp-cmt-action" onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('show')">
            <i class="far fa-comment"></i> Trả lời
        </button>
        @endauth
    </div>

    {{-- Reply form --}}
    @auth('customer')
    <div class="fp-reply-form" id="reply-{{ $comment->id }}">
        <form action="{{ route('forum.comments.store', $comment->post) }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="content" placeholder="Trả lời {{ $comment->author_name }}..." required rows="2" maxlength="2000" class="fp-reply-textarea"></textarea>
            <div class="fp-reply-actions">
                <button type="button" class="fp-btn-cancel" onclick="this.closest('.fp-reply-form').classList.remove('show')">Hủy</button>
                <button type="submit" class="fm-btn-create" style="font-size:0.8rem;padding:0.375rem 0.75rem;"><i class="fas fa-reply"></i> Gửi</button>
            </div>
        </form>
    </div>
    @endauth

    {{-- Nested replies --}}
    @if($comment->publishedReplies->count() > 0)
    <div class="fp-cmt-replies">
        @foreach($comment->publishedReplies as $reply)
            @include('lamgame.pages.forum.partials.comment', ['comment' => $reply])
        @endforeach
    </div>
    @endif
</div>
