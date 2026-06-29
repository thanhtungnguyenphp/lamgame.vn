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
            <a href="{{ route('lottery.mien-bac') }}" class="lt-nav__item {{ $region === 'mien-bac' ? 'lt-nav__item--highlight' : '' }}">Miền Bắc</a>
            <a href="{{ route('lottery.mien-trung') }}" class="lt-nav__item {{ $region === 'mien-trung' ? 'lt-nav__item--highlight' : '' }}">Miền Trung</a>
            <a href="{{ route('lottery.mien-nam') }}" class="lt-nav__item {{ $region === 'mien-nam' ? 'lt-nav__item--highlight' : '' }}">Miền Nam</a>
        </nav>

        @forelse($draws as $draw)
        <section class="lt-section">
            <h2 class="lt-draw-date">{{ \Carbon\Carbon::parse($draw->date)->format('d/m/Y') }}@if($draw->province) — {{ $draw->province }}@endif</h2>
            @include('lamgame.pages.lottery._result_table', ['draw' => $draw])
        </section>
        @empty
            <p class="lt-empty">Chưa có kết quả.</p>
        @endforelse

        <section class="lt-section lt-links">
            <h2 class="lt-section__title">Xem thêm</h2>
            <ul>
                <li><a href="{{ route('lottery.vietlott') }}">Vietlott — Power, Mega, Keno</a></li>
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
</style>
@endpush
