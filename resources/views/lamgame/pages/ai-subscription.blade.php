@extends('layouts.master')

@section('page_title', $page_title ?? 'AI Tools cho Game Developer - Làm Game')
@section('page_description', $page_description ?? 'Công cụ AI hỗ trợ lập trình game: Code Generate, Debug, Unit Test, Asset Generate.')

@push('meta')
<meta property="og:title" content="AI Tools cho Game Developer - Làm Game">
<meta property="og:description" content="Công cụ AI hỗ trợ lập trình game. Gói Free, Pro $9/tháng, Business $29/tháng.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/ai-tools') }}">
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "AI Tools cho Game Developer",
    "description": "Công cụ AI hỗ trợ lập trình game",
    "url": "{{ url('/ai-tools') }}",
    "provider": { "@type": "Organization", "name": "Làm Game", "url": "https://lamgame.vn" }
}
</script>
@endpush

@section('content')
<div id="ai-subscription-app">
    {{-- Hero --}}
    <section style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);padding:60px 0;text-align:center;color:#fff">
        <div class="container">
            <h1 style="font-size:2.5rem;font-weight:800;margin-bottom:12px">🤖 AI Tools cho Game Developer</h1>
            <p style="font-size:1.15rem;color:#94a3b8;max-width:600px;margin:0 auto">Tăng tốc phát triển game với AI — Code Generate, Debug, Unit Test, Asset Generate</p>
        </div>
    </section>

    {{-- Pricing Cards --}}
    <section style="padding:48px 0;background:#f8fafc">
        <div class="container">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;max-width:960px;margin:0 auto" id="plans-grid">
                <div style="text-align:center;padding:40px;color:#64748b">Đang tải gói dịch vụ...</div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section style="padding:48px 0">
        <div class="container" style="max-width:800px;margin:0 auto">
            <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:32px">So sánh tính năng</h2>
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse;font-size:0.95rem" id="features-table">
                    <thead>
                        <tr style="background:#f1f5f9">
                            <th style="padding:12px 16px;text-align:left;border-bottom:2px solid #e2e8f0">Tính năng</th>
                            <th style="padding:12px 16px;text-align:center;border-bottom:2px solid #e2e8f0">Free</th>
                            <th style="padding:12px 16px;text-align:center;border-bottom:2px solid #e2e8f0;background:#eff6ff">Pro</th>
                            <th style="padding:12px 16px;text-align:center;border-bottom:2px solid #e2e8f0">Business</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Game Concept</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">3/tháng</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">100/tháng</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">♾️ Unlimited</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Code Generate</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">50/tháng</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">♾️ Unlimited</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Debug</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">30/tháng</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">♾️ Unlimited</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Unit Test</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">20/tháng</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">♾️ Unlimited</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Asset Generate</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">100/tháng</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Code Review</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">10/tháng</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">♾️ Unlimited</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">AI Model</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">GPT-4o mini</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">GPT-4o</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">GPT-4o + Claude</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">Export Project</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">❌</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">✅</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">✅</td></tr>
                        <tr><td style="padding:10px 16px;border-bottom:1px solid #f1f5f9">Chat History</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">7 ngày</td><td style="text-align:center;border-bottom:1px solid #f1f5f9;background:#eff6ff">30 ngày</td><td style="text-align:center;border-bottom:1px solid #f1f5f9">♾️ Unlimited</td></tr>
                        <tr><td style="padding:10px 16px">Priority Queue</td><td style="text-align:center">❌</td><td style="text-align:center;background:#eff6ff">✅</td><td style="text-align:center">✅</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section style="padding:48px 0;background:#f8fafc">
        <div class="container" style="max-width:700px;margin:0 auto">
            <h2 style="text-align:center;font-size:1.8rem;font-weight:700;margin-bottom:24px">Câu hỏi thường gặp</h2>
            <div style="margin-bottom:16px">
                <h4 style="font-weight:600;margin-bottom:6px">Thanh toán bằng gì?</h4>
                <p style="color:#64748b">Thanh toán qua PayPal — hỗ trợ thẻ Visa, Mastercard, tài khoản PayPal quốc tế.</p>
            </div>
            <div style="margin-bottom:16px">
                <h4 style="font-weight:600;margin-bottom:6px">Có thể hủy bất cứ lúc nào không?</h4>
                <p style="color:#64748b">Có. Bạn có thể hủy subscription bất cứ lúc nào. Gói sẽ còn hiệu lực đến hết chu kỳ thanh toán.</p>
            </div>
            <div style="margin-bottom:16px">
                <h4 style="font-weight:600;margin-bottom:6px">Quota reset khi nào?</h4>
                <p style="color:#64748b">Quota được reset vào đầu mỗi tháng (ngày 1).</p>
            </div>
            <div>
                <h4 style="font-weight:600;margin-bottom:6px">Gói Free có giới hạn thời gian không?</h4>
                <p style="color:#64748b">Không. Gói Free miễn phí vĩnh viễn với 3 lần AI Game Concept mỗi tháng.</p>
            </div>
        </div>
    </section>
