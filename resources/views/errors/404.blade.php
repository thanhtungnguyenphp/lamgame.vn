@extends('layouts.master')

@section('page_title', 'Trang không tồn tại - 404 | LamGame.vn')
@section('page_description', 'Trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.')

@push('meta')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-lg">
        <h1 class="text-8xl font-bold text-purple-500 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-100 mb-4">Trang không tồn tại</h2>
        <p class="text-gray-400 mb-8">
            Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc tạm thời không khả dụng.
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
