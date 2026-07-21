@extends('layouts.master')
@section('page_title', 'Employer Dashboard - Làm Game')
@push('meta')<meta name="robots" content="noindex, nofollow">@endpush

@section('content')
<div class="emp-page">
    <div class="emp-container">
        <div class="emp-header">
            <h1>📊 Employer Dashboard</h1>
            <a href="{{ route('employer.jobs.create') }}" class="emp-btn emp-btn--primary">+ Đăng Job Mới</a>
        </div>

        {{-- Stats --}}
        <div class="emp-stats">
            <div class="emp-stat"><span class="emp-stat__num">{{ $stats['active_jobs'] }}</span><span class="emp-stat__label">Jobs Active</span></div>
            <div class="emp-stat"><span class="emp-stat__num">{{ $stats['total_applications'] }}</span><span class="emp-stat__label">Ứng viên</span></div>
            <div class="emp-stat"><span class="emp-stat__num">{{ $stats['total_views'] }}</span><span class="emp-stat__label">Lượt xem</span></div>
            <div class="emp-stat"><span class="emp-stat__num">{{ $stats['total_jobs'] }}</span><span class="emp-stat__label">Tổng Jobs</span></div>
        </div>

        <div class="emp-grid">
            {{-- Recent Jobs --}}
            <div class="emp-card">
                <h3>📋 Jobs gần đây</h3>
                @forelse($recentJobs as $job)
                <div class="emp-item">
                    <a href="{{ route('employer.jobs.edit', $job->id) }}">{{ Str::limit($job->title, 40) }}</a>
                    <span class="emp-badge emp-badge--{{ $job->status }}">{{ $job->status }}</span>
                </div>
                @empty
                <p class="emp-muted">Chưa có job nào. <a href="{{ route('employer.jobs.create') }}">Tạo ngay →</a></p>
                @endforelse
                <a href="{{ route('employer.jobs') }}" class="emp-link">Xem tất cả →</a>
            </div>

            {{-- Recent Applications --}}
            <div class="emp-card">
                <h3>👤 Ứng viên mới</h3>
                @forelse($recentApplications as $app)
                <div class="emp-item">
                    <span>{{ $app->applicant_name }} — <em>{{ Str::limit($app->jobPosting->title ?? '', 25) }}</em></span>
                    <span class="emp-badge emp-badge--{{ $app->status }}">{{ $app->status }}</span>
                </div>
                @empty
                <p class="emp-muted">Chưa có ứng viên.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.emp-page{background:#070B14;min-height:80vh;padding:40px 0}
.emp-container{max-width:1000px;margin:0 auto;padding:0 20px}
.emp-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.emp-header h1{font-size:1.5rem;font-weight:700;color:#F5F7FA}
.emp-btn{padding:10px 20px;border-radius:8px;font-weight:600;text-decoration:none;font-size:.9rem}
.emp-btn--primary{background:#6C63FF;color:#fff}
.emp-btn--primary:hover{background:#5a52e0}
.emp-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:32px}
.emp-stat{text-align:center;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.1);border-radius:10px;padding:16px}
.emp-stat__num{display:block;font-size:1.5rem;font-weight:700;color:#7C5CFF}
.emp-stat__label{font-size:.8rem;color:#7A8599;margin-top:4px}
.emp-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.emp-card{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:12px;padding:20px}
.emp-card h3{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:14px}
.emp-item{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:.88rem;color:#B7C0D1}
.emp-item a{color:#B7C0D1;text-decoration:none}.emp-item a:hover{color:#7C5CFF}
.emp-badge{font-size:.72rem;padding:2px 8px;border-radius:10px;font-weight:600}
.emp-badge--active{background:rgba(16,185,129,.15);color:#34D399}
.emp-badge--draft{background:rgba(107,114,128,.2);color:#9CA3AF}
.emp-badge--paused{background:rgba(245,158,11,.15);color:#FBBF24}
.emp-badge--pending{background:rgba(107,114,128,.2);color:#9CA3AF}
.emp-badge--reviewed{background:rgba(59,130,246,.15);color:#60A5FA}
.emp-badge--shortlisted{background:rgba(245,158,11,.15);color:#FBBF24}
.emp-badge--accepted{background:rgba(16,185,129,.15);color:#34D399}
.emp-badge--rejected{background:rgba(239,68,68,.15);color:#F87171}
.emp-muted{color:#5A6577;font-size:.85rem}
.emp-link{display:block;margin-top:12px;font-size:.85rem;color:#7C5CFF;text-decoration:none}
@media(max-width:768px){.emp-stats{grid-template-columns:repeat(2,1fr)}.emp-grid{grid-template-columns:1fr}}
</style>
@endpush
