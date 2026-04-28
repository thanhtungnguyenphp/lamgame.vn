@extends('layouts.master')

@section('page_title', 'Bài viết đã lưu - Forum')

@section('content')
<div class="fm-page">
    <div class="fm-hdr">
        <div class="container">
            <div class="fm-hdr-row">
                <div>
                    <h1 class="fm-hdr-title">🔖 Bài viết đã lưu</h1>
                    <p class="fm-hdr-desc">Các bài viết bạn đã bookmark</p>
                </div>
                <a href="{{ route('forum.index') }}" class="fm-btn-create" style="background:#64748b;">
                    <i class="fas fa-arrow-left"></i> Quay lại Forum
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="fm-content" style="max-width:800px;margin:1.5rem auto;">
            @forelse($posts as $post)
                @include('lamgame.pages.forum.partials.post-card', ['post' => $post])
            @empty
            <div style="text-align:center;padding:3rem 1rem;color:#94a3b8;">
                <div style="font-size:2.5rem;margin-bottom:0.75rem;">📑</div>
                <h3 style="color:#475569;">Chưa có bài viết nào được lưu</h3>
                <p>Nhấn nút <i class="far fa-bookmark"></i> trên bài viết để lưu lại.</p>
            </div>
            @endforelse

            @if($posts->hasPages())
            <div style="margin-top:1.5rem;">{{ $posts->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.fm-page { min-height: 60vh; }
.fm-hdr { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff; padding: 1.5rem 0; }
.fm-hdr-row { display: flex; justify-content: space-between; align-items: center; }
.fm-hdr-title { font-size: 1.5rem; font-weight: 700; margin: 0; }
.fm-hdr-desc { color: #a0aec0; font-size: 0.9rem; margin: 0.25rem 0 0; }
.fm-btn-create { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; background: linear-gradient(135deg, #6a4c93, #9b5de5); color: #fff; text-decoration: none; }
</style>
@endpush
@endsection
