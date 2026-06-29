@extends('layouts.master')
@section('page_title') {{ $seo_title ?? "BXH {$league->name}" }} @endsection
@section('page_description', $seo_description ?? '')
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SportsOrganization",
    "name": "{{ $league->name }}",
    "sport": "Football"
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
    <h1 class="text-2xl font-bold mb-4">🏆 {{ $league->name }} — Bảng xếp hạng</h1>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Đội</th>
                    <th class="p-2">Trận</th>
                    <th class="p-2">Thắng</th>
                    <th class="p-2">Hòa</th>
                    <th class="p-2">Thua</th>
                    <th class="p-2">BT</th>
                    <th class="p-2">BB</th>
                    <th class="p-2">HS</th>
                    <th class="p-2 font-bold">Điểm</th>
                    <th class="p-2">Phong độ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($standings as $s)
                <tr class="border-b border-gray-700 hover:bg-gray-800">
                    <td class="p-2">{{ $s->position }}</td>
                    <td class="p-2"><a href="{{ route('sport.team', $s->team_id) }}" class="text-blue-400 hover:underline">{{ $s->team->name ?? $s->team_id }}</a></td>
                    <td class="p-2 text-center">{{ $s->played }}</td>
                    <td class="p-2 text-center">{{ $s->won }}</td>
                    <td class="p-2 text-center">{{ $s->drawn }}</td>
                    <td class="p-2 text-center">{{ $s->lost }}</td>
                    <td class="p-2 text-center">{{ $s->goals_for }}</td>
                    <td class="p-2 text-center">{{ $s->goals_against }}</td>
                    <td class="p-2 text-center">{{ $s->goal_difference > 0 ? '+' : '' }}{{ $s->goal_difference }}</td>
                    <td class="p-2 text-center font-bold">{{ $s->points }}</td>
                    <td class="p-2 text-center">{{ $s->form }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
