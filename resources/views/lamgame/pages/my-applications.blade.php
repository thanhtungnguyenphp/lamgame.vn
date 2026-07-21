{{-- My Applications — Track job application status --}}
@extends('layouts.master')

@section('page_title', $page_title ?? 'Đơn ứng tuyển của tôi')
@section('page_description', $page_description ?? '')

@push('meta')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div class="ma-page">
    <div class="ma-container">
        <div class="ma-header">
            <h1 class="ma-header__title">📋 Đơn ứng tuyển của tôi</h1>
            <p class="ma-header__desc">Theo dõi trạng thái đơn ứng tuyển việc làm game</p>
        </div>

        {{-- Status filter tabs --}}
        <div class="ma-tabs">
            <a href="{{ route('lamgame.my-applications') }}" class="ma-tab {{ !$currentStatus ? 'ma-tab--active' : '' }}">Tất cả</a>
            <a href="{{ route('lamgame.my-applications', ['status' => 'pending']) }}" class="ma-tab {{ $currentStatus === 'pending' ? 'ma-tab--active' : '' }}">⏳ Chờ xử lý</a>
            <a href="{{ route('lamgame.my-applications', ['status' => 'reviewed']) }}" class="ma-tab {{ $currentStatus === 'reviewed' ? 'ma-tab--active' : '' }}">👁 Đã xem</a>
            <a href="{{ route('lamgame.my-applications', ['status' => 'shortlisted']) }}" class="ma-tab {{ $currentStatus === 'shortlisted' ? 'ma-tab--active' : '' }}">⭐ Lọt vòng</a>
            <a href="{{ route('lamgame.my-applications', ['status' => 'accepted']) }}" class="ma-tab {{ $currentStatus === 'accepted' ? 'ma-tab--active' : '' }}">✅ Chấp nhận</a>
            <a href="{{ route('lamgame.my-applications', ['status' => 'rejected']) }}" class="ma-tab {{ $currentStatus === 'rejected' ? 'ma-tab--active' : '' }}">❌ Từ chối</a>
        </div>

        {{-- Applications list --}}
        @if($applications->isEmpty())
        <div class="ma-empty">
            <div class="ma-empty__icon">📭</div>
            <h3>Chưa có đơn ứng tuyển nào</h3>
            <p>Bạn chưa ứng tuyển vị trí nào. Khám phá các cơ hội việc làm game ngay!</p>
            <a href="{{ route('lamgame.viec-lam-game') }}" class="ma-btn">🎮 Xem việc làm Game</a>
        </div>
        @else
        <div class="ma-list">
            @foreach($applications as $app)
            @php
                $job = $app->jobPosting;
                $statusMap = [
                    'pending' => ['label' => 'Chờ xử lý', 'class' => 'pending', 'icon' => '⏳'],
                    'reviewed' => ['label' => 'Đã xem', 'class' => 'reviewed', 'icon' => '👁'],
                    'shortlisted' => ['label' => 'Lọt vòng', 'class' => 'shortlisted', 'icon' => '⭐'],
                    'accepted' => ['label' => 'Chấp nhận', 'class' => 'accepted', 'icon' => '✅'],
                    'rejected' => ['label' => 'Từ chối', 'class' => 'rejected', 'icon' => '❌'],
                ];
                $status = $statusMap[$app->status] ?? $statusMap['pending'];
            @endphp
            <div class="ma-card">
                <div class="ma-card__main">
                    <div class="ma-card__info">
                        @if($job)
                        <h3 class="ma-card__title">
                            <a href="{{ route('lamgame.job.detail', $job->slug) }}">{{ $job->title }}</a>
                        </h3>
                        <p class="ma-card__company">{{ $job->company_name ?? 'Công ty' }} · {{ $job->location ?? 'Việt Nam' }}</p>
                        @else
                        <h3 class="ma-card__title">Vị trí không còn tồn tại</h3>
                        <p class="ma-card__company">—</p>
                        @endif
                        <div class="ma-card__meta">
                            <span>📅 Nộp: {{ $app->applied_at ? $app->applied_at->format('d/m/Y') : $app->created_at->format('d/m/Y') }}</span>
                            <span>🔑 Mã: {{ $app->application_code ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="ma-card__status">
                        <span class="ma-badge ma-badge--{{ $status['class'] }}">{{ $status['icon'] }} {{ $status['label'] }}</span>
                    </div>
                </div>
                @if($app->cover_letter)
                <details class="ma-card__detail">
                    <summary>Xem cover letter</summary>
                    <p>{{ Str::limit($app->cover_letter, 300) }}</p>
                </details>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($applications->hasPages())
        <div class="ma-pagination">
            {{ $applications->appends(request()->query())->links('pagination::simple-tailwind') }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.ma-page{background:#070B14;min-height:80vh;padding:40px 0}
.ma-container{max-width:900px;margin:0 auto;padding:0 20px}
.ma-header{margin-bottom:24px}
.ma-header__title{font-size:1.6rem;font-weight:700;color:#F5F7FA;margin-bottom:6px}
.ma-header__desc{color:#7A8599;font-size:.95rem}
.ma-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;border-bottom:1px solid rgba(124,92,255,.15);padding-bottom:12px}
.ma-tab{padding:6px 14px;border-radius:20px;font-size:.85rem;color:#7A8599;text-decoration:none;transition:all .2s}
.ma-tab:hover{color:#F5F7FA;background:rgba(124,92,255,.1)}
.ma-tab--active{color:#fff;background:#6C63FF;font-weight:600}
.ma-list{display:flex;flex-direction:column;gap:12px}
.ma-card{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:12px;padding:18px 20px;transition:border-color .2s}
.ma-card:hover{border-color:rgba(124,92,255,.3)}
.ma-card__main{display:flex;justify-content:space-between;align-items:flex-start;gap:16px}
.ma-card__title{font-size:1.05rem;font-weight:600;color:#F5F7FA;margin-bottom:4px}
.ma-card__title a{color:inherit;text-decoration:none}
.ma-card__title a:hover{color:#7C5CFF}
.ma-card__company{color:#7A8599;font-size:.88rem;margin-bottom:8px}
.ma-card__meta{display:flex;gap:16px;font-size:.82rem;color:#5A6577}
.ma-card__status{flex-shrink:0}
.ma-badge{padding:5px 12px;border-radius:16px;font-size:.8rem;font-weight:600;white-space:nowrap}
.ma-badge--pending{background:rgba(107,114,128,.2);color:#9CA3AF}
.ma-badge--reviewed{background:rgba(59,130,246,.15);color:#60A5FA}
.ma-badge--shortlisted{background:rgba(245,158,11,.15);color:#FBBF24}
.ma-badge--accepted{background:rgba(16,185,129,.15);color:#34D399}
.ma-badge--rejected{background:rgba(239,68,68,.15);color:#F87171}
.ma-card__detail{margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.05)}
.ma-card__detail summary{cursor:pointer;color:#7A8599;font-size:.85rem}
.ma-card__detail p{color:#9CA3AF;font-size:.88rem;margin-top:8px;line-height:1.6}
.ma-empty{text-align:center;padding:60px 20px}
.ma-empty__icon{font-size:3rem;margin-bottom:12px}
.ma-empty h3{color:#F5F7FA;font-size:1.2rem;margin-bottom:8px}
.ma-empty p{color:#7A8599;margin-bottom:20px}
.ma-btn{display:inline-block;padding:10px 24px;background:#6C63FF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;transition:background .2s}
.ma-btn:hover{background:#5a52e0}
.ma-pagination{margin-top:24px;display:flex;justify-content:center}
@media(max-width:640px){.ma-card__main{flex-direction:column;gap:10px}.ma-card__meta{flex-direction:column;gap:4px}.ma-tabs{gap:4px}}
</style>
@endpush
