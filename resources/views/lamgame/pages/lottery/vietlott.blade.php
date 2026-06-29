{{-- Vietlott Overview --}}
@extends('layouts.master')

@section('page_title', $title)
@section('page_description', $description)

@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "url": "{{ route('lottery.vietlott') }}"
}
</script>
@endpush

@section('content')
<div class="lt-page">
    <section class="lt-hero">
        <div class="bl-container">
            <h1 class="lt-hero__title">🎰 Kết Quả <span class="bl-gradient-text">Vietlott</span></h1>
            <p class="lt-hero__sub">Power 6/55, Mega 6/45, Max 3D, Keno — cập nhật mới nhất</p>
        </div>
    </section>

    <div class="bl-container lt-content">
        <nav class="lt-nav">
            <a href="{{ route('lottery.index') }}" class="lt-nav__item">← Tổng hợp</a>
            <a href="{{ route('lottery.power655') }}" class="lt-nav__item">Power 6/55</a>
            <a href="{{ route('lottery.mega645') }}" class="lt-nav__item">Mega 6/45</a>
            <a href="{{ route('lottery.keno') }}" class="lt-nav__item">Keno</a>
        </nav>

        @foreach(['power655' => 'Power 6/55', 'mega645' => 'Mega 6/45', 'max3d' => 'Max 3D', 'keno' => 'Keno'] as $key => $label)
        <section class="lt-section">
            <h2 class="lt-section__title">
                <a href="{{ route($key === 'keno' ? 'lottery.keno' : ($key === 'power655' ? 'lottery.power655' : ($key === 'mega645' ? 'lottery.mega645' : 'lottery.vietlott'))) }}">{{ $label }}</a>
            </h2>
            @if(!empty($data[$key]) && $data[$key]->result && !empty($data[$key]->result->prize_data['numbers']))
                <p class="lt-draw-date">{{ \Carbon\Carbon::parse($data[$key]->date)->format('d/m/Y') }}</p>
                <div class="lt-balls">
                    @foreach($data[$key]->result->prize_data['numbers'] as $i => $num)
                        <span class="lt-ball {{ $key === 'power655' && $i === count($data[$key]->result->prize_data['numbers'])-1 ? 'lt-ball--special' : '' }}">{{ $num }}</span>
                    @endforeach
                </div>
                @if($data[$key]->result->jackpot_data)
                    <div class="lt-jackpot">
                        @foreach($data[$key]->result->jackpot_data as $jKey => $jVal)
                            <span>💰 {{ ucfirst(str_replace('_',' ',$jKey)) }}: <strong>{{ number_format($jVal) }}đ</strong></span>
                        @endforeach
                    </div>
                @endif
            @else
                <p class="lt-empty">Chưa có kết quả</p>
            @endif
        </section>
        @endforeach
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
.lt-nav__item:hover { background: #2563eb; border-color: #2563eb; color: #fff; }
.lt-section { margin: 1.5rem 0; padding: 1.5rem; background: #1a2332; border-radius: 12px; border: 1px solid #2d3748; }
.lt-section__title { font-size: 1.3rem; color: #fff; margin-bottom: .5rem; }
.lt-section__title a { color: #60a5fa; text-decoration: none; }
.lt-draw-date { color: #64748b; font-size: .85rem; margin-bottom: .5rem; }
.lt-balls { display: flex; gap: .5rem; flex-wrap: wrap; }
.lt-ball { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #ef4444); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .9rem; }
.lt-ball--special { background: linear-gradient(135deg, #8b5cf6, #6366f1); }
.lt-jackpot { margin-top: .75rem; display: flex; flex-wrap: wrap; gap: 1rem; color: #fbbf24; font-size: .9rem; }
.lt-empty { color: #64748b; font-style: italic; }
</style>
@endpush
