{{-- SEO Landing: Lịch quay thưởng xổ số --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)
@section('canonical_url'){{ route('lottery.schedule') }}@endsection

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "url": "{{ route('lottery.schedule') }}",
    "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Xổ số", "item": "{{ route('lottery.index') }}"},
            {"@type": "ListItem", "position": 2, "name": "Lịch quay thưởng"}
        ]
    }
}
</script>
@endpush

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">📅 Lịch Quay Thưởng <span class="bl-gradient-text">Xổ Số</span></h1>
            <p class="lt-hero__sub">Lịch xổ số 3 miền và Vietlott hàng tuần — biết trước đài quay mỗi ngày</p>
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
            <a href="{{ route('lottery.check') }}" class="lt-nav__item">🔍 Dò số</a>
            <a href="{{ route('lottery.schedule') }}" class="lt-nav__item lt-nav__item--highlight">📅 Lịch quay</a>
        </nav>

        {{-- Today highlight --}}
        <section class="lt-section lt-today">
            <h2 class="lt-section__title">🎯 Hôm nay ({{ $todayLabel }})</h2>
            @if(!empty($todaySchedule))
            <div class="lt-today-grid">
                @foreach($todaySchedule as $item)
                <a href="{{ route('lottery.province', $item['slug']) }}" class="lt-today-card">
                    <span class="lt-today-region">{{ $item['region_label'] }}</span>
                    <span class="lt-today-name">{{ $item['name'] }}</span>
                    <span class="lt-today-time">{{ $item['time'] }}</span>
                </a>
                @endforeach
            </div>
            @else
            <p class="lt-empty">Không có đài quay hôm nay.</p>
            @endif
        </section>

        {{-- Full week schedule --}}
        <section class="lt-section">
            <h2 class="lt-section__title">Lịch quay cả tuần</h2>
            <div class="lt-schedule-table-wrap">
                <table class="lt-schedule-table">
                    <thead>
                        <tr>
                            <th>Thứ</th>
                            <th>Miền Nam (16:15)</th>
                            <th>Miền Trung (17:15)</th>
                            <th>Miền Bắc (18:15)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weekSchedule as $day => $regions)
                        <tr class="{{ $day === $currentDay ? 'lt-schedule-today' : '' }}">
                            <td><strong>{{ $dayLabels[$day] }}</strong></td>
                            <td>
                                @foreach($regions['mien-nam'] ?? [] as $p)
                                <a href="{{ route('lottery.province', $p['slug']) }}">{{ $p['name'] }}</a>@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($regions['mien-trung'] ?? [] as $p)
                                <a href="{{ route('lottery.province', $p['slug']) }}">{{ $p['name'] }}</a>@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($regions['mien-bac'] ?? [] as $p)
                                <a href="{{ route('lottery.province', $p['slug']) }}">{{ $p['name'] }}</a>@if(!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Vietlott schedule --}}
        <section class="lt-section">
            <h2 class="lt-section__title">Lịch quay Vietlott</h2>
            <div class="lt-vietlot-schedule">
                <div class="lt-vs-card"><strong>Power 6/55</strong><span>Thứ 3, 5, 7 — 18:00</span></div>
                <div class="lt-vs-card"><strong>Mega 6/45</strong><span>Thứ 4, 6, CN — 18:00</span></div>
                <div class="lt-vs-card"><strong>Max 3D / Max 3D Pro</strong><span>Thứ 2, 4, 6 — 18:00</span></div>
                <div class="lt-vs-card"><strong>Keno</strong><span>Hàng ngày, 6:00–21:55 (mỗi 10 phút)</span></div>
            </div>
        </section>

        {{-- SEO content --}}
        <section class="lt-section lt-seo-content">
            <h2 class="lt-section__title">Giới thiệu lịch xổ số Việt Nam</h2>
            <p>Xổ số Việt Nam hoạt động hàng ngày với 3 miền quay thưởng vào các khung giờ cố định. Mỗi ngày có 2-3 đài miền Nam, 2-3 đài miền Trung quay cùng lúc, và 1 đài miền Bắc (Hà Nội quay hàng ngày).</p>
            <p>Vietlott có lịch quay riêng cho từng sản phẩm. Keno là loại quay nhiều nhất — mỗi 10 phút từ sáng tới tối.</p>
        </section>

        {{-- Links --}}
        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.index') }}">Kết quả xổ số hôm nay</a></li>
                <li><a href="{{ route('lottery.check') }}">Dò vé số online</a></li>
                <li><a href="{{ route('lottery.statistics') }}">Thống kê & soi cầu</a></li>
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
.lt-today-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.75rem}
.lt-today-card{display:flex;flex-direction:column;padding:.75rem;background:#0f1923;border:1px solid #2d3748;border-radius:10px;text-decoration:none;transition:border-color .2s}
.lt-today-card:hover{border-color:#2563eb}
.lt-today-region{font-size:.7rem;text-transform:uppercase;color:#64748b;letter-spacing:.5px}
.lt-today-name{font-weight:700;color:#fff;margin:.25rem 0}
.lt-today-time{font-size:.8rem;color:#60a5fa}
.lt-schedule-table-wrap{overflow-x:auto}
.lt-schedule-table{width:100%;border-collapse:collapse;min-width:500px}
.lt-schedule-table th{text-align:left;color:#64748b;padding:.5rem;border-bottom:1px solid #2d3748;font-size:.8rem;text-transform:uppercase}
.lt-schedule-table td{padding:.5rem;color:#e2e8f0;border-bottom:1px solid #1a2332;font-size:.9rem}
.lt-schedule-table a{color:#60a5fa;text-decoration:none}
.lt-schedule-table a:hover{text-decoration:underline}
.lt-schedule-today{background:#0f1923}
.lt-schedule-today td:first-child{color:#34d399}
.lt-vietlot-schedule{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.75rem}
.lt-vs-card{padding:.75rem;background:#0f1923;border:1px solid #2d3748;border-radius:8px}
.lt-vs-card strong{display:block;color:#fff;margin-bottom:.25rem}
.lt-vs-card span{font-size:.85rem;color:#a0aec0}
.lt-seo-content p{color:#94a3b8;line-height:1.7;margin:.5rem 0}
.lt-empty{color:#64748b;font-style:italic}
.lt-links ul{list-style:none;padding:0}
.lt-links li{margin:.5rem 0}
.lt-links a{color:#60a5fa;text-decoration:none}
@media(max-width:640px){.lt-today-grid{grid-template-columns:1fr 1fr}}
</style>
@endpush
