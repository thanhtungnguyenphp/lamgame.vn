@extends('layouts.master')

@section('page_title', $page_title)
@section('page_description', $page_description)

@push('meta')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "LamGame.vn - Thuê Team Dev",
    "description": "{{ $page_description }}",
    "url": "{{ url()->current() }}",
    "areaServed": "VN",
    "serviceType": ["Game Development", "Web Development", "Mobile App Development", "AI Solutions"]
}
</script>
@endpush

@push('styles')
<style>
    .hire-hero { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color: #fff; padding: 80px 0 60px; text-align: center; }
    .hire-hero h1 { font-size: 2.5rem; margin-bottom: 16px; }
    .hire-hero p { font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; padding: 60px 0; }
    .service-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px 24px; text-align: center; transition: box-shadow 0.2s; }
    .service-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .service-icon { font-size: 48px; margin-bottom: 16px; }
    .service-card h3 { font-size: 1.2rem; margin-bottom: 8px; color: #1f2937; }
    .service-card p { color: #6b7280; font-size: 14px; line-height: 1.6; }
    .process-section { background: #f9fafb; padding: 60px 0; }
    .process-steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 32px; margin-top: 32px; }
    .step { text-align: center; }
    .step-num { width: 48px; height: 48px; background: #2563eb; color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; margin-bottom: 12px; }
    .step h4 { margin-bottom: 8px; }
    .step p { color: #6b7280; font-size: 14px; }
    .quote-section { padding: 60px 0; max-width: 640px; margin: 0 auto; }
    .quote-section h2 { text-align: center; margin-bottom: 32px; }
    .quote-form label { display: block; font-weight: 500; margin-bottom: 4px; margin-top: 16px; }
    .quote-form input, .quote-form select, .quote-form textarea { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; }
    .quote-form textarea { resize: vertical; min-height: 120px; }
    .quote-form button { margin-top: 24px; width: 100%; padding: 14px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
    .quote-form button:hover { background: #1d4ed8; }
    .quote-form button:disabled { opacity: 0.6; cursor: not-allowed; }
    .section-title { text-align: center; font-size: 1.8rem; margin-bottom: 8px; }
    .section-subtitle { text-align: center; color: #6b7280; margin-bottom: 32px; }
</style>
@endpush

@section('content')
    <section class="hire-hero">
        <div class="container">
            <h1>Thuê Team Dev chuyên nghiệp</h1>
            <p>Đội ngũ lập trình viên giàu kinh nghiệm, sẵn sàng biến ý tưởng của bạn thành sản phẩm thực tế</p>
        </div>
    </section>

    <section class="section-content">
        <div class="container">
            <h2 class="section-title">Dịch vụ của chúng tôi</h2>
            <p class="section-subtitle">Giải pháp toàn diện cho mọi nhu cầu phát triển phần mềm</p>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🎮</div>
                    <h3>Game Development</h3>
                    <p>Unity, Unreal Engine, Godot. Game mobile, PC, WebGL. Từ casual đến mid-core.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌐</div>
                    <h3>Web Development</h3>
                    <p>Laravel, React, Vue.js. Website, web app, e-commerce, CMS, API backend.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">📱</div>
                    <h3>Mobile App</h3>
                    <p>React Native, Flutter. Ứng dụng iOS & Android, cross-platform.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">🤖</div>
                    <h3>AI Solutions</h3>
                    <p>Chatbot, AI agent, tích hợp LLM, xử lý ngôn ngữ tự nhiên, computer vision.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="process-section">
        <div class="container">
            <h2 class="section-title">Quy trình làm việc</h2>
            <div class="process-steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <h4>Tư vấn & Báo giá</h4>
                    <p>Phân tích yêu cầu, đề xuất giải pháp và báo giá minh bạch</p>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <h4>Thiết kế & Lên kế hoạch</h4>
                    <p>Wireframe, UI/UX, kiến trúc hệ thống, timeline chi tiết</p>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <h4>Phát triển & Review</h4>
                    <p>Sprint 2 tuần, demo thường xuyên, feedback liên tục</p>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <h4>Bàn giao & Hỗ trợ</h4>
                    <p>Deploy, chuyển giao source code, hỗ trợ sau bàn giao</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-content">
        <div class="container">
            <div class="quote-section">
                <h2>Gửi yêu cầu báo giá</h2>
                <div id="quote-message"></div>
                <form class="quote-form" id="hireForm" onsubmit="event.preventDefault(); submitHireForm()">
                    <label>Họ và tên *</label>
                    <input type="text" name="name" required maxlength="100">

                    <label>Email *</label>
                    <input type="email" name="email" required maxlength="255">

                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" maxlength="20">

                    <label>Công ty</label>
                    <input type="text" name="company" maxlength="255">

                    <label>Loại dự án *</label>
                    <select name="project_type" required>
                        <option value="">Chọn loại dự án</option>
                        <option value="game">🎮 Game Development</option>
                        <option value="web">🌐 Web Development</option>
                        <option value="app">📱 Mobile App</option>
                        <option value="ai">🤖 AI Solutions</option>
                        <option value="other">📦 Khác</option>
                    </select>

                    <label>Ngân sách dự kiến</label>
                    <select name="budget_range">
                        <option value="">Chưa xác định</option>
                        <option value="< 10M">Dưới 10 triệu</option>
                        <option value="10M - 50M">10 - 50 triệu</option>
                        <option value="50M - 200M">50 - 200 triệu</option>
                        <option value="> 200M">Trên 200 triệu</option>
                    </select>

                    <label>Mô tả dự án *</label>
                    <textarea name="description" required maxlength="5000" placeholder="Mô tả chi tiết yêu cầu, tính năng mong muốn, timeline..."></textarea>

                    <button type="submit" id="hireSubmitBtn">Gửi yêu cầu báo giá</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
function submitHireForm() {
    const form = document.getElementById('hireForm');
    const btn = document.getElementById('hireSubmitBtn');
    const msg = document.getElementById('quote-message');
    const data = Object.fromEntries(new FormData(form));

    btn.disabled = true;
    btn.textContent = 'Đang gửi...';

    fetch('/api/v1/hire-request', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        btn.textContent = 'Gửi yêu cầu báo giá';
        if (d.status === 'success') {
            msg.innerHTML = '<div style="background:#f0fdf4;color:#16a34a;padding:16px;border-radius:8px;margin-bottom:16px;text-align:center">' + d.message + '</div>';
            form.reset();
        } else {
            const errors = d.errors ? Object.values(d.errors).flat().join('<br>') : (d.message || 'Có lỗi xảy ra');
            msg.innerHTML = '<div style="background:#fef2f2;color:#dc2626;padding:16px;border-radius:8px;margin-bottom:16px">' + errors + '</div>';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = 'Gửi yêu cầu báo giá';
        msg.innerHTML = '<div style="background:#fef2f2;color:#dc2626;padding:16px;border-radius:8px;margin-bottom:16px">Có lỗi xảy ra. Vui lòng thử lại.</div>';
    });
}
</script>
@endpush
