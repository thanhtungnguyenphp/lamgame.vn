@extends('layouts.job-admin')

@section('title', 'Đăng Job Mới')
@section('page-title', 'Đăng Job Mới')

@push('styles')
<link href="{{ asset('css/admin/job-form.css') }}" rel="stylesheet">
@endpush

@section('content')
<form method="POST" action="{{ route('admin.jobs.store') }}" enctype="multipart/form-data" class="space-y-8">
    @csrf
    
    <!-- Job Information -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Thông tin Job</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Tiêu đề Job *</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" required 
                               value="{{ old('title') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="job_type" class="block text-sm font-medium leading-6 text-gray-900">Loại Job *</label>
                    <div class="mt-2">
                        <select name="job_type" id="job_type" required 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn loại job</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="experience_level" class="block text-sm font-medium leading-6 text-gray-900">Cấp độ kinh nghiệm *</label>
                    <div class="mt-2">
                        <select name="experience_level" id="experience_level" required 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn cấp độ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="job_location" class="block text-sm font-medium leading-6 text-gray-900">Địa điểm làm việc *</label>
                    <div class="mt-2">
                        <select name="job_location" id="job_location" required 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn địa điểm</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="application_method" class="block text-sm font-medium leading-6 text-gray-900">Phương thức ứng tuyển</label>
                    <div class="mt-2">
                        <select name="application_method" id="application_method" 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn phương thức</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="education_level" class="block text-sm font-medium leading-6 text-gray-900">Trình độ học vấn</label>
                    <div class="mt-2">
                        <select name="education_level" id="education_level" 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn trình độ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="english_level" class="block text-sm font-medium leading-6 text-gray-900">Trình độ tiếng Anh</label>
                    <div class="mt-2">
                        <select name="english_level" id="english_level" 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn trình độ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="company_size" class="block text-sm font-medium leading-6 text-gray-900">Quy mô công ty</label>
                    <div class="mt-2">
                        <select name="company_size" id="company_size" 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn quy mô</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="short_description" class="block text-sm font-medium leading-6 text-gray-900">Mô tả ngắn *</label>
                    <div class="mt-2">
                        <textarea name="short_description" id="short_description" rows="3" required 
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ old('short_description') }}</textarea>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Mô tả chi tiết *</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="8" required 
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary & Benefits -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Lương & Phúc lợi</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="salary_range" class="block text-sm font-medium leading-6 text-gray-900">Mức lương</label>
                    <div class="mt-2">
                        <select name="salary_range" id="salary_range" 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn mức lương</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900">Kỹ năng yêu cầu</label>
                    <div class="mt-2" id="required_skills_container">
                        <!-- Checkboxes sẽ được tạo từ API -->
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Phúc lợi</label>
                    <div class="mt-2" id="job_benefits_container">
                        <!-- Checkboxes sẽ được tạo từ API -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Thông tin liên hệ</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="contact_email" class="block text-sm font-medium leading-6 text-gray-900">Email liên hệ *</label>
                    <div class="mt-2">
                        <input type="email" name="contact_email" id="contact_email" required 
                               value="{{ old('contact_email') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium leading-6 text-gray-900">Số điện thoại</label>
                    <div class="mt-2">
                        <input type="text" name="contact_phone" id="contact_phone" 
                               value="{{ old('contact_phone') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Information -->
    @if($company)
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Thông tin công ty</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="company_name" class="block text-sm font-medium leading-6 text-gray-900">Tên công ty *</label>
                    <div class="mt-2">
                        <input type="text" name="company[name]" id="company_name" required 
                               value="{{ $company->name ?? old('company.name') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="company_website" class="block text-sm font-medium leading-6 text-gray-900">Website</label>
                    <div class="mt-2">
                        <input type="url" name="company[website]" id="company_website" 
                               value="{{ $company->website ?? old('company.website') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="company_description" class="block text-sm font-medium leading-6 text-gray-900">Mô tả công ty</label>
                    <div class="mt-2">
                        <textarea name="company[description]" id="company_description" rows="4" 
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ $company->description ?? old('company.description') }}</textarea>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="company_logo" class="block text-sm font-medium leading-6 text-gray-900">Logo công ty</label>
                    <div class="mt-2">
                        <input type="file" name="company_logo" id="company_logo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        @if($company && $company->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Current Logo" class="h-20 w-20 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Actions -->
    <div class="flex items-center justify-end gap-x-6">
        <a href="{{ route('admin.jobs.index') }}" 
           class="text-sm font-semibold leading-6 text-gray-900">Hủy bỏ</a>
        <button type="submit" 
                class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
            Đăng Job
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
console.log('Script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, starting API call');
    
    fetch('/api/jobs/options/form-data', {
        method: 'GET',
        headers: { 
            'Authorization': 'Bearer null',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('API Response:', data);
        
        const attributes = data.data.attributes;
        
        // Populate job_type
        const jobTypeSelect = document.getElementById('job_type');
        if (jobTypeSelect) {
            const placeholder = jobTypeSelect.querySelector('option[value=""]');
            jobTypeSelect.innerHTML = '';
            if (placeholder) jobTypeSelect.appendChild(placeholder);
            
            attributes.job_type.options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.id;
                opt.textContent = option.value;
                jobTypeSelect.appendChild(opt);
            });
            console.log('Job type populated');
        }
        
        // Populate experience_level
        const expSelect = document.getElementById('experience_level');
        if (expSelect) {
            const placeholder = expSelect.querySelector('option[value=""]');
            expSelect.innerHTML = '';
            if (placeholder) expSelect.appendChild(placeholder);
            
            attributes.experience_level.options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.id;
                opt.textContent = option.value;
                expSelect.appendChild(opt);
            });
            console.log('Experience level populated');
        }
        
        // Populate job_location
        const locSelect = document.getElementById('job_location');
        if (locSelect) {
            const placeholder = locSelect.querySelector('option[value=""]');
            locSelect.innerHTML = '';
            if (placeholder) locSelect.appendChild(placeholder);
            
            attributes.job_location.options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.id;
                opt.textContent = option.value;
                locSelect.appendChild(opt);
            });
            console.log('Job location populated');
        }
        
        // Populate salary_range
        const salarySelect = document.getElementById('salary_range');
        if (salarySelect) {
            const placeholder = salarySelect.querySelector('option[value=""]');
            salarySelect.innerHTML = '';
            if (placeholder) salarySelect.appendChild(placeholder);
            
            attributes.salary_range.options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.id;
                opt.textContent = option.value;
                salarySelect.appendChild(opt);
            });
            console.log('Salary range populated');
        }
        
        // Populate required_skills checkboxes
        const skillsContainer = document.getElementById('required_skills_container');
        if (skillsContainer) {
            skillsContainer.innerHTML = '';
            attributes.required_skills.options.forEach(option => {
                const wrapper = document.createElement('div');
                wrapper.className = 'flex items-center mb-2';
                wrapper.innerHTML = `
                    <input type="checkbox" name="required_skills[]" value="${option.id}" 
                           id="skill_${option.id}" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                    <label for="skill_${option.id}" class="ml-2 text-sm text-gray-900">
                        ${option.value}
                    </label>
                `;
                skillsContainer.appendChild(wrapper);
            });
            console.log('Skills populated');
        }
        
        // Populate job_benefits checkboxes
        const benefitsContainer = document.getElementById('job_benefits_container');
        if (benefitsContainer) {
            benefitsContainer.innerHTML = '';
            attributes.job_benefits.options.forEach(option => {
                const wrapper = document.createElement('div');
                wrapper.className = 'flex items-center mb-2';
                wrapper.innerHTML = `
                    <input type="checkbox" name="job_benefits[]" value="${option.id}" 
                           id="benefit_${option.id}" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                    <label for="benefit_${option.id}" class="ml-2 text-sm text-gray-900">
                        ${option.value}
                    </label>
                `;
                benefitsContainer.appendChild(wrapper);
            });
            console.log('Benefits populated');
        }
        
        console.log('All fields populated successfully');
    })
    .catch(error => {
        console.error('Error loading form data:', error);
    });
});
</script>
@endpush
