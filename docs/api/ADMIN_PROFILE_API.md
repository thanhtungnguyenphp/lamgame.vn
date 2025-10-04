# API Quản Lý Profile Admin

## Tổng quan

API cho phép admin đăng nhập và quản lý thông tin profile của mình. Sử dụng Laravel Sanctum để xác thực với Bearer Token.

**Base URL**: `/api/auth/`

---

## 📋 **Danh sách API Endpoints**

| Method | Endpoint | Mô tả | Auth Required |
|--------|----------|-------|---------------|
| POST | `/api/auth/login` | Đăng nhập admin | ❌ |
| POST | `/api/auth/register` | Đăng ký admin mới | ❌ |
| GET | `/api/auth/user` | **Lấy thông tin profile admin** | ✅ |
| PUT | `/api/auth/profile` | Cập nhật profile admin | ✅ |
| PUT | `/api/auth/password` | Đổi mật khẩu | ✅ |
| POST | `/api/auth/logout` | Đăng xuất | ✅ |
| POST | `/api/auth/forgot-password` | Quên mật khẩu | ❌ |
| POST | `/api/auth/reset-password` | Reset mật khẩu | ❌ |

---

## 🔐 **1. Đăng Nhập Admin**

### `POST /api/auth/login`

Đăng nhập và nhận access token để sử dụng cho các API khác.

#### **Request:**
```json
{
    "email": "admin@lamgame.vn",
    "password": "admin123",
    "device_name": "Mobile App" // Optional
}
```

#### **Headers:**
```
Content-Type: application/json
Accept: application/json
```

#### **Response Success (200):**
```json
{
    "status": "success",
    "message": "Đăng nhập thành công.",
    "data": {
        "access_token": "1|xxxxx-your-token-here",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Admin Name",
            "email": "admin@lamgame.vn",
            "image": "http://lamgame.vn/storage/admin/profile.jpg",
            "image_url": "http://lamgame.vn/storage/admin/profile.jpg",
            "status": true,
            "role_id": 1,
            "role": {
                "id": 1,
                "name": "Super Admin",
                "permission_type": "all"
            },
            "created_at": "2025-01-01 00:00:00",
            "updated_at": "2025-01-01 00:00:00",
            "profile_completed": true
        }
    }
}
```

#### **Response Error (401):**
```json
{
    "status": "error",
    "message": "Đăng nhập không thành công.",
    "errors": {
        "password": ["Mật khẩu không chính xác."]
    }
}
```

---

## 👤 **2. Lấy Thông Tin Profile Admin**

### `GET /api/auth/user`

**🌟 API chính để mobile app lấy thông tin admin sau khi đăng nhập.**

#### **Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

#### **Response Success (200):**
```json
{
    "user": {
        "id": 1,
        "name": "Nguyễn Văn Admin",
        "email": "admin@lamgame.vn",
        "image": "http://lamgame.vn/storage/admin/profile.jpg",
        "image_url": "http://lamgame.vn/storage/admin/profile.jpg",
        "status": true,
        "role_id": 1,
        "role": {
            "id": 1,
            "name": "Super Admin",
            "permission_type": "all"
        },
        "created_at": "2025-01-01 00:00:00",
        "updated_at": "2025-01-01 00:00:00",
        "profile_completed": true
    }
}
```

#### **Mô tả các fields:**

| Field | Type | Mô tả |
|-------|------|-------|
| `id` | integer | ID admin |
| `name` | string | Tên admin |
| `email` | string | Email admin |
| `image` | string\|null | URL ảnh đại diện (full URL) |
| `image_url` | string\|null | URL ảnh đại diện (alternative) |
| `status` | boolean | Trạng thái tài khoản (true=active, false=disabled) |
| `role_id` | integer | ID vai trò |
| `role` | object\|null | Thông tin vai trò (nếu load relationship) |
| `role.name` | string | Tên vai trò (Super Admin, Editor, etc.) |
| `role.permission_type` | string | Loại quyền ("all", "custom") |
| `created_at` | string | Ngày tạo tài khoản |
| `updated_at` | string | Ngày cập nhật cuối |
| `profile_completed` | boolean | Profile đã hoàn thành chưa |

---

## ✏️ **3. Cập Nhật Profile Admin**

### `PUT /api/auth/profile`

Cập nhật thông tin profile admin.

#### **Headers:**
```
Authorization: Bearer {access_token}
Content-Type: multipart/form-data
```

#### **Request Body (Form Data):**
```json
{
    "name": "Tên Admin Mới",
    "email": "newemail@lamgame.vn",
    "image": File // Upload ảnh mới (optional)
}
```

#### **Validation Rules:**
- `name`: Bắt buộc, 2-255 ký tự
- `email`: Bắt buộc, email hợp lệ, unique
- `image`: Optional, jpg/jpeg/png, max 2MB

