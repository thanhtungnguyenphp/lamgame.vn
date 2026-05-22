{{-- AI Tools — Minimal Pricing Landing Page --}}
@extends('layouts.master')

@section('page_title', 'AI Tools cho Game Developer — LamGame.vn')
@section('page_description', 'Công cụ AI hỗ trợ lập trình game. Gói Free, Pro $9/tháng, Business $29/tháng.')

@section('content')
<div class="ai-page">

{{-- HERO --}}
<section class="ai-hero">
    <div class="ai-hero__bg"></div>
    <div class="ai-hero__content">
        <span class="ai-badge">✨ Powered by GPT-4 & Claude</span>
        <h1>AI Tools cho<br>Game Developer</h1>
        <p>Tăng tốc phát triển game 10x. Code, Debug, Test, Review — tất cả bằng AI.</p>
        <div class="ai-hero__cta">
            <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--primary">Dùng thử miễn phí</a>
            <a href="#pricing" class="ai-btn ai-btn--ghost">Xem bảng giá ↓</a>
        </div>
        <div class="ai-hero__stats">
            <span><strong>5.200+</strong> lượt dùng</span>
            <span><strong>6</strong> công cụ AI</span>
            <span><strong>98%</strong> hài lòng</span>
        </div>
    </div>
</section>

{{-- TOOLS --}}
<section class="ai-sec">
    <div class="ai-container">
        <h2 class="ai-sec__title">6 công cụ AI chuyên biệt</h2>
        <p class="ai-sec__sub">Mỗi tool được fine-tune cho game development</p>
        <div class="ai-tools-grid">
            <div class="ai-tool"><span class="ai-tool__icon">🎮</span><h3>Game Concept</h3><p>Brainstorm ý tưởng game, GDD outline</p></div>
            <div class="ai-tool"><span class="ai-tool__icon">⚡</span><h3>Code Generate</h3><p>Sinh code Unity/Unreal/Godot</p></div>
            <div class="ai-tool"><span class="ai-tool__icon">🐛</span><h3>Debug</h3><p>Tìm & fix bug tự động</p></div>
            <div class="ai-tool"><span class="ai-tool__icon">🧪</span><h3>Unit Test</h3><p>Tạo test cases cho game logic</p></div>
            <div class="ai-tool"><span class="ai-tool__icon">🔍</span><h3>Code Review</h3><p>Review code, gợi ý tối ưu</p></div>
            <div class="ai-tool"><span class="ai-tool__icon">🎨</span><h3>Asset Generate</h3><p>Tạo sprite, UI, tilemap bằng AI</p></div>
        </div>
    </div>
</section>

