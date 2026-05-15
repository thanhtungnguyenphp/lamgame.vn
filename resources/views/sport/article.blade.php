@extends('shop::layouts.master')
@section('page_title') {{ $article->title }} @endsection

@section('content-wrapper')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <a href="{{ route('sport.articles') }}" class="text-blue-400 text-sm">← Tin tức thể thao</a>
    <h1 class="text-2xl font-bold mt-3 mb-4">{{ $article->title }}</h1>
    <p class="text-sm text-gray-400 mb-6">{{ $article->created_at->format('d/m/Y H:i') }} • {{ $article->source ?? 'lamgame.vn' }}</p>
    <div class="prose prose-invert max-w-none">{!! $article->content !!}</div>
    @if($article->source_url)
        <p class="mt-6 text-sm"><a href="{{ $article->source_url }}" target="_blank" rel="nofollow" class="text-blue-400">Nguồn gốc →</a></p>
    @endif
</div>
@endsection
