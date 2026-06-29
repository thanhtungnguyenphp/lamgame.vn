@extends('layouts.master')
@section('page_title') {{ $team->name }} @endsection

@push('styles')
<style>.container{max-width:1100px}</style>
@endpush
@push('styles')
<link rel="stylesheet" href="/css/sport-utils.css">
@endpush
@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">{{ $team->name }}</h1>

    @if($standing)
    <div class="p-4 bg-gray-800 rounded-lg mb-6">
        <p>BXH: <strong>#{{ $standing->position }}</strong> | Điểm: {{ $standing->points }} | {{ $standing->won }}W {{ $standing->drawn }}D {{ $standing->lost }}L</p>
    </div>
    @endif

    <h2 class="text-lg font-semibold mb-3">Lịch thi đấu gần đây</h2>
    <div class="space-y-2">
        @foreach($fixtures as $match)
        <a href="{{ route('sport.match', $match->id) }}" class="flex justify-between items-center p-3 bg-gray-800 rounded hover:bg-gray-700">
            <span>{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</span>
            <span class="text-sm">
                @if($match->status === 'finished') {{ $match->home_score }}-{{ $match->away_score }}
                @else {{ $match->start_time->format('d/m H:i') }} @endif
            </span>
        </a>
        @endforeach
    </div>
</div>
@endsection
