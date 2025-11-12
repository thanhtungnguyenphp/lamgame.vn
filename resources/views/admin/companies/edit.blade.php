@extends('layouts.job-admin')

@section('title', 'Chỉnh Sửa Công Ty')
@section('page-title', 'Chỉnh sửa Công ty')

@section('content')
<form method="POST" action="{{ route('admin.companies.update', $company->id) }}" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @method('PUT')
    
    <!-- Company Information -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Thông tin Công ty</h3>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Tên công ty *</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required 
                               value="{{ old('name', $company->name) }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="website" class="block text-sm font-medium leading-6 text-gray-900">Website</label>
                    <div class="mt-2">
                        <input type="url" name="website" id="website" 
                               value="{{ old('website', $company->website) }}"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Mô tả công ty</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="4" 
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ old('description', $company->description) }}</textarea>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="logo" class="block text-sm font-medium leading-6 text-gray-900">Logo công ty</label>
                    <div class="mt-2">
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        @if($company->logo)
                            <div class="mt-4 flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                         alt="Current Logo" 
                                         class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Logo hiện tại</p>
                                    <p class="text-xs text-gray-500">Chọn file mới để thay đổi</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex items-center justify-end gap-x-6">
        <a href="{{ route('admin.companies.index') }}" 
           class="text-sm font-semibold leading-6 text-gray-900">Hủy bỏ</a>
        <button type="submit" 
                class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
            Cập nhật
        </button>
    </div>
</form>
@endsection
