<!DOCTYPE html>
<html>
<head>
    <title>Đăng Job Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Đăng Job Mới</h2>
            <a href="{{ route('job.dashboard.index') }}" class="btn btn-secondary">Quay Lại</a>
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
                <form method="POST" action="{{ route('job.dashboard.store') }}">
                    @csrf
                    
                    <!-- Thông tin cơ bản -->
                    <h5 class="mb-3">Thông tin cơ bản</h5>
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
                                <select class="form-select" name="job_type" id="job_type" required>
                                    <option value="">Chọn loại job</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="short_description" rows="2" placeholder="Mô tả ngắn gọn về job..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết *</label>
                        <textarea class="form-control" name="description" rows="5" required placeholder="Mô tả chi tiết về job, yêu cầu, trách nhiệm..."></textarea>
                    </div>

                    <!-- Yêu cầu công việc -->
                    <h5 class="mb-3 mt-4">Yêu cầu công việc</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kinh nghiệm *</label>
                                <select class="form-select" name="experience_level" id="experience_level" required>
                                    <option value="">Chọn mức kinh nghiệm</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mức lương *</label>
                                <select class="form-select" name="salary_range" id="salary_range" required>
                                    <option value="">Chọn mức lương</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Địa điểm làm việc</label>
                                <select class="form-select" name="job_location" id="job_location">
                                    <option value="">Chọn địa điểm</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kỹ năng yêu cầu</label>
                                <select class="form-control" name="required_skills[]" id="required_skills" multiple>
                                </select>
                                <small class="text-muted">Chọn hoặc nhập kỹ năng mới</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quyền lợi và liên hệ -->
                    <h5 class="mb-3 mt-4">Quyền lợi & Liên hệ</h5>
                    <div class="mb-3">
                        <label class="form-label">Quyền lợi</label>
                        <select class="form-control" name="job_benefits[]" id="job_benefits" multiple>
                        </select>
                        <small class="text-muted">Chọn hoặc nhập quyền lợi mới</small>
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
                                <input type="text" class="form-control" name="contact_phone" placeholder="0123456789">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Đăng Job</button>
                        <a href="{{ route('job.dashboard.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script>
    $(document).ready(function() {
        console.log('Loading job form data...');
        
        // Load form data from API
        $.ajax({
            url: '/api/jobs/options/form-data',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer null'
            },
            dataType: 'json',
            success: function(response) {
                console.log('API Response:', response);
                
                if (response.success && response.data && response.data.attributes) {
                    const attrs = response.data.attributes;
                    
                    // Populate job types
                    if (attrs.job_type && attrs.job_type.options) {
                        const jobTypeSelect = $('#job_type');
                        attrs.job_type.options.forEach(function(item) {
                            jobTypeSelect.append(`<option value="${item.id}">${item.value}</option>`);
                        });
                        console.log('Loaded job types:', attrs.job_type.options.length);
                    }
                    
                    // Populate experience levels
                    if (attrs.experience_level && attrs.experience_level.options) {
                        const experienceSelect = $('#experience_level');
                        attrs.experience_level.options.forEach(function(item) {
                            experienceSelect.append(`<option value="${item.id}">${item.value}</option>`);
                        });
                        console.log('Loaded experience levels:', attrs.experience_level.options.length);
                    }
                    
                    // Populate salary ranges
                    if (attrs.salary_range && attrs.salary_range.options) {
                        const salarySelect = $('#salary_range');
                        attrs.salary_range.options.forEach(function(item) {
                            salarySelect.append(`<option value="${item.id}">${item.value}</option>`);
                        });
                        console.log('Loaded salary ranges:', attrs.salary_range.options.length);
                    }
                    
                    // Populate locations
                    if (attrs.job_location && attrs.job_location.options) {
                        const locationSelect = $('#job_location');
                        attrs.job_location.options.forEach(function(item) {
                            locationSelect.append(`<option value="${item.id}">${item.value}</option>`);
                        });
                        console.log('Loaded locations:', attrs.job_location.options.length);
                    }
                    
                    // Populate skills for Select2
                    if (attrs.required_skills && attrs.required_skills.options) {
                        const skillsData = attrs.required_skills.options.map(item => ({
                            id: item.id,
                            text: item.value
                        }));
                        
                        $('#required_skills').select2({
                            data: skillsData,
                            tags: true,
                            tokenSeparators: [','],
                            placeholder: 'Chọn kỹ năng...'
                        });
                        console.log('Loaded skills:', skillsData.length);
                    } else {
                        $('#required_skills').select2({
                            tags: true,
                            tokenSeparators: [','],
                            placeholder: 'Chọn kỹ năng...'
                        });
                    }
                    
                    // Populate benefits for Select2
                    if (attrs.job_benefits && attrs.job_benefits.options) {
                        const benefitsData = attrs.job_benefits.options.map(item => ({
                            id: item.id,
                            text: item.value
                        }));
                        
                        $('#job_benefits').select2({
                            data: benefitsData,
                            tags: true,
                            tokenSeparators: [','],
                            placeholder: 'Chọn quyền lợi...'
                        });
                        console.log('Loaded benefits:', benefitsData.length);
                    } else {
                        $('#job_benefits').select2({
                            tags: true,
                            tokenSeparators: [','],
                            placeholder: 'Chọn quyền lợi...'
                        });
                    }
                    
                    console.log('Form data loaded successfully!');
                } else {
                    console.error('Invalid API response structure');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: error,
                    response: xhr.responseText
                });
                
                // Initialize Select2 even if API fails
                $('#required_skills').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: 'Chọn kỹ năng...'
                });
                
                $('#job_benefits').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: 'Chọn quyền lợi...'
                });
            }
        });
    });
    </script>
</body>
</html>