{{-- PRICING --}}
<section class="ai-sec ai-sec--alt" id="pricing">
    <div class="ai-container">
        <h2 class="ai-sec__title">Chọn gói phù hợp</h2>
        <p class="ai-sec__sub">Bắt đầu miễn phí, nâng cấp khi cần</p>
        <div class="ai-pricing">
            {{-- FREE --}}
            <div class="ai-plan">
                <h3>Free</h3>
                <div class="ai-plan__price">$0</div>
                <div class="ai-plan__period">mãi mãi</div>
                <ul>
                    <li>✅ 20 requests/ngày</li>
                    <li>✅ 3 tools cơ bản</li>
                    <li>✅ GPT-3.5</li>
                    <li>❌ Priority queue</li>
                    <li>❌ API access</li>
                </ul>
                <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--outline">Bắt đầu miễn phí</a>
            </div>
            {{-- PRO --}}
            <div class="ai-plan ai-plan--pop">
                <span class="ai-plan__badge">Phổ biến nhất</span>
                <h3>Pro</h3>
                <div class="ai-plan__price">$9<span>/tháng</span></div>
                <div class="ai-plan__period">~210.000₫</div>
                <ul>
                    <li>✅ 200 requests/ngày</li>
                    <li>✅ Tất cả 6 tools</li>
                    <li>✅ GPT-4 + Claude</li>
                    <li>✅ Priority queue</li>
                    <li>❌ API access</li>
                </ul>
                <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--primary">Nâng cấp Pro</a>
            </div>
            {{-- BUSINESS --}}
            <div class="ai-plan">
                <h3>Business</h3>
                <div class="ai-plan__price">$29<span>/tháng</span></div>
                <div class="ai-plan__period">~680.000₫</div>
                <ul>
                    <li>✅ Unlimited requests</li>
                    <li>✅ Tất cả 6 tools</li>
                    <li>✅ GPT-4 + Claude</li>
                    <li>✅ Priority queue</li>
                    <li>✅ API access</li>
                </ul>
                <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--outline">Chọn Business</a>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="ai-sec">
    <div class="ai-container">
        <h2 class="ai-sec__title">3 bước đơn giản</h2>
        <div class="ai-steps">
            <div class="ai-step"><span class="ai-step__num">1</span><h3>Đăng ký</h3><p>Tạo tài khoản miễn phí trong 30 giây</p></div>
            <div class="ai-step"><span class="ai-step__num">2</span><h3>Chọn tool</h3><p>Chọn công cụ AI phù hợp nhu cầu</p></div>
            <div class="ai-step"><span class="ai-step__num">3</span><h3>Nhận kết quả</h3><p>AI xử lý và trả kết quả trong vài giây</p></div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="ai-sec ai-sec--alt">
    <div class="ai-container">
        <h2 class="ai-sec__title">Câu hỏi thường gặp</h2>
        <div class="ai-faq">
            <details class="ai-faq__item"><summary>Có cần biết code để dùng AI Tools?</summary><p>Không bắt buộc. AI Tools hỗ trợ cả người mới bắt đầu. Bạn chỉ cần mô tả yêu cầu bằng tiếng Việt.</p></details>
            <details class="ai-faq__item"><summary>Gói Free có giới hạn gì?</summary><p>Gói Free cho phép 20 requests/ngày với 3 tools cơ bản (Concept, Code, Debug). Đủ để trải nghiệm trước khi nâng cấp.</p></details>
            <details class="ai-faq__item"><summary>Có thể hủy gói Pro bất cứ lúc nào?</summary><p>Có. Bạn có thể hủy subscription bất cứ lúc nào. Gói sẽ còn hiệu lực đến hết chu kỳ thanh toán.</p></details>
            <details class="ai-faq__item"><summary>AI có hỗ trợ Unity, Unreal, Godot?</summary><p>Có. AI được train trên code của cả 3 engine phổ biến nhất. Bạn chỉ cần chọn engine khi sử dụng.</p></details>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="ai-final-cta">
    <div class="ai-container">
        <h2>Sẵn sàng tăng tốc phát triển game?</h2>
        <p>Tham gia 5.200+ developer đang dùng AI Tools mỗi ngày</p>
        <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--primary ai-btn--lg">Dùng thử miễn phí →</a>
    </div>
</section>

