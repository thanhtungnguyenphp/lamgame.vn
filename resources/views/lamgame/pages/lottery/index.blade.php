{{-- Lottery Index — Kết quả xổ số hôm nay --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)
@section('canonical_url'){{ route('lottery.index') }}@endsection

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "url": "{{ route('lottery.index') }}",
    "mainEntity": {
        "@type": "ItemList",
        "name": "Kết quả xổ số hôm nay",
        "numberOfItems": 3,
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Xổ số miền Bắc", "url": "{{ route('lottery.mien-bac') }}"},
            {"@type": "ListItem", "position": 2, "name": "Xổ số miền Trung", "url": "{{ route('lottery.mien-trung') }}"},
            {"@type": "ListItem", "position": 3, "name": "Xổ số miền Nam", "url": "{{ route('lottery.mien-nam') }}"}
        ]
    }
}
</script>
@endpush

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">🎰 Kết Quả <span class="bl-gradient-text">Xổ Số</span> Hôm Nay</h1>
            <p class="lt-hero__sub">Cập nhật kết quả XSMB, XSMT, XSMN và Vietlott nhanh nhất — {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
        </div>
    </section>

    <div class="bl-container lt-content">
        {{-- Quick Nav --}}
        <nav class="lt-nav" aria-label="Chuyển vùng xổ số">
            <a href="{{ route('lottery.mien-bac') }}" class="lt-nav__item">🎯 Miền Bắc</a>
            <a href="{{ route('lottery.mien-trung') }}" class="lt-nav__item">🎯 Miền Trung</a>
            <a href="{{ route('lottery.mien-nam') }}" class="lt-nav__item">🎯 Miền Nam</a>
            <a href="{{ route('lottery.vietlott') }}" class="lt-nav__item lt-nav__item--highlight">⭐ Vietlott</a>
            <a href="{{ route('lottery.statistics') }}" class="lt-nav__item">📊 Thống kê</a>
            <a href="{{ route('lottery.check') }}" class="lt-nav__item">🔍 Dò số</a>
            <a href="{{ route('lottery.schedule') }}" class="lt-nav__item">📅 Lịch quay</a>
        </nav>

        {{-- Miền Bắc --}}
        <section class="lt-section">
            <h2 class="lt-section__title"><a href="{{ route('lottery.mien-bac') }}">Xổ Số Miền Bắc - XSMB</a></h2>
            @if($data['mienBac'] && $data['mienBac']->result)
                @include('lamgame.pages.lottery._result_table', ['draw' => $data['mienBac']])
            @else
                <p class="lt-empty">Chưa có kết quả. Quay thưởng lúc 18:15.</p>
            @endif
        </section>

        {{-- Vietlott --}}
        <section class="lt-section">
            <h2 class="lt-section__title"><a href="{{ route('lottery.vietlott') }}">Vietlott - Power 6/55, Mega 6/45</a></h2>
            @forelse($data['vietlot'] as $draw)
                <div class="lt-vietlot-card">
                    <h3>{{ strtoupper($draw->game) }} — {{ \Carbon\Carbon::parse($draw->date)->format('d/m') }}</h3>
                    @if($draw->result && !empty($draw->result->prize_data['numbers']))
                        <div class="lt-balls">
                            @foreach($draw->result->prize_data['numbers'] as $num)
                                <span class="lt-ball">{{ $num }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="lt-empty">Chưa có kết quả Vietlott hôm nay. Quay thưởng lúc 18:00.</p>
            @endforelse
        </section>

        {{-- 🔥 GỢI Ý SỐ HÔM NAY --}}
        <section class="lt-section lt-prediction">
            <h2 class="lt-section__title">🔥 Gợi Ý Số Hôm Nay</h2>
            <p class="lt-prediction__desc">Dựa trên thống kê 30 ngày — <em>Tham khảo, xổ số là ngẫu nhiên</em></p>
            <div class="lt-prediction__grid" id="prediction-widget">
                <div class="lt-prediction__card">
                    <h3>🎯 Số HOT</h3>
                    <div class="lt-prediction__numbers" id="hot-numbers"><span class="lt-chip lt-chip--loading">...</span></div>
                    <p class="lt-prediction__note">Hay về nhất 7 ngày qua</p>
                </div>
                <div class="lt-prediction__card">
                    <h3>⏳ Lô Gan</h3>
                    <div class="lt-prediction__numbers" id="due-numbers"><span class="lt-chip lt-chip--loading">...</span></div>
                    <p class="lt-prediction__note">Chưa về lâu nhất</p>
                </div>
                <div class="lt-prediction__card">
                    <h3>🔄 Vừa trở lại</h3>
                    <div class="lt-prediction__numbers" id="returned-numbers"><span class="lt-chip lt-chip--loading">...</span></div>
                    <p class="lt-prediction__note">Gan lâu rồi mới về</p>
                </div>
            </div>
            <div class="lt-prediction__cta">
                <a href="{{ route('lottery.statistics') }}" class="lt-btn lt-btn--primary">📊 Xem thống kê đầy đủ</a>
                <a href="{{ route('lottery.check') }}" class="lt-btn lt-btn--outline">🔍 Dò vé số ngay</a>
            </div>
        </section>

        {{-- 📊 THỐNG KÊ NHANH --}}
        <section class="lt-section lt-stats-mini">
            <h2 class="lt-section__title">📊 Thống Kê Nhanh — Miền Bắc</h2>
            <div class="lt-stats-mini__grid">
                <div class="lt-stats-mini__card">
                    <h4>Đầu đuôi (30 ngày)</h4>
                    <div id="head-tail-chart" class="lt-head-tail"></div>
                </div>
                <div class="lt-stats-mini__card">
                    <h4>Cặp hay đi cùng</h4>
                    <div id="frequent-pairs" class="lt-pairs-list"></div>
                </div>
            </div>
            <a href="{{ route('lottery.statistics') }}" class="lt-stats-mini__more">Xem thống kê đầy đủ →</a>
        </section>

        {{-- 🔍 DÒ SỐ NHANH --}}
        <section class="lt-section lt-check-inline">
            <h2 class="lt-section__title">🔍 Dò Số Nhanh</h2>
            <p class="lt-check-inline__desc">Nhập 2 số cuối để kiểm tra</p>
            <div class="lt-check-inline__form">
                <input type="text" id="quick-check-input" maxlength="2" placeholder="VD: 36" class="lt-check-inline__input" inputmode="numeric">
                <button id="quick-check-btn" class="lt-btn lt-btn--primary">Dò ngay</button>
            </div>
            <div id="quick-check-result" class="lt-check-inline__result" style="display:none;"></div>
            <a href="{{ route('lottery.check') }}" class="lt-check-inline__more">Dò nhiều số → Dò vé đầy đủ</a>
        </section>

        {{-- 📱 CTA ĐĂNG KÝ --}}
        <section class="lt-section lt-cta-register">
            <div class="lt-cta-register__content">
                <h2>📱 Nhận Gợi Ý Số Mỗi Ngày</h2>
                <p>Đăng ký miễn phí để nhận kết quả + gợi ý số dựa trên thống kê</p>
                <ul class="lt-cta-register__benefits">
                    <li>✅ Thông báo kết quả ngay khi có</li>
                    <li>✅ Gợi ý số hot/lô gan hàng ngày</li>
                    <li>✅ Dò vé tự động</li>
                    <li>✅ Thống kê theo đài yêu thích</li>
                </ul>
                @guest
                <a href="/auth/login" class="lt-btn lt-btn--glow">🚀 Đăng ký miễn phí</a>
                @else
                <a href="{{ route('lottery.check') }}" class="lt-btn lt-btn--primary">🔍 Dò vé số của bạn</a>
                @endguest
            </div>
        </section>

        {{-- Internal Links --}}
        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.keno') }}">Kết quả Keno trực tiếp</a></li>
                <li><a href="{{ route('lottery.power655') }}">Power 6/55 — Jackpot mới nhất</a></li>
                <li><a href="{{ route('lottery.mega645') }}">Mega 6/45 — Thống kê</a></li>
                <li><a href="{{ route('lottery.statistics') }}">📊 Thống kê & soi cầu xổ số</a></li>
                <li><a href="{{ route('lottery.check') }}">🔍 Dò vé số online</a></li>
                <li><a href="{{ route('lottery.schedule') }}">📅 Lịch quay thưởng</a></li>
            </ul>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.bl-container{max-width:1100px;margin:0 auto;padding:0 24px}
.lt-page { min-height: 100vh; padding-bottom: 4rem; }
.lt-hero { padding: 3rem 0 2rem; text-align: center; }
.lt-hero__title { font-size: 2rem; font-weight: 800; color: #fff; margin-bottom: .5rem; }
.lt-hero__sub { color: #a0aec0; font-size: 1.1rem; }
.lt-nav { display: flex; gap: .75rem; flex-wrap: wrap; margin: 1.5rem 0; }
.lt-nav__item { padding: .5rem 1rem; background: #1a2332; border: 1px solid #2d3748; border-radius: 8px; color: #e2e8f0; text-decoration: none; font-weight: 600; transition: all .2s; }
.lt-nav__item:hover, .lt-nav__item--highlight { background: #2563eb; border-color: #2563eb; color: #fff; }
.lt-section { margin: 2rem 0; padding: 1.5rem; background: #1a2332; border-radius: 12px; border: 1px solid #2d3748; }
.lt-section__title { font-size: 1.3rem; color: #fff; margin-bottom: 1rem; }
.lt-section__title a { color: #60a5fa; text-decoration: none; }
.lt-balls { display: flex; gap: .5rem; flex-wrap: wrap; }
.lt-ball { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .9rem; }
.lt-vietlot-card { padding: 1rem; margin-bottom: 1rem; background: #0f1923; border-radius: 8px; }
.lt-vietlot-card h3 { color: #a0aec0; font-size: .9rem; margin-bottom: .5rem; }
.lt-empty { color: #64748b; font-style: italic; }
.lt-result-province { font-size: .85rem; color: #60a5fa; font-weight: 600; margin-bottom: .5rem; padding-bottom: .4rem; border-bottom: 1px solid #2d3748; }
.lt-result-table { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #1e293b; }
.lt-result-table:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.lt-table { width: 100%; border-collapse: collapse; }
.lt-table th { text-align: left; color: #64748b; padding: .3rem .5rem; width: 40px; }
.lt-table td { color: #e2e8f0; padding: .3rem .5rem; font-weight: 600; }
.lt-table__db { font-size: 1.5rem; color: #ef4444; }

/* Prediction */
.lt-prediction__desc { color: #64748b; font-size: .85rem; margin-bottom: 1rem; }
.lt-prediction__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
.lt-prediction__card { background: #0f1923; border: 1px solid #2d3748; border-radius: 10px; padding: 1rem; }
.lt-prediction__card h3 { font-size: .9rem; color: #e2e8f0; margin-bottom: .75rem; }
.lt-prediction__numbers { display: flex; gap: .4rem; flex-wrap: wrap; min-height: 32px; }
.lt-prediction__note { font-size: .72rem; color: #64748b; margin-top: .5rem; }
.lt-prediction__cta { display: flex; gap: .75rem; flex-wrap: wrap; }
.lt-chip { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 28px; padding: 0 8px; border-radius: 6px; font-weight: 700; font-size: .8rem; }
.lt-chip--hot { background: #dc2626; color: #fff; }
.lt-chip--due { background: #2563eb; color: #fff; }
.lt-chip--return { background: #059669; color: #fff; }
.lt-chip--loading { background: #1e293b; color: #64748b; }

/* Buttons */
.lt-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .6rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: .85rem; text-decoration: none; border: none; cursor: pointer; transition: all .2s; }
.lt-btn--primary { background: #2563eb; color: #fff; }
.lt-btn--primary:hover { background: #1d4ed8; }
.lt-btn--outline { background: transparent; color: #60a5fa; border: 1px solid #60a5fa; }
.lt-btn--outline:hover { background: rgba(96,165,250,.1); }
.lt-btn--glow { background: linear-gradient(135deg, #7c3aed, #2563eb); color: #fff; box-shadow: 0 4px 20px rgba(124,58,237,.3); }
.lt-btn--glow:hover { box-shadow: 0 6px 30px rgba(124,58,237,.5); transform: translateY(-1px); }

/* Stats mini */
.lt-stats-mini__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
.lt-stats-mini__card { background: #0f1923; border: 1px solid #2d3748; border-radius: 10px; padding: 1rem; }
.lt-stats-mini__card h4 { font-size: .85rem; color: #a0aec0; margin-bottom: .75rem; }
.lt-stats-mini__more { color: #60a5fa; font-size: .85rem; text-decoration: none; }
.lt-head-tail__bar { display: inline-block; height: 18px; background: #2563eb; border-radius: 3px; margin-right: 4px; margin-bottom: 3px; vertical-align: middle; }
.lt-head-tail__label { font-size: .7rem; color: #64748b; display: inline-block; width: 16px; }
.lt-pairs-list__item { display: flex; justify-content: space-between; padding: .3rem 0; border-bottom: 1px solid #1e293b; font-size: .8rem; color: #e2e8f0; }

/* Check inline */
.lt-check-inline__desc { color: #64748b; font-size: .85rem; margin-bottom: 1rem; }
.lt-check-inline__form { display: flex; gap: .75rem; margin-bottom: 1rem; }
.lt-check-inline__input { width: 100px; padding: .6rem 1rem; background: #0f1923; border: 2px solid #2d3748; border-radius: 8px; color: #fff; font-size: 1.2rem; font-weight: 700; text-align: center; outline: none; }
.lt-check-inline__input:focus { border-color: #2563eb; }
.lt-check-inline__result { padding: 1rem; background: #0f1923; border-radius: 8px; margin-bottom: .5rem; }
.lt-check-inline__result--found { border: 1px solid #10b981; }
.lt-check-inline__result--not-found { border: 1px solid #64748b; }
.lt-check-inline__more { font-size: .8rem; color: #60a5fa; text-decoration: none; }

/* CTA Register */
.lt-cta-register { background: linear-gradient(135deg, #1e1b4b, #1e3a5f) !important; border-color: #4f46e5 !important; text-align: center; }
.lt-cta-register__content h2 { font-size: 1.3rem; color: #fff; margin-bottom: .5rem; }
.lt-cta-register__content p { color: #a0aec0; margin-bottom: 1rem; }
.lt-cta-register__benefits { list-style: none; padding: 0; margin-bottom: 1.5rem; text-align: left; max-width: 300px; margin-left: auto; margin-right: auto; }
.lt-cta-register__benefits li { color: #e2e8f0; padding: .3rem 0; font-size: .85rem; }

/* Links */
.lt-links ul { list-style: none; padding: 0; columns: 2; gap: 1rem; }
.lt-links li { margin-bottom: .5rem; }
.lt-links a { color: #60a5fa; text-decoration: none; font-size: .9rem; }
.lt-links a:hover { text-decoration: underline; }

@media(max-width:768px) {
    .lt-prediction__grid { grid-template-columns: 1fr; }
    .lt-stats-mini__grid { grid-template-columns: 1fr; }
    .lt-check-inline__form { flex-direction: row; }
    .lt-links ul { columns: 1; }
}
.lt-links ul { list-style: none; padding: 0; }
.lt-links li { margin: .5rem 0; }
.lt-links a { color: #60a5fa; text-decoration: none; }
.lt-links a:hover { text-decoration: underline; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load prediction data
    fetch('/api/v1/lottery/statistics?region=mien-bac&type=prediction')
        .then(r => r.json())
        .then(d => {
            if (d.status === 'ok' && d.data.prediction) {
                const p = d.data.prediction;
                // Hot numbers
                const hotEl = document.getElementById('hot-numbers');
                hotEl.innerHTML = p.hot_candidates.slice(0, 6).map(x =>
                    `<span class="lt-chip lt-chip--hot">${x.number}</span>`
                ).join('');
                // Due numbers
                const dueEl = document.getElementById('due-numbers');
                dueEl.innerHTML = p.due_candidates.slice(0, 6).map(x =>
                    `<span class="lt-chip lt-chip--due">${x.number}</span>`
                ).join('');
            }
        }).catch(() => {});

    // Load gap data for "just returned"
    fetch('/api/v1/lottery/statistics?region=mien-bac&type=gap')
        .then(r => r.json())
        .then(d => {
            if (d.status === 'ok' && d.data.gap) {
                const retEl = document.getElementById('returned-numbers');
                const items = d.data.gap.just_returned || [];
                retEl.innerHTML = items.length > 0
                    ? items.slice(0, 6).map(x => `<span class="lt-chip lt-chip--return">${x.number}</span>`).join('')
                    : '<span class="lt-chip lt-chip--loading">Chưa có</span>';
            }
        }).catch(() => {});

    // Load head/tail + pairs
    fetch('/api/v1/lottery/statistics?region=mien-bac&type=pattern')
        .then(r => r.json())
        .then(d => {
            if (d.status === 'ok' && d.data.pattern) {
                const pat = d.data.pattern;
                // Head tail chart
                const chartEl = document.getElementById('head-tail-chart');
                if (pat.sum_distribution) {
                    const max = Math.max(...pat.sum_distribution);
                    let html = '<div style="font-size:.7rem;color:#64748b;margin-bottom:.5rem;">Phân bố tổng 2 số (0-18):</div>';
                    html += pat.sum_distribution.map((v, i) => {
                        const width = Math.max(2, (v / max) * 100);
                        return `<span class="lt-head-tail__label">${i}</span><span class="lt-head-tail__bar" style="width:${width}%;" title="${i}: ${v}"></span>`;
                    }).join('<br>');
                    chartEl.innerHTML = html;
                }
                // Frequent pairs
                const pairsEl = document.getElementById('frequent-pairs');
                pairsEl.innerHTML = pat.frequent_pairs.slice(0, 8).map(x =>
                    `<div class="lt-pairs-list__item"><span>${x.pair}</span><span style="color:#60a5fa;">${x.together_count}x</span></div>`
                ).join('');
            }
        }).catch(() => {});

    // Quick check
    const checkBtn = document.getElementById('quick-check-btn');
    const checkInput = document.getElementById('quick-check-input');
    const checkResult = document.getElementById('quick-check-result');

    if (checkBtn) {
        checkBtn.addEventListener('click', function() {
            const num = checkInput.value.trim();
            if (num.length !== 2 || isNaN(num)) {
                checkResult.style.display = 'block';
                checkResult.className = 'lt-check-inline__result lt-check-inline__result--not-found';
                checkResult.innerHTML = '<span style="color:#ef4444;">Vui lòng nhập đúng 2 chữ số (00-99)</span>';
                return;
            }

            fetch('/api/v1/lottery/traditional?region=mien-bac')
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok' && d.data && d.data.results) {
                        const prizes = d.data.results[0]?.prizes || {};
                        let found = [];
                        Object.entries(prizes).forEach(([prize, nums]) => {
                            (nums || []).forEach(n => {
                                if (n.slice(-2) === num) {
                                    found.push({prize: prize.replace('giai_', 'G.').replace('db', 'ĐB'), number: n});
                                }
                            });
                        });

                        checkResult.style.display = 'block';
                        if (found.length > 0) {
                            checkResult.className = 'lt-check-inline__result lt-check-inline__result--found';
                            checkResult.innerHTML = `<span style="color:#10b981;font-weight:700;">🎉 TRÚNG! Số ${num} về ${found.length} lần:</span><br>` +
                                found.map(f => `<span style="color:#e2e8f0;">${f.prize}: <strong>${f.number}</strong></span>`).join(', ') +
                                `<br><small style="color:#64748b;">Kết quả ngày ${d.data.date} — ${d.data.region_name}</small>`;
                        } else {
                            checkResult.className = 'lt-check-inline__result lt-check-inline__result--not-found';
                            checkResult.innerHTML = `<span style="color:#64748b;">Số <strong>${num}</strong> không về hôm nay (${d.data.date})</span>`;
                        }
                    }
                }).catch(() => {
                    checkResult.style.display = 'block';
                    checkResult.innerHTML = '<span style="color:#ef4444;">Lỗi kết nối. Thử lại sau.</span>';
                });
        });

        checkInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') checkBtn.click();
        });
    }
});
</script>
@endpush
