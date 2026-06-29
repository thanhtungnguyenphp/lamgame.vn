@extends('layouts.master')
@section('page_title') {{ $seo_title ?? 'Thể thao — Tin tức, Lịch thi đấu, BXH' }} @endsection
@section('page_description', $seo_description ?? '')
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SportsOrganization",
    "name": "LamGame Thể Thao",
    "url": "{{ route('sport.index') }}",
    "description": "{{ $seo_description ?? '' }}"
}
</script>
@endpush

@push('styles')
<style>.container{max-width:1100px}</style>
@endpush
@push('styles')
<link rel="stylesheet" href="/css/sport-utils.css">
@endpush
@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">⚽ Thể thao</h1>

    {{-- Live Matches --}}
    @if($liveMatches->isNotEmpty())
    <section class="mb-8">
        <h2 class="text-xl font-semibold mb-3 text-red-500">🔴 Đang diễn ra</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($liveMatches as $match)
            <a href="{{ route('sport.match', $match->id) }}" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700">
                <div class="flex justify-between items-center">
                    <span>{{ $match->homeTeam->name }}</span>
                    <span class="text-xl font-bold text-yellow-400">{{ $match->home_score }} - {{ $match->away_score }}</span>
                    <span>{{ $match->awayTeam->name }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Upcoming --}}
    <section class="mb-8">
        <h2 class="text-xl font-semibold mb-3">📅 Sắp diễn ra</h2>
        <div class="space-y-2">
            @foreach($upcoming as $match)
            <a href="{{ route('sport.match', $match->id) }}" class="flex justify-between items-center p-3 bg-gray-800 rounded hover:bg-gray-700">
                <span>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</span>
                <span class="text-sm text-gray-400">{{ $match->start_time->format('d/m H:i') }}</span>
            </a>
            @endforeach
        </div>
        <a href="{{ route('sport.fixtures') }}" class="inline-block mt-3 text-blue-400">Xem tất cả →</a>
    </section>

    {{-- Articles --}}
    <section>
        <h2 class="text-xl font-semibold mb-3">📰 Tin tức</h2>
        <div class="space-y-3">
            @foreach($recentArticles as $article)
            <a href="{{ route('sport.article', $article->id) }}" class="block p-3 bg-gray-800 rounded hover:bg-gray-700">
                <h3 class="font-medium">{{ $article->title }}</h3>
                <p class="text-sm text-gray-400 mt-1">{{ Str::limit($article->summary, 100) }}</p>
            </a>
            @endforeach
        </div>
        <a href="{{ route('sport.articles') }}" class="inline-block mt-3 text-blue-400">Xem tất cả →</a>
    </section>
</div>
@endsection
