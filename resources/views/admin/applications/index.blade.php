@extends('layouts.job-admin')

@section('title', 'Quản Lý Ứng Viên')
@section('page-title', 'Quản lý Ứng viên')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('admin.applications.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Lọc theo Job</label>
                        <select name="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="">Tất cả Jobs</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ $jobId == $job->id ? 'selected' : '' }}>
                                    {{ $job->title ?: 'Job #' . $job->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 sm:justify-end">
                        <button type="submit" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                            Lọc
                        </button>
                        @if(request('job_id'))
                            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                Bỏ lọc
                            </a>
                        @endif
                    </div>
                </div>

                <p class="text-sm text-gray-500">
                    @if($jobId)
                        Hiển thị {{ $applications->total() }} ứng viên cho job ID #{{ $jobId }}
                    @else
                        Hiển thị {{ $applications->total() }} ứng viên cho tất cả jobs
                    @endif
                </p>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">Danh sách Ứng Viên</h1>
                    <p class="mt-2 text-sm text-gray-700">Xem và quản lý các ứng viên đã apply job</p>
                </div>
            </div>

            @if($applications->count() > 0)
                <div class="mt-6 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Họ Tên</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Số ĐT</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Job Apply</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ngày Apply</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Trạng Thái</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                        <tr class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                {{ $application->applicant_name ?: 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $application->applicant_email ?: 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $application->applicant_phone ?: 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $application->job_title ?: 'Job #' . $application->job_id }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $application->applied_at ? date('d/m/Y H:i', strtotime($application->applied_at)) : 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
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
                                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $status['class'] }}">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- View details -->
                                                    <a href="{{ route('admin.applications.show', $application->id) }}" 
                                                       class="text-primary-600 hover:text-primary-900 p-1 rounded-md hover:bg-primary-50" 
                                                       title="Xem chi tiết">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s2.25-6.75 9.75-6.75S21.75 12 21.75 12 19.5 18.75 12 18.75 2.25 12 2.25 12z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                        </svg>
                                                    </a>

                                                    <!-- View CV -->
                                                    @if($application->resume_file_path)
                                                        <a href="{{ route('admin.applications.download-cv', $application->id) }}" 
                                                           target="_blank" 
                                                           class="text-gray-600 hover:text-gray-900 p-1 rounded-md hover:bg-gray-50" 
                                                           title="Xem CV">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v2.378a2.25 2.25 0 01-.659 1.591l-2.622 2.622a2.25 2.25 0 01-1.591.659H6.75A2.25 2.25 0 014.5 19.5v-15A2.25 2.25 0 016.75 2.25h6.378a2.25 2.25 0 011.591.659l4.622 4.622a2.25 2.25 0 01.659 1.591V14.25z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 9h3" />
                                                            </svg>
                                                        </a>
                                                    @endif

                                                    <!-- Delete -->
                                                    <form method="POST" 
                                                          action="{{ route('admin.applications.destroy', $application->id) }}" 
                                                          class="inline"
                                                          onsubmit="return confirm('Bạn có chắc muốn xóa ứng viên này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50" 
                                                                title="Xóa">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $applications->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Chưa có ứng viên nào</h3>
                    <p class="mt-1 text-sm text-gray-500">Các ứng viên apply job sẽ hiển thị ở đây.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
