<!DOCTYPE html>
<html>
<head>
    <title>Đăng Job Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <form method="POST" action="{{ route('job.dashboard.store') }}" enctype="multipart/form-data">>
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

                    <!-- Thông tin công ty -->
                    <h5 class="mb-3 mt-4">Thông tin công ty</h5>
                    <div id="company-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên công ty *</label>
                                    <input type="text" class="form-control" name="company[name]" id="company_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Website</label>
                                    <input type="url" class="form-control" name="company[website]" id="company_website" placeholder="https://example.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Logo công ty</label>
                                    <input type="file" class="form-control" name="company_logo" id="company_logo" accept="image/*">
                                    <small class="text-muted">Chọn file ảnh (JPG, PNG, GIF). Tối đa 2MB</small>
                                    <div id="logo_preview" class="mt-2" style="display: none;">
                                        <img id="logo_image" src="" alt="Logo preview" style="max-width: 100px; max-height: 100px; border-radius: 8px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mô tả công ty</label>
                                    <textarea class="form-control" name="company[description]" id="company_description" rows="3" placeholder="Mô tả ngắn về công ty..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email công ty</label>
                                    <input type="email" class="form-control" name="company[email]" id="company_email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="company[phone]" id="company_phone">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Số nhân viên</label>
                                    <input type="number" class="form-control" name="company[employee_count]" id="company_employee_count" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Năm thành lập</label>
                                    <input type="number" class="form-control" name="company[founded_year]" id="company_founded_year" min="1900" max="{{ date('Y') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngành nghề</label>
                                    <input type="text" class="form-control" name="company[industry]" id="company_industry" placeholder="Game Development">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" name="company[address]" id="company_address" rows="2" placeholder="Địa chỉ công ty..."></textarea>
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
        
        // Load company info first
        loadCompanyInfo();
        
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

    function loadCompanyInfo() {
        @if(isset($company) && $company)
            // Fill company form with existing data
            const company = @json($company);
            $('#company_name').val(company.name || '');
            $('#company_description').val(company.description || '');
            $('#company_website').val(company.website || '');
            $('#company_email').val(company.email || '');
            $('#company_phone').val(company.phone || '');
            $('#company_employee_count').val(company.employee_count || '');
            $('#company_founded_year').val(company.founded_year || '');
            $('#company_industry').val(company.industry || '');
            $('#company_address').val(company.address || '');
            
            // Show existing logo if available
            if (company.logo_base64) {
                $('#logo_preview').show();
                $('#logo_image').attr('src', company.logo_base64);
            }
            
            // Add info message
            $('#company-section').prepend(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Thông tin công ty hiện tại. Bạn có thể cập nhật thông tin này.
                </div>
            `);
        @else
            // Add message for new company
            $('#company-section').prepend(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Bạn chưa có thông tin công ty. Vui lòng nhập thông tin công ty để đăng job.
                </div>
            `);
        @endif
    }

    // Logo preview functionality
    $(document).on('change', '#company_logo', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                $(this).val('');
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Vui lòng chọn file ảnh.');
                $(this).val('');
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logo_preview').show();
                $('#logo_image').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $('#logo_preview').hide();
        }
    });
    </script>
</body>
</html>
