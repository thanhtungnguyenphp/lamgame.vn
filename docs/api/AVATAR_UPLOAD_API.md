# 📸 Avatar Upload API Documentation

## 🎯 Overview

API endpoint để tải lên và lưu avatar cho admin user trong hệ thống LamGame.vn. API này cho phép admin users upload và quản lý ảnh đại diện của mình thông qua mobile app hoặc frontend.

## 🔗 Endpoint

```
POST /api/auth/avatar
```

## 🔐 Authentication

- **Required**: `Bearer Token` (Laravel Sanctum)
- **Middleware**: `auth:sanctum`

## 📋 Request Details

### Headers
```http
Content-Type: multipart/form-data
Authorization: Bearer {access_token}
```

### Parameters

| Parameter | Type | Required | Description | Validation |
|-----------|------|----------|-------------|------------|
| `avatar` | File | Yes | Ảnh đại diện | `required\|image\|mimes:jpeg,jpg,png,gif,webp\|max:2048` |

### Validation Rules

- **File Type**: Chỉ chấp nhận: `jpeg`, `jpg`, `png`, `gif`, `webp`
- **File Size**: Tối đa 2MB (2048KB)
- **Required**: Bắt buộc phải có file

## 📤 Response Format

### ✅ Success Response (200)

```json
{
  "status": "success",
  "message": "Tải lên ảnh đại diện thành công.",
  "data": {
    "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@lamgame.vn", 
      "image": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
      "image_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
      "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
      "status": true,
      "role_id": 1,
      "created_at": "2023-10-05 14:20:34",
      "updated_at": "2023-10-05 14:20:34",
      "profile_completed": true
    }
  }
}
```

### ❌ Validation Error (422)

```json
{
  "status": "error", 
  "message": "Dữ liệu không hợp lệ.",
  "errors": {
    "avatar": [
      "Vui lòng chọn ảnh đại diện."
    ]
  }
}
```

### ❌ File Too Large (422)

```json
{
  "status": "error",
  "message": "Dữ liệu không hợp lệ.", 
  "errors": {
    "avatar": [
      "Kích thước ảnh không được vượt quá 2MB."
    ]
  }
}
```

### ❌ Invalid File Type (422)

```json
{
  "status": "error",
  "message": "Dữ liệu không hợp lệ.",
  "errors": {
    "avatar": [
      "Ảnh phải có định dạng: jpeg, jpg, png, gif, webp."
    ]
  }
}
```

### ❌ Unauthorized (401)

```json
{
  "status": "error",
  "message": "Unauthenticated."
}
```

### ❌ Server Error (500)

```json
{
  "status": "error",
  "message": "Có lỗi xảy ra khi tải lên ảnh đại diện.",
  "errors": {
    "system": [
      "Vui lòng thử lại sau hoặc liên hệ admin."
    ]
  }
}
```

## 🛠️ Technical Implementation

### Image Processing
- **Automatic Resize**: 300x300 pixels (maintain aspect ratio with crop to fit)
- **Storage Location**: `storage/app/public/admin/`
- **File Naming**: `avatar_{user_id}_{timestamp}.{extension}`
- **Old Avatar Cleanup**: Tự động xóa ảnh cũ khi upload ảnh mới

### Security Features
- **Authentication Required**: Chỉ authenticated admin users mới có thể upload
- **File Validation**: Kiểm tra loại file và kích thước
- **Unique Filename**: Tránh conflict và overwrite files
- **Path Traversal Protection**: Laravel Storage handles path security

## 📱 Usage Examples

### cURL Example

```bash
curl -X POST "https://lamgame.localhost/api/auth/avatar" \
  -H "Authorization: Bearer {your_access_token}" \
  -H "Content-Type: multipart/form-data" \
  -F "avatar=@/path/to/your/image.jpg"
```

### JavaScript (Fetch)

