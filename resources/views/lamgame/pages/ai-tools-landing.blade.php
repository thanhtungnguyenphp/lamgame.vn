{{-- AI Tools — Marketplace Landing Page (Optimized UX/UI) --}}
@extends('layouts.master')

@section('page_title', 'AI Tools cho Game Developer — LamGame.vn')
@section('page_description', 'Công cụ AI hỗ trợ game developer tạo concept, code, debug, test và review với quota minh bạch.')

@section('content')
<div class="ai-page">

{{-- HERO --}}
<section class="ai-hero">
    <div class="ai-hero__bg"></div>
    <div class="ai-hero__content">
        <span class="ai-badge">✨ Multi-model AI · Quota minh bạch</span>
        <h1>Tăng tốc workflow <br>phát triển game</h1>
        <p>Code, Debug, Test, Review — AI hỗ trợ các tác vụ lặp lại. Bạn tập trung vào sáng tạo.</p>
        <div class="ai-hero__cta">
            <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--primary">Dùng thử miễn phí</a>
            <a href="#pricing" class="ai-btn ai-btn--ghost">Xem bảng giá ↓</a>
        </div>
    </div>
</section>

{{-- TRUST BAR --}}
<section class="ai-trust">
    <div class="ai-container">
        <div class="ai-trust__grid">
            <div class="ai-trust__item"><strong>{{ number_format($siteMetrics['registered_users'] ?? 0) }}+</strong><span>Developers</span></div>
            <div class="ai-trust__item"><strong>{{ $siteMetrics['published_sources'] ?? 0 }}+</strong><span>Source Code</span></div>
            <div class="ai-trust__item"><strong>{{ $siteMetrics['blog_posts'] ?? 0 }}+</strong><span>Bài viết</span></div>
            <div class="ai-trust__item"><strong>7</strong><span>Workflow AI</span></div>
        </div>
    </div>
</section>

{{-- USE CASES — Outcome driven --}}
<section class="ai-sec">
    <div class="ai-container">
        <h2 class="ai-sec__title">AI hỗ trợ gì cho bạn?</h2>
        <p class="ai-sec__sub">Tăng tốc các tác vụ coding thường ngày</p>
        <div class="ai-outcomes">
            <div class="ai-outcome">
                <span class="ai-outcome__icon">⚡</span>
                <h3>Sinh code nhanh hơn</h3>
                <p>AI sinh code Unity/Unreal/Godot từ mô tả. Bạn review và tùy chỉnh.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🐛</span>
                <h3>Hỗ trợ debug</h3>
                <p>Paste error → AI phân tích nguyên nhân → đề xuất hướng xử lý.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🧪</span>
                <h3>Tạo unit test</h3>
                <p>AI gợi ý test cases cho game logic, giảm thời gian viết test.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">📝</span>
                <h3>Giảm tác vụ lặp lại</h3>
                <p>Boilerplate code, documentation, refactoring — AI xử lý nhanh.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🎨</span>
                <h3>Hỗ trợ tạo asset</h3>
                <p>Tạo sprite, UI mockup, tilemap concept với AI image generation.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🔍</span>
                <h3>Code review</h3>
                <p>AI review code — phát hiện issues, gợi ý tối ưu và best practices.</p>
            </div>
        </div>
    </div>
</section>

