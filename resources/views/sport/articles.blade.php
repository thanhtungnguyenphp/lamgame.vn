@extends('shop::layouts.master')
@section('page_title') Tin tức thể thao @endsection

@section('content-wrapper')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📰 Tin tức thể thao</h1>
    <div class="space-y-4">
        @foreach($articles as $article)
        <a href="{{ route('sport.article', $article->id) }}" class="block p-4 bg-gray-800 rounded-lg hover:bg-gray-700">
            <h2 class="text-lg font-semibold">{{ $article->title }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ Str::limit($article->summary, 150) }}</p>
            <span class="text-xs text-gray-500 mt-2 inline-block">{{ $article->created_at->diffForHumans() }}</span>
        </a>
        @endforeach
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
</div>
@endsection