</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{--ai-bg:#070B14;--ai-bg2:#0B1020;--ai-bg3:#111827;--ai-purple:#7C5CFF;--ai-cyan:#00D1FF;--ai-pink:#FF4D8D;--ai-text:#F5F7FA;--ai-muted:#7A8599;--ai-border:rgba(124,92,255,.12)}
.ai-page{background:var(--ai-bg);color:var(--ai-text);font-family:'Inter',sans-serif}
.ai-container{max-width:1100px;margin:0 auto;padding:0 24px}

/* HERO */
.ai-hero{position:relative;padding:100px 24px 80px;text-align:center;overflow:hidden}
.ai-hero__bg{position:absolute;inset:0;background:radial-gradient(ellipse at 50% 30%,rgba(124,92,255,.15) 0%,transparent 60%),radial-gradient(ellipse at 80% 70%,rgba(0,209,255,.08) 0%,transparent 50%)}
.ai-hero__content{position:relative;max-width:640px;margin:0 auto}
.ai-badge{display:inline-block;background:rgba(124,92,255,.1);border:1px solid rgba(124,92,255,.3);border-radius:20px;padding:6px 16px;font-size:.8rem;color:#B7C0D1;margin-bottom:24px}
.ai-hero h1{font-family:'Space Grotesk',sans-serif;font-size:clamp(2.4rem,5vw,3.6rem);font-weight:800;line-height:1.1;margin-bottom:16px;background:linear-gradient(135deg,#F5F7FA,#B7C0D1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.ai-hero p{font-size:1.15rem;color:var(--ai-muted);margin-bottom:32px;line-height:1.6}
.ai-hero__cta{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:40px}
.ai-hero__stats{display:flex;gap:32px;justify-content:center;font-size:.9rem;color:var(--ai-muted)}
.ai-hero__stats strong{color:var(--ai-cyan);font-weight:700}

/* BUTTONS */
.ai-btn{display:inline-flex;align-items:center;padding:12px 24px;border-radius:8px;font-weight:600;font-size:.9rem;text-decoration:none!important;transition:all .3s;border:none;cursor:pointer}
.ai-btn--primary{background:linear-gradient(135deg,var(--ai-purple),#5B3FCC);color:#fff!important;box-shadow:0 4px 20px rgba(124,92,255,.3)}
.ai-btn--primary:hover{box-shadow:0 6px 30px rgba(124,92,255,.5);transform:translateY(-2px)}
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

/* TOOLS GRID */
.ai-tools-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.ai-tool{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:14px;padding:28px 20px;text-align:center;transition:all .3s}
.ai-tool:hover{border-color:var(--ai-purple);box-shadow:0 0 25px rgba(124,92,255,.12);transform:translateY(-3px)}
.ai-tool__icon{font-size:2.2rem;display:block;margin-bottom:12px}
.ai-tool h3{font-size:1rem;font-weight:600;margin-bottom:6px;color:var(--ai-text)}
.ai-tool p{font-size:.85rem;color:var(--ai-muted);margin:0}

/* PRICING */
.ai-pricing{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:960px;margin:0 auto}
.ai-plan{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:16px;padding:36px 24px;text-align:center;position:relative;transition:all .3s}
.ai-plan:hover{border-color:var(--ai-purple)}
.ai-plan--pop{border:2px solid var(--ai-purple);box-shadow:0 0 40px rgba(124,92,255,.12)}
.ai-plan__badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--ai-purple);color:#fff;padding:4px 16px;border-radius:20px;font-size:.75rem;font-weight:600}
.ai-plan h3{font-size:1.2rem;font-weight:700;margin-bottom:12px;color:var(--ai-text)}
.ai-plan__price{font-size:2.8rem;font-weight:800;color:var(--ai-text);margin-bottom:4px}
.ai-plan__price span{font-size:1rem;font-weight:400;color:var(--ai-muted)}
.ai-plan__period{color:var(--ai-muted);font-size:.85rem;margin-bottom:24px}
.ai-plan ul{list-style:none;padding:0;text-align:left;margin:0 0 28px;font-size:.88rem}
.ai-plan li{padding:8px 0;border-bottom:1px solid rgba(124,92,255,.06);color:#B7C0D1}
.ai-plan .ai-btn{width:100%;justify-content:center}

/* STEPS */
.ai-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;text-align:center}
.ai-step{padding:24px}
.ai-step__num{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:rgba(124,92,255,.1);border:1px solid rgba(124,92,255,.3);border-radius:50%;font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:700;color:var(--ai-purple);margin-bottom:16px}
.ai-step h3{font-size:1rem;font-weight:600;margin-bottom:8px;color:var(--ai-text)}
.ai-step p{font-size:.85rem;color:var(--ai-muted);margin:0}

/* FAQ */
.ai-faq{max-width:700px;margin:0 auto}
.ai-faq__item{background:rgba(17,24,39,.6);border:1px solid var(--ai-border);border-radius:10px;margin-bottom:12px;overflow:hidden}
.ai-faq__item summary{padding:16px 20px;font-weight:600;cursor:pointer;color:var(--ai-text);font-size:.95rem}
.ai-faq__item summary:hover{color:var(--ai-cyan)}
.ai-faq__item p{padding:0 20px 16px;color:var(--ai-muted);font-size:.9rem;line-height:1.6;margin:0}

/* FINAL CTA */
.ai-final-cta{padding:80px 24px;text-align:center;background:radial-gradient(ellipse at center,rgba(124,92,255,.1) 0%,transparent 70%)}
.ai-final-cta h2{font-family:'Space Grotesk',sans-serif;font-size:2rem;font-weight:700;margin-bottom:12px}
.ai-final-cta p{color:var(--ai-muted);margin-bottom:32px;font-size:1.05rem}

/* RESPONSIVE */
@media(max-width:768px){
    .ai-tools-grid,.ai-pricing,.ai-steps{grid-template-columns:1fr}
    .ai-hero{padding:60px 20px 50px}
    .ai-hero__stats{flex-direction:column;gap:12px}
}
</style>
@endpush
