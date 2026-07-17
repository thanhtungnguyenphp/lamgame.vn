{{-- SEO Landing: Dò vé số online --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)
@section('canonical_url'){{ route('lottery.check') }}@endsection

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "url": "{{ route('lottery.check') }}",
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Xổ số", "item": "{{ route('lottery.index') }}"},
            {"@type": "ListItem", "position": 2, "name": "Dò vé số"}
        ]
    }
}
</script>
@endpush

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">🔍 Dò Vé Số <span class="bl-gradient-text">Online</span></h1>
            <p class="lt-hero__sub">Nhập số trên vé — kiểm tra kết quả xổ số 3 miền và Vietlott nhanh nhất</p>
        </div>
    </section>

    <div class="bl-container lt-content">
        {{-- Navigation --}}
        <nav class="lt-nav">
            <a href="{{ route('lottery.index') }}" class="lt-nav__item">← Tổng hợp</a>
            <a href="{{ route('lottery.mien-bac') }}" class="lt-nav__item">🎯 Miền Bắc</a>
            <a href="{{ route('lottery.mien-trung') }}" class="lt-nav__item">🎯 Miền Trung</a>
            <a href="{{ route('lottery.mien-nam') }}" class="lt-nav__item">🎯 Miền Nam</a>
            <a href="{{ route('lottery.vietlott') }}" class="lt-nav__item">⭐ Vietlott</a>
            <a href="{{ route('lottery.statistics') }}" class="lt-nav__item">📊 Thống kê</a>
            <a href="{{ route('lottery.check') }}" class="lt-nav__item lt-nav__item--highlight">🔍 Dò số</a>
            <a href="{{ route('lottery.schedule') }}" class="lt-nav__item">📅 Lịch quay</a>
        </nav>

        {{-- Check form --}}
        <section class="lt-section lt-check-form">
            <h2 class="lt-section__title">Nhập số cần dò</h2>
            <form id="lottery-check-form" class="lt-form">
                <div class="lt-form-row">
                    <label for="check-numbers" class="lt-label">Số trên vé (cách nhau dấu phẩy)</label>
                    <input type="text" id="check-numbers" class="lt-input" placeholder="VD: 123456, 789012" required>
                </div>
                <div class="lt-form-row lt-form-row--inline">
                    <div>
                        <label for="check-region" class="lt-label">Miền / Loại</label>
                        <select id="check-region" class="lt-select">
                            <option value="mien-bac">Miền Bắc</option>
                            <option value="mien-trung">Miền Trung</option>
                            <option value="mien-nam">Miền Nam</option>
                            <option value="vietlot">Vietlott</option>
                        </select>
                    </div>
                    <div>
                        <label for="check-date" class="lt-label">Ngày quay</label>
                        <input type="date" id="check-date" class="lt-input" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <button type="submit" class="lt-btn">Dò số ngay</button>
            </form>

            <div id="check-result" class="lt-check-result" style="display:none">
                <h3>Kết quả dò vé</h3>
                <div id="check-result-content"></div>
            </div>
        </section>

        {{-- How to --}}
        <section class="lt-section lt-seo-content">
            <h2 class="lt-section__title">Hướng dẫn dò vé số online</h2>
            <ol>
                <li>Nhập dãy số in trên vé xổ số của bạn vào ô bên trên.</li>
                <li>Chọn miền (Bắc/Trung/Nam) hoặc Vietlott tương ứng với loại vé.</li>
                <li>Chọn ngày quay thưởng cần kiểm tra.</li>
                <li>Nhấn <strong>"Dò số ngay"</strong> — hệ thống sẽ đối chiếu tự động.</li>
            </ol>
            <h3>Lưu ý:</h3>
            <ul>
                <li>Kết quả dò dựa trên dữ liệu từ Vietlott và các đài xổ số chính thức.</li>
                <li>Vé xổ số miền Bắc/Trung/Nam dò theo 5-6 số cuối tùy giải.</li>
                <li>Vé Vietlott (Power, Mega) dò theo bộ số đã chọn.</li>
            </ul>
        </section>

        {{-- Internal links --}}
        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.index') }}">Kết quả xổ số hôm nay</a></li>
                <li><a href="{{ route('lottery.statistics') }}">Thống kê & soi cầu xổ số</a></li>
                <li><a href="{{ route('lottery.schedule') }}">Lịch quay thưởng</a></li>
                <li><a href="{{ route('lottery.vietlott') }}">Kết quả Vietlott</a></li>
            </ul>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.bl-container{max-width:1100px;margin:0 auto;padding:0 24px}
