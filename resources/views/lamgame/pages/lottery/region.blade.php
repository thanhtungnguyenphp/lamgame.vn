{{-- Lottery Region (Miền Bắc/Trung/Nam) --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">{{ $title }}</h1>
            <p class="lt-hero__sub">{{ $description }}</p>
        </div>
    </section>

    <div class="bl-container lt-content">
        <nav class="lt-nav">
            <a href="{{ route('lottery.index') }}" class="lt-nav__item">← Tổng hợp</a>
            <a href="{{ route('lottery.mien-bac') }}" class="lt-nav__item {{ $region === 'mien-bac' ? 'lt-nav__item--highlight' : '' }}">🎯 Miền Bắc</a>
            <a href="{{ route('lottery.mien-trung') }}" class="lt-nav__item {{ $region === 'mien-trung' ? 'lt-nav__item--highlight' : '' }}">🎯 Miền Trung</a>
            <a href="{{ route('lottery.mien-nam') }}" class="lt-nav__item {{ $region === 'mien-nam' ? 'lt-nav__item--highlight' : '' }}">🎯 Miền Nam</a>
            <a href="{{ route('lottery.vietlott') }}" class="lt-nav__item">⭐ Vietlott</a>
            <a href="{{ route('lottery.statistics') }}" class="lt-nav__item">📊 Thống kê</a>
            <a href="{{ route('lottery.check') }}" class="lt-nav__item">🔍 Dò số</a>
            <a href="{{ route('lottery.schedule') }}" class="lt-nav__item">📅 Lịch quay</a>
        </nav>

        @forelse($draws as $draw)
        <section class="lt-section">
            <h2 class="lt-draw-date">{{ \Carbon\Carbon::parse($draw->date)->format('d/m/Y') }}@if($draw->province) — {{ $draw->province }}@endif</h2>
            @include('lamgame.pages.lottery._result_table', ['draw' => $draw])
        </section>
        @empty
            <p class="lt-empty">Chưa có kết quả.</p>
        @endforelse

        {{-- Dò số nhanh + Gợi ý --}}
        <section class="lt-section lt-inline-actions">
            <div class="lt-inline-actions__grid">
                <div class="lt-inline-actions__item">
                    <h3>🔍 Dò Số Nhanh</h3>
                    <div class="lt-inline-actions__form">
                        <input type="text" id="quick-check-input" maxlength="2" placeholder="2 số" class="lt-inline-actions__input" inputmode="numeric">
                        <button id="quick-check-btn" class="lt-btn lt-btn--primary lt-btn--sm">Dò</button>
                    </div>
                    <div id="quick-check-result" style="display:none;margin-top:.5rem;font-size:.8rem;"></div>
                </div>
                <div class="lt-inline-actions__item">
                    <h3>🔥 Số HOT hôm nay</h3>
                    <div id="hot-numbers-mini" class="lt-inline-actions__chips"><span class="lt-chip lt-chip--loading">...</span></div>
                </div>
                <div class="lt-inline-actions__item">
                    <h3>⏳ Lô Gan lâu nhất</h3>
                    <div id="due-numbers-mini" class="lt-inline-actions__chips"><span class="lt-chip lt-chip--loading">...</span></div>
                </div>
            </div>
        </section>

        {{-- CTA Đăng ký --}}
        <section class="lt-section lt-cta-mini">
            <span>📱</span>
            <div>
                <strong>Nhận gợi ý số + dò vé tự động</strong>
                <span>Đăng ký miễn phí → nhận thông báo kết quả mỗi ngày</span>
            </div>
            @guest
            <a href="/auth/login" class="lt-btn lt-btn--glow lt-btn--sm">Đăng ký</a>
            @else
            <a href="{{ route('lottery.check') }}" class="lt-btn lt-btn--primary lt-btn--sm">Dò vé</a>
            @endguest
        </section>

        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.vietlott') }}">Vietlott — Power, Mega, Keno</a></li>
                <li><a href="{{ route('lottery.statistics') }}">📊 Thống kê & soi cầu</a></li>
                <li><a href="{{ route('lottery.check') }}">🔍 Dò vé số online</a></li>
                <li><a href="{{ route('lottery.schedule') }}">📅 Lịch quay thưởng</a></li>
                <li><a href="{{ route('lottery.index') }}">Kết quả xổ số hôm nay</a></li>
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
.lt-hero__title { font-size: 2rem; font-weight: 800; color: #fff; }
.lt-hero__sub { color: #a0aec0; }
.lt-nav { display: flex; gap: .75rem; flex-wrap: wrap; margin: 1.5rem 0; }
.lt-nav__item { padding: .5rem 1rem; background: #1a2332; border: 1px solid #2d3748; border-radius: 8px; color: #e2e8f0; text-decoration: none; font-weight: 600; }
.lt-nav__item:hover, .lt-nav__item--highlight { background: #2563eb; border-color: #2563eb; color: #fff; }
.lt-section { margin: 1.5rem 0; padding: 1.5rem; background: #1a2332; border-radius: 12px; border: 1px solid #2d3748; }
.lt-draw-date { font-size: 1rem; color: #a0aec0; margin-bottom: .75rem; }
.lt-table { width: 100%; border-collapse: collapse; }
.lt-table th { text-align: left; color: #64748b; padding: .3rem .5rem; width: 40px; }
.lt-table td { color: #e2e8f0; padding: .3rem .5rem; font-weight: 600; }
.lt-table__db { font-size: 1.5rem; color: #ef4444; }
.lt-result-province { font-size: .85rem; color: #60a5fa; font-weight: 600; margin-bottom: .5rem; padding-bottom: .4rem; border-bottom: 1px solid #2d3748; }
.lt-result-table { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #1e293b; }
.lt-result-table:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.lt-balls { display: flex; gap: .5rem; flex-wrap: wrap; }
.lt-ball { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; }
.lt-empty { color: #64748b; font-style: italic; }
.lt-links ul { list-style: none; padding: 0; }
.lt-links a { color: #60a5fa; text-decoration: none; }
.lt-section__title { font-size: 1.2rem; color: #fff; margin-bottom: 1rem; }

/* Inline Actions */
.lt-inline-actions__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
.lt-inline-actions__item { background: #0f1923; border: 1px solid #2d3748; border-radius: 10px; padding: 1rem; }
.lt-inline-actions__item h3 { font-size: .85rem; color: #e2e8f0; margin-bottom: .75rem; }
.lt-inline-actions__form { display: flex; gap: .5rem; }
.lt-inline-actions__input { width: 60px; padding: .4rem .6rem; background: #1a2332; border: 2px solid #2d3748; border-radius: 6px; color: #fff; font-size: 1rem; font-weight: 700; text-align: center; outline: none; }
.lt-inline-actions__input:focus { border-color: #2563eb; }
.lt-inline-actions__chips { display: flex; gap: .3rem; flex-wrap: wrap; }
.lt-chip { display: inline-flex; align-items: center; justify-content: center; min-width: 28px; height: 24px; padding: 0 6px; border-radius: 5px; font-weight: 700; font-size: .75rem; }
.lt-chip--hot { background: #dc2626; color: #fff; }
.lt-chip--due { background: #2563eb; color: #fff; }
.lt-chip--loading { background: #1e293b; color: #64748b; }

/* CTA Mini */
.lt-cta-mini { display: flex; align-items: center; gap: 1rem; background: linear-gradient(135deg, #1e1b4b, #1e3a5f) !important; border-color: #4f46e5 !important; }
.lt-cta-mini span:first-child { font-size: 1.5rem; }
.lt-cta-mini div { flex: 1; }
.lt-cta-mini strong { display: block; color: #fff; font-size: .9rem; }
.lt-cta-mini div span { color: #a0aec0; font-size: .75rem; }

/* Buttons */
.lt-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: 6px; font-weight: 600; font-size: .8rem; text-decoration: none; border: none; cursor: pointer; transition: all .2s; }
.lt-btn--sm { padding: .4rem .8rem; font-size: .75rem; }
.lt-btn--primary { background: #2563eb; color: #fff; }
.lt-btn--glow { background: linear-gradient(135deg, #7c3aed, #2563eb); color: #fff; box-shadow: 0 3px 15px rgba(124,58,237,.3); }

/* Links */
.lt-links ul { list-style: none; padding: 0; columns: 2; }
.lt-links li { margin-bottom: .4rem; }
.lt-links a { color: #60a5fa; text-decoration: none; font-size: .85rem; }

@media(max-width:768px) {
    .lt-inline-actions__grid { grid-template-columns: 1fr; }
    .lt-cta-mini { flex-wrap: wrap; }
    .lt-links ul { columns: 1; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const region = '{{ $region }}';

    // Load hot + due numbers
    fetch(`/api/v1/lottery/statistics?region=${region}&type=prediction`)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'ok' && d.data.prediction) {
                const p = d.data.prediction;
                const hotEl = document.getElementById('hot-numbers-mini');
                if (hotEl) hotEl.innerHTML = p.hot_candidates.slice(0, 5).map(x => `<span class="lt-chip lt-chip--hot">${x.number}</span>`).join('');
                const dueEl = document.getElementById('due-numbers-mini');
                if (dueEl) dueEl.innerHTML = p.due_candidates.slice(0, 5).map(x => `<span class="lt-chip lt-chip--due">${x.number}</span>`).join('');
            }
        }).catch(() => {});

    // Quick check
    const btn = document.getElementById('quick-check-btn');
    const input = document.getElementById('quick-check-input');
    const result = document.getElementById('quick-check-result');
    if (btn) {
        btn.addEventListener('click', function() {
            const num = input.value.trim();
            if (num.length !== 2 || isNaN(num)) { result.style.display='block'; result.innerHTML='<span style="color:#ef4444;">Nhập 2 số (00-99)</span>'; return; }
            fetch(`/api/v1/lottery/traditional?region=${region}`)
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'ok' && d.data && d.data.results) {
                        let found = [];
                        d.data.results.forEach(r => {
                            Object.entries(r.prizes || {}).forEach(([prize, nums]) => {
                                (nums || []).forEach(n => { if (n.slice(-2) === num) found.push({prize: prize.replace('giai_','G.').replace('db','ĐB'), number: n, province: r.province}); });
                            });
                        });
                        result.style.display = 'block';
                        result.innerHTML = found.length > 0
                            ? `<span style="color:#10b981;font-weight:700;">🎉 TRÚNG ${found.length} lần!</span> ` + found.map(f => `${f.prize}:${f.number}`).join(', ')
                            : `<span style="color:#64748b;">Số ${num} không về (${d.data.date})</span>`;
                    }
                }).catch(() => { result.style.display='block'; result.innerHTML='Lỗi kết nối'; });
        });
        input.addEventListener('keydown', e => { if (e.key === 'Enter') btn.click(); });
    }
});
</script>
@endpush
