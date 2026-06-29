{{-- Lottery Game Detail (Power/Mega/Keno) --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}"
}
</script>
@endpush

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
            <a href="{{ route('lottery.index') }}" class="lt-nav__item">← Tất cả</a>
            <a href="{{ route('lottery.power655') }}" class="lt-nav__item {{ $game === 'power655' ? 'lt-nav__item--highlight' : '' }}">Power 6/55</a>
            <a href="{{ route('lottery.mega645') }}" class="lt-nav__item {{ $game === 'mega645' ? 'lt-nav__item--highlight' : '' }}">Mega 6/45</a>
            <a href="{{ route('lottery.keno') }}" class="lt-nav__item {{ $game === 'keno' ? 'lt-nav__item--highlight' : '' }}">Keno</a>
        </nav>

        @forelse($draws as $draw)
        <section class="lt-section">
            <div class="lt-draw-header">
                <h2 class="lt-draw-date">{{ \Carbon\Carbon::parse($draw->date)->format('d/m/Y') }}@if($draw->period) — Kỳ #{{ $draw->period }}@endif</h2>
            </div>
            @if($draw->result && !empty($draw->result->prize_data['numbers']))
                <div class="lt-balls">
                    @foreach($draw->result->prize_data['numbers'] as $i => $num)
                        <span class="lt-ball {{ $game === 'power655' && $i === count($draw->result->prize_data['numbers'])-1 ? 'lt-ball--special' : '' }}">{{ $num }}</span>
                    @endforeach
                </div>
                @if($draw->result->jackpot_data)
                    <div class="lt-jackpot">
                        @if(!empty($draw->result->jackpot_data['jackpot1']))
                            <span>💰 Jackpot 1: <strong>{{ number_format($draw->result->jackpot_data['jackpot1']) }}đ</strong></span>
                        @endif
                        @if(!empty($draw->result->jackpot_data['jackpot2']))
                            <span>💰 Jackpot 2: <strong>{{ number_format($draw->result->jackpot_data['jackpot2']) }}đ</strong></span>
                        @endif
                    </div>
                @endif
            @else
                <p class="lt-empty">Chưa có kết quả</p>
            @endif
        </section>
        @empty
            <p class="lt-empty">Chưa có dữ liệu.</p>
        @endforelse

        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.vietlott') }}">Tổng hợp Vietlott</a></li>
                <li><a href="{{ route('lottery.index') }}">Kết quả xổ số 3 miền</a></li>
                <li><a href="{{ route('lottery.mien-bac') }}">XSMB hôm nay</a></li>
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
.lt-hero__sub { color: #a0aec0; font-size: 1rem; }
.lt-nav { display: flex; gap: .75rem; flex-wrap: wrap; margin: 1.5rem 0; }
.lt-nav__item { padding: .5rem 1rem; background: #1a2332; border: 1px solid #2d3748; border-radius: 8px; color: #e2e8f0; text-decoration: none; font-weight: 600; transition: all .2s; }
.lt-nav__item:hover, .lt-nav__item--highlight { background: #2563eb; border-color: #2563eb; color: #fff; }
.lt-section { margin: 1.5rem 0; padding: 1.5rem; background: #1a2332; border-radius: 12px; border: 1px solid #2d3748; }
.lt-draw-date { font-size: 1rem; color: #a0aec0; margin-bottom: .75rem; }
.lt-balls { display: flex; gap: .5rem; flex-wrap: wrap; margin: .5rem 0; }
.lt-ball { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .9rem; }
.lt-ball--special { background: linear-gradient(135deg, #8b5cf6, #6366f1); }
.lt-jackpot { margin-top: .75rem; display: flex; flex-wrap: wrap; gap: 1rem; color: #fbbf24; font-size: .9rem; }
.lt-empty { color: #64748b; font-style: italic; }
.lt-links ul { list-style: none; padding: 0; }
.lt-links li { margin: .5rem 0; }
.lt-links a { color: #60a5fa; text-decoration: none; }
.lt-section__title { font-size: 1.2rem; color: #fff; margin-bottom: 1rem; }
</style>
@endpush
