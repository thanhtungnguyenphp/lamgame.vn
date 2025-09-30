# API Đăng Nhập (Login API)

## Tổng quan

API đăng nhập cho phép người dùng xác thực và nhận token để truy cập các tính năng được bảo vệ của hệ thống.

**Endpoint**: `POST /api/auth/login`

## Request

### Headers
```
Content-Type: application/json
Accept: application/json
```

### Body Parameters
| Tham số | Kiểu | Bắt buộc | Mô tả |
|---------|------|----------|--------|
| email | string | Có | Email đăng nhập |
| password | string | Có | Mật khẩu |
| device_name | string | Không | Tên thiết bị/trình duyệt |

### Ví dụ Request
```json
{
    "email": "user@example.com",
    "password": "your_password",
    "device_name": "Chrome on MacOS"
}
```

## Response

### 1. Đăng nhập thành công (200 OK)
```json
{
    "status": "success",
    "message": "Đăng nhập thành công.",
    "data": {
        "access_token": "1|mYtXkriIAzEzVDR8fyRdf67cktZTh7kMW5QtGZ8s...",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Test User",
            "email": "test@example.com",
            "phone": null,
            "avatar": null,
            "email_verified_at": "2025-09-30T08:20:06.000000Z",
            "created_at": "2025-09-30T08:20:06.000000Z",
            "updated_at": "2025-09-30T08:20:06.000000Z"
        }
    }
}
```

### 2. Mật khẩu không đúng (401 Unauthorized)
```json
{
    "status": "error",
    "message": "Đăng nhập không thành công.",
    "errors": {
        "password": ["Mật khẩu không chính xác."]
    }
}
```

### 3. Email không tồn tại (401 Unauthorized)
```json
{
    "status": "error",
    "message": "Người dùng không tồn tại.",
    "errors": {
        "email": ["Email không tồn tại trong hệ thống."]
    }
}
```

### 4. Thiếu thông tin (422 Unprocessable Entity)
```json
{
    "status": "error",
    "message": "Dữ liệu không hợp lệ.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### 5. Tài khoản bị khóa (401 Unauthorized)
```json
{
    "status": "error",
    "message": "Tài khoản bị khóa.",
    "errors": {
        "account": ["Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin."]
    }
}
```

### 6. Lỗi hệ thống (500 Internal Server Error)
```json
{
    "status": "error",
    "message": "Có lỗi xảy ra khi đăng nhập.",
    "errors": {
        "system": ["Vui lòng thử lại sau hoặc liên hệ admin."]
    }
}
```

## Validation Rules

| Trường | Quy tắc |
|--------|----------|
| email | - Bắt buộc<br>- Phải là email hợp lệ<br>- Phải tồn tại trong hệ thống |
| password | - Bắt buộc<br>- Phải khớp với mật khẩu đã mã hóa trong database |
| device_name | - Không bắt buộc<br>- Nếu không có sẽ tự lấy User-Agent hoặc "Unknown Device" |

## Authentication

Sau khi đăng nhập thành công:
1. Sử dụng token nhận được trong header các request tiếp theo:
```
Authorization: Bearer {access_token}
```
2. Token không có thời hạn hết hạn mặc định
3. Có thể logout để hủy token

## Các trường hợp đặc biệt

1. **Rate Limiting**: 
   - Mặc định 60 requests/phút
   - Reset sau mỗi phút
   - Áp dụng theo IP address

2. **Account Status**:
   - Tài khoản phải có status = true để đăng nhập
   - Tài khoản bị khóa (status = false) không thể đăng nhập

3. **Security**:
   - Mật khẩu được mã hóa bằng bcrypt
   - Token được tạo unique cho mỗi thiết bị
   - Sử dụng Laravel Sanctum cho API authentication

## Ví dụ sử dụng

### cURL
```bash
curl -X POST https://lamgame.localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123",
    "device_name": "API Test"
  }'
```

### JavaScript/Fetch
```javascript
const response = await fetch('https://lamgame.localhost/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'test@example.com',
    password: 'password123',
    device_name: 'Web Browser'
  })
});

const data = await response.json();
```

### PHP/Guzzle
```php
$client = new \GuzzleHttp\Client();
$response = $client->post('https://lamgame.localhost/api/auth/login', [
    'json' => [
        'email' => 'test@example.com',
        'password' => 'password123',
        'device_name' => 'PHP Client'
    ]
]);
```

## Best Practices

1. **Bảo mật**:
   - Luôn sử dụng HTTPS
   - Không lưu trữ token ở local storage (XSS risk)
   - Không gửi token trong URL

2. **Error Handling**:
   - Luôn kiểm tra status code
   - Xử lý tất cả các loại lỗi có thể xảy ra
   - Hiển thị thông báo lỗi phù hợp cho người dùng

3. **Performance**:
   - Cache token ở client side
   - Sử dụng token cho đến khi logout
   - Tránh gọi API login không cần thiết

## Troubleshooting

### Các lỗi thường gặp

1. **"Email không tồn tại"**
   - Kiểm tra email đã đăng ký chưa
   - Kiểm tra lỗi chính tả

2. **"Mật khẩu không chính xác"**
   - Kiểm tra Caps Lock
   - Reset mật khẩu nếu cần

3. **"Tài khoản bị khóa"**
   - Liên hệ admin để mở khóa
   - Kiểm tra email thông báo lý do khóa

4. **"Lỗi hệ thống"**
   - Thử lại sau vài phút
   - Clear cache và cookies
   - Kiểm tra kết nối mạng