{{-- SEO Landing: Thống kê xổ số / Soi cầu --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)
@section('canonical_url'){{ route('lottery.statistics') }}@endsection

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "url": "{{ route('lottery.statistics') }}",
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Xổ số", "item": "{{ route('lottery.index') }}"},
            {"@type": "ListItem", "position": 2, "name": "Thống kê"}
        ]
    }
}
</script>
@endpush

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">📊 Thống Kê <span class="bl-gradient-text">Xổ Số</span> & Soi Cầu</h1>
            <p class="lt-hero__sub">Phân tích tần suất, lô gan, đầu đuôi — hỗ trợ dự đoán xổ số 3 miền</p>
        </div>
    </section>

    <div class="bl-container lt-content">
        {{-- Region selector --}}
        <nav class="lt-nav" aria-label="Chọn miền">
            <a href="{{ route('lottery.statistics', ['region' => 'mien-bac']) }}" class="lt-nav__item {{ $region === 'mien-bac' ? 'lt-nav__item--highlight' : '' }}">Miền Bắc</a>
            <a href="{{ route('lottery.statistics', ['region' => 'mien-trung']) }}" class="lt-nav__item {{ $region === 'mien-trung' ? 'lt-nav__item--highlight' : '' }}">Miền Trung</a>
            <a href="{{ route('lottery.statistics', ['region' => 'mien-nam']) }}" class="lt-nav__item {{ $region === 'mien-nam' ? 'lt-nav__item--highlight' : '' }}">Miền Nam</a>
        </nav>

        {{-- Frequency --}}
        @if(!empty($stats['frequency']))
        <section class="lt-section">
            <h2 class="lt-section__title">🔥 Lô về nhiều nhất ({{ $days }} ngày gần nhất)</h2>
            <div class="lt-stat-grid">
                @foreach(array_slice($stats['frequency']['top_pairs'] ?? [], 0, 10) as $item)
                <div class="lt-stat-card lt-stat-card--hot">
                    <span class="lt-stat-num">{{ $item['number'] }}</span>
                    <span class="lt-stat-count">{{ $item['count'] }} lần</span>
                </div>
                @endforeach
            </div>
        </section>

        <section class="lt-section">
            <h2 class="lt-section__title">❄️ Lô gan — lâu chưa về</h2>
            <div class="lt-stat-grid">
                @foreach(array_slice($stats['frequency']['cold_pairs'] ?? [], 0, 10) as $item)
                <div class="lt-stat-card lt-stat-card--cold">
                    <span class="lt-stat-num">{{ $item['number'] }}</span>
                    <span class="lt-stat-count">{{ $item['count'] }} lần</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Streaks --}}
        @if(!empty($stats['streaks']['current']))
        <section class="lt-section">
            <h2 class="lt-section__title">🎯 Lô đang ra liên tiếp</h2>
            <div class="lt-stat-grid">
                @foreach(array_slice($stats['streaks']['current'], 0, 10) as $item)
                <div class="lt-stat-card lt-stat-card--streak">
                    <span class="lt-stat-num">{{ $item['number'] }}</span>
                    <span class="lt-stat-count">{{ $item['consecutive_days'] }} ngày liên tiếp</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Head/Tail --}}
        @if(!empty($stats['head_tail']))
        <section class="lt-section">
            <h2 class="lt-section__title">📈 Thống kê Đầu - Đuôi</h2>
            <div class="lt-ht-wrap">
                <div class="lt-ht-col">
                    <h3>Đầu</h3>
                    @foreach((array)$stats['head_tail']['heads'] as $digit => $count)
                    <div class="lt-ht-row">
                        <span class="lt-ht-digit">{{ $digit }}</span>
                        <div class="lt-ht-bar" style="width: {{ min(100, $count / max(1, max((array)$stats['head_tail']['heads'])) * 100) }}%"></div>
                        <span class="lt-ht-val">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="lt-ht-col">
                    <h3>Đuôi</h3>
                    @foreach((array)$stats['head_tail']['tails'] as $digit => $count)
                    <div class="lt-ht-row">
                        <span class="lt-ht-digit">{{ $digit }}</span>
                        <div class="lt-ht-bar lt-ht-bar--tail" style="width: {{ min(100, $count / max(1, max((array)$stats['head_tail']['tails'])) * 100) }}%"></div>
                        <span class="lt-ht-val">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- SEO Content --}}
        <section class="lt-section lt-seo-content">
            <h2 class="lt-section__title">Thống kê xổ số là gì?</h2>
            <p>Thống kê xổ số giúp người chơi phân tích tần suất xuất hiện, chuỗi lô gan (lâu chưa về), và xu hướng đầu đuôi qua nhiều kỳ quay. Đây là công cụ hỗ trợ soi cầu dựa trên dữ liệu thực tế.</p>
            <h3>Các chỉ số phân tích:</h3>
            <ul>
                <li><strong>Lô về nhiều:</strong> Những cặp số xuất hiện thường xuyên nhất trong khoảng thời gian.</li>
                <li><strong>Lô gan:</strong> Những cặp số lâu chưa ra — theo xác suất có khả năng về sớm.</li>
                <li><strong>Chuỗi liên tiếp:</strong> Lô đang ra nhiều ngày liền — cho biết xu hướng ngắn hạn.</li>
                <li><strong>Đầu/Đuôi:</strong> Phân tích chữ số hàng chục và hàng đơn vị.</li>
            </ul>
        </section>

        {{-- Internal links --}}
        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.index') }}">Kết quả xổ số hôm nay</a></li>
                <li><a href="{{ route('lottery.check') }}">Dò vé số online</a></li>
                <li><a href="{{ route('lottery.schedule') }}">Lịch quay thưởng xổ số</a></li>
                <li><a href="{{ route('lottery.mien-bac') }}">XSMB — Miền Bắc</a></li>
                <li><a href="{{ route('lottery.mien-trung') }}">XSMT — Miền Trung</a></li>
                <li><a href="{{ route('lottery.mien-nam') }}">XSMN — Miền Nam</a></li>
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
.lt-nav{display:flex;gap:.75rem;flex-wrap:wrap;margin:1.5rem 0}
.lt-nav__item{padding:.5rem 1rem;background:#1a2332;border:1px solid #2d3748;border-radius:8px;color:#e2e8f0;text-decoration:none;font-weight:600;transition:all .2s}
.lt-nav__item:hover,.lt-nav__item--highlight{background:#2563eb;border-color:#2563eb;color:#fff}
.lt-section{margin:2rem 0;padding:1.5rem;background:#1a2332;border-radius:12px;border:1px solid #2d3748}
.lt-section__title{font-size:1.2rem;color:#fff;margin-bottom:1rem}
.lt-stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:.75rem}
.lt-stat-card{text-align:center;padding:.75rem .5rem;border-radius:10px;background:#0f1923;border:1px solid #2d3748}
.lt-stat-num{display:block;font-size:1.4rem;font-weight:800;color:#fff}
.lt-stat-count{font-size:.75rem;color:#64748b}
.lt-stat-card--hot .lt-stat-num{color:#ef4444}
.lt-stat-card--cold .lt-stat-num{color:#60a5fa}
.lt-stat-card--streak .lt-stat-num{color:#34d399}
.lt-ht-wrap{display:grid;grid-template-columns:1fr 1fr;gap:2rem}
.lt-ht-col h3{color:#a0aec0;font-size:.9rem;margin-bottom:.75rem}
.lt-ht-row{display:flex;align-items:center;gap:.5rem;margin:.4rem 0}
.lt-ht-digit{width:20px;font-weight:700;color:#fff;text-align:center}
.lt-ht-bar{height:18px;background:linear-gradient(90deg,#f59e0b,#ef4444);border-radius:4px;min-width:4px}
.lt-ht-bar--tail{background:linear-gradient(90deg,#60a5fa,#8b5cf6)}
.lt-ht-val{font-size:.8rem;color:#64748b}
.lt-seo-content p,.lt-seo-content li{color:#94a3b8;line-height:1.7}
.lt-seo-content ul{padding-left:1.5rem;margin:.75rem 0}
.lt-seo-content h3{color:#e2e8f0;font-size:1rem;margin:1rem 0 .5rem}
.lt-links ul{list-style:none;padding:0}
.lt-links li{margin:.5rem 0}
.lt-links a{color:#60a5fa;text-decoration:none}
.lt-links a:hover{text-decoration:underline}
@media(max-width:640px){.lt-ht-wrap{grid-template-columns:1fr}.lt-stat-grid{grid-template-columns:repeat(5,1fr)}}
</style>
@endpush
