<!DOCTYPE html>
<html>
<head>
    <title>Companies Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .actions { display: flex; gap: 10px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>Companies Management</h1>
            <a href="{{ route('admin.companies.create') }}" class="btn btn-success">+ Add Company</a>
        </div>

        <div class="page-content">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Industry</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($companies) && $companies->count() > 0)
                        @foreach($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <span style="color: #6c757d;">No logo</span>
                                    @endif
                                </td>
                                <td><strong>{{ $company->name }}</strong></td>
                                <td>{{ $company->email ?? '-' }}</td>
                                <td>{{ $company->phone ?? '-' }}</td>
                                <td>{{ $company->industry ?? '-' }}</td>
                                <td>{{ $company->created_at ? $company->created_at->format('Y-m-d H:i') : '-' }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.companies.edit', $company->id) }}" class="btn">Edit</a>
                                    <form method="POST" action="{{ route('admin.companies.destroy', $company->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this company?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">No companies found. <a href="{{ route('admin.companies.create') }}">Add the first company</a></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
