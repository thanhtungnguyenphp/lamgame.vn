{{-- Company Profile — Public company page with active jobs --}}
@extends('layouts.master')

@section('page_title', $page_title ?? $company->name . ' - Tuyển dụng')
@section('page_description', $page_description ?? '')

@section('canonical_url'){{ route('lamgame.company.profile', $company->id) }}@endsection

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ $company->name }}",
    "description": "{{ Str::limit(strip_tags($company->description), 200) }}",
    "url": "{{ route('lamgame.company.profile', $company->id) }}",
    @if($company->logo_url)"logo": "{{ $company->logo_url }}",@endif
    @if($company->website)"sameAs": ["{{ $company->website }}"],@endif
    @if($company->address)"address": {"@type": "PostalAddress", "addressLocality": "{{ $company->address }}"},@endif
    "numberOfEmployees": {"@type": "QuantitativeValue", "value": "{{ $company->employee_count ?? 'N/A' }}"}
}
</script>
@endpush

@section('content')
<div class="cp-page">
    {{-- Company Header --}}
    <section class="cp-hero">
        <div class="cp-container">
            <div class="cp-hero__content">
                <div class="cp-hero__logo">
                    @if($company->logo_url)
                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" loading="lazy">
                    @else
                    <div class="cp-hero__logo-placeholder">{{ strtoupper(substr($company->name, 0, 2)) }}</div>
                    @endif
                </div>
                <div class="cp-hero__info">
                    <h1 class="cp-hero__name">{{ $company->name }}</h1>
                    <div class="cp-hero__meta">
                        @if($company->industry)<span>🎮 {{ $company->industry }}</span>@endif
                        @if($company->address)<span>📍 {{ $company->address }}</span>@endif
                        @if($company->employee_count)<span>👥 {{ $company->employee_count }} nhân viên</span>@endif
                        @if($company->founded_year)<span>📅 Thành lập {{ $company->founded_year }}</span>@endif
                    </div>
                    <div class="cp-hero__actions">
                        @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="noopener" class="cp-btn cp-btn--outline">🌐 Website</a>
                        @endif
                        @if($company->email)
                        <a href="mailto:{{ $company->email }}" class="cp-btn cp-btn--outline">📧 Liên hệ</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="cp-stats">
                <div class="cp-stat"><span class="cp-stat__num">{{ $totalJobs }}</span><span class="cp-stat__label">Việc làm</span></div>
                <div class="cp-stat"><span class="cp-stat__num">{{ $totalApplications }}</span><span class="cp-stat__label">Lượt ứng tuyển</span></div>
            </div>
        </div>
    </section>

    <div class="cp-container cp-body">
        {{-- About --}}
        @if($company->description)
        <section class="cp-section">
            <h2 class="cp-section__title">Giới thiệu</h2>
            <div class="cp-about">{!! nl2br(e($company->description)) !!}</div>
        </section>
        @endif

        {{-- Active Jobs --}}
        <section class="cp-section">
            <h2 class="cp-section__title">Vị trí đang tuyển ({{ $totalJobs }})</h2>

            @if($jobs->isEmpty())
            <div class="cp-empty">
                <p>Hiện tại {{ $company->name }} chưa có vị trí tuyển dụng nào.</p>
                <a href="{{ route('lamgame.viec-lam-game') }}" class="cp-btn">Xem tất cả việc làm →</a>
            </div>
            @else
            <div class="cp-jobs">
                @foreach($jobs as $job)
                <a href="{{ route('lamgame.job.detail', $job->slug) }}" class="cp-job-card">
                    <div class="cp-job-card__main">
                        <h3 class="cp-job-card__title">{{ $job->title }}</h3>
                        <div class="cp-job-card__meta">
                            <span>💰 {{ $job->salary_range ?? 'Thỏa thuận' }}</span>
                            <span>📍 {{ $job->location ?? 'Việt Nam' }}</span>
                            <span>💼 {{ $job->job_type ?? 'Full-time' }}</span>
                        </div>
                        @if($job->skills->count())
                        <div class="cp-job-card__skills">
                            @foreach($job->skills->take(4) as $skill)
                            <span class="cp-tag">{{ $skill->skill_name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="cp-job-card__action">
                        <span class="cp-job-card__time">{{ $job->created_at->diffForHumans() }}</span>
                        <span class="cp-btn cp-btn--sm">Xem chi tiết →</span>
                    </div>
                </a>
                @endforeach
            </div>

            @if($jobs->hasPages())
            <div class="cp-pagination">
                {{ $jobs->links('pagination::simple-tailwind') }}
            </div>
            @endif
            @endif
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
.cp-page{background:#070B14;min-height:80vh}
.cp-container{max-width:900px;margin:0 auto;padding:0 20px}
.cp-hero{padding:48px 0 32px;border-bottom:1px solid rgba(124,92,255,.1)}
.cp-hero__content{display:flex;gap:20px;align-items:flex-start;margin-bottom:24px}
.cp-hero__logo img{width:80px;height:80px;border-radius:12px;object-fit:cover;border:2px solid rgba(124,92,255,.2)}
.cp-hero__logo-placeholder{width:80px;height:80px;border-radius:12px;background:linear-gradient(135deg,#6C63FF,#00D1FF);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;color:#fff}
.cp-hero__name{font-size:1.6rem;font-weight:700;color:#F5F7FA;margin-bottom:8px}
.cp-hero__meta{display:flex;flex-wrap:wrap;gap:12px;color:#7A8599;font-size:.88rem;margin-bottom:12px}
.cp-hero__actions{display:flex;gap:8px}
.cp-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.85rem;font-weight:500;text-decoration:none;transition:all .2s}
.cp-btn--outline{border:1px solid rgba(124,92,255,.3);color:#B7C0D1;background:transparent}
.cp-btn--outline:hover{border-color:#6C63FF;color:#fff;background:rgba(124,92,255,.1)}
.cp-btn--sm{font-size:.8rem;padding:6px 12px;background:rgba(124,92,255,.1);color:#7C5CFF;border-radius:6px}
.cp-stats{display:flex;gap:24px}
.cp-stat{text-align:center}
.cp-stat__num{display:block;font-size:1.4rem;font-weight:700;color:#7C5CFF}
.cp-stat__label{font-size:.8rem;color:#7A8599}
.cp-body{padding:32px 0 60px}
.cp-section{margin-bottom:32px}
.cp-section__title{font-size:1.15rem;font-weight:600;color:#F5F7FA;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid rgba(255,255,255,.05)}
.cp-about{color:#9CA3AF;font-size:.92rem;line-height:1.7}
.cp-jobs{display:flex;flex-direction:column;gap:12px}
.cp-job-card{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:10px;text-decoration:none;transition:all .2s}
.cp-job-card:hover{border-color:rgba(124,92,255,.3);transform:translateY(-1px)}
.cp-job-card__title{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:6px}
.cp-job-card__meta{display:flex;gap:12px;font-size:.82rem;color:#7A8599;margin-bottom:8px}
.cp-job-card__skills{display:flex;gap:6px;flex-wrap:wrap}
.cp-tag{padding:3px 8px;background:rgba(124,92,255,.1);color:#A78BFA;border-radius:4px;font-size:.75rem}
.cp-job-card__action{display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0}
.cp-job-card__time{font-size:.78rem;color:#5A6577}
.cp-empty{text-align:center;padding:40px;color:#7A8599}
.cp-empty .cp-btn{margin-top:12px;background:#6C63FF;color:#fff}
.cp-pagination{margin-top:20px;display:flex;justify-content:center}
@media(max-width:640px){.cp-hero__content{flex-direction:column;align-items:center;text-align:center}.cp-hero__meta{justify-content:center}.cp-job-card{flex-direction:column;align-items:flex-start;gap:12px}.cp-job-card__action{align-items:flex-start;flex-direction:row}}
</style>
@endpush