</div>

{{-- Subscription JS --}}
<script>
(function() {
    const API = '{{ url("/api/v1/subscription") }}';

    function formatLimit(v) {
        if (v === -1) return '♾️ Unlimited';
        if (v === 0 || v === false) return '❌';
        if (v === true) return '✅';
        return v + '/tháng';
    }

    function planCard(p, highlight) {
        const border = highlight ? 'border:2px solid #3b82f6;' : 'border:1px solid #e2e8f0;';
        const badge = highlight ? '<div style="background:#3b82f6;color:#fff;padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:600;display:inline-block;margin-bottom:12px">Phổ biến nhất</div>' : '';
        const price = parseFloat(p.price) === 0 ? '<span style="font-size:2.2rem;font-weight:800">Miễn phí</span>' : '<span style="font-size:2.2rem;font-weight:800">$' + parseInt(p.price) + '</span><span style="color:#64748b;font-size:0.95rem">/tháng</span>';
        const btnStyle = highlight ? 'background:#3b82f6;color:#fff;' : 'background:#1e293b;color:#fff;';
        const btnText = parseFloat(p.price) === 0 ? 'Bắt đầu miễn phí' : 'Đăng ký ' + p.name;

        return '<div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;' + border + '">'
            + badge
            + '<h3 style="font-size:1.3rem;font-weight:700;margin-bottom:8px">' + p.name + '</h3>'
            + '<div style="margin-bottom:20px">' + price + '</div>'
            + '<ul style="list-style:none;padding:0;text-align:left;margin-bottom:24px;font-size:0.9rem;color:#475569">'
            + '<li style="padding:6px 0">✦ AI Concept: ' + formatLimit(p.features.ai_concept) + '</li>'
            + '<li style="padding:6px 0">✦ Code Generate: ' + formatLimit(p.features.ai_generate) + '</li>'
            + '<li style="padding:6px 0">✦ Debug: ' + formatLimit(p.features.ai_debug) + '</li>'
            + '<li style="padding:6px 0">✦ Model: ' + p.features.ai_model + '</li>'
            + '</ul>'
            + '<button onclick="doSubscribe(\'' + p.slug + '\')" style="width:100%;padding:12px;border:none;border-radius:10px;font-size:1rem;font-weight:600;cursor:pointer;' + btnStyle + '">' + btnText + '</button>'
            + '</div>';
    }

    fetch(API + '/plans').then(r => r.json()).then(d => {
        if (d.status === 'ok' && d.data) {
            const grid = document.getElementById('plans-grid');
            grid.innerHTML = d.data.map((p, i) => planCard(p, i === 1)).join('');
        }
    }).catch(() => {});

    window.doSubscribe = function(plan) {
        const token = localStorage.getItem('lg_access_token');
        if (!token) {
            if (confirm('Bạn cần đăng nhập để đăng ký gói. Chuyển đến trang đăng nhập?')) {
                window.location.href = '{{ url("/customer/login") }}';
            }
            return;
        }

        fetch(API + '/subscribe', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify({ plan: plan })
        })
        .then(r => r.json())
        .then(d => {
            if (d.data && d.data.approval_url) {
                window.location.href = d.data.approval_url;
            } else if (d.data && d.data.status === 'active') {
                alert('🎉 Đăng ký gói ' + plan.toUpperCase() + ' thành công!');
                location.reload();
            } else {
                alert(d.error?.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
        })
        .catch(() => alert('Không thể kết nối server.'));
    };
})();
</script>
@endsection
