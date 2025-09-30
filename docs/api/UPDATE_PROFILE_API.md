# API Cập Nhật Hồ Sơ Người Dùng

## Tổng quan

API cho phép người dùng cập nhật thông tin hồ sơ của mình sau khi đã đăng nhập.

**Endpoint**: `PUT /api/auth/profile`

## Request

### Headers
```
Authorization: Bearer {access_token}
Content-Type: application/json
Accept: application/json
```

### Body Parameters
| Tham số | Kiểu | Bắt buộc | Mô tả |
|---------|------|----------|--------|
| name | string | Có | Tên người dùng |
| email | string | Có | Email |
| phone | string | Không | Số điện thoại |
| avatar | file | Không | Ảnh đại diện (jpg, png, gif < 2MB) |
| bio | string | Không | Giới thiệu ngắn |
| current_password | string | Có* | Bắt buộc khi thay đổi email |

### Validation Rules
| Trường | Quy tắc |
|--------|----------|
| name | - Bắt buộc<br>- Tối thiểu 2 ký tự<br>- Tối đa 255 ký tự |
| email | - Bắt buộc<br>- Phải là email hợp lệ<br>- Unique (trừ email hiện tại)<br>- Cần current_password khi thay đổi |
| phone | - Không bắt buộc<br>- Định dạng số điện thoại VN<br>- 10-11 số |
| avatar | - Không bắt buộc<br>- jpg, jpeg, png, gif<br>- Max 2MB |
| bio | - Không bắt buộc<br>- Tối đa 500 ký tự |
| current_password | - Bắt buộc khi đổi email<br>- Phải khớp với mật khẩu hiện tại |

### Ví dụ Request

```json
{
    "name": "Nguyễn Văn A",
    "email": "nguyenvana@example.com",
    "phone": "0912345678",
    "bio": "Frontend Developer với 5 năm kinh nghiệm"
}
```

## Response

### 1. Cập nhật thành công (200 OK)
```json
{
    "status": "success",
    "message": "Cập nhật hồ sơ thành công.",
    "data": {
        "user": {
            "id": 1,
            "name": "Nguyễn Văn A",
            "email": "nguyenvana@example.com",
            "phone": "0912345678",
            "avatar": "https://domain.com/storage/avatars/user1.jpg",
            "bio": "Frontend Developer với 5 năm kinh nghiệm",
            "email_verified_at": "2025-09-30T08:20:06.000000Z",
            "created_at": "2025-09-30T08:20:06.000000Z",
            "updated_at": "2025-09-30T08:33:45.000000Z"
        }
    }
}
```

### 2. Lỗi Validation (422 Unprocessable Entity)
```json
{
    "status": "error",
    "message": "Dữ liệu không hợp lệ.",
    "errors": {
        "name": [
            "Tên người dùng là bắt buộc"
        ],
        "email": [
            "Email không đúng định dạng"
        ],
        "phone": [
            "Số điện thoại không đúng định dạng"
        ]
    }
}
```

### 3. Lỗi Email đã tồn tại (422 Unprocessable Entity)
```json
{
    "status": "error",
    "message": "Dữ liệu không hợp lệ.",
    "errors": {
        "email": [
            "Email này đã được sử dụng."
        ]
    }
}
```

### 4. Lỗi xác thực khi đổi email (401 Unauthorized)
```json
{
    "status": "error",
    "message": "Mật khẩu hiện tại không chính xác.",
    "errors": {
        "current_password": [
            "Mật khẩu hiện tại không chính xác"
        ]
    }
}
```

### 5. Lỗi khi upload avatar (422 Unprocessable Entity)
```json
{
    "status": "error",
    "message": "Dữ liệu không hợp lệ.",
    "errors": {
        "avatar": [
            "File phải là ảnh (jpg, jpeg, png, gif)",
            "Kích thước file không được vượt quá 2MB"
        ]
    }
}
```

## Các trường hợp đặc biệt

1. **Thay đổi email**:
   - Yêu cầu nhập mật khẩu hiện tại
   - Email mới phải chưa được sử dụng
   - Cần verify email mới (nếu có chức năng verify)

2. **Upload avatar**:
   - File cũ sẽ bị xóa khi upload file mới
   - Tự động resize để tối ưu
   - Tạo nhiều kích thước khác nhau (thumbnail, medium)

3. **Xử lý phone**:
   - Tự động format số điện thoại
   - Chấp nhận các format phổ biến (+84, 0, 84)
   - Lưu ở format chuẩn trong database

## Ví dụ Implementation

### cURL
```bash
# Cập nhật thông tin cơ bản
curl -X PUT https://lamgame.localhost/api/auth/profile \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Nguyễn Văn A",
    "email": "nguyenvana@example.com",
    "phone": "0912345678",
    "bio": "Frontend Developer"
  }'

# Upload avatar
curl -X PUT https://lamgame.localhost/api/auth/profile \
  -H "Authorization: Bearer {token}" \
  -F "avatar=@/path/to/image.jpg" \
  -F "name=Nguyễn Văn A" \
  -F "email=nguyenvana@example.com"
```

### JavaScript/Fetch
```javascript
// Cập nhật thông tin
const response = await fetch('/api/auth/profile', {
  method: 'PUT',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'Nguyễn Văn A',
    email: 'nguyenvana@example.com',
    phone: '0912345678'
  })
});

// Upload avatar
const formData = new FormData();
formData.append('avatar', fileInput.files[0]);
formData.append('name', 'Nguyễn Văn A');
formData.append('email', 'nguyenvana@example.com');

const response = await fetch('/api/auth/profile', {
  method: 'PUT',
  headers: {
    'Authorization': `Bearer ${token}`
  },
  body: formData
});