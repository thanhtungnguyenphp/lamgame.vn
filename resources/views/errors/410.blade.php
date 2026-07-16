@extends('layouts.master')

@section('page_title', 'Trang đã bị xóa - 410 | LamGame.vn')
@section('page_description', 'Nội dung này đã bị xóa vĩnh viễn.')

@push('meta')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-lg">
        <h1 class="text-8xl font-bold text-red-500 mb-4">410</h1>
        <h2 class="text-2xl font-semibold text-gray-100 mb-4">Nội dung đã bị xóa</h2>
        <p class="text-gray-400 mb-8">
            Trang này đã bị xóa vĩnh viễn và sẽ không quay lại. Hãy khám phá các nội dung khác trên LamGame.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                🏠 Trang chủ
            </a>
            <a href="/blog" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                📖 Blog
            </a>
            <a href="/source-game" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                🎮 Source Game
            </a>
        </div>
    </div>
</div>
@endsection
