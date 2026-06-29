{{-- SEO Landing: Xổ số theo tỉnh --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)
@section('canonical_url'){{ route('lottery.province', $province->code) }}@endsection

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "url": "{{ route('lottery.province', $province->code) }}",
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Xổ số", "item": "{{ route('lottery.index') }}"},
            {"@type": "ListItem", "position": 2, "name": "{{ $regionLabel }}", "item": "{{ route('lottery.' . $province->region) }}"},
            {"@type": "ListItem", "position": 3, "name": "{{ $province->name }}"}
        ]
    }
}
</script>
@endpush

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">🎰 Kết Quả Xổ Số <span class="bl-gradient-text">{{ $province->name }}</span></h1>
            <p class="lt-hero__sub">XS{{ strtoupper($province->code) }} — Cập nhật kết quả {{ $province->name }} mới nhất. Quay thưởng {{ $drawDays }}.</p>
        </div>
    </section>

    <div class="bl-container lt-content">
        {{-- Info card --}}
        <section class="lt-section lt-province-info">
            <div class="lt-pinfo-grid">
                <div><span class="lt-pinfo-label">Đài</span><span class="lt-pinfo-val">{{ $province->name }}</span></div>
                <div><span class="lt-pinfo-label">Miền</span><span class="lt-pinfo-val">{{ $regionLabel }}</span></div>
                <div><span class="lt-pinfo-label">Ngày quay</span><span class="lt-pinfo-val">{{ $drawDays }}</span></div>
                <div><span class="lt-pinfo-label">Giờ quay</span><span class="lt-pinfo-val">{{ $drawTime }}</span></div>
            </div>
        </section>

        {{-- Latest results --}}
        <section class="lt-section">
            <h2 class="lt-section__title">Kết quả gần nhất</h2>
            @forelse($draws as $draw)
            <div class="lt-draw-block">
                <h3 class="lt-draw-date">{{ \Carbon\Carbon::parse($draw->date)->format('d/m/Y') }}</h3>
                @include('lamgame.pages.lottery._result_table', ['draw' => $draw])
            </div>
            @empty
                <p class="lt-empty">Chưa có kết quả cho đài {{ $province->name }}.</p>
            @endforelse
        </section>

        {{-- Related provinces --}}
        @if(!empty($relatedProvinces))
        <section class="lt-section">
            <h2 class="lt-section__title">Các đài {{ $regionLabel }} khác</h2>
            <div class="lt-province-grid">
                @foreach($relatedProvinces as $p)
                <a href="{{ route('lottery.province', $p->code) }}" class="lt-province-link {{ $p->code === $province->code ? 'lt-province-link--active' : '' }}">XS{{ strtoupper($p->code) }} — {{ $p->name }}</a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- SEO content --}}
        <section class="lt-section lt-seo-content">
            <h2 class="lt-section__title">Xổ số {{ $province->name }} (XS{{ strtoupper($province->code) }})</h2>
            <p>Đài xổ số {{ $province->name }} thuộc {{ $regionLabel }}, quay thưởng vào {{ $drawDays }} hàng tuần lúc {{ $drawTime }}. Kết quả được cập nhật trực tiếp ngay khi có từ đài.</p>
            <p>Cơ cấu giải thưởng gồm Giải Đặc biệt, G1–G8 với tổng giá trị hàng tỷ đồng mỗi kỳ quay.</p>
        </section>

        {{-- Links --}}
        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.index') }}">Kết quả xổ số hôm nay</a></li>
                <li><a href="{{ route('lottery.' . $province->region) }}">Xổ số {{ $regionLabel }}</a></li>
                <li><a href="{{ route('lottery.statistics', ['region' => $province->region]) }}">Thống kê {{ $regionLabel }}</a></li>
                <li><a href="{{ route('lottery.check') }}">Dò vé số online</a></li>
                <li><a href="{{ route('lottery.schedule') }}">Lịch quay thưởng</a></li>
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
.lt-hero__sub{color:#a0aec0;font-size:1rem}
.lt-section{margin:2rem 0;padding:1.5rem;background:#1a2332;border-radius:12px;border:1px solid #2d3748}
.lt-section__title{font-size:1.2rem;color:#fff;margin-bottom:1rem}
.lt-province-info{background:linear-gradient(135deg,#1a2332,#0f1923)}
.lt-pinfo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:1rem}
.lt-pinfo-label{display:block;font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.5px}
.lt-pinfo-val{color:#fff;font-weight:700;font-size:1.05rem}
.lt-draw-block{padding:1rem 0;border-bottom:1px solid #2d3748}
.lt-draw-block:last-child{border-bottom:none}
.lt-draw-date{font-size:.95rem;color:#a0aec0;margin-bottom:.5rem}
.lt-province-grid{display:flex;flex-wrap:wrap;gap:.5rem}
.lt-province-link{padding:.4rem .75rem;background:#0f1923;border:1px solid #2d3748;border-radius:6px;color:#e2e8f0;font-size:.85rem;text-decoration:none;transition:all .2s}
.lt-province-link:hover,.lt-province-link--active{background:#2563eb;border-color:#2563eb;color:#fff}
.lt-seo-content p{color:#94a3b8;line-height:1.7;margin:.5rem 0}
.lt-empty{color:#64748b;font-style:italic}
.lt-links ul{list-style:none;padding:0}
.lt-links li{margin:.5rem 0}
.lt-links a{color:#60a5fa;text-decoration:none}
.lt-links a:hover{text-decoration:underline}
</style>
@endpush
