@extends('layouts.master')

@section('page_title', '🔥 Trending - Forum')

@section('content')
<div class="fm-page">
    <div class="fm-hdr">
        <div class="container">
            <div class="fm-hdr-row">
                <div>
                    <h1 class="fm-hdr-title">🔥 Bài viết đang hot</h1>
                    <p class="fm-hdr-desc">Nội dung được quan tâm nhiều nhất trong 30 ngày qua</p>
                </div>
                <a href="{{ route('forum.index') }}" class="fm-btn-back"><i class="fas fa-arrow-left"></i> Forum</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="fm-content" style="max-width:800px;margin:1.5rem auto;">
            @forelse($posts as $i => $post)
            <div class="ft-card">
                <span class="ft-rank">#{{ $i + 1 }}</span>
                <div class="ft-body">
                    <a href="{{ route('forum.posts.show', $post->slug) }}" class="ft-title">{{ $post->title }}</a>
                    <div class="ft-meta">
                        <span>{{ $post->author_name }}</span>
                        <span>👁 {{ $post->views_count }}</span>
                        <span>💬 {{ $post->comments_count }}</span>
                        <span>👍 {{ $post->likes_count }}</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <span class="ft-score" title="Hot score">{{ number_format($post->hot_score) }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:3rem;color:#94a3b8;">
                <div style="font-size:2.5rem;">📊</div>
                <p>Chưa có dữ liệu trending. Hot scores được tính mỗi giờ.</p>
            </div>
            @endforelse
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
.fm-btn-back { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; background: #64748b; color: #fff; text-decoration: none; font-size: 0.85rem; }
.ft-card { display: flex; align-items: center; gap: 1rem; padding: 1rem; border-bottom: 1px solid #f1f5f9; }
.ft-rank { font-size: 1.25rem; font-weight: 800; color: #6a4c93; min-width: 2.5rem; text-align: center; }
.ft-body { flex: 1; min-width: 0; }
.ft-title { font-size: 1rem; font-weight: 600; color: #1a202c; text-decoration: none; display: block; }
.ft-title:hover { color: #6a4c93; }
.ft-meta { font-size: 0.8rem; color: #94a3b8; margin-top: 0.25rem; display: flex; gap: 0.75rem; flex-wrap: wrap; }
.ft-score { background: #fef3c7; color: #d97706; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 4px; white-space: nowrap; }
</style>
@endpush
@endsection
