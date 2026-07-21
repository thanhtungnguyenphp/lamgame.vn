{{-- Saved Jobs — Bookmarked job postings --}}
@extends('layouts.master')

@section('page_title', $page_title ?? 'Việc làm đã lưu')
@section('page_description', $page_description ?? '')

@push('meta')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div class="sj-page">
    <div class="sj-container">
        <div class="sj-header">
            <h1 class="sj-header__title">🔖 Việc làm đã lưu</h1>
            <p class="sj-header__desc">Xem lại các vị trí bạn quan tâm</p>
        </div>

        @if($savedJobs->isEmpty())
        <div class="sj-empty">
            <div class="sj-empty__icon">💼</div>
            <h3>Chưa lưu việc làm nào</h3>
            <p>Nhấn nút "Lưu việc làm" trên trang chi tiết để lưu lại xem sau.</p>
            <a href="{{ route('lamgame.viec-lam-game') }}" class="sj-btn">🎮 Tìm việc làm Game</a>
        </div>
        @else
        <div class="sj-grid">
            @foreach($savedJobs as $saved)
            @php $job = $saved->jobPosting; @endphp
            @if($job)
            <div class="sj-card" data-job-id="{{ $job->id }}">
                <div class="sj-card__header">
                    <h3 class="sj-card__title">
                        <a href="{{ route('lamgame.job.detail', $job->slug) }}">{{ $job->title }}</a>
                    </h3>
                    <button class="sj-card__unsave" onclick="unsaveJob({{ $job->id }}, this)" title="Bỏ lưu">
                        <i class="fa fa-heart"></i>
                    </button>
                </div>
                <p class="sj-card__company">{{ $job->company_name ?? 'Công ty' }}</p>
                <div class="sj-card__meta">
                    <span>💰 {{ $job->salary_range ?? 'Thỏa thuận' }}</span>
                    <span>📍 {{ $job->location ?? 'Việt Nam' }}</span>
                    <span>💼 {{ $job->job_type ?? 'Full-time' }}</span>
                </div>
                @if($job->skills && $job->skills->count())
                <div class="sj-card__skills">
                    @foreach($job->skills->take(4) as $skill)
                    <span class="sj-tag">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
                @endif
                <div class="sj-card__footer">
                    <span class="sj-card__time">Lưu {{ $saved->saved_at->diffForHumans() }}</span>
                    <a href="{{ route('lamgame.job.detail', $job->slug) }}" class="sj-card__apply">Xem & Ứng tuyển →</a>
                </div>
                @if($job->application_deadline && $job->application_deadline->isFuture() && $job->application_deadline->diffInDays() <= 3)
                <div class="sj-card__urgent">⚡ Còn {{ $job->application_deadline->diffInDays() }} ngày</div>
                @endif
            </div>
            @endif
            @endforeach
        </div>

        @if($savedJobs->hasPages())
        <div class="sj-pagination">{{ $savedJobs->links('pagination::simple-tailwind') }}</div>
        @endif
        @endif

        {{-- Job Alerts section --}}
        <div class="sj-alerts">
            <h2 class="sj-alerts__title">🔔 Thông báo việc làm</h2>
            <p class="sj-alerts__desc">Nhận email khi có job mới phù hợp với bạn</p>

            <form class="sj-alert-form" onsubmit="createAlert(event)">
                <div class="sj-alert-form__row">
                    <input type="text" name="keywords" placeholder="Keywords (Unity, Senior, Remote...)" class="sj-input">
                    <input type="text" name="location" placeholder="Địa điểm" class="sj-input sj-input--sm">
                    <select name="frequency" class="sj-select">
                        <option value="daily">Hàng ngày</option>
                        <option value="weekly">Hàng tuần</option>
                    </select>
                    <button type="submit" class="sj-btn sj-btn--sm">+ Tạo alert</button>
                </div>
            </form>

            <div id="alertsList"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sj-page{background:#070B14;min-height:80vh;padding:40px 0}
.sj-container{max-width:1000px;margin:0 auto;padding:0 20px}
.sj-header{margin-bottom:24px}
.sj-header__title{font-size:1.5rem;font-weight:700;color:#F5F7FA;margin-bottom:4px}
.sj-header__desc{color:#7A8599;font-size:.9rem}
.sj-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;margin-bottom:32px}
.sj-card{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.08);border-radius:12px;padding:18px;transition:all .2s;position:relative}
.sj-card:hover{border-color:rgba(124,92,255,.25)}
.sj-card__header{display:flex;justify-content:space-between;align-items:flex-start;gap:8px}
.sj-card__title{font-size:1rem;font-weight:600;color:#F5F7FA;margin-bottom:4px;flex:1}
.sj-card__title a{color:inherit;text-decoration:none}
.sj-card__title a:hover{color:#7C5CFF}
.sj-card__unsave{background:none;border:none;color:#F87171;cursor:pointer;font-size:1.1rem;padding:4px}
.sj-card__unsave:hover{transform:scale(1.2)}
.sj-card__company{color:#7A8599;font-size:.85rem;margin-bottom:8px}
.sj-card__meta{display:flex;flex-wrap:wrap;gap:10px;font-size:.8rem;color:#5A6577;margin-bottom:10px}
.sj-card__skills{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px}
.sj-tag{padding:2px 8px;background:rgba(124,92,255,.1);color:#A78BFA;border-radius:4px;font-size:.73rem}
.sj-card__footer{display:flex;justify-content:space-between;align-items:center}
.sj-card__time{font-size:.78rem;color:#5A6577}
.sj-card__apply{font-size:.82rem;color:#7C5CFF;text-decoration:none;font-weight:500}
.sj-card__apply:hover{text-decoration:underline}
.sj-card__urgent{position:absolute;top:12px;right:12px;background:rgba(245,158,11,.15);color:#FBBF24;font-size:.72rem;padding:2px 8px;border-radius:10px;font-weight:600}
.sj-empty{text-align:center;padding:60px 20px}
.sj-empty__icon{font-size:3rem;margin-bottom:12px}
.sj-empty h3{color:#F5F7FA;margin-bottom:8px}
.sj-empty p{color:#7A8599;margin-bottom:20px}
.sj-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#6C63FF;color:#fff;border:none;border-radius:8px;text-decoration:none;font-weight:600;cursor:pointer;font-size:.9rem}
.sj-btn:hover{background:#5a52e0}
.sj-btn--sm{padding:8px 14px;font-size:.82rem}
.sj-alerts{margin-top:48px;padding-top:32px;border-top:1px solid rgba(124,92,255,.1)}
.sj-alerts__title{font-size:1.2rem;font-weight:600;color:#F5F7FA;margin-bottom:4px}
.sj-alerts__desc{color:#7A8599;font-size:.88rem;margin-bottom:16px}
.sj-alert-form__row{display:flex;gap:8px;flex-wrap:wrap}
.sj-input{flex:1;min-width:150px;padding:8px 14px;background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.15);border-radius:8px;color:#F5F7FA;font-size:.88rem}
.sj-input--sm{max-width:140px}
.sj-select{padding:8px 12px;background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.15);border-radius:8px;color:#F5F7FA;font-size:.85rem}
.sj-pagination{display:flex;justify-content:center}
@media(max-width:640px){.sj-grid{grid-template-columns:1fr}.sj-alert-form__row{flex-direction:column}}
</style>
@endpush

@push('scripts')
<script>
function unsaveJob(jobId, btn) {
    fetch('/job/' + jobId + '/save', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
    }).then(r => r.json()).then(d => {
        if (!d.saved) {
            btn.closest('.sj-card').style.opacity = '0.3';
            setTimeout(() => btn.closest('.sj-card').remove(), 300);
        }
    });
}

function createAlert(e) {
    e.preventDefault();
    var form = e.target;
    var data = {
        keywords: form.keywords.value,
        location: form.location.value,
        frequency: form.frequency.value
    };
    fetch('/job/alerts', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'},
        body: JSON.stringify(data)
    }).then(r => r.json()).then(d => {
        if (d.alert) {
            form.reset();
            alert('✅ ' + d.message);
            location.reload();
        } else {
            alert('❌ ' + (d.message || 'Có lỗi'));
        }
    });
}
</script>
@endpush
