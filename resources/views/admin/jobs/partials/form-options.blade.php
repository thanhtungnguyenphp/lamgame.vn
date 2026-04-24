{{-- Job form options - rendered server-side from filterOptions --}}
<script>
window.jobFormOptions = @json([
    'job_types' => ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship', 'Remote', 'Hybrid'],
    'experience_levels' => ['Intern', 'Fresher', 'Junior (1-3 năm)', 'Middle (3-5 năm)', 'Senior (5+ năm)', 'Lead/Manager (7+ năm)', 'Director (10+ năm)'],
    'locations' => ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Cần Thơ', 'Đồng Nai', 'Remote'],
    'salary_ranges' => ['Dưới 10 triệu', '10-20 triệu', '20-30 triệu', '30-50 triệu', '50-80 triệu', 'Trên 80 triệu', 'Thỏa thuận'],
    'education_levels' => ['Không yêu cầu', 'Trung cấp/Cao đẳng', 'Đại học', 'Thạc sĩ', 'Tiến sĩ'],
    'english_levels' => ['Không yêu cầu', 'Cơ bản', 'Giao tiếp tốt', 'Thành thạo', 'Bản ngữ'],
    'company_sizes' => ['Startup (1-10 người)', 'Nhỏ (10-50 người)', 'Trung bình (50-200 người)', 'Lớn (200-1000 người)', 'Tập đoàn (1000+ người)'],
    'application_methods' => ['Gửi email', 'Ứng tuyển online', 'Liên hệ trực tiếp', 'Qua website công ty'],
    'skills' => $filterOptions['skills'] ?? [],
    'benefits' => $filterOptions['benefits'] ?? [],
]);
window.existingJobData = @json($job ?? null);
</script>
