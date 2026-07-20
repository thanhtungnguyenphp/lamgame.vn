{{-- AI Tools — Marketplace Landing Page (Optimized UX/UI) --}}
@extends('layouts.master')

@section('page_title', 'AI Tools cho Game Developer — LamGame.vn')
@section('page_description', 'Công cụ AI giúp game developer tiết kiệm 80% thời gian. Code, Debug, Test, Review — tất cả bằng AI.')

@section('content')
<div class="ai-page">

{{-- HERO --}}
<section class="ai-hero">
    <div class="ai-hero__bg"></div>
    <div class="ai-hero__content">
        <span class="ai-badge">✨ Powered by GPT-4 & Claude</span>
        <h1>Tiết kiệm 80% thời gian <br>phát triển game</h1>
        <p>Code, Debug, Test, Review — AI xử lý trong vài giây. Bạn tập trung vào sáng tạo.</p>
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
            <div class="ai-trust__item"><strong>5.200+</strong><span>Developer đang dùng</span></div>
            <div class="ai-trust__item"><strong>120.000+</strong><span>Requests đã xử lý</span></div>
            <div class="ai-trust__item"><strong>98%</strong><span>Hài lòng</span></div>
            <div class="ai-trust__item"><strong>6</strong><span>Công cụ AI chuyên biệt</span></div>
        </div>
    </div>
</section>

{{-- USE CASES — Outcome driven --}}
<section class="ai-sec">
    <div class="ai-container">
        <h2 class="ai-sec__title">AI giải quyết gì cho bạn?</h2>
        <p class="ai-sec__sub">Không chỉ là tools — mà là kết quả thực tế</p>
        <div class="ai-outcomes">
            <div class="ai-outcome">
                <span class="ai-outcome__icon">⚡</span>
                <h3>Giảm 80% thời gian code</h3>
                <p>AI sinh code Unity/Unreal/Godot chính xác. Bạn chỉ cần mô tả logic.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🐛</span>
                <h3>Fix bug trong 10 giây</h3>
                <p>Paste error → AI phân tích nguyên nhân → đề xuất fix ngay lập tức.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🧪</span>
                <h3>Test coverage tăng 5x</h3>
                <p>AI tạo unit test tự động cho game logic, không cần viết tay.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">💰</span>
                <h3>Tiết kiệm $2.000+/tháng</h3>
                <p>Thay thế 1 junior developer. ROI dương từ tuần đầu tiên.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🎨</span>
                <h3>Asset trong 30 giây</h3>
                <p>Tạo sprite, UI, tilemap bằng AI — không cần designer.</p>
            </div>
            <div class="ai-outcome">
                <span class="ai-outcome__icon">🔍</span>
                <h3>Code review chuyên nghiệp</h3>
                <p>AI review code như senior dev — phát hiện bug, gợi ý tối ưu.</p>
            </div>
        </div>
    </div>
</section>

