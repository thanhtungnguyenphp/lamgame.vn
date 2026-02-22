<div class="fm-card {{ isset($isSticky) && $isSticky ? 'sticky' : '' }}">
    <div class="fm-card-top">
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
        <a href="{{ route('forum.category', $post->category->slug) }}" class="fm-cat-link">{{ $post->category->icon ?? '' }} {{ $post->category->name }}</a>
        @if($post->is_featured)<span class="fm-type" style="background:#fef3c7;color:#92400e;">✨ Nổi bật</span>@endif
        <span class="fm-time">{{ $post->time_ago }}</span>
    </div>

    <h3 class="fm-card-title">
        <a href="{{ route('forum.posts.show', $post->slug) }}">{{ $post->title }}</a>
    </h3>

    @if($post->excerpt)
    <p class="fm-card-excerpt">{{ $post->excerpt }}</p>
    @endif

    @if($post->tags->count() > 0)
    <div class="fm-card-tags">
        @foreach($post->tags->take(4) as $tag)
        <a href="{{ route('forum.tag', $tag->slug) }}" class="fm-card-tag" style="background:{{ $tag->color }}20;color:{{ $tag->color }};">{{ $tag->name }}</a>
        @endforeach
        @if($post->tags->count() > 4)
        <span class="fm-card-tag" style="background:#e2e8f0;color:#718096;">+{{ $post->tags->count() - 4 }}</span>
        @endif
    </div>
    @endif

    <div class="fm-card-footer">
        <div class="fm-avatar">{{ strtoupper(substr($post->author_name, 0, 1)) }}</div>
        <span class="fm-author">{{ $post->author_name }}</span>
        <div class="fm-card-stats">
            <span class="fm-card-stat"><i class="far fa-eye"></i> {{ number_format($post->views_count) }}</span>
            <span class="fm-card-stat"><i class="far fa-comment"></i> {{ number_format($post->comments_count) }}</span>
            <span class="fm-card-stat"><i class="far fa-heart"></i> {{ number_format($post->likes_count) }}</span>
        </div>
    </div>
</div>
