@extends('layouts.master')
@section('page_title', 'Quản lý Jobs - Employer')
@push('meta')<meta name="robots" content="noindex, nofollow">@endpush

@section('content')
<div class="emp-page">
    <div class="emp-container">
        <div class="emp-header">
            <h1>📋 Quản lý Jobs</h1>
            <a href="{{ route('employer.jobs.create') }}" class="emp-btn emp-btn--primary">+ Đăng Job Mới</a>
        </div>

        {{-- Status tabs --}}
        <div class="emp-tabs">
            <a href="{{ route('employer.jobs') }}" class="emp-tab {{ !$status ? 'emp-tab--active' : '' }}">Tất cả</a>
            <a href="{{ route('employer.jobs', ['status' => 'active']) }}" class="emp-tab {{ $status === 'active' ? 'emp-tab--active' : '' }}">Active</a>
            <a href="{{ route('employer.jobs', ['status' => 'draft']) }}" class="emp-tab {{ $status === 'draft' ? 'emp-tab--active' : '' }}">Draft</a>
            <a href="{{ route('employer.jobs', ['status' => 'paused']) }}" class="emp-tab {{ $status === 'paused' ? 'emp-tab--active' : '' }}">Paused</a>
            <a href="{{ route('employer.jobs', ['status' => 'expired']) }}" class="emp-tab {{ $status === 'expired' ? 'emp-tab--active' : '' }}">Expired</a>
        </div>

        @if(session('success'))
        <div class="emp-alert emp-alert--success">{{ session('success') }}</div>
        @endif

        @if($jobs->isEmpty())
        <div class="emp-empty"><p>Chưa có job nào. <a href="{{ route('employer.jobs.create') }}">Tạo job đầu tiên →</a></p></div>
        @else
        <div class="emp-table">
            @foreach($jobs as $job)
            <div class="emp-job-row">
                <div class="emp-job-row__main">
                    <h3><a href="{{ route('employer.jobs.edit', $job->id) }}">{{ $job->title }}</a></h3>
                    <div class="emp-job-row__meta">
                        <span>📍 {{ $job->location ?? 'Remote' }}</span>
                        <span>👁 {{ $job->view_count }} views</span>
                        <span>📨 {{ $job->application_count }} ứng viên</span>
                        <span>📅 {{ $job->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="emp-job-row__actions">
                    <span class="emp-badge emp-badge--{{ $job->status }}">{{ $job->status }}</span>
                    <form method="POST" action="{{ route('employer.jobs.toggle-publish', $job->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="emp-btn-sm">{{ $job->status === 'active' ? '⏸ Pause' : '▶ Publish' }}</button>
                    </form>
                    <a href="{{ route('employer.jobs.applications', $job->id) }}" class="emp-btn-sm">👤 Ứng viên</a>
                </div>
            </div>
            @endforeach
        </div>
        {{ $jobs->appends(request()->query())->links('pagination::simple-tailwind') }}
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.emp-page{background:#070B14;min-height:80vh;padding:40px 0}
.emp-container{max-width:1000px;margin:0 auto;padding:0 20px}
.emp-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.emp-header h1{font-size:1.4rem;font-weight:700;color:#F5F7FA}
.emp-btn{padding:10px 20px;border-radius:8px;font-weight:600;text-decoration:none;font-size:.88rem}
.emp-btn--primary{background:#6C63FF;color:#fff}.emp-btn--primary:hover{background:#5a52e0}
.emp-tabs{display:flex;gap:8px;margin-bottom:20px;border-bottom:1px solid rgba(124,92,255,.1);padding-bottom:10px}
.emp-tab{padding:6px 14px;border-radius:16px;font-size:.83rem;color:#7A8599;text-decoration:none;transition:all .2s}
.emp-tab:hover{color:#fff;background:rgba(124,92,255,.1)}
.emp-tab--active{color:#fff;background:#6C63FF}
.emp-alert--success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34D399;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:.88rem}
.emp-table{display:flex;flex-direction:column;gap:10px}
.emp-job-row{display:flex;justify-content:space-between;align-items:center;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:10px;padding:16px 20px;gap:16px}
.emp-job-row:hover{border-color:rgba(124,92,255,.2)}
.emp-job-row__main h3{font-size:.95rem;font-weight:600;color:#F5F7FA;margin-bottom:5px}
.emp-job-row__main h3 a{color:inherit;text-decoration:none}.emp-job-row__main h3 a:hover{color:#7C5CFF}
.emp-job-row__meta{display:flex;gap:12px;font-size:.78rem;color:#5A6577}
.emp-job-row__actions{display:flex;gap:8px;align-items:center;flex-shrink:0}
.emp-badge{font-size:.72rem;padding:3px 8px;border-radius:10px;font-weight:600}
.emp-badge--active{background:rgba(16,185,129,.15);color:#34D399}
.emp-badge--draft{background:rgba(107,114,128,.2);color:#9CA3AF}
.emp-badge--paused{background:rgba(245,158,11,.15);color:#FBBF24}
.emp-badge--expired{background:rgba(239,68,68,.1);color:#F87171}
.emp-btn-sm{padding:5px 10px;border-radius:6px;font-size:.78rem;background:rgba(124,92,255,.1);color:#A78BFA;border:none;cursor:pointer;text-decoration:none}
.emp-btn-sm:hover{background:rgba(124,92,255,.2);color:#fff}
.emp-empty{text-align:center;padding:40px;color:#7A8599}
.emp-empty a{color:#7C5CFF}
@media(max-width:768px){.emp-job-row{flex-direction:column;align-items:flex-start}.emp-job-row__actions{margin-top:10px}}
</style>
@endpush
