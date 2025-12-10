@extends('layouts.job-admin')

@section('title', 'Chi tiết Ứng viên')
@section('page-title', 'Chi tiết Ứng viên')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Top bar: back + meta -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5M3 12h18" />
                </svg>
                Quay lại
            </a>
            @if($application->application_code)
                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                    Mã đơn: <span class="ml-1 font-mono">{{ $application->application_code }}</span>
                </span>
            @endif
        </div>
        <div class="text-right text-xs text-gray-500">
            Nộp đơn lúc
            <span class="font-medium">
                {{ $application->applied_at ? date('d/m/Y H:i', strtotime($application->applied_at)) : 'N/A' }}
            </span>
        </div>
    </div>

    <!-- Applicant & job summary -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold leading-6 text-gray-900">
                        {{ $application->applicant_name ?: 'Ứng viên không tên' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Ứng tuyển vào: <span class="font-medium">{{ $application->job_title ?: 'Job #' . $application->job_id }}</span>
                    </p>
                </div>
                <div>
                    @php
                        $statusMap = [
                            'pending' => [
                                'label' => 'Chờ xử lý',
                                'class' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            ],
                            'reviewed' => [
                                'label' => 'Đã xem',
                                'class' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            ],
                            'shortlisted' => [
                                'label' => 'Lọt vòng',
                                'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            ],
                            'rejected' => [
                                'label' => 'Từ chối',
                                'class' => 'bg-red-50 text-red-700 ring-red-600/20',
                            ],
                            'accepted' => [
                                'label' => 'Chấp nhận',
                                'class' => 'bg-green-50 text-green-700 ring-green-600/20',
                            ],
                        ];
                        $status = $statusMap[$application->status] ?? [
                            'label' => 'Mới',
                            'class' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $status['class'] }}">
                        {{ $status['label'] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900">Thông tin liên hệ</h3>
                    <dl class="space-y-2 text-sm text-gray-600">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</dt>
                            <dd>
                                @if($application->applicant_email)
                                    <a href="mailto:{{ $application->applicant_email }}" class="text-primary-600 hover:text-primary-800">
                                        {{ $application->applicant_email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Chưa cung cấp</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Số điện thoại</dt>
                            <dd>
                                @if($application->applicant_phone)
                                    <a href="tel:{{ $application->applicant_phone }}" class="text-primary-600 hover:text-primary-800">
                                        {{ $application->applicant_phone }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Chưa cung cấp</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900">Thông tin ứng tuyển</h3>
                    <dl class="space-y-2 text-sm text-gray-600">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Job</dt>
                            <dd>{{ $application->job_title ?: 'Job #' . $application->job_id }}</dd>
                        </div>

                        @php
                            $additionalInfo = is_string($application->additional_info)
                                ? json_decode($application->additional_info, true)
                                : $application->additional_info;
                        @endphp

                        @if(!empty($additionalInfo['experience_level']))
                            @php
                                $expMap = [
                                    'fresher' => 'Fresher',
                                    'junior'  => 'Junior (1-2 năm)',
                                    'middle'  => 'Middle (3-5 năm)',
                                    'senior'  => 'Senior (5+ năm)',
                                ];
                            @endphp
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kinh nghiệm</dt>
                                <dd>{{ $expMap[$additionalInfo['experience_level']] ?? $additionalInfo['experience_level'] }}</dd>
                            </div>
                        @endif

                        @if(!empty($additionalInfo['applied_via']))
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nguồn ứng tuyển</dt>
                                <dd>{{ $additionalInfo['applied_via'] }}</dd>
                            </div>
                        @endif

                        @if(!empty($additionalInfo['ip_address']))
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">IP Address</dt>
                                <dd class="font-mono">{{ $additionalInfo['ip_address'] }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content: cover letter, notes, CV & actions -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            @if($application->cover_letter)
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-base font-semibold leading-6 text-gray-900 mb-2">Thư giới thiệu</h3>
                        <div class="rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 whitespace-pre-line">
                            {{ $application->cover_letter }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-4 py-5 sm:p-6 space-y-4">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Cập nhật trạng thái & ghi chú</h3>
                    <form method="POST" action="{{ route('admin.applications.update', $application->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                            <select name="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Đã xem</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Lọt vòng</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Chấp nhận</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ghi chú nội bộ</label>
                            <textarea name="employer_notes" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                      placeholder="Nhập ghi chú về ứng viên (chỉ admin thấy)...">{{ $application->employer_notes }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v10.5A2.25 2.25 0 009.75 19.5h4.5a2.25 2.25 0 002.25-2.25V13.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @if($application->resume_file_path)
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6 space-y-3">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">CV / Resume</h3>
                        <p class="text-sm text-gray-500 break-all">
                            {{ basename($application->resume_file_path) }}
                        </p>
                        <a href="{{ route('admin.applications.download-cv', $application->id) }}" target="_blank"
                           class="inline-flex w-full items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-700 border border-gray-300 shadow-sm hover:bg-gray-50">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12 12 16.5M12 16.5 7.5 12M12 16.5V3" />
                            </svg>
                            Tải xuống CV
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg">
                <div class="px-4 py-5 sm:p-6 space-y-3">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Hành động nhanh</h3>
                    <div class="space-y-2">
                        <a href="mailto:{{ $application->applicant_email }}"
                           class="inline-flex w-full items-center justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75 12 12.75l8.25-6" />
                            </svg>
                            Gửi email cho ứng viên
                        </a>

                        @if($application->applicant_phone)
                            <a href="tel:{{ $application->applicant_phone }}"
                               class="inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372a1.125 1.125 0 00-.852-1.09l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293a.75.75 0 01-1.21-.053A12.035 12.035 0 0112.53 14.53a12.035 12.035 0 01-3.059-4.342.75.75 0 01-.053-1.21l1.293-.97a1.125 1.125 0 00.417-1.173L9.022 2.852A1.125 1.125 0 007.932 2H6.75A2.25 2.25 0 004.5 4.25v2.5z" />
                                </svg>
                                Gọi điện
                            </a>
                        @endif

                        <form method="POST" action="{{ route('admin.applications.destroy', $application->id) }}"
                              onsubmit="return confirm('Bạn có chắc muốn xóa ứng viên này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Xóa ứng viên
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
