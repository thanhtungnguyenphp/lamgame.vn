@extends('layouts.master')

@section('page_title', '🏆 Bảng xếp hạng - Forum')

@section('content')
<div class="fm-page">
    <div class="fm-hdr">
        <div class="container">
            <div class="fm-hdr-row">
                <div>
                    <h1 class="fm-hdr-title">🏆 Bảng xếp hạng</h1>
                    <p class="fm-hdr-desc">Thành viên đóng góp nhiều nhất cho cộng đồng</p>
                </div>
                <a href="{{ route('forum.index') }}" class="fm-btn-back"><i class="fas fa-arrow-left"></i> Forum</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="max-width:600px;margin:1.5rem auto;">
            <div class="fl-tabs">
                <a href="{{ route('forum.leaderboard', ['period' => 'all']) }}" class="fl-tab {{ $period === 'all' ? 'active' : '' }}">Tổng</a>
                <a href="{{ route('forum.leaderboard', ['period' => 'month']) }}" class="fl-tab {{ $period === 'month' ? 'active' : '' }}">Tháng này</a>
            </div>

            @forelse($leaders as $i => $leader)
            @php
                $user = $period === 'all' ? $leader : $leader->customer;
                $points = $period === 'all' ? $leader->reputation : $leader->month_points;
                if (!$user) continue;
                $badge = app(\App\Services\Forum\ForumReputationService::class)->getBadge($user->reputation ?? 0);
            @endphp
            <div class="fl-row {{ $i < 3 ? 'fl-top' : '' }}">
                <span class="fl-rank">{{ $i + 1 }}</span>
                <div class="fl-user">
                    <div class="fm-avatar" style="width:36px;height:36px;">{{ strtoupper(substr($user->first_name ?? '?', 0, 1)) }}</div>
                    <div>
                        <span class="fl-name">{{ $user->first_name }} {{ $user->last_name }}</span>
                        <span class="fl-badge">{{ $badge['icon'] }} {{ $badge['name'] }}</span>
                    </div>
                </div>
                <span class="fl-points">{{ number_format($points) }} điểm</span>
            </div>
            @empty
            <div style="text-align:center;padding:3rem;color:#94a3b8;">
                <div style="font-size:2.5rem;">🏅</div>
                <p>Chưa có dữ liệu xếp hạng.</p>
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
.fm-hdr-actions { display: flex; gap: 0.75rem; align-items: center; }
.fm-btn-back { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; background: #64748b; color: #fff; text-decoration: none; font-size: 0.85rem; }
.fm-avatar { border-radius: 50%; background: linear-gradient(135deg, #6a4c93, #9b5de5); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; }
.fl-tabs { display: flex; gap: 0.5rem; margin-bottom: 1rem; }
.fl-tab { padding: 0.5rem 1.25rem; border-radius: 6px; background: #f1f5f9; color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
.fl-tab.active { background: #6a4c93; color: #fff; }
.fl-row { display: flex; align-items: center; gap: 1rem; padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; }
.fl-top { background: #fffbeb; }
.fl-rank { font-size: 1.1rem; font-weight: 800; color: #6a4c93; min-width: 2rem; text-align: center; }
.fl-user { display: flex; align-items: center; gap: 0.75rem; flex: 1; }
.fl-name { font-weight: 600; color: #1a202c; font-size: 0.95rem; }
.fl-badge { font-size: 0.75rem; color: #64748b; margin-left: 0.5rem; }
.fl-points { font-weight: 700; color: #d97706; font-size: 0.9rem; white-space: nowrap; }
</style>
@endpush
@endsection