#### **Response Success (200):**
```json
{
    "status": "success",
    "message": "Cập nhật hồ sơ thành công.",
    "data": {
        "user": {
            "id": 1,
            "name": "Tên Admin Mới",
            "email": "newemail@lamgame.vn",
            "image": "http://lamgame.vn/storage/admin/new_profile.jpg",
            "image_url": "http://lamgame.vn/storage/admin/new_profile.jpg",
            "status": true,
            "role_id": 1,
            "created_at": "2025-01-01 00:00:00",
            "updated_at": "2025-01-03 08:45:00",
            "profile_completed": true
        }
    }
}
```

---

## 🔑 **4. Đổi Mật Khẩu**

### `PUT /api/auth/password`

#### **Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

#### **Request:**
```json
{
    "current_password": "oldpassword123",
    "password": "newpassword456",
    "password_confirmation": "newpassword456"
}
```

#### **Response Success (200):**
```json
{
    "message": "Password changed successfully"
}
```

---

## 🚪 **5. Đăng Xuất**

### `POST /api/auth/logout`

Revoke token hiện tại.

#### **Headers:**
```
Authorization: Bearer {access_token}
```

#### **Response:**
```json
{
    "message": "Successfully logged out"
}
```

---

## 📱 **Mobile App Integration Guide**

### **Step 1: Login Flow**
```javascript
// 1. Login và lưu token
const loginResponse = await fetch('/api/auth/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'admin@lamgame.vn',
        password: 'password',
        device_name: 'iPhone App'
    })
});

const loginData = await loginResponse.json();
const token = loginData.data.access_token;
const user = loginData.data.user;

// Lưu token vào secure storage
await AsyncStorage.setItem('access_token', token);
await AsyncStorage.setItem('user_data', JSON.stringify(user));
```

### **Step 2: Get Profile Data**
```javascript
// Lấy thông tin profile admin
const getProfile = async () => {
    const token = await AsyncStorage.getItem('access_token');
    
    const response = await fetch('/api/auth/user', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });
    
    if (response.status === 401) {
        // Token expired - redirect to login
        redirectToLogin();
        return;
    }
    
    const profileData = await response.json();
    return profileData.user;
};
```

### **Step 3: Update Profile**
```javascript
// Cập nhật profile với image
const updateProfile = async (name, email, imageUri) => {
    const token = await AsyncStorage.getItem('access_token');
    const formData = new FormData();
    
    formData.append('name', name);
    formData.append('email', email);
    
    if (imageUri) {
        formData.append('image', {
            uri: imageUri,
            type: 'image/jpeg',
            name: 'profile.jpg'
        });
    }
    
    const response = await fetch('/api/auth/profile', {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: formData
    });
    
    return await response.json();
};
```

---

## 🔧 **Error Handling**

### **Common Error Responses:**

#### **401 Unauthorized (Token hết hạn/không hợp lệ):**
```json
{
    "message": "Unauthenticated."
}
```
**Action:** Redirect user to login screen

#### **422 Validation Error:**
```json
{
    "status": "error", 
    "message": "Dữ liệu không hợp lệ.",
    "errors": {
        "name": ["Tên người dùng là bắt buộc"],
        "email": ["Email không đúng định dạng"]
    }
}
```

#### **500 Server Error:**
```json
{
    "status": "error",
    "message": "Có lỗi xảy ra khi cập nhật hồ sơ.",
    "errors": {
        "system": ["Vui lòng thử lại sau hoặc liên hệ admin."]
    }
}
```

---

## 🧪 **Testing với cURL**

### **Login:**
```bash
curl -X POST http://lamgame.vn/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@lamgame.vn",
    "password": "password",
    "device_name": "Test Device"
  }'
```

### **Get Profile:**
```bash
curl -X GET http://lamgame.vn/api/auth/user \
  -H "Authorization: Bearer {your-token}" \
  -H "Accept: application/json"
```

### **Update Profile:**
```bash
curl -X PUT http://lamgame.vn/api/auth/profile \
  -H "Authorization: Bearer {your-token}" \
  -F "name=New Admin Name" \
  -F "email=newadmin@lamgame.vn" \
  -F "image=@/path/to/profile.jpg"
```

---

## ⚠️ **Best Practices cho Mobile App**

1. **Token Management:**
   - Lưu token trong secure storage (Keychain/Keystore)
   - Check 401 responses và auto-redirect to login
   - Implement token refresh nếu cần

2. **Caching:**
   - Cache profile data locally để giảm API calls
   - Update cache khi profile được cập nhật
   - Implement pull-to-refresh

3. **Image Handling:**
   - Cache avatar images để tăng performance
   - Compress images before upload
   - Show loading states during upload

4. **Error Handling:**
   - Show user-friendly error messages
   - Implement retry mechanism cho network errors
   - Log errors for debugging

5. **Security:**
   - Luôn sử dụng HTTPS
   - Validate inputs trên client-side trước khi gửi
   - Don't store sensitive data in logs

---

## 📊 **Response Status Codes**

| Code | Meaning | Action |
|------|---------|--------|
| 200 | Success | Continue normal flow |
| 401 | Unauthorized | Redirect to login |
| 403 | Forbidden | Show access denied message |
| 422 | Validation Error | Show field-specific errors |
| 429 | Too Many Requests | Implement retry with backoff |
| 500 | Server Error | Show generic error, retry later |