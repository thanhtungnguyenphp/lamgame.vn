# 📝 User Registration API Documentation

## Endpoint
```
POST /api/auth/register
```

## Description
API để đăng ký admin user mới trong hệ thống Bagisto. Sau khi đăng ký thành công, API sẽ trả về access token để admin có thể sử dụng ngay mà không cần đăng nhập lại.

## Request Headers
```
Content-Type: application/json
Accept: application/json
```

## Request Body
```json
{
  "name": "Nguyễn Văn A",
  "email": "nguyenvana@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!",
  "device_name": "iPhone 15 Pro",
  "terms_accepted": 1
}
```

### Required Fields
| Field | Type | Description |
|-------|------|-------------|
| `name` | string | Tên đầy đủ của người dùng (2-255 ký tự) |
| `email` | string | Email hợp lệ và chưa được sử dụng |
| `password` | string | Mật khẩu ít nhất 8 ký tự |
| `password_confirmation` | string | Xác nhận mật khẩu phải khớp với password |
| `terms_accepted` | boolean | Phải là `true` hoặc `1` |

### Optional Fields
| Field | Type | Description |
|-------|------|-------------|
| `device_name` | string | Tên thiết bị cho token |

## Validation Rules

### Name
- **Required**: Bắt buộc
- **Min Length**: 2 ký tự
- **Max Length**: 255 ký tự
- **Type**: String

### Email
- **Required**: Bắt buộc
- **Format**: RFC email format với DNS validation
- **Unique**: Phải chưa tồn tại trong hệ thống
- **Max Length**: 255 ký tự

### Password
- **Required**: Bắt buộc
- **Min Length**: 8 ký tự
- **Must Match**: `password_confirmation`
- **Format**: Không có yêu cầu phức tạp đặc biệt

### Phone (Optional)
- **Format**: Chỉ chấp nhận số, dấu cách, dấu gạch ngang, dấu cộng, dấu ngoặc đơn
- **Min Length**: 10 ký tự
- **Max Length**: 15 ký tự
- **Auto-format**: Tự động chuyển đổi +84 thành 0

### Terms Accepted
- **Required**: Bắt buộc
- **Value**: Phải là `true`, `1`, `"yes"`, `"on"`

## Success Response (201)
```json
{
  "status": "success",
  "message": "Đăng ký tài khoản thành công.",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Nguyễn Văn A",
      "email": "nguyenvana@example.com",
      "phone": "0909123456",
      "avatar": null,
      "bio": null,
      "status": true,
      "email_verified_at": null,
      "created_at": "2025-09-30 10:30:00",
      "updated_at": "2025-09-30 10:30:00",
      "profile_completed": true
    }
  }
}
```

## Error Responses

### Validation Error (422)
```json
{
  "status": "error",
  "message": "Dữ liệu không hợp lệ.",
  "errors": {
    "email": ["Email này đã được sử dụng."],
    "password": ["Xác nhận mật khẩu không khớp."],
    "terms_accepted": ["Bạn phải đồng ý với điều khoản sử dụng."]
  }
}
```

### Server Error (500)
```json
{
  "status": "error",
  "message": "Có lỗi xảy ra khi đăng ký tài khoản.",
  "errors": {
    "system": ["Vui lòng thử lại sau hoặc liên hệ admin."]
  }
}
```

## Error Messages (Vietnamese)

### Name Field
- `Tên là bắt buộc.`
- `Tên phải có ít nhất 2 ký tự.`
- `Tên không được quá 255 ký tự.`

### Email Field
- `Email là bắt buộc.`
- `Email không đúng định dạng.`
- `Email này đã được sử dụng.`
- `Email không được quá 255 ký tự.`

### Password Field
- `Mật khẩu là bắt buộc.`
- `Mật khẩu phải có ít nhất 8 ký tự.`
- `Xác nhận mật khẩu không khớp.`

### Phone Field
- `Số điện thoại không đúng định dạng.`
- `Số điện thoại phải có ít nhất 10 ký tự.`
- `Số điện thoại không được quá 15 ký tự.`

### Terms Accepted
- `Bạn phải đồng ý với điều khoản sử dụng.`

## Usage Examples

### cURL Example
```bash
curl -X POST "http://localhost:8000/api/auth/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Nguyễn Văn A",
    "email": "nguyenvana@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "phone": "0909123456",
    "device_name": "Mobile App",
    "terms_accepted": 1
  }'
```

### JavaScript Example
```javascript
const response = await fetch('http://localhost:8000/api/auth/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    name: 'Nguyễn Văn A',
    email: 'nguyenvana@example.com',
    password: 'Password123!',
    password_confirmation: 'Password123!',
    phone: '0909123456',
    device_name: 'Web Browser',
    terms_accepted: 1
  })
});

const data = await response.json();

if (data.status === 'success') {
  // Store access token
  localStorage.setItem('access_token', data.data.access_token);
  console.log('User registered:', data.data.user);
} else {
  console.error('Registration failed:', data.errors);
}
```

## Notes

### Data Processing
- **Phone Formatting**: Số điện thoại tự động loại bỏ ký tự không phải số và chuyển đổi +84 thành 0
- **Email Normalization**: Email được chuyển thành chữ thường và loại bỏ khoảng trắng
- **Name Trimming**: Tên được loại bỏ khoảng trắng đầu và cuối

### Security Features
- **Password Hashing**: Mật khẩu được hash bằng bcrypt
- **Rate Limiting**: API có giới hạn 60 requests/minute
- **Input Validation**: Tất cả input đều được validate nghiêm ngặt
- **Logging**: Các hoạt động đăng ký được log để audit

### Token Usage
- **Token Type**: Bearer token (Sanctum)
- **Usage**: Thêm vào header: `Authorization: Bearer {access_token}`
- **Expiration**: Token không có thời hạn mặc định (tuỳ cấu hình)

## Testing
Sử dụng file `test_register_api.php` để test API:
```bash
php test_register_api.php
```

## Related Endpoints
- `POST /api/auth/login` - Đăng nhập
- `GET /api/auth/user` - Lấy thông tin user (cần token)
- `PUT /api/auth/profile` - Cập nhật hồ sơ (cần token)
- `POST /api/auth/logout` - Đăng xuất (cần token)