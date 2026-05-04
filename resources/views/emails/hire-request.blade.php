<x-mail::message>
# Yêu cầu báo giá mới

**Khách hàng:** {{ $request->name }}
**Email:** {{ $request->email }}
**SĐT:** {{ $request->phone ?? 'Không cung cấp' }}
**Công ty:** {{ $request->company ?? 'Cá nhân' }}

**Loại dự án:** {{ ucfirst($request->project_type) }}
**Ngân sách:** {{ $request->budget_range ?? 'Chưa xác định' }}

**Mô tả:**
{{ $request->description }}

<x-mail::button :url="url('/admin/hire-requests')">
Xem trong Admin
</x-mail::button>

Thời gian: {{ $request->created_at->format('d/m/Y H:i') }}
</x-mail::message>
