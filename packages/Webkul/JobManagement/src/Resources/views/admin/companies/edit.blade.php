<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #e9ecef; padding-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #495057; }
        .required::after { content: " *"; color: red; }
        input, textarea, select { width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        input:focus, textarea:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 2px rgba(0,123,255,0.25); }
        .btn { padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; font-weight: 500; }
        .btn-primary { background: #007bff; color: white; }
        .btn-secondary { background: #6c757d; color: white; margin-left: 10px; }
        .btn:hover { opacity: 0.9; }
        .current-logo { margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 4px; }
        .current-logo img { border-radius: 4px; border: 2px solid #dee2e6; }
        .error { color: #dc3545; font-size: 12px; margin-top: 5px; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Company: {{ $company->name ?? 'Unknown' }}</h1>
            <div>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">← Back to List</a>
            </div>
        </div>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.companies.update', $company->id) }}" enctype="multipart/form-data" accept-charset="UTF-8">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="required">Company Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $company->name ?? '') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Brief description about the company...">{!! old('description', e($company->description ?? '')) !!}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $company->website ?? '') }}" placeholder="https://example.com">
                    @error('website')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $company->email ?? '') }}" placeholder="contact@company.com">
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone ?? '') }}" placeholder="+1 234 567 8900">
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" name="industry" id="industry" value="{{ old('industry', $company->industry ?? '') }}" placeholder="Technology, Healthcare, etc.">
                    @error('industry')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="employee_count">Employee Count</label>
                    <input type="number" name="employee_count" id="employee_count" value="{{ old('employee_count', $company->employee_count ?? '') }}" min="1" placeholder="50">
                    @error('employee_count')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="founded_year">Founded Year</label>
                    <input type="number" name="founded_year" id="founded_year" value="{{ old('founded_year', $company->founded_year ?? '') }}" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                    @error('founded_year')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="logo">Company Logo</label>
                @if(isset($company->logo) && $company->logo)
                    <div class="current-logo">
                        <strong>Current Logo:</strong><br>
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name ?? 'Company Logo' }}" style="width: 120px; height: 120px; object-fit: cover; margin-top: 10px;">
                    </div>
                @endif
                <input type="file" name="logo" id="logo" accept="image/*">
                <small style="color: #6c757d;">Upload a new logo to replace the current one. Supported formats: JPG, PNG, GIF (max 2MB)</small>
                @error('logo')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <button type="submit" class="btn btn-primary">Update Company</button>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