{{-- PRICING — 5 Plans --}}
<section class="ai-sec ai-sec--alt" id="pricing">
    <div class="ai-container">
        <h2 class="ai-sec__title">Chọn gói phù hợp</h2>
        <p class="ai-sec__sub">Bắt đầu miễn phí, nâng cấp khi cần</p>
        <div class="ai-pricing">
            {{-- FREE --}}
            <div class="ai-plan">
                <h3>Free</h3>
                <p class="ai-plan__for">Cho người mới bắt đầu</p>
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
            {{-- BASIC --}}
            <div class="ai-plan">
                <h3>Basic</h3>
                <p class="ai-plan__for">Cho indie developer</p>
                <div class="ai-plan__price">$5<span>/tháng</span></div>
                <div class="ai-plan__period">~115.000₫</div>
                <ul>
                    <li>✅ 80 requests/ngày</li>
                    <li>✅ 5 tools</li>
                    <li>✅ GPT-4</li>
                    <li>❌ Priority queue</li>
                    <li>❌ API access</li>
                </ul>
                <a href="{{ route('lamgame.ai-tools-dashboard') }}?subscribe=basic" class="ai-btn ai-btn--outline">Chọn Basic</a>
            </div>
            {{-- PRO --}}
            <div class="ai-plan ai-plan--pop">
                <span class="ai-plan__badge">Phổ biến nhất</span>
                <h3>Pro</h3>
                <p class="ai-plan__for">Cho developer chuyên nghiệp</p>
                <div class="ai-plan__price">$9<span>/tháng</span></div>
                <div class="ai-plan__period">~210.000₫</div>
                <ul>
                    <li>✅ 200 requests/ngày</li>
                    <li>✅ Tất cả 6 tools</li>
                    <li>✅ GPT-4 + Claude</li>
                    <li>✅ Priority queue</li>
                    <li>❌ API access</li>
                </ul>
                <a href="{{ route('lamgame.ai-tools-dashboard') }}?subscribe=pro" class="ai-btn ai-btn--primary">Nâng cấp Pro</a>
            </div>
            {{-- STUDIO --}}
            <div class="ai-plan">
                <h3>Studio</h3>
                <p class="ai-plan__for">Cho team 2-10 người</p>
                <div class="ai-plan__price">$29<span>/tháng</span></div>
                <div class="ai-plan__period">~680.000₫</div>
                <ul>
                    <li>✅ Unlimited requests</li>
                    <li>✅ Tất cả 6 tools</li>
                    <li>✅ GPT-4 + Claude</li>
                    <li>✅ Priority queue</li>
                    <li>✅ API access</li>
                </ul>
                <a href="{{ route('lamgame.ai-tools-dashboard') }}?subscribe=studio" class="ai-btn ai-btn--outline">Chọn Studio</a>
            </div>
            {{-- ENTERPRISE --}}
            <div class="ai-plan">
                <h3>Enterprise</h3>
                <p class="ai-plan__for">Cho studio lớn</p>
                <div class="ai-plan__price">Liên hệ</div>
                <div class="ai-plan__period">tuỳ chỉnh</div>
                <ul>
                    <li>✅ Unlimited everything</li>
                    <li>✅ Custom models</li>
                    <li>✅ Dedicated support</li>
                    <li>✅ On-premise option</li>
                    <li>✅ SLA 99.9%</li>
                </ul>
                <a href="/contact" class="ai-btn ai-btn--outline">Liên hệ sales</a>
            </div>
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="ai-sec">
    <div class="ai-container">
        <h2 class="ai-sec__title">Developer nói gì?</h2>
        <div class="ai-testimonials">
            <div class="ai-testimonial">
                <p>"AI Code Generate giúp tôi ship game đầu tiên trong 2 tuần thay vì 2 tháng."</p>
                <div class="ai-testimonial__author"><strong>Minh Tuấn</strong><span>Indie Developer</span></div>
            </div>
            <div class="ai-testimonial">
                <p>"Debug tool tìm ra bug mà team tôi mất 3 ngày không fix được. Chỉ trong 10 giây."</p>
                <div class="ai-testimonial__author"><strong>Hoàng Nam</strong><span>Unity Developer</span></div>
            </div>
            <div class="ai-testimonial">
                <p>"Tiết kiệm ít nhất 15 giờ/tuần cho team 5 người. ROI cực kỳ tốt."</p>
                <div class="ai-testimonial__author"><strong>Thu Hà</strong><span>Studio Lead</span></div>
            </div>
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
            <details class="ai-faq__item"><summary>Có thể hủy gói bất cứ lúc nào?</summary><p>Có. Bạn có thể hủy subscription bất cứ lúc nào. Gói sẽ còn hiệu lực đến hết chu kỳ thanh toán.</p></details>
            <details class="ai-faq__item"><summary>AI có hỗ trợ Unity, Unreal, Godot?</summary><p>Có. AI được train trên code của cả 3 engine phổ biến nhất. Bạn chỉ cần chọn engine khi sử dụng.</p></details>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="ai-final-cta">
    <div class="ai-container">
        <h2>Sẵn sàng tiết kiệm hàng trăm giờ?</h2>
        <p>Tham gia 5.200+ developer đang dùng AI Tools mỗi ngày</p>
        <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--primary ai-btn--lg">Dùng thử miễn phí →</a>
    </div>
</section>

</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Be+Vietnam+Pro:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
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
