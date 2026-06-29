@extends('layouts.master')
@section('page_title') {{ $seo_title ?? "Lịch thi đấu — {$date}" }} @endsection
@section('page_description', $seo_description ?? '')
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "{{ $seo_title ?? '' }}",
    "numberOfItems": {{ $matches->flatten()->count() }}
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
    <h1 class="text-2xl font-bold mb-4">📅 Lịch thi đấu</h1>

    {{-- Date picker --}}
    <div class="flex gap-2 mb-6">
        @for($i = -3; $i <= 7; $i++)
            @php $d = today()->addDays($i)->toDateString(); @endphp
            <a href="{{ route('sport.fixtures', ['date' => $d]) }}"
               class="px-3 py-1 rounded {{ $d === $date ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300' }}">
                {{ today()->addDays($i)->format('d/m') }}
            </a>
        @endfor
    </div>

    @forelse($matches as $leagueId => $leagueMatches)
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2 text-yellow-400">{{ $leagueMatches->first()->league->name ?? $leagueId }}</h2>
            <div class="space-y-2">
                @foreach($leagueMatches as $match)
                <a href="{{ route('sport.match', $match->id) }}" class="flex justify-between items-center p-3 bg-gray-800 rounded hover:bg-gray-700">
                    <span class="w-1/3 text-right">{{ $match->homeTeam->name }}</span>
                    <span class="w-1/3 text-center font-bold">
                        @if($match->status === 'finished')
                            {{ $match->home_score }} - {{ $match->away_score }}
                        @elseif($match->status === 'live')
                            <span class="text-red-400">{{ $match->home_score }} - {{ $match->away_score }}</span>
                        @else
                            {{ $match->start_time->format('H:i') }}
                        @endif
                    </span>
                    <span class="w-1/3">{{ $match->awayTeam->name }}</span>
                </a>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-gray-400">Không có trận đấu nào trong ngày này.</p>
    @endforelse
</div>
@endsection
