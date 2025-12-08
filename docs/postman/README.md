# Postman Collection - Job Management APIs

## Giới thiệu

Collection này chứa tất cả các API endpoints cho hệ thống quản lý Job của LamGame.vn.

## Import vào Postman

1. Mở Postman
2. Click **Import** ở góc trên bên trái
3. Chọn file `Job_APIs.postman_collection.json`
4. Click **Import**

## Cấu hình

### Environment Variables

Sau khi import, cần cấu hình các biến môi trường:

1. Click vào **Environments** trong Postman
2. Tạo environment mới (ví dụ: "LamGame Local")
3. Thêm các biến:

| Variable | Value | Description |
|----------|-------|-------------|
| `base_url` | `https://lamgame.localhost/api` | Base URL của API |
| `auth_token` | `your_token_here` | Bearer token sau khi login |

### Lấy Auth Token

Để test các API protected, cần lấy auth token:

1. Login vào hệ thống
2. Copy Bearer token từ response
3. Paste vào biến `auth_token` trong Environment

## Cấu trúc Collection

### 1. Job Options (Public) - 2 APIs
- **Get Form Data**: Load tất cả options cho form tạo job
- **Search Options**: Tìm kiếm với autocomplete

### 2. Jobs (Public) - 5 APIs
- **List Jobs**: Danh sách jobs công khai
- **Get Job Detail**: Chi tiết 1 job
- **Get Categories**: Danh sách categories
- **Get Attributes**: Danh sách attributes
- **Apply Job**: Ứng tuyển job

### 3. User Jobs (Protected) - 11 APIs
- **List My Jobs**: Danh sách jobs của user
- **Create Job**: Tạo job mới
- **Get My Job Detail**: Chi tiết job của user
- **Update Job**: Cập nhật job
- **Delete Job**: Xóa job
- **Toggle Job Status**: Bật/tắt trạng thái job
- **Get Statistics**: Thống kê jobs
- **Duplicate Job**: Nhân bản job
- **Extend Deadline**: Gia hạn deadline
- **Preview Job**: Xem trước job
- **Boost Job**: Đẩy job lên featured

### 4. Dashboard (Protected) - 3 APIs
- **Get Dashboard**: Tổng quan dashboard
- **Get Job Applications**: Danh sách ứng viên của job
- **Update Application Status**: Cập nhật trạng thái ứng viên

### 5. Analytics (Protected) - 5 APIs
- **Get Overview**: Tổng quan analytics
- **Get Job Analytics**: Analytics của 1 job
- **Get Trends**: Xu hướng theo thời gian
- **Compare Jobs**: So sánh nhiều jobs
- **Get Insights**: Insights và recommendations

### 6. Bulk Operations (Protected) - 7 APIs
- **Bulk Create**: Tạo nhiều jobs cùng lúc
- **Bulk Update**: Cập nhật nhiều jobs
- **Bulk Delete**: Xóa nhiều jobs
- **Bulk Toggle Status**: Đổi trạng thái nhiều jobs
- **Bulk Duplicate**: Nhân bản nhiều jobs
- **Bulk Archive**: Archive nhiều jobs
- **Get Bulk Operation Status**: Kiểm tra trạng thái bulk operation

### 7. Import/Export (Protected) - 7 APIs
- **Import Jobs**: Import jobs từ CSV/Excel
- **Export Jobs (GET)**: Export jobs với query params
- **Export Jobs (POST)**: Export jobs với body
- **Download Import Template**: Tải template import
- **Preview Import**: Xem trước data trước khi import
- **Get Import History**: Lịch sử import
- **Get Field Mapping Options**: Options cho field mapping

## Tổng số APIs: 40 endpoints

## Rate Limiting

- Public APIs: 60 requests/phút
- Job Options APIs: 120 requests/phút
- Protected APIs: 60 requests/phút
- Bulk Operations: 30 requests/phút
- Import/Export: 20 requests/phút

## Authentication

Các API có nhãn **(Protected)** yêu cầu Bearer token trong header:

```
Authorization: Bearer {your_token}
```

Collection đã được cấu hình sẵn để tự động thêm token từ biến `{{auth_token}}`.

## Testing Flow

### 1. Test Public APIs (không cần auth)
1. Get Form Data
2. Search Options
3. List Jobs
4. Get Job Detail

### 2. Test Protected APIs (cần auth)
1. Set `auth_token` trong Environment
2. Create Job
3. List My Jobs
4. Update Job
5. Get Dashboard
6. Get Analytics

### 3. Test Bulk Operations
1. Bulk Create
2. Bulk Update
3. Get Bulk Operation Status

### 4. Test Import/Export
1. Download Import Template
2. Preview Import
3. Import Jobs
4. Export Jobs

## Notes

- Tất cả request body đều sử dụng JSON format
- File upload sử dụng `multipart/form-data`
- Response format thống nhất:
  ```json
  {
    "success": true/false,
    "message": "...",
    "data": {...}
  }
  ```

## Support

Nếu có vấn đề với API, vui lòng tham khảo:
- API Documentation: `/docs/API_JOB_OPTIONS.md`
- Source code: `/app/Http/Controllers/Api/`

---

**Last Updated**: 2025-12-08
**Version**: 2.0.0
