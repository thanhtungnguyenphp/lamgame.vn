@extends('layouts.master')

@section('page_title', 'FIFA World Cup 2026 — Lịch thi đấu, Kết quả, Tin tức | LamGame.vn')
@section('page_description', 'Cập nhật World Cup 2026 tại Mỹ, Canada, Mexico: lịch thi đấu 48 đội, kết quả trực tiếp, tin tức, phân tích chuyên sâu và thông tin từng đội tuyển.')
@section('og_image', asset('images/world-cup-2026-og.jpg'))
@section('twitter_card', 'summary_large_image')

@push('meta')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'SportsEvent',
    'name' => 'FIFA World Cup 2026',
    'description' => 'Giải vô địch bóng đá thế giới 2026 tại Mỹ, Canada, Mexico. 48 đội tuyển, 104 trận đấu.',
    'startDate' => '2026-06-11',
    'endDate' => '2026-07-19',
    'location' => [
        '@type' => 'Place',
        'name' => 'United States, Canada, Mexico',
        'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'US']
    ],
    'organizer' => ['@type' => 'Organization', 'name' => 'FIFA', 'url' => 'https://www.fifa.com'],
    'sport' => 'Football',
    'url' => url('/world-cup-2026'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/world-cup-2026.css') }}">
@endpush

@section('content')
<div class="wc26" id="world-cup-2026">
    @include('lamgame.landing.world-cup-2026.hero')
    @include('lamgame.landing.world-cup-2026.countdown')
    @include('lamgame.landing.world-cup-2026.schedule')
    @include('lamgame.landing.world-cup-2026.groups')
    @include('lamgame.landing.world-cup-2026.news')
    @include('lamgame.landing.world-cup-2026.teams')
    @include('lamgame.landing.world-cup-2026.venues')
    @include('lamgame.landing.world-cup-2026.cta')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/world-cup-2026.js') }}"></script>
@endpush
