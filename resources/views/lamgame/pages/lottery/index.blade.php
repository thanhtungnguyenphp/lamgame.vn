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
.lt-links ul { list-style: none; padding: 0; }
.lt-links li { margin: .5rem 0; }
.lt-links a { color: #60a5fa; text-decoration: none; }
.lt-links a:hover { text-decoration: underline; }
</style>
@endpush