{{-- PRICING — rendered from active database plans --}}
<section class="ai-sec ai-sec--alt" id="pricing">
    <div class="ai-container">
        <h2 class="ai-sec__title">Chọn gói phù hợp</h2>
        <p class="ai-sec__sub">Giá và quota dưới đây được lấy trực tiếp từ cấu hình đang hoạt động</p>
        @php
            $quotaLabel = fn ($value) => $value === -1 ? 'Không giới hạn' : number_format((int) $value) . ' lượt/tháng';
        @endphp
        <div class="ai-pricing">
            @foreach($plans as $plan)
            @php $features = $plan->features ?? []; @endphp
            <div class="ai-plan {{ $plan->slug === 'pro' ? 'ai-plan--pop' : '' }}">
                @if($plan->slug === 'pro')<span class="ai-plan__badge">Phổ biến nhất</span>@endif
                <h3>{{ $plan->name }}</h3>
                <p class="ai-plan__for">
                    {{ match($plan->slug) {
                        'free' => 'Trải nghiệm workflow cơ bản',
                        'basic' => 'Cho indie developer',
                        'pro' => 'Cho developer sử dụng thường xuyên',
                        'studio', 'business' => 'Cho team phát triển game',
                        'enterprise' => 'Giải pháp tùy chỉnh',
                        default => 'Gói AI Tools'
                    } }}
                </p>
                <div class="ai-plan__price">
                    @if($plan->slug === 'enterprise')
                        Liên hệ
                    @elseif((float) $plan->price === 0.0)
                        $0
                    @else
                        ${{ number_format((float) $plan->price, 0) }}<span>/tháng</span>
                    @endif
                </div>
                <div class="ai-plan__period">{{ $plan->billing_interval === 'monthly' ? 'Thanh toán hàng tháng' : $plan->billing_interval }}</div>
                <ul>
                    <li>✅ Concept: {{ $quotaLabel($features['ai_concept'] ?? 0) }}</li>
                    <li>{{ ($features['ai_generate'] ?? 0) !== 0 ? '✅' : '—' }} Sinh code: {{ $quotaLabel($features['ai_generate'] ?? 0) }}</li>
                    <li>{{ ($features['ai_debug'] ?? 0) !== 0 ? '✅' : '—' }} Debug: {{ $quotaLabel($features['ai_debug'] ?? 0) }}</li>
                    <li>{{ ($features['ai_code_review'] ?? 0) !== 0 ? '✅' : '—' }} Review: {{ $quotaLabel($features['ai_code_review'] ?? 0) }}</li>
                    <li>{{ !empty($features['priority_queue']) ? '✅ Priority queue' : '— Standard queue' }}</li>
                </ul>
                @if($plan->slug === 'enterprise')
                    <a href="/hire" class="ai-btn ai-btn--outline" data-ai-plan="enterprise">Liên hệ tư vấn</a>
                @else
                    <a href="{{ route('lamgame.ai-tools-dashboard') }}?subscribe={{ $plan->slug }}"
                       class="ai-btn {{ $plan->slug === 'pro' ? 'ai-btn--primary' : 'ai-btn--outline' }}"
                       data-ai-plan="{{ $plan->slug }}">
                        {{ $plan->slug === 'free' ? 'Bắt đầu miễn phí' : 'Chọn ' . $plan->name }}
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS - REMOVED: No verified user testimonials yet --}}
{{-- When we have real verified testimonials, we can add them back --}}

{{-- FAQ --}}
<section class="ai-sec ai-sec--alt">
    <div class="ai-container">
        <h2 class="ai-sec__title">Câu hỏi thường gặp</h2>
        <div class="ai-faq">
            <details class="ai-faq__item"><summary>Có cần biết code để dùng AI Tools?</summary><p>Không bắt buộc. AI Tools hỗ trợ cả người mới bắt đầu. Bạn chỉ cần mô tả yêu cầu bằng tiếng Việt.</p></details>
            <details class="ai-faq__item"><summary>Gói Free có giới hạn gì?</summary><p>Quota của từng gói được hiển thị trực tiếp trong bảng giá. Gói Free hiện cung cấp lượt Concept giới hạn theo tháng để bạn trải nghiệm trước khi nâng cấp.</p></details>
            <details class="ai-faq__item"><summary>Có thể hủy gói bất cứ lúc nào?</summary><p>Có. Bạn có thể hủy subscription bất cứ lúc nào. Gói sẽ còn hiệu lực đến hết chu kỳ thanh toán.</p></details>
            <details class="ai-faq__item"><summary>AI có hỗ trợ Unity, Unreal, Godot?</summary><p>Có. AI được train trên code của cả 3 engine phổ biến nhất. Bạn chỉ cần chọn engine khi sử dụng.</p></details>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="ai-final-cta">
    <div class="ai-container">
        <h2>Sẵn sàng tăng tốc workflow?</h2>
        <p>Dùng thử miễn phí — không cần thẻ tín dụng</p>
        <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--primary ai-btn--lg">Dùng thử miễn phí →</a>
    </div>
