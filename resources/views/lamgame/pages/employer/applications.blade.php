@extends('layouts.master')
@section('page_title', 'Ứng viên: ' . $job->title . ' - Employer')
@push('meta')<meta name="robots" content="noindex, nofollow">@endpush

@section('content')
<div class="emp-page">
    <div class="emp-container">
        <div class="emp-header">
            <div>
                <h1>👤 Ứng viên</h1>
                <p style="color:#7A8599;font-size:.88rem;margin-top:4px">{{ $job->title }} — {{ $applications->total() }} đơn</p>
            </div>
            <a href="{{ route('employer.jobs') }}" class="emp-btn-sm">← Quay lại Jobs</a>
        </div>

        @if(session('success'))
        <div class="emp-alert emp-alert--success">{{ session('success') }}</div>
        @endif

        @if($applications->isEmpty())
        <div class="emp-empty"><p>Chưa có ứng viên nào cho vị trí này.</p></div>
        @else
        <div class="emp-table">
            @foreach($applications as $app)
            <div class="emp-app-row">
                <div class="emp-app-row__info">
                    <h4>{{ $app->applicant_name }}</h4>
                    <div class="emp-app-row__contact">
                        <a href="mailto:{{ $app->applicant_email }}">📧 {{ $app->applicant_email }}</a>
                        @if($app->applicant_phone)<span>📱 {{ $app->applicant_phone }}</span>@endif
                    </div>
                    <p class="emp-app-row__date">📅 {{ $app->applied_at?->format('d/m/Y H:i') ?? $app->created_at->format('d/m/Y') }} · Mã: {{ $app->application_code ?? '—' }}</p>
                    @if($app->cover_letter)
                    <details class="emp-app-row__letter"><summary>Cover letter</summary><p>{{ Str::limit($app->cover_letter, 500) }}</p></details>
                    @endif
                </div>
                <div class="emp-app-row__actions">
                    <form method="POST" action="{{ route('employer.applications.status', $app->id) }}" class="emp-status-form">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" {{ $app->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="reviewed" {{ $app->status === 'reviewed' ? 'selected' : '' }}>👁 Reviewed</option>
                            <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>⭐ Shortlisted</option>
                            <option value="accepted" {{ $app->status === 'accepted' ? 'selected' : '' }}>✅ Accepted</option>
                            <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                        </select>
                    </form>
                    @if($app->resume_file_path)
                    <a href="{{ route('admin.applications.download-cv', $app->id) }}" class="emp-btn-sm" target="_blank">📄 CV</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        {{ $applications->links('pagination::simple-tailwind') }}
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
.emp-alert--success{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34D399;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:.88rem}
.emp-table{display:flex;flex-direction:column;gap:10px}
.emp-app-row{display:flex;justify-content:space-between;align-items:flex-start;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:10px;padding:16px 20px;gap:16px}
.emp-app-row:hover{border-color:rgba(124,92,255,.2)}
.emp-app-row__info h4{font-size:.95rem;font-weight:600;color:#F5F7FA;margin-bottom:4px}
.emp-app-row__contact{display:flex;gap:12px;font-size:.82rem;color:#7A8599;margin-bottom:4px}
.emp-app-row__contact a{color:#7A8599;text-decoration:none}.emp-app-row__contact a:hover{color:#7C5CFF}
.emp-app-row__date{font-size:.78rem;color:#5A6577}
.emp-app-row__letter{margin-top:8px;font-size:.85rem;color:#9CA3AF}
.emp-app-row__letter summary{cursor:pointer;color:#7A8599}
.emp-app-row__actions{display:flex;flex-direction:column;gap:8px;align-items:flex-end;flex-shrink:0}
.emp-status-form select{padding:5px 10px;background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.2);border-radius:6px;color:#F5F7FA;font-size:.78rem}
.emp-btn-sm{padding:5px 10px;border-radius:6px;font-size:.78rem;background:rgba(124,92,255,.1);color:#A78BFA;text-decoration:none}
.emp-empty{text-align:center;padding:40px;color:#7A8599}
@media(max-width:768px){.emp-app-row{flex-direction:column}}
</style>
@endpush
