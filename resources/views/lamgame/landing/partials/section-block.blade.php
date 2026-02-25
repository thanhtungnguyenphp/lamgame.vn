@php
    $type = $section['type'] ?? 'text';
    $bg = ($section['bg'] ?? false) ? 'lp-section--alt' : '';
@endphp

<section class="lp-section {{ $bg }}">
    <div class="container">
        @if(!empty($section['title']))
            <h2 class="lp-section__title">{{ $section['title'] }}</h2>
        @endif

        @if($type === 'text' && !empty($section['content']))
            <div class="lp-section__text">{!! $section['content'] !!}</div>

        @elseif($type === 'image-text')
            <div class="lp-imgtext {{ ($section['image_position'] ?? 'left') === 'right' ? 'lp-imgtext--rev' : '' }}">
                @if(!empty($section['image']))
                <div class="lp-imgtext__img"><img src="{{ $section['image'] }}" alt="{{ $section['title'] ?? '' }}" loading="lazy"></div>
                @endif
                <div class="lp-imgtext__body">{!! $section['content'] ?? '' !!}</div>
            </div>

        @elseif($type === 'cards' && !empty($section['items']))
            <div class="lp-cards lp-cards--{{ count($section['items']) }}">
                @foreach($section['items'] as $card)
                <div class="lp-card">
                    @if(!empty($card['icon']))<div class="lp-card__ico">{{ $card['icon'] }}</div>@endif
                    @if(!empty($card['title']))<h3 class="lp-card__h">{{ $card['title'] }}</h3>@endif
                    @if(!empty($card['text']))<p class="lp-card__p">{{ $card['text'] }}</p>@endif
                </div>
                @endforeach
            </div>

        @elseif($type === 'cta')
            <div class="lp-ctabox">
                @if(!empty($section['content']))<div class="lp-ctabox__text">{!! $section['content'] !!}</div>@endif
                @if(!empty($section['cta_text']) && !empty($section['cta_url']))
                    <a href="{{ $section['cta_url'] }}" class="ev-cta">
                        <span>{{ $section['cta_text'] }}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

@pushOnce('styles')
<style>
    /* Image + Text */
    .lp-imgtext { display: flex; gap: 3rem; align-items: center; }
    .lp-imgtext--rev { flex-direction: row-reverse; }
    .lp-imgtext__img { flex: 1; }
    .lp-imgtext__img img { width: 100%; border-radius: 16px; }
    .lp-imgtext__body { flex: 1; font-size: 1.05rem; line-height: 1.8; color: #555; }

    /* Cards */
    .lp-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        max-width: 1100px; margin: 0 auto;
    }
    .lp-cards--3 { grid-template-columns: repeat(3, 1fr); }
    .lp-cards--4 { grid-template-columns: repeat(4, 1fr); }
    .lp-card {
        background: #fff; border-radius: 16px; padding: 2rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        text-align: center; transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(106,76,147,0.08);
    }
    .lp-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(106,76,147,0.15);
        border-color: rgba(106,76,147,0.2);
    }
    .lp-card__ico {
        font-size: 2.2rem;
        display: flex; align-items: center; justify-content: center;
        width: 64px; height: 64px; margin: 0 auto 1rem;
        background: linear-gradient(135deg, #ede9fe, #fef3c7);
        border-radius: 18px;
    }
    .lp-card__h {
        font-size: 1.1rem; font-weight: 700;
        color: #1e1b4b; margin-bottom: 0.5rem;
    }
    .lp-card__p {
        font-size: 0.85rem; color: #6b7280; line-height: 1.6; margin: 0;
    }

    /* CTA Box */
    .lp-ctabox {
        text-align: center; padding: 3.5rem 2rem;
        background: linear-gradient(135deg, #1e1b4b 0%, #6a4c93 50%, #7c3aed 100%);
        border-radius: 24px; color: #fff;
        box-shadow: 0 8px 40px rgba(106,76,147,0.25);
    }
    .lp-ctabox__text { font-size: 1.15rem; margin-bottom: 2rem; line-height: 1.7; }
    .lp-ctabox__text p { margin: 0 0 0.5rem; }

    @media (max-width: 768px) {
        .lp-imgtext, .lp-imgtext--rev { flex-direction: column; gap: 1.5rem; }
        .lp-cards--3, .lp-cards--4 { grid-template-columns: repeat(2, 1fr); }
        .lp-card { padding: 1.5rem 1rem; }
        .lp-card__ico { width: 52px; height: 52px; font-size: 1.8rem; border-radius: 14px; }
        .lp-ctabox { padding: 2.5rem 1.5rem; border-radius: 16px; }
    }
    @media (max-width: 400px) {
        .lp-cards, .lp-cards--3, .lp-cards--4 { grid-template-columns: 1fr; }
    }
</style>
@endPushOnce