</section>

</div>
@endsection

@push('styles')
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet"></noscript>
<style>
:root{--ai-bg:#070B14;--ai-bg2:#0B1020;--ai-card:#111827;--ai-purple:#7C5CFF;--ai-cyan:#00D1FF;--ai-text:#F5F7FA;--ai-muted:#7A8599;--ai-border:rgba(124,92,255,.12)}
.ai-page{background:var(--ai-bg);color:var(--ai-text);font-family:'Inter',sans-serif}
.ai-container{max-width:1200px;margin:0 auto;padding:0 24px}

/* HERO */
.ai-hero{position:relative;padding:100px 24px 80px;text-align:center;overflow:hidden}
.ai-hero__bg{position:absolute;inset:0;background:radial-gradient(ellipse at 50% 20%,rgba(124,92,255,.15) 0%,transparent 55%),radial-gradient(ellipse at 80% 80%,rgba(0,209,255,.08) 0%,transparent 40%)}
.ai-hero__bg::before{content:'';position:absolute;top:15%;left:5%;width:350px;height:350px;background:rgba(124,92,255,.04);border-radius:50%;filter:blur(100px)}
.ai-hero__content{position:relative;max-width:640px;margin:0 auto}
.ai-badge{display:inline-block;background:rgba(124,92,255,.1);border:1px solid rgba(124,92,255,.3);border-radius:20px;padding:6px 16px;font-size:.8rem;color:#B7C0D1;margin-bottom:24px}
.ai-hero h1{font-family:'Space Grotesk',sans-serif;font-size:clamp(2.2rem,5vw,3.4rem);font-weight:800;line-height:1.15;margin-bottom:16px;background:linear-gradient(135deg,#F5F7FA 30%,#B7C0D1);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.ai-hero p{font-size:1.15rem;color:var(--ai-muted);margin-bottom:32px;line-height:1.6}
.ai-hero__cta{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* TRUST BAR */
.ai-trust{padding:40px 0;border-top:1px solid var(--ai-border);border-bottom:1px solid var(--ai-border)}
.ai-trust__grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center}
.ai-trust__item strong{display:block;font-size:1.6rem;font-weight:800;color:var(--ai-cyan);margin-bottom:4px}
.ai-trust__item span{font-size:.82rem;color:var(--ai-muted)}

/* BUTTONS */
.ai-btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 24px;border-radius:10px;font-weight:600;font-size:.9rem;text-decoration:none!important;transition:all .3s;border:none;cursor:pointer}
.ai-btn--primary{background:linear-gradient(135deg,var(--ai-purple),#5B3FCC);color:#fff!important;box-shadow:0 4px 20px rgba(124,92,255,.3)}
.ai-btn--primary:hover{box-shadow:0 8px 32px rgba(124,92,255,.5);transform:translateY(-2px)}
.ai-btn--outline{background:transparent;color:var(--ai-purple)!important;border:1.5px solid var(--ai-purple)}
.ai-btn--outline:hover{background:rgba(124,92,255,.08)}
.ai-btn--ghost{background:transparent;color:var(--ai-muted)!important;border:1.5px solid rgba(255,255,255,.15)}
.ai-btn--ghost:hover{border-color:var(--ai-cyan);color:var(--ai-cyan)!important}
.ai-btn--lg{padding:16px 36px;font-size:1.05rem}

/* SECTIONS */
.ai-sec{padding:72px 0}
.ai-sec--alt{background:var(--ai-bg2)}
.ai-sec__title{font-family:'Space Grotesk',sans-serif;text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:8px}
.ai-sec__sub{text-align:center;color:var(--ai-muted);margin-bottom:40px;font-size:1rem}

/* OUTCOMES */
.ai-outcomes{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.ai-outcome{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:14px;padding:28px 22px;transition:all .3s}
.ai-outcome:hover{border-color:var(--ai-purple);box-shadow:0 8px 28px rgba(124,92,255,.1);transform:translateY(-3px)}
.ai-outcome__icon{font-size:2rem;display:block;margin-bottom:12px}
.ai-outcome h3{font-size:.95rem;font-weight:700;margin-bottom:8px;color:var(--ai-text)}
.ai-outcome p{font-size:.85rem;color:var(--ai-muted);margin:0;line-height:1.5}

/* PRICING — 5 plans */
.ai-pricing{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;max-width:1200px;margin:0 auto}
.ai-plan{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:16px;padding:32px 18px;text-align:center;position:relative;transition:all .3s}
.ai-plan:hover{border-color:var(--ai-purple);transform:translateY(-4px)}
.ai-plan--pop{border:2px solid var(--ai-purple);box-shadow:0 0 40px rgba(124,92,255,.12);transform:scale(1.03)}
.ai-plan--pop:hover{transform:scale(1.03) translateY(-4px)}
.ai-plan__badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--ai-purple);color:#fff;padding:4px 14px;border-radius:20px;font-size:.72rem;font-weight:600;white-space:nowrap}
.ai-plan h3{font-size:1.1rem;font-weight:700;margin-bottom:4px;color:var(--ai-text)}
.ai-plan__for{font-size:.78rem;color:var(--ai-muted);margin-bottom:16px}
.ai-plan__price{font-size:2.4rem;font-weight:800;color:var(--ai-text);margin-bottom:2px}
.ai-plan__price span{font-size:.9rem;font-weight:400;color:var(--ai-muted)}
.ai-plan__period{color:var(--ai-muted);font-size:.82rem;margin-bottom:20px}
.ai-plan ul{list-style:none;padding:0;text-align:left;margin:0 0 24px;font-size:.82rem}
.ai-plan li{padding:7px 0;border-bottom:1px solid rgba(124,92,255,.06);color:#B7C0D1}
.ai-plan .ai-btn{width:100%}

/* TESTIMONIALS */
.ai-testimonials{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.ai-testimonial{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:14px;padding:28px 22px;transition:all .3s}
.ai-testimonial:hover{border-color:rgba(0,209,255,.3)}
.ai-testimonial p{font-size:.92rem;color:var(--ai-text);line-height:1.6;margin-bottom:16px;font-style:italic}
.ai-testimonial__author strong{display:block;font-size:.85rem;color:var(--ai-text)}
.ai-testimonial__author span{font-size:.78rem;color:var(--ai-muted)}

/* FAQ */
.ai-faq{max-width:700px;margin:0 auto}
.ai-faq__item{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:10px;margin-bottom:12px;overflow:hidden}
.ai-faq__item summary{padding:16px 20px;font-weight:600;cursor:pointer;color:var(--ai-text);font-size:.92rem;transition:color .2s}
.ai-faq__item summary:hover{color:var(--ai-cyan)}
.ai-faq__item p{padding:0 20px 16px;color:var(--ai-muted);font-size:.88rem;line-height:1.6;margin:0}

/* FINAL CTA */
.ai-final-cta{padding:80px 24px;text-align:center;background:radial-gradient(ellipse at center,rgba(124,92,255,.1) 0%,transparent 70%)}
.ai-final-cta h2{font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;margin-bottom:12px}
.ai-final-cta p{color:var(--ai-muted);margin-bottom:32px;font-size:1.05rem}

/* RESPONSIVE */
@media(max-width:1024px){
    .ai-pricing{grid-template-columns:repeat(3,1fr)}
}
@media(max-width:768px){
    .ai-outcomes,.ai-testimonials{grid-template-columns:1fr}
    .ai-pricing{grid-template-columns:1fr;max-width:360px}
    .ai-trust__grid{grid-template-columns:repeat(2,1fr);gap:16px}
    .ai-hero{padding:60px 20px 50px}
    .ai-plan--pop{transform:none}
    .ai-plan--pop:hover{transform:translateY(-4px)}
}
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.trackRevenueEvent?.('view_ai_pricing', {plan_count: {{ $plans->count() }}}, 'ai-pricing-view');
    document.querySelectorAll('[data-ai-plan]').forEach(function (link) {
        link.addEventListener('click', function () {
            window.trackRevenueEvent?.('select_ai_plan', {plan: link.dataset.aiPlan});
        });
    });
});
</script>
@endpush
