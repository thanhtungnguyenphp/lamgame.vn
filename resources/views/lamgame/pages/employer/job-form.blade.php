@extends('layouts.master')
@section('page_title', ($job ? 'Sửa: ' . $job->title : 'Đăng Job Mới') . ' - Employer')
@push('meta')<meta name="robots" content="noindex, nofollow">@endpush

@section('content')
<div class="emp-page">
    <div class="emp-container" style="max-width:750px">
        <div class="emp-header">
            <h1>{{ $job ? '✏️ Sửa Job' : '➕ Đăng Job Mới' }}</h1>
            <a href="{{ route('employer.jobs') }}" class="emp-btn-sm">← Quay lại</a>
        </div>

        @if($errors->any())
        <div class="emp-alert emp-alert--error">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ $job ? route('employer.jobs.update', $job->id) : route('employer.jobs.store') }}" class="emp-form">
            @csrf
            @if($job) @method('PUT') @endif

            <div class="emp-field">
                <label>Tiêu đề job *</label>
                <input type="text" name="title" value="{{ old('title', $job->title ?? '') }}" required placeholder="VD: Senior Unity Developer">
            </div>

            <div class="emp-row">
                <div class="emp-field">
                    <label>Loại công việc *</label>
                    <select name="job_type" required>
                        <option value="full-time" {{ old('job_type', $job->job_type ?? '') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part-time" {{ old('job_type', $job->job_type ?? '') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="contract" {{ old('job_type', $job->job_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="freelance" {{ old('job_type', $job->job_type ?? '') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="intern" {{ old('job_type', $job->job_type ?? '') == 'intern' ? 'selected' : '' }}>Internship</option>
                    </select>
                </div>
                <div class="emp-field">
                    <label>Level</label>
                    <input type="text" name="experience_level" value="{{ old('experience_level', $job->experience_level ?? '') }}" placeholder="junior, senior, lead...">
                </div>
            </div>

            <div class="emp-row">
                <div class="emp-field">
                    <label>Địa điểm</label>
                    <input type="text" name="location" value="{{ old('location', $job->location ?? '') }}" placeholder="Hồ Chí Minh">
                </div>
                <div class="emp-field">
                    <label>Remote?</label>
                    <select name="is_remote">
                        <option value="0" {{ old('is_remote', $job->is_remote ?? 0) == 0 ? 'selected' : '' }}>Không</option>
                        <option value="1" {{ old('is_remote', $job->is_remote ?? 0) == 1 ? 'selected' : '' }}>Có (Remote)</option>
                    </select>
                </div>
            </div>

            <div class="emp-row">
                <div class="emp-field">
                    <label>Salary Range (text)</label>
                    <input type="text" name="salary_range" value="{{ old('salary_range', $job->salary_range ?? '') }}" placeholder="15-25 triệu">
                </div>
                <div class="emp-field">
                    <label>Deadline</label>
                    <input type="date" name="application_deadline" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d') ?? '') }}">
                </div>
            </div>

            <div class="emp-field">
                <label>Mô tả ngắn</label>
                <textarea name="short_description" rows="2" placeholder="Mô tả 1-2 câu...">{{ old('short_description', $job->short_description ?? '') }}</textarea>
            </div>

            <div class="emp-field">
                <label>Mô tả chi tiết *</label>
                <textarea name="description" rows="12" required placeholder="Mô tả công việc, yêu cầu, quyền lợi...">{{ old('description', $job->description ?? '') }}</textarea>
            </div>

            <div class="emp-field">
                <label>Skills (cách nhau bởi dấu phẩy)</label>
                <input type="text" name="skills" value="{{ old('skills', $job ? $job->skills->pluck('skill_name')->implode(', ') : '') }}" placeholder="Unity, C#, Photoshop, Blender">
            </div>

            <div class="emp-field">
                <label>Benefits (cách nhau bởi dấu phẩy)</label>
                <input type="text" name="benefits" value="{{ old('benefits', $job ? $job->benefits->pluck('benefit_name')->implode(', ') : '') }}" placeholder="Lương tháng 13, Bảo hiểm, Remote">
            </div>

            <div class="emp-actions">
                <button type="submit" class="emp-btn emp-btn--primary">{{ $job ? '💾 Lưu thay đổi' : '📝 Tạo Job (Draft)' }}</button>
                <a href="{{ route('employer.jobs') }}" class="emp-btn emp-btn--ghost">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.emp-page{background:#070B14;min-height:80vh;padding:40px 0}
.emp-container{margin:0 auto;padding:0 20px}
.emp-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.emp-header h1{font-size:1.4rem;font-weight:700;color:#F5F7FA}
.emp-form{display:flex;flex-direction:column;gap:16px}
.emp-field{display:flex;flex-direction:column;gap:5px}
.emp-field label{font-size:.82rem;color:#7A8599;font-weight:500}
.emp-field input,.emp-field select,.emp-field textarea{padding:10px 14px;background:rgba(17,24,39,.8);border:1px solid rgba(124,92,255,.15);border-radius:8px;color:#F5F7FA;font-size:.9rem;transition:border-color .2s}
.emp-field input:focus,.emp-field select:focus,.emp-field textarea:focus{outline:none;border-color:#6C63FF}
.emp-field textarea{resize:vertical;font-family:inherit;line-height:1.6}
.emp-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.emp-actions{display:flex;gap:12px;margin-top:8px}
.emp-btn{padding:12px 24px;border-radius:8px;font-weight:600;text-decoration:none;font-size:.9rem;border:none;cursor:pointer}
.emp-btn--primary{background:#6C63FF;color:#fff}.emp-btn--primary:hover{background:#5a52e0}
.emp-btn--ghost{background:transparent;border:1px solid rgba(124,92,255,.3);color:#B7C0D1}.emp-btn--ghost:hover{border-color:#6C63FF}
.emp-btn-sm{padding:6px 12px;border-radius:6px;font-size:.82rem;background:rgba(124,92,255,.1);color:#A78BFA;text-decoration:none}
.emp-alert--error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#F87171;padding:10px;border-radius:8px;font-size:.85rem}
@media(max-width:640px){.emp-row{grid-template-columns:1fr}}
</style>
@endpush
