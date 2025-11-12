@extends('admin.layouts.app')

@section('title', __('job_management::app.admin.companies.title'))

@section('admin-content')
<div class="admin-page">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="page-header__content">
                <h1 class="page-header__title">{{ __('job_management::app.admin.companies.title') }}</h1>
                <p class="page-header__subtitle">Quản lý danh sách công ty và tuyển dụng</p>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-dark">{{ __('job_management::app.admin.companies.title') }}</h4>
                        <small class="text-muted">Manage companies</small>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('job_management::app.admin.companies.add-title') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Logo</th>
                                <th class="border-0">Name</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Phone</th>
                                <th class="border-0">Industry</th>
                                <th class="border-0">Created At</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <span class="text-muted">No logo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="company-info">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.companies.edit', $company->id) }}" class="text-decoration-none text-dark fw-semibold">
                                                {{ $company->name }}
                                            </a>
                                        </h6>
                                    </div>
                                </td>
                                <td>{{ $company->email ?? '-' }}</td>
                                <td>{{ $company->phone ?? '-' }}</td>
                                <td>{{ $company->industry ?? '-' }}</td>
                                <td>
                                    <div class="date-info">
                                        {{ $company->created_at ? $company->created_at->format('M d, Y') : '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.companies.edit', $company->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCompany({{ $company->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No companies found</h5>
                                        <p class="text-muted">Start by creating your first company</p>
                                        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create Company
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
        }
        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }
        .company-info h6 a:hover {
            color: #0d6efd !important;
        }
        .action-buttons .btn {
            margin: 0 2px;
        }
        .empty-state {
            padding: 2rem;
        }
    </style>

    <script>
        function deleteCompany(companyId) {
            if (confirm('Are you sure you want to delete this company?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/companies/${companyId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
