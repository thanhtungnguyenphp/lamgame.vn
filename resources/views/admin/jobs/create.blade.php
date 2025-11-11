@extends('layouts.job-admin')

@section('title', 'Đăng Job Mới')
@section('page-title', 'Đăng Job Mới')

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
                            <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="freelance" {{ old('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="experience_level" class="block text-sm font-medium leading-6 text-gray-900">Cấp độ kinh nghiệm *</label>
                    <div class="mt-2">
                        <select name="experience_level" id="experience_level" required 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">Chọn cấp độ</option>
                            <option value="intern" {{ old('experience_level') == 'intern' ? 'selected' : '' }}>Thực tập sinh</option>
                            <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>Junior (0-2 năm)</option>
                            <option value="middle" {{ old('experience_level') == 'middle' ? 'selected' : '' }}>Middle (2-5 năm)</option>
                            <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior (5+ năm)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="job_location" class="block text-sm font-medium leading-6 text-gray-900">Địa điểm làm việc *</label>
                    <div class="mt-2">
                        <input type="text" name="job_location" id="job_location" required 
                               value="{{ old('job_location') }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
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
                        <input type="text" name="salary_range" id="salary_range" 
                               value="{{ old('salary_range') }}" placeholder="VD: 15-25 triệu"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="required_skills" class="block text-sm font-medium leading-6 text-gray-900">Kỹ năng yêu cầu</label>
                    <div class="mt-2">
                        <input type="text" name="required_skills" id="required_skills" 
                               value="{{ old('required_skills') }}" placeholder="VD: PHP, Laravel, MySQL"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="job_benefits" class="block text-sm font-medium leading-6 text-gray-900">Phúc lợi</label>
                    <div class="mt-2">
                        <textarea name="job_benefits" id="job_benefits" rows="3" 
                                  placeholder="VD: Bảo hiểm, thưởng tháng 13, du lịch..."
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ old('job_benefits') }}</textarea>
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
