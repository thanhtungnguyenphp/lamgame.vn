@extends('admin.layouts.app')

@section('title', 'Chỉnh Sửa Job')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
.form-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-section h5 {
    color: #1f2937;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.company-logo-preview {
    max-width: 150px;
    max-height: 150px;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
}
</style>
@endpush

@section('admin-content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h1>Chỉnh Sửa Job</h1>
            <p>Cập nhật thông tin job posting</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.jobs.index') }}" class="btn btn--secondary">
                <i class="icon-arrow-left"></i>
                Quay Lại
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Thông tin cơ bản -->
        <div class="form-section">
            <h5><i class="fas fa-info-circle"></i> Thông tin cơ bản</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề Job *</label>
                        <input type="text" class="form-control" name="title" required value="{{ old('title', $job->name) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Loại Job *</label>
                        <select class="form-select" name="job_type" required>
                            <option value="">Chọn loại job</option>
                            <option value="full-time" {{ old('job_type', $attributes[40] ?? '') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="part-time" {{ old('job_type', $attributes[40] ?? '') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ old('job_type', $attributes[40] ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="freelance" {{ old('job_type', $attributes[40] ?? '') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Cấp độ kinh nghiệm *</label>
                        <select class="form-select" name="experience_level" required>
                            <option value="">Chọn cấp độ</option>
                            <option value="intern" {{ old('experience_level', $attributes[41] ?? '') == 'intern' ? 'selected' : '' }}>Thực tập sinh</option>
                            <option value="junior" {{ old('experience_level', $attributes[41] ?? '') == 'junior' ? 'selected' : '' }}>Junior (0-2 năm)</option>
                            <option value="middle" {{ old('experience_level', $attributes[41] ?? '') == 'middle' ? 'selected' : '' }}>Middle (2-5 năm)</option>
                            <option value="senior" {{ old('experience_level', $attributes[41] ?? '') == 'senior' ? 'selected' : '' }}>Senior (5+ năm)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Địa điểm làm việc *</label>
                        <input type="text" class="form-control" name="job_location" required value="{{ old('job_location', $attributes[43] ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả ngắn *</label>
                <textarea class="form-control" name="short_description" rows="3" required>{{ old('short_description', $job->short_description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả chi tiết *</label>
                <textarea class="form-control" name="description" rows="8" required>{{ old('description', $job->description) }}</textarea>
            </div>
        </div>

        <!-- Thông tin lương và phúc lợi -->
        <div class="form-section">
            <h5><i class="fas fa-dollar-sign"></i> Lương & Phúc lợi</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Mức lương</label>
                        <input type="text" class="form-control" name="salary_range" value="{{ old('salary_range', $attributes[42] ?? '') }}" placeholder="VD: 15-25 triệu">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kỹ năng yêu cầu</label>
                        <input type="text" class="form-control" name="required_skills" value="{{ old('required_skills', $attributes[45] ?? '') }}" placeholder="VD: PHP, Laravel, MySQL">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Phúc lợi</label>
                <textarea class="form-control" name="job_benefits" rows="3" placeholder="VD: Bảo hiểm, thưởng tháng 13, du lịch...">{{ old('job_benefits', $attributes[48] ?? '') }}</textarea>
            </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="form-section">
            <h5><i class="fas fa-phone"></i> Thông tin liên hệ</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email liên hệ *</label>
                        <input type="email" class="form-control" name="contact_email" required value="{{ old('contact_email', $attributes[50] ?? '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $attributes[51] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin công ty -->
        @if($company)
        <div class="form-section">
            <h5><i class="fas fa-building"></i> Thông tin công ty</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tên công ty *</label>
                        <input type="text" class="form-control" name="company[name]" required value="{{ old('company.name', $company->name) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" class="form-control" name="company[website]" value="{{ old('company.website', $company->website) }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả công ty</label>
                <textarea class="form-control" name="company[description]" rows="4">{{ old('company.description', $company->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Logo công ty</label>
                <input type="file" class="form-control" name="company_logo" accept="image/*">
                @if($company && $company->logo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Current Logo" class="company-logo-preview">
                    </div>
                @endif
            </div>
        </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn btn--primary">
                <i class="icon-check"></i>
                Cập Nhật Job
            </button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn--secondary">
                Hủy bỏ
            </a>
        </div>
    </form>
</div>
@endsection
