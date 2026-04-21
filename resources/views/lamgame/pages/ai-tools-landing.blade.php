@extends('layouts.master')

@section('page_title', 'AI Tools cho Game Developer — Làm Game')
@section('page_description', 'Tăng tốc phát triển game với AI: Game Concept, Code Generate, Debug, Unit Test, Code Review. Miễn phí bắt đầu.')

@push('meta')
<meta property="og:title" content="AI Tools cho Game Developer — Làm Game">
<meta property="og:description" content="Công cụ AI hỗ trợ lập trình game. Gói Free, Pro $9/tháng, Business $29/tháng.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/ai-tools') }}">
<link rel="canonical" href="{{ url('/ai-tools') }}">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => 'AI Tools cho Game Developer',
    'description' => 'Công cụ AI hỗ trợ lập trình game: Game Concept, Code Generate, Debug, Unit Test, Code Review.',
    'url' => url('/ai-tools'),
    'provider' => ['@type' => 'Organization', 'name' => 'Làm Game', 'url' => 'https://lamgame.vn'],
    'offers' => [
        ['@type' => 'Offer', 'name' => 'Free', 'price' => '0', 'priceCurrency' => 'USD'],
        ['@type' => 'Offer', 'name' => 'Pro', 'price' => '9', 'priceCurrency' => 'USD'],
        ['@type' => 'Offer', 'name' => 'Business', 'price' => '29', 'priceCurrency' => 'USD'],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Trang chủ', 'item' => config('app.url')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'AI Tools', 'item' => url('/ai-tools')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<style>
:root{--ai-primary:#3b82f6;--ai-dark:#0f172a;--ai-gray:#64748b}
.ai-lp *{box-sizing:border-box}
.ai-lp section{padding:64px 0}
.ai-lp .container{max-width:1100px;margin:0 auto;padding:0 20px}

/* Hero */
.ai-hero{background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#0f172a 100%);padding:80px 0;text-align:center;color:#fff;position:relative;overflow:hidden}
.ai-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 50%,rgba(59,130,246,.15),transparent 60%),radial-gradient(circle at 70% 50%,rgba(139,92,246,.1),transparent 60%)}
.ai-hero h1{font-size:2.8rem;font-weight:800;margin:0 0 16px;position:relative}
.ai-hero p{font-size:1.2rem;color:#94a3b8;max-width:640px;margin:0 auto 32px;position:relative}
.ai-hero__cta{display:inline-flex;gap:12px;flex-wrap:wrap;justify-content:center;position:relative}
.ai-btn{display:inline-block;padding:14px 32px;border-radius:10px;font-size:1rem;font-weight:600;text-decoration:none;transition:transform .2s,box-shadow .2s}
.ai-btn:hover{transform:translateY(-2px)}
.ai-btn--primary{background:var(--ai-primary);color:#fff;box-shadow:0 4px 20px rgba(59,130,246,.4)}
.ai-btn--outline{border:2px solid rgba(255,255,255,.3);color:#fff}
.ai-btn--outline:hover{border-color:#fff}

/* Stats */
.ai-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;margin-top:48px;position:relative}
.ai-stat{text-align:center}
.ai-stat__num{font-size:2rem;font-weight:800;color:#fff}
.ai-stat__label{font-size:.85rem;color:#94a3b8;margin-top:4px}

/* Tools showcase */
.ai-tools-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px}
.ai-tool-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:32px 24px;transition:box-shadow .2s}
.ai-tool-card:hover{box-shadow:0 8px 30px rgba(0,0,0,.08)}
.ai-tool-card__icon{font-size:2.2rem;margin-bottom:12px}
.ai-tool-card h3{font-size:1.15rem;font-weight:700;margin:0 0 8px}
.ai-tool-card p{color:var(--ai-gray);font-size:.92rem;line-height:1.6;margin:0}

/* How it works */
.ai-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:32px;text-align:center}
.ai-step__num{width:48px;height:48px;border-radius:50%;background:var(--ai-primary);color:#fff;font-size:1.2rem;font-weight:700;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px}
.ai-step h3{font-size:1.05rem;font-weight:700;margin:0 0 8px}
.ai-step p{color:var(--ai-gray);font-size:.9rem;line-height:1.5}

/* Use cases */
.ai-usecases{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px}
.ai-usecase{background:#f8fafc;border-radius:12px;padding:24px;border-left:4px solid var(--ai-primary)}
.ai-usecase h4{font-weight:700;margin:0 0 6px;font-size:1rem}
.ai-usecase p{color:var(--ai-gray);font-size:.88rem;margin:0;line-height:1.5}

/* Pricing */
.ai-pricing{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;max-width:960px;margin:0 auto}
.ai-plan{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:36px 24px;text-align:center;position:relative}
.ai-plan--pop{border:2px solid var(--ai-primary);box-shadow:0 8px 30px rgba(59,130,246,.12)}
.ai-plan__badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--ai-primary);color:#fff;padding:4px 16px;border-radius:20px;font-size:.78rem;font-weight:600}
.ai-plan h3{font-size:1.3rem;font-weight:700;margin:0 0 8px}
.ai-plan__price{font-size:2.4rem;font-weight:800;margin-bottom:4px}
.ai-plan__period{color:var(--ai-gray);font-size:.9rem;margin-bottom:20px}
.ai-plan ul{list-style:none;padding:0;text-align:left;margin:0 0 24px;font-size:.9rem;color:#475569}
.ai-plan li{padding:7px 0;border-bottom:1px solid #f1f5f9}
.ai-plan li:last-child{border:none}
.ai-plan .ai-btn{width:100%;text-align:center}

/* Features table */
.ai-ftable{width:100%;border-collapse:collapse;font-size:.92rem}
.ai-ftable th,.ai-ftable td{padding:11px 16px;border-bottom:1px solid #f1f5f9}
.ai-ftable thead th{background:#f1f5f9;font-weight:600;border-bottom:2px solid #e2e8f0}
.ai-ftable th:first-child,.ai-ftable td:first-child{text-align:left}
.ai-ftable th:not(:first-child),.ai-ftable td:not(:first-child){text-align:center}
.ai-ftable .pop-col{background:#eff6ff}

/* FAQ */
.ai-faq{max-width:700px;margin:0 auto}
.ai-faq__item{margin-bottom:16px}
.ai-faq__q{font-weight:600;margin:0 0 6px;font-size:1rem;cursor:pointer}
.ai-faq__a{color:var(--ai-gray);font-size:.92rem;line-height:1.6;margin:0}

/* CTA bottom */
.ai-cta-bottom{background:linear-gradient(135deg,#0f172a,#1e3a5f);text-align:center;color:#fff}
.ai-cta-bottom h2{font-size:2rem;font-weight:800;margin:0 0 12px}
.ai-cta-bottom p{color:#94a3b8;margin:0 0 28px;font-size:1.05rem}

.ai-section-title{text-align:center;font-size:1.8rem;font-weight:700;margin:0 0 12px}
.ai-section-sub{text-align:center;color:var(--ai-gray);margin:0 0 40px;font-size:1rem}

@media(max-width:768px){
    .ai-hero h1{font-size:2rem}
    .ai-stats{grid-template-columns:repeat(2,1fr);gap:16px}
    .ai-steps{grid-template-columns:1fr;gap:24px}
    .ai-tools-grid{grid-template-columns:1fr}
    .ai-pricing{grid-template-columns:1fr;max-width:400px}
}
</style>
@endpush

@section('content')
<div class="ai-lp">
    {{-- Flash messages --}}
    @if(session('success'))
    <div style="background:#166534;color:#4ade80;text-align:center;padding:12px;font-weight:600">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#7f1d1d;color:#fca5a5;text-align:center;padding:12px;font-weight:600">❌ {{ session('error') }}</div>
    @endif

    {{-- HERO --}}
    <section class="ai-hero">
        <div class="container">
            <h1>🤖 AI Tools cho Game Developer</h1>
            <p>Từ ý tưởng đến code hoàn chỉnh trong vài phút. Tạo Game Concept, sinh code, debug, viết test — tất cả bằng AI.</p>
            <div class="ai-hero__cta">
                <a href="#pricing" class="ai-btn ai-btn--primary">Bắt đầu miễn phí</a>
                @if($customer)
                <a href="{{ route('lamgame.ai-tools-dashboard') }}" class="ai-btn ai-btn--outline">Vào Dashboard</a>
                @else
                <a href="#tools" class="ai-btn ai-btn--outline">Xem tính năng</a>
                @endif
            </div>
            <div class="ai-stats">
                <div class="ai-stat"><div class="ai-stat__num">6</div><div class="ai-stat__label">AI Tools</div></div>
                <div class="ai-stat"><div class="ai-stat__num">5+</div><div class="ai-stat__label">AI Models</div></div>
                <div class="ai-stat"><div class="ai-stat__num">$0</div><div class="ai-stat__label">Bắt đầu</div></div>
                <div class="ai-stat"><div class="ai-stat__num">&lt;10s</div><div class="ai-stat__label">Phản hồi</div></div>
            </div>
        </div>
    </section>

    {{-- TOOLS SHOWCASE --}}
    <section id="tools" style="background:#f8fafc">
        <div class="container">
            <h2 class="ai-section-title">Bộ công cụ AI toàn diện</h2>
            <p class="ai-section-sub">Mọi thứ bạn cần để phát triển game nhanh hơn</p>
            <div class="ai-tools-grid">
                <div class="ai-tool-card">
                    <div class="ai-tool-card__icon">💡</div>
                    <h3>AI Game Concept</h3>
                    <p>Mô tả ý tưởng → nhận Game Design Document hoàn chỉnh: gameplay, mechanics, monetization, tech stack.</p>
                </div>
                <div class="ai-tool-card">
                    <div class="ai-tool-card__icon">⚡</div>
                    <h3>AI Code Generate</h3>
                    <p>Sinh code Unity C#, Godot GDScript, Phaser JS từ mô tả. Hỗ trợ player controller, UI, inventory, save system.</p>
                </div>
                <div class="ai-tool-card">
                    <div class="ai-tool-card__icon">🐛</div>
                    <h3>AI Debug</h3>
                    <p>Paste code lỗi → AI phân tích nguyên nhân, đề xuất fix, giải thích chi tiết. Hỗ trợ C#, GDScript, JS.</p>
                </div>
                <div class="ai-tool-card">
                    <div class="ai-tool-card__icon">🧪</div>
                    <h3>AI Unit Test</h3>
                    <p>Tự động sinh unit test cho game logic. NUnit (Unity), GUT (Godot), Jest (Phaser).</p>
                </div>
                <div class="ai-tool-card">
                    <div class="ai-tool-card__icon">🔍</div>
                    <h3>AI Code Review</h3>
                    <p>Review code theo best practices: performance, memory, architecture. Gợi ý refactor cụ thể.</p>
                </div>
                <div class="ai-tool-card">
                    <div class="ai-tool-card__icon">🎨</div>
                    <h3>AI Asset Generate</h3>
                    <p>Tạo sprite, icon, UI mockup từ mô tả text. Pixel art, flat design, cartoon style.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section>
        <div class="container">
            <h2 class="ai-section-title">Cách hoạt động</h2>
            <p class="ai-section-sub">3 bước đơn giản để bắt đầu</p>
            <div class="ai-steps">
                <div class="ai-step">
                    <div class="ai-step__num">1</div>
                    <h3>Đăng ký miễn phí</h3>
                    <p>Tạo tài khoản và bắt đầu với gói Free — không cần thẻ tín dụng.</p>
                </div>
                <div class="ai-step">
                    <div class="ai-step__num">2</div>
                    <h3>Chọn công cụ AI</h3>
                    <p>Vào Dashboard, chọn tool phù hợp: Concept, Code, Debug, Test, Review.</p>
                </div>
                <div class="ai-step">
                    <div class="ai-step__num">3</div>
                    <h3>Nhận kết quả</h3>
                    <p>AI xử lý và trả kết quả trong vài giây. Copy, chỉnh sửa, sử dụng ngay.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- USE CASES --}}
    <section style="background:#f8fafc">
        <div class="container">
            <h2 class="ai-section-title">Ai nên dùng?</h2>
            <p class="ai-section-sub">Phù hợp với mọi cấp độ game developer</p>
            <div class="ai-usecases">
                <div class="ai-usecase">
                    <h4>🎓 Người mới học</h4>
                    <p>Tạo game concept đầu tiên, sinh code mẫu để học, debug lỗi nhanh khi mắc kẹt.</p>
                </div>
                <div class="ai-usecase">
                    <h4>👨‍💻 Indie Developer</h4>
                    <p>Tăng tốc prototype, sinh boilerplate code, viết test tự động cho game logic.</p>
                </div>
                <div class="ai-usecase">
                    <h4>🏢 Studio nhỏ</h4>
                    <p>Code review tự động, tạo GDD nhanh cho pitch, sinh asset placeholder.</p>
                </div>
                <div class="ai-usecase">
                    <h4>📚 Giáo viên / Mentor</h4>
                    <p>Tạo bài tập game dev, sinh code mẫu cho học viên, review bài nộp.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="pricing">
        <div class="container">
            <h2 class="ai-section-title">Bảng giá</h2>
            <p class="ai-section-sub">Bắt đầu miễn phí, nâng cấp khi cần</p>
            <div class="ai-pricing">
                {{-- Free --}}
                <div class="ai-plan">
                    <h3>Free</h3>
                    <div class="ai-plan__price">$0</div>
                    <div class="ai-plan__period">Miễn phí mãi mãi</div>
                    <ul>
                        <li>✦ AI Game Concept: 3/tháng</li>
                        <li>✦ AI Model: GPT-4o mini</li>
                        <li>✦ Chat History: 7 ngày</li>
                        <li>✦ Ứng tuyển việc: 3/tháng</li>
                    </ul>
                    @if($customer)
                    <form method="POST" action="{{ route('lamgame.ai-subscribe') }}">
                        @csrf
                        <input type="hidden" name="plan" value="free">
                        <button type="submit" class="ai-btn" style="background:#1e293b;color:#fff;border:none;cursor:pointer">Bắt đầu miễn phí</button>
                    </form>
                    @else
                    <a href="{{ route('shop.customer.session.index') }}" class="ai-btn" style="background:#1e293b;color:#fff">Đăng ký ngay</a>
                    @endif
                </div>
                {{-- Pro --}}
                <div class="ai-plan ai-plan--pop">
                    <div class="ai-plan__badge">Phổ biến nhất</div>
                    <h3>Pro</h3>
                    <div class="ai-plan__price">$9</div>
                    <div class="ai-plan__period">/tháng</div>
                    <ul>
                        <li>✦ AI Concept: 100/tháng</li>
                        <li>✦ Code Generate: 50/tháng</li>
                        <li>✦ Debug: 30/tháng</li>
                        <li>✦ Unit Test: 20/tháng</li>
                        <li>✦ Code Review: 10/tháng</li>
                        <li>✦ AI Model: GPT-4o</li>
                        <li>✦ Export Project ✅</li>
                        <li>✦ Priority Queue ✅</li>
                    </ul>
                    @if($customer)
                    <form method="POST" action="{{ route('lamgame.ai-subscribe') }}">
                        @csrf
                        <input type="hidden" name="plan" value="pro">
                        <button type="submit" class="ai-btn ai-btn--primary" style="border:none;cursor:pointer">Đăng ký Pro</button>
                    </form>
                    @else
                    <a href="{{ route('shop.customer.session.index') }}" class="ai-btn ai-btn--primary">Đăng ký Pro</a>
                    @endif
                </div>
                {{-- Business --}}
                <div class="ai-plan">
                    <h3>Business</h3>
                    <div class="ai-plan__price">$29</div>
                    <div class="ai-plan__period">/tháng</div>
                    <ul>
                        <li>✦ Tất cả tool: ♾️ Unlimited</li>
                        <li>✦ AI Asset Generate: 100/tháng</li>
                        <li>✦ AI Model: GPT-4o + Claude</li>
                        <li>✦ Featured Job: 2/tháng</li>
                        <li>✦ Freelancer Contact ✅</li>
                        <li>✦ Chat History: ♾️</li>
                        <li>✦ Priority Queue ✅</li>
                    </ul>
                    @if($customer)
                    <form method="POST" action="{{ route('lamgame.ai-subscribe') }}">
                        @csrf
                        <input type="hidden" name="plan" value="business">
                        <button type="submit" class="ai-btn" style="background:#1e293b;color:#fff;border:none;cursor:pointer">Đăng ký Business</button>
                    </form>
                    @else
                    <a href="{{ route('shop.customer.session.index') }}" class="ai-btn" style="background:#1e293b;color:#fff">Đăng ký Business</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES TABLE --}}
    <section style="background:#f8fafc">
        <div class="container" style="max-width:800px">
            <h2 class="ai-section-title">So sánh chi tiết</h2>
            <div style="overflow-x:auto">
                <table class="ai-ftable">
                    <thead>
                        <tr>
                            <th>Tính năng</th>
                            <th>Free</th>
                            <th class="pop-col">Pro</th>
                            <th>Business</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>AI Game Concept</td><td>3/tháng</td><td class="pop-col">100/tháng</td><td>♾️</td></tr>
                        <tr><td>AI Code Generate</td><td>❌</td><td class="pop-col">50/tháng</td><td>♾️</td></tr>
                        <tr><td>AI Debug</td><td>❌</td><td class="pop-col">30/tháng</td><td>♾️</td></tr>
                        <tr><td>AI Unit Test</td><td>❌</td><td class="pop-col">20/tháng</td><td>♾️</td></tr>
                        <tr><td>AI Code Review</td><td>❌</td><td class="pop-col">10/tháng</td><td>♾️</td></tr>
                        <tr><td>AI Asset Generate</td><td>❌</td><td class="pop-col">❌</td><td>100/tháng</td></tr>
                        <tr><td>AI Model</td><td>GPT-4o mini</td><td class="pop-col">GPT-4o</td><td>GPT-4o + Claude</td></tr>
                        <tr><td>Export Project</td><td>❌</td><td class="pop-col">✅</td><td>✅</td></tr>
                        <tr><td>Chat History</td><td>7 ngày</td><td class="pop-col">30 ngày</td><td>♾️</td></tr>
                        <tr><td>Priority Queue</td><td>❌</td><td class="pop-col">✅</td><td>✅</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section>
        <div class="container ai-faq">
            <h2 class="ai-section-title">Câu hỏi thường gặp</h2>
            <p class="ai-section-sub">&nbsp;</p>
            <div class="ai-faq__item">
                <h4 class="ai-faq__q">Thanh toán bằng gì?</h4>
                <p class="ai-faq__a">Thanh toán qua PayPal — hỗ trợ Visa, Mastercard, tài khoản PayPal quốc tế.</p>
            </div>
            <div class="ai-faq__item">
                <h4 class="ai-faq__q">Có thể hủy bất cứ lúc nào không?</h4>
                <p class="ai-faq__a">Có. Hủy subscription bất cứ lúc nào. Gói còn hiệu lực đến hết chu kỳ thanh toán.</p>
            </div>
            <div class="ai-faq__item">
                <h4 class="ai-faq__q">Quota reset khi nào?</h4>
                <p class="ai-faq__a">Quota reset vào đầu mỗi tháng (ngày 1).</p>
            </div>
            <div class="ai-faq__item">
                <h4 class="ai-faq__q">Gói Free có giới hạn thời gian không?</h4>
                <p class="ai-faq__a">Không. Gói Free miễn phí vĩnh viễn với 3 lần AI Game Concept mỗi tháng.</p>
            </div>
            <div class="ai-faq__item">
                <h4 class="ai-faq__q">Hỗ trợ engine nào?</h4>
                <p class="ai-faq__a">Unity (C#), Godot 4 (GDScript), Phaser 3 (JavaScript), và các framework HTML5 khác.</p>
            </div>
            <div class="ai-faq__item">
                <h4 class="ai-faq__q">Dữ liệu code có được bảo mật không?</h4>
                <p class="ai-faq__a">Có. Code bạn gửi chỉ dùng để xử lý request, không lưu trữ hay dùng để train AI.</p>
            </div>
        </div>
    </section>

    {{-- CTA BOTTOM --}}
    <section class="ai-cta-bottom">
        <div class="container">
            <h2>Sẵn sàng tăng tốc phát triển game?</h2>
            <p>Đăng ký miễn phí ngay hôm nay — không cần thẻ tín dụng.</p>
            @if($customer)
            <a href="#pricing" class="ai-btn ai-btn--primary">Chọn gói phù hợp</a>
            @else
            <a href="{{ route('shop.customer.session.index') }}" class="ai-btn ai-btn--primary">Đăng ký miễn phí</a>
            @endif
        </div>
    </section>
</div>
@endsection