.lt-page{min-height:100vh;padding-bottom:4rem}
.lt-hero{padding:3rem 0 2rem;text-align:center}
.lt-hero__title{font-size:2rem;font-weight:800;color:#fff;margin-bottom:.5rem}
.lt-hero__sub{color:#a0aec0;font-size:1.05rem}
.lt-section{margin:2rem 0;padding:1.5rem;background:#1a2332;border-radius:12px;border:1px solid #2d3748}
.lt-section__title{font-size:1.2rem;color:#fff;margin-bottom:1rem}
.lt-form-row{margin-bottom:1rem}
.lt-form-row--inline{display:flex;gap:1rem}
.lt-form-row--inline>div{flex:1}
.lt-label{display:block;color:#a0aec0;font-size:.85rem;margin-bottom:.4rem}
.lt-input,.lt-select{width:100%;padding:.6rem .8rem;background:#0f1923;border:1px solid #2d3748;border-radius:8px;color:#fff;font-size:.95rem}
.lt-input:focus,.lt-select:focus{border-color:#2563eb;outline:none}
.lt-btn{padding:.7rem 1.5rem;background:#2563eb;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:1rem;margin-top:.5rem;transition:background .2s}
.lt-btn:hover{background:#1d4ed8}
.lt-check-result{margin-top:1.5rem;padding:1rem;background:#0f1923;border-radius:8px;border:1px solid #2d3748}
.lt-check-result h3{color:#34d399;margin-bottom:.75rem}
.lt-seo-content p,.lt-seo-content li{color:#94a3b8;line-height:1.7}
.lt-seo-content ol,.lt-seo-content ul{padding-left:1.5rem;margin:.75rem 0}
.lt-seo-content h3{color:#e2e8f0;font-size:1rem;margin:1rem 0 .5rem}
.lt-links ul{list-style:none;padding:0}
.lt-links li{margin:.5rem 0}
.lt-links a{color:#60a5fa;text-decoration:none}
.lt-links a:hover{text-decoration:underline}
@media(max-width:640px){.lt-form-row--inline{flex-direction:column;gap:.75rem}}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('lottery-check-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const numbers = document.getElementById('check-numbers').value.trim();
    const region = document.getElementById('check-region').value;
    const date = document.getElementById('check-date').value;
    const resultDiv = document.getElementById('check-result');
    const contentDiv = document.getElementById('check-result-content');

    if (!numbers) return;

    contentDiv.innerHTML = '<p style="color:#a0aec0">Đang dò...</p>';
    resultDiv.style.display = 'block';

    try {
        const res = await fetch('/api/v1/lottery/check', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify({numbers: numbers.split(',').map(n => n.trim()), region, date})
        });
        const data = await res.json();

        if (data.success && data.data) {
            let html = '';
            data.data.forEach(item => {
                const icon = item.matched ? '🎉' : '❌';
                html += `<div style="padding:.5rem 0;border-bottom:1px solid #2d3748">${icon} <strong style="color:#fff">${item.number}</strong> — <span style="color:${item.matched ? '#34d399' : '#ef4444'}">${item.matched ? 'Trúng ' + item.prize : 'Không trúng'}</span></div>`;
            });
            contentDiv.innerHTML = html || '<p style="color:#64748b">Không có kết quả phù hợp.</p>';
        } else {
            contentDiv.innerHTML = '<p style="color:#ef4444">' + (data.message || 'Không tìm thấy kết quả cho ngày này.') + '</p>';
        }
    } catch (err) {
        contentDiv.innerHTML = '<p style="color:#ef4444">Lỗi kết nối. Vui lòng thử lại.</p>';
    }
});
</script>
@endpush
