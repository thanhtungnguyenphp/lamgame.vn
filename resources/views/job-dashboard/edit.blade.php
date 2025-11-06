<!DOCTYPE html>
<html>
<head>
    <title>Sửa Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Sửa Job: {{ $job->name ?: $job->sku }}</h2>
            <a href="{{ route('job.dashboard.jobs') }}" class="btn btn-secondary">Quay Lại</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('job.dashboard.update', $job->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Thông tin cơ bản -->
                    <h5 class="mb-3">Thông tin cơ bản</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề Job *</label>
                                <input type="text" class="form-control" name="title" value="{{ $job->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Loại Job *</label>
                                <select class="form-select" name="job_type" required>
                                    <option value="">Chọn loại job</option>
                                    <option value="full-time" {{ ($attributes[40] ?? '') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="part-time" {{ ($attributes[40] ?? '') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="contract" {{ ($attributes[40] ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="internship" {{ ($attributes[40] ?? '') == 'internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="freelance" {{ ($attributes[40] ?? '') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="short_description" rows="2">{{ $job->short_description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết *</label>
                        <textarea class="form-control" name="description" rows="5" required>{{ $job->description }}</textarea>
                    </div>

                    <!-- Yêu cầu công việc -->
                    <h5 class="mb-3 mt-4">Yêu cầu công việc</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kinh nghiệm *</label>
                                <select class="form-select" name="experience_level" required>
                                    <option value="">Chọn mức kinh nghiệm</option>
                                    <option value="fresher" {{ ($attributes[41] ?? '') == 'fresher' ? 'selected' : '' }}>Fresher (0-1 năm)</option>
                                    <option value="junior" {{ ($attributes[41] ?? '') == 'junior' ? 'selected' : '' }}>Junior (1-3 năm)</option>
                                    <option value="middle" {{ ($attributes[41] ?? '') == 'middle' ? 'selected' : '' }}>Middle (3-5 năm)</option>
                                    <option value="senior" {{ ($attributes[41] ?? '') == 'senior' ? 'selected' : '' }}>Senior (5+ năm)</option>
                                    <option value="lead" {{ ($attributes[41] ?? '') == 'lead' ? 'selected' : '' }}>Team Lead</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mức lương *</label>
                                <select class="form-select" name="salary_range" required>
                                    <option value="">Chọn mức lương</option>
                                    <option value="5m-10m" {{ ($attributes[42] ?? '') == '5m-10m' ? 'selected' : '' }}>5-10 triệu</option>
                                    <option value="10m-15m" {{ ($attributes[42] ?? '') == '10m-15m' ? 'selected' : '' }}>10-15 triệu</option>
                                    <option value="15m-20m" {{ ($attributes[42] ?? '') == '15m-20m' ? 'selected' : '' }}>15-20 triệu</option>
                                    <option value="20m-30m" {{ ($attributes[42] ?? '') == '20m-30m' ? 'selected' : '' }}>20-30 triệu</option>
                                    <option value="30m-50m" {{ ($attributes[42] ?? '') == '30m-50m' ? 'selected' : '' }}>30-50 triệu</option>
                                    <option value="50m+" {{ ($attributes[42] ?? '') == '50m+' ? 'selected' : '' }}>Trên 50 triệu</option>
                                    <option value="negotiate" {{ ($attributes[42] ?? '') == 'negotiate' ? 'selected' : '' }}>Thỏa thuận</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Địa điểm làm việc</label>
                                <select class="form-select" name="job_location">
                                    <option value="">Chọn địa điểm</option>
                                    <option value="ha-noi" {{ ($attributes[43] ?? '') == 'ha-noi' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="ho-chi-minh" {{ ($attributes[43] ?? '') == 'ho-chi-minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                    <option value="da-nang" {{ ($attributes[43] ?? '') == 'da-nang' ? 'selected' : '' }}>Đà Nẵng</option>
                                    <option value="remote" {{ ($attributes[43] ?? '') == 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="hybrid" {{ ($attributes[43] ?? '') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="other" {{ ($attributes[43] ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kỹ năng yêu cầu</label>
                                <input type="text" class="form-control" name="required_skills" value="{{ $attributes[45] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Quyền lợi và liên hệ -->
                    <h5 class="mb-3 mt-4">Quyền lợi & Liên hệ</h5>
                    <div class="mb-3">
                        <label class="form-label">Quyền lợi</label>
                        <textarea class="form-control" name="job_benefits" rows="3">{{ $attributes[48] ?? '' }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email liên hệ *</label>
                                <input type="email" class="form-control" name="contact_email" value="{{ $attributes[50] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="contact_phone" value="{{ $attributes[51] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Cập Nhật Job</button>
                        <a href="{{ route('job.dashboard.jobs') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
