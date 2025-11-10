@extends('admin::layouts.content')

@section('page_title')
    {{ __('job_management::app.admin.companies.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('job_management::app.admin.companies.add-title') }}</h1>
                </div>
                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('job_management::app.admin.companies.save') }}
                    </button>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-lg btn-secondary">
                        {{ __('job_management::app.admin.companies.back') }}
                    </a>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title">
                                {{ __('job_management::app.admin.companies.general') }}
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="name" class="required">{{ __('job_management::app.admin.companies.name') }}</label>
                                <input type="text" class="control" name="name" id="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('job_management::app.admin.companies.description') }}</label>
                                <textarea class="control" name="description" id="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="website">{{ __('job_management::app.admin.companies.website') }}</label>
                                <input type="url" class="control" name="website" id="website" value="{{ old('website') }}">
                                @error('website')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('job_management::app.admin.companies.email') }}</label>
                                <input type="email" class="control" name="email" id="email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">{{ __('job_management::app.admin.companies.phone') }}</label>
                                <input type="text" class="control" name="phone" id="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="industry">{{ __('job_management::app.admin.companies.industry') }}</label>
                                <input type="text" class="control" name="industry" id="industry" value="{{ old('industry') }}">
                                @error('industry')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="employee_count">{{ __('job_management::app.admin.companies.employee-count') }}</label>
                                <input type="number" class="control" name="employee_count" id="employee_count" value="{{ old('employee_count') }}" min="1">
                                @error('employee_count')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="founded_year">{{ __('job_management::app.admin.companies.founded-year') }}</label>
                                <input type="number" class="control" name="founded_year" id="founded_year" value="{{ old('founded_year') }}" min="1900" max="{{ date('Y') }}">
                                @error('founded_year')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="logo">{{ __('job_management::app.admin.companies.logo') }}</label>
                                <input type="file" class="control" name="logo" id="logo" accept="image/*">
                                @error('logo')
                                    <span class="control-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
