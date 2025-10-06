# API Documentation

## 🚀 **Mobile App APIs**

- [**📱 Mobile App Quick Reference**](MOBILE_APP_API_REFERENCE.md) - **Hướng dẫn nhanh cho mobile dev team**

## Authentication APIs

- [**📋 Get Profile API**](get-profile-api.md) - **📱 API chính lấy thông tin profile đầy đủ cho mobile app**
- [**Admin Profile API**](ADMIN_PROFILE_API.md) - **📱 API chính cho mobile app quản lý profile admin**
- [Login API](LOGIN_API.md) - Tài liệu chi tiết về API đăng nhập
- [Update Profile API](UPDATE_PROFILE_API.md) - Tài liệu chi tiết về API cập nhật hồ sơ người dùng
- [Authentication Guide](auth.md) - Hướng dẫn xác thực tổng quan

## Job Management APIs

- [User Job Management API](user-job-management.md) - API quản lý job cho admin/user đã đăng nhập
- [Public Job API](public-job-api.md) - API public để xem danh sách việc làm
- [Job API Testing Guide](job-api-testing.md) - Hướng dẫn test các Job API

## File Structure
```
docs/api/
├── README.md                    # Overview and index
├── get-profile-api.md          # 📋 Get Profile API (Mobile App)
├── ADMIN_PROFILE_API.md        # 📱 Admin Profile API (Mobile App)
├── LOGIN_API.md                # Login API documentation  
├── UPDATE_PROFILE_API.md       # Update Profile API
├── auth.md                     # Authentication guide
├── user-job-management.md      # User Job Management API
├── public-job-api.md           # Public Job API
├── job-api-testing.md          # Job API testing guide
└── COMMON_RESPONSES.md         # Common API response formats
```

## Common Headers
Tất cả các API request cần có các headers sau:
```
Content-Type: application/json
Accept: application/json
```

Các API cần xác thực cần thêm header:
```
Authorization: Bearer {access_token}
```

## Status Codes

| Status Code | Meaning |
|------------|---------|
| 200 | Success |
| 401 | Unauthorized / Invalid credentials |
| 403 | Forbidden / No permission |
| 404 | Resource not found |
| 422 | Validation error |
| 429 | Too many requests |
| 500 | Server error |

## Response Format

Tất cả các API đều trả về cùng một format:

```json
{
    "status": "success|error",
    "message": "Thông báo cho người dùng",
    "data": {
        // Data khi thành công
    },
    "errors": {
        // Chi tiết lỗi khi thất bại
    }
}
```

## Rate Limiting

- Default: 60 requests/minute
- Headers trả về:
  - `X-RateLimit-Limit`
  - `X-RateLimit-Remaining`
  - `X-RateLimit-Reset`

## Error Handling

Tất cả lỗi đều được trả về với format:
```json
{
    "status": "error",
    "message": "Thông báo lỗi chung",
    "errors": {
        "field": [
            "Chi tiết lỗi"
        ]
    }
}
```

## Authentication

- Sử dụng Laravel Sanctum
- Token-based authentication
- Tokens không có expiry time
- Có thể revoke token khi logout

## Security

- Tất cả requests phải qua HTTPS
- Passwords được hash với bcrypt
- Rate limiting để prevent brute force
- Token revocation khi logout