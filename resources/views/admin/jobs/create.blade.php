@extends('layouts.job-admin')

@section('title', 'Đăng Job Mới')
@section('page-title', 'Đăng Job Mới')

@push('styles')
<link href="{{ asset('css/admin/job-form.css') }}" rel="stylesheet">
@endpush

@push('scripts')
@include('admin.jobs.partials.form-options')
<script src="{{ asset('js/job-form-v2.js') }}" defer></script>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.job-postings.store') }}" enctype="multipart/form-data" class="space-y-8">
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
                    <label for="required_skills" class="block text-sm font-medium leading-6 text-gray-900">Kỹ năng yêu cầu</label>
                    <div class="mt-2">
                        <select id="required_skills" name="required_skills[]" multiple>
                            <!-- Options will be loaded via API -->
                        </select>
                        <p class="mt-1 text-xs text-gray-500" id="skills_count" 
                           data-empty-text="Chưa chọn kỹ năng nào" 
                           data-suffix="kỹ năng đã chọn">
                            Chưa chọn kỹ năng nào
                        </p>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="job_benefits" class="block text-sm font-medium leading-6 text-gray-900">Phúc lợi</label>
                    <div class="mt-2">
                        <select id="job_benefits" name="job_benefits[]" multiple>
                            <!-- Options will be loaded via API -->
                        </select>
                        <p class="mt-1 text-xs text-gray-500" id="benefits_count" 
                           data-empty-text="Chưa chọn phúc lợi nào" 
                           data-suffix="phúc lợi đã chọn">
                            Chưa chọn phúc lợi nào
                        </p>
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
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Thông tin công ty</h3>
            
            @if($company)
                <div class="mb-4 rounded-md bg-blue-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Thông tin công ty hiện tại. Bạn có thể cập nhật thông tin này.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mb-4 rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Bạn chưa có thông tin công ty. Vui lòng nhập thông tin công ty để đăng job.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
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

    <!-- Form Actions -->
    <div class="flex items-center justify-end gap-x-6">
        <a href="{{ route('admin.job-postings.index') }}" 
           class="text-sm font-semibold leading-6 text-gray-900">Hủy bỏ</a>
        <button type="submit" 
                class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
            Đăng Job
        </button>
    </div>
</form>
@endsection