```javascript
const formData = new FormData();
formData.append('avatar', imageFile);

fetch('/api/auth/avatar', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${accessToken}`,
  },
  body: formData
})
.then(response => response.json())
.then(data => {
  if (data.status === 'success') {
    console.log('Avatar uploaded:', data.data.avatar_url);
    // Update UI with new avatar
  }
});
```

### React Native Example

```javascript
const uploadAvatar = async (imageUri) => {
  const formData = new FormData();
  formData.append('avatar', {
    uri: imageUri,
    type: 'image/jpeg',
    name: 'avatar.jpg',
  });

  try {
    const response = await fetch('/api/auth/avatar', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'multipart/form-data',
      },
      body: formData,
    });
    
    const result = await response.json();
    return result;
  } catch (error) {
    console.error('Upload failed:', error);
  }
};
```

## 🔄 Integration with Existing APIs

### Login Response
Sau khi upload avatar, user profile sẽ được cập nhật. Login API sẽ trả về thông tin avatar mới:

```json
{
  "status": "success",
  "data": {
    "access_token": "...",
    "user": {
      "id": 1,
      "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg"
    }
  }
}
```

### Get Profile API
`GET /api/auth/user` sẽ trả về avatar URL đã được cập nhật.

### Update Profile API  
`PUT /api/auth/profile` vẫn hoạt động bình thường để cập nhật thông tin khác. Avatar riêng biệt qua endpoint này.

## 📂 File Management

### Storage Structure
```
storage/
├── app/
│   └── public/
│       └── admin/
│           ├── avatar_1_1696507234.jpg
│           ├── avatar_2_1696507890.png
│           └── ...
```

### Public Access
```
public/
└── storage/       # Symlinked to storage/app/public/
    └── admin/
        └── avatar_*.jpg
```

## 🚀 Mobile App Integration

### Kịch bản sử dụng:

1. **User đăng nhập** → Nhận access token
2. **Vào màn hình profile** → Hiển thị avatar hiện tại (nếu có)
3. **Chọn "Edit Profile"** → Hiển thị option upload avatar
4. **Chọn "Upload Avatar"** → Mở gallery/camera
5. **Chọn ảnh** → Validate kích thước và format
6. **Upload** → Gọi `POST /api/auth/avatar`
7. **Success** → Cập nhật UI với avatar URL mới
8. **Lưu profile** → Avatar đã được lưu tự động

## 🧪 Testing

### Test Cases

1. **✅ Valid Upload**
   - File: JPG, 1MB
   - Expected: 200, avatar uploaded successfully

2. **❌ File Too Large**  
   - File: JPG, 3MB
   - Expected: 422, validation error

3. **❌ Invalid Format**
   - File: PDF, 500KB  
   - Expected: 422, format validation error

4. **❌ No Authentication**
   - Request: No Bearer token
   - Expected: 401, unauthorized

5. **❌ Missing File**
   - Request: Empty form data
   - Expected: 422, required validation error

## 🔗 Related APIs

- `POST /api/auth/login` - Login và nhận token
- `GET /api/auth/user` - Get user profile (bao gồm avatar)
- `PUT /api/auth/profile` - Update profile info khác
- `POST /api/auth/logout` - Logout

## 📊 Logging

### Success Log
```
[INFO] Avatar uploaded successfully
Context: {
  "user_id": 1,
  "filename": "avatar_1_1696507234.jpg", 
  "size": 1024576,
  "ip": "192.168.1.100"
}
```

### Error Log  
```
[ERROR] Avatar upload failed
Context: {
  "user_id": 1,
  "error": "Intervention\\Image\\Exception\\NotWritableException",
  "ip": "192.168.1.100"
}
```

## 🎨 Mobile UI Considerations

### Mobile-First Design (theo rule của user):
- **Responsive Avatar Display**: Hiển thị tốt trên màn hình nhỏ
- **Touch-Friendly Upload Button**: Dễ dàng tap để upload
- **Progress Indicator**: Hiển thị tiến trình upload
- **Error Handling**: Thông báo lỗi rõ ràng bằng tiếng Việt
- **Image Preview**: Xem trước ảnh trước khi upload
- **Compression Option**: Tự động nén ảnh nếu quá lớn

---

## ⚡ Quick Start

1. **Login** để lấy access token
2. **Prepare image file** (max 2MB, jpg/png/gif/webp)  
3. **POST /api/auth/avatar** với Bearer token và form-data
4. **Handle response** và update UI với avatar URL mới

**🔥 Sẵn sàng tích hợp vào mobile app!**