@extends('layouts.job-admin')

@section('title', 'Quản Lý Công Ty')
@section('page-title', 'Quản lý Công ty')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Thông tin Công ty</h1>
                <p class="mt-2 text-sm text-gray-700">Quản lý thông tin công ty của bạn</p>
            </div>
        </div>

        @if($companies->count() > 0)
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($companies as $company)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <!-- Company Logo -->
                        <div class="flex items-center mb-4">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" 
                                     alt="{{ $company->name }}" 
                                     class="h-12 w-12 rounded-lg object-cover">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $company->name }}</h3>
                                @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank" 
                                       class="text-sm text-primary-600 hover:text-primary-500">
                                        {{ parse_url($company->website, PHP_URL_HOST) }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Company Description -->
                        @if($company->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $company->description }}</p>
                        @endif

                        <!-- Company Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>Tạo: {{ $company->created_at ? $company->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.companies.edit', $company->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Chỉnh sửa
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($companies->hasPages())
                <div class="mt-6">
                    {{ $companies->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">Chưa có thông tin công ty</h3>
                <p class="mt-1 text-sm text-gray-500">Thông tin công ty sẽ được tạo khi bạn đăng job đầu tiên</p>
                <div class="mt-6">
                    <a href="{{ route('admin.jobs.create') }}" 
                       class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Đăng Job Đầu Tiên
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
