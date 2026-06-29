@extends('layouts.master')
@section('page_title') {{ $seo_title ?? $match->homeTeam->name . ' vs ' . $match->awayTeam->name }} @endsection
@section('page_description', $seo_description ?? '')
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SportsEvent",
    "name": "{{ $match->homeTeam->name ?? '' }} vs {{ $match->awayTeam->name ?? '' }}",
    "startDate": "{{ $match->start_time?->toIso8601String() }}",
    "location": { "@type": "Place", "name": "{{ $match->venue ?? 'TBD' }}" },
    "homeTeam": { "@type": "SportsTeam", "name": "{{ $match->homeTeam->name ?? '' }}" },
    "awayTeam": { "@type": "SportsTeam", "name": "{{ $match->awayTeam->name ?? '' }}" },
    "competitor": [
        { "@type": "SportsTeam", "name": "{{ $match->homeTeam->name ?? '' }}" },
        { "@type": "SportsTeam", "name": "{{ $match->awayTeam->name ?? '' }}" }
    ]
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
    {{-- Score header --}}
    <div class="text-center p-6 bg-gray-800 rounded-lg mb-6">
        <p class="text-sm text-gray-400 mb-2">{{ $match->league->name ?? '' }} • {{ $match->start_time->format('d/m/Y H:i') }}</p>
        <div class="flex justify-center items-center gap-8">
            <div class="text-center">
                <a href="{{ route('sport.team', $match->home_team_id) }}" class="text-lg font-semibold hover:text-blue-400">{{ $match->homeTeam->name }}</a>
            </div>
            <div class="text-3xl font-bold {{ $match->status === 'live' ? 'text-red-400' : '' }}">
                {{ $match->home_score }} - {{ $match->away_score }}
            </div>
            <div class="text-center">
                <a href="{{ route('sport.team', $match->away_team_id) }}" class="text-lg font-semibold hover:text-blue-400">{{ $match->awayTeam->name }}</a>
            </div>
        </div>
        @if($match->status === 'live')
            <span class="inline-block mt-2 px-2 py-1 bg-red-600 text-white text-xs rounded">LIVE {{ $match->minute }}'</span>
        @elseif($match->status === 'finished')
            <span class="inline-block mt-2 px-2 py-1 bg-gray-600 text-white text-xs rounded">Kết thúc</span>
        @endif
        @if($match->venue)<p class="text-sm text-gray-400 mt-2">📍 {{ $match->venue }}</p>@endif
    </div>

    {{-- Events timeline --}}
    @if($match->events && $match->events->isNotEmpty())
    <section class="mb-6">
        <h2 class="text-lg font-semibold mb-3">⚡ Diễn biến</h2>
        <div class="space-y-2">
            @foreach($match->events as $event)
            <div class="flex items-center gap-3 p-2 bg-gray-800 rounded text-sm">
                <span class="text-yellow-400 w-10">{{ $event->minute }}'</span>
                <span>{{ $event->type }} — {{ $event->player_name }}</span>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
