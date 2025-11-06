@extends('job-dashboard.layout')

@section('title', 'Đăng Job Mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Đăng Job Mới</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('job.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề Job *</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Loại Job *</label>
                        <select class="form-select" name="job_type" required>
                            <option value="full-time">Full-time</option>
                            <option value="part-time">Part-time</option>
                            <option value="contract">Contract</option>
                            <option value="internship">Internship</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kinh nghiệm *</label>
                        <select class="form-select" name="experience_level" required>
                            <option value="fresher">Fresher</option>
                            <option value="junior">Junior (1-3 năm)</option>
                            <option value="senior">Senior (3+ năm)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Mức lương *</label>
                        <select class="form-select" name="salary_range" required>
                            <option value="5m-10m">5-10 triệu</option>
                            <option value="10m-20m">10-20 triệu</option>
                            <option value="20m-30m">20-30 triệu</option>
                            <option value="30m-50m">30-50 triệu</option>
                            <option value="50m+">Trên 50 triệu</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả Job *</label>
                <textarea class="form-control" name="description" rows="5" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Địa điểm</label>
                        <input type="text" class="form-control" name="job_location" placeholder="Hà Nội, TP.HCM...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kỹ năng yêu cầu</label>
                        <input type="text" class="form-control" name="required_skills" placeholder="PHP, Laravel, Vue.js...">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Quyền lợi</label>
                <textarea class="form-control" name="job_benefits" rows="3" placeholder="Bảo hiểm, thưởng..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email liên hệ *</label>
                        <input type="email" class="form-control" name="contact_email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="contact_phone">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Đăng Job</button>
                <a href="{{ route('job.my-jobs') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
