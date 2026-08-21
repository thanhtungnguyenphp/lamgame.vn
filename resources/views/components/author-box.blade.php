{{-- Author Box Component for Blog Posts --}}
{{-- Usage: <x-author-box :author="$author" /> --}}

@props(['author', 'showBio' => true, 'compact' => false])

@if($author)
<div class="author-box {{ $compact ? 'author-box--compact' : '' }}">
    <div class="author-box__avatar">
        <a href="{{ route('authors.show', $author->slug) }}">
            <img src="{{ $author->avatar_url }}" 
                 alt="{{ $author->name }}"
                 loading="lazy"
                 width="80"
                 height="80">
        </a>
        @if($author->is_verified)
            <span class="author-box__verified" title="Verified Author">✓</span>
        @endif
    </div>
    
    <div class="author-box__content">
        <h4 class="author-box__name">
            <a href="{{ route('authors.show', $author->slug) }}">
                {{ $author->name }}
            </a>
            @if($author->is_staff)
                <span class="author-box__badge">LamGame Team</span>
            @endif
        </h4>
        
        @if($author->title)
            <p class="author-box__title">{{ $author->title }}</p>
        @endif
        
        @if(!$compact && $author->expertise)
            <div class="author-box__expertise">
                @foreach(array_slice($author->expertise, 0, 5) as $skill)
                    <span class="tag">{{ $skill }}</span>
                @endforeach
            </div>
        @endif
        
        @if($showBio && $author->bio)
            <p class="author-box__bio">{{ Str::limit($author->bio, 150) }}</p>
        @endif
        
        @if(!$compact && $author->experience_text)
            <p class="author-box__experience">{{ $author->experience_text }}</p>
        @endif
        
        <div class="author-box__actions">
            <a href="{{ route('authors.show', $author->slug) }}" class="author-box__link">
                Xem hồ sơ tác giả →
            </a>
            
            @if(!$compact)
                <div class="author-box__socials">
                    @if($author->hasSocialLink('github'))
                        <a href="{{ $author->getSocialLink('github') }}" 
                           target="_blank" 
                           rel="noopener"
                           class="author-box__social"
                           aria-label="GitHub">
                            <i class="fa fa-github"></i>
                        </a>
                    @endif
                    @if($author->hasSocialLink('linkedin'))
                        <a href="{{ $author->getSocialLink('linkedin') }}" 
                           target="_blank" 
                           rel="noopener"
                           class="author-box__social"
                           aria-label="LinkedIn">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    @endif
                    @if($author->hasSocialLink('twitter'))
                        <a href="{{ $author->getSocialLink('twitter') }}" 
                           target="_blank" 
                           rel="noopener"
                           class="author-box__social"
                           aria-label="Twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.author-box {
    display: flex;
    gap: 1.25rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border: 1px solid #dee2e6;
    margin: 2rem 0;
}

.author-box--compact {
    padding: 1rem;
    gap: 1rem;
}

.author-box__avatar {
    position: relative;
    flex-shrink: 0;
}

.author-box__avatar img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.author-box--compact .author-box__avatar img {
    width: 60px;
    height: 60px;
}

.author-box__verified {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 22px;
    height: 22px;
    background: #10b981;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    border: 2px solid #fff;
}

.author-box__content {
    flex: 1;
    min-width: 0;
}

.author-box__name {
    margin: 0 0 0.25rem;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.author-box__name a {
    color: #1a1a2e;
    text-decoration: none;
}

.author-box__name a:hover {
    color: #6366f1;
}

.author-box__badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    background: #6366f1;
    color: white;
    border-radius: 4px;
    font-weight: 500;
}

.author-box__title {
    margin: 0 0 0.5rem;
    color: #6b7280;
    font-size: 0.9rem;
}

.author-box__expertise {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 0.75rem;
}

.author-box__expertise .tag {
    font-size: 0.75rem;
    padding: 0.2rem 0.5rem;
    background: #e0e7ff;
    color: #4338ca;
    border-radius: 4px;
}

.author-box__bio {
    margin: 0 0 0.75rem;
    color: #4b5563;
    font-size: 0.9rem;
    line-height: 1.5;
}

.author-box__experience {
    margin: 0 0 0.75rem;
    color: #6b7280;
    font-size: 0.85rem;
}

.author-box__actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.author-box__link {
    color: #6366f1;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.author-box__link:hover {
    text-decoration: underline;
}

.author-box__socials {
    display: flex;
    gap: 0.5rem;
}

.author-box__social {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fff;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s;
}

.author-box__social:hover {
    background: #6366f1;
    color: white;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .author-box {
        background: linear-gradient(135deg, #1e1e2e 0%, #2d2d3a 100%);
        border-color: #3d3d4a;
    }
    
    .author-box__name a {
        color: #e5e7eb;
    }
    
    .author-box__bio,
    .author-box__title {
        color: #9ca3af;
    }
    
    .author-box__expertise .tag {
        background: #3730a3;
        color: #c7d2fe;
    }
    
    .author-box__social {
        background: #3d3d4a;
        color: #9ca3af;
    }
}

/* Responsive */
@media (max-width: 480px) {
    .author-box {
        flex-direction: column;
        text-align: center;
    }
    
    .author-box__avatar {
        align-self: center;
    }
    
    .author-box__expertise {
        justify-content: center;
    }
    
    .author-box__actions {
        justify-content: center;
    }
}
</style>
@endpush
@endif
