# Mobile App API Quick Reference

## 🎯 **API Endpoints cho Mobile App**

### 📱 **Admin Profile Management**

| Endpoint | Method | Purpose | Documentation |
|----------|--------|---------|---------------|
| `/api/auth/login` | POST | Đăng nhập admin | [Chi tiết](ADMIN_PROFILE_API.md#1-đăng-nhập-admin) |
| `/api/auth/user` | GET | **Lấy profile admin** | [Chi tiết](ADMIN_PROFILE_API.md#2-lấy-thông-tin-profile-admin) |
| `/api/auth/profile` | PUT | Cập nhật profile | [Chi tiết](ADMIN_PROFILE_API.md#3-cập-nhật-profile-admin) |
| `/api/auth/password` | PUT | Đổi mật khẩu | [Chi tiết](ADMIN_PROFILE_API.md#4-đổi-mật-khẩu) |
| `/api/auth/logout` | POST | Đăng xuất | [Chi tiết](ADMIN_PROFILE_API.md#5-đăng-xuất) |

---

## 🚀 **Quick Start cho Mobile Dev**

### **1. Login Flow**
```javascript
// Login và lấy token
POST /api/auth/login
{
    "email": "admin@lamgame.vn",
    "password": "password",
    "device_name": "iPhone App"
}

// Response: { data: { access_token, user } }
// Lưu access_token vào secure storage
```

### **2. Get User Profile** ⭐
```javascript
// API chính để hiển thị thông tin user trên app
GET /api/auth/user
Headers: Authorization: Bearer {token}

// Response: { user: { id, name, email, image_url, role, ... } }
```

### **3. Update Profile**
```javascript
// Cập nhật name, email, avatar
PUT /api/auth/profile
Headers: Authorization: Bearer {token}
Content-Type: multipart/form-data

FormData: { name, email, image }
```

---

## 📋 **Response Data Structure**

### **User Profile Object:**
```json
{
    "id": 1,
    "name": "Admin Name",
    "email": "admin@lamgame.vn", 
    "image": "http://domain.com/storage/admin/profile.jpg",
    "image_url": "http://domain.com/storage/admin/profile.jpg",
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
```

### **Common Response Format:**
```json
{
    "status": "success|error",
    "message": "Thông báo",
    "data": { ... },
    "errors": { ... }
}
```

---

## ⚡ **Error Handling**

| Status Code | Meaning | Action for Mobile App |
|-------------|---------|----------------------|
| `401` | Token expired/invalid | Redirect to login screen |
| `422` | Validation errors | Show field-specific error messages |
| `500` | Server error | Show "Try again later" message |

---

## 🔐 **Authentication Headers**

```javascript
// All protected API calls need:
headers: {
    'Authorization': `Bearer ${access_token}`,
    'Accept': 'application/json',
    'Content-Type': 'application/json' // or multipart/form-data for file uploads
}
```

---

## 🎨 **UI Components Mapping**

### **Profile Screen:**
- **Avatar:** `user.image_url` hoặc `user.image`
- **Name:** `user.name` 
- **Email:** `user.email`
- **Role Badge:** `user.role.name`
- **Account Status:** `user.status` (true=active, false=disabled)

### **Edit Profile Screen:**
- **Name Input:** `user.name` (editable)
- **Email Input:** `user.email` (editable)
- **Avatar Upload:** Update `user.image`

### **Login Screen:**
- **Email Input:** Required
- **Password Input:** Required
- **Device Name:** Optional (dùng để track device)

---

## 🧪 **Testing Commands**

### **Test Login:**
```bash
curl -X POST http://lamgame.vn/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@lamgame.vn","password":"password"}'
```

### **Test Get Profile:**
```bash
curl -X GET http://lamgame.vn/api/auth/user \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 📚 **Full Documentation**

- **[ADMIN_PROFILE_API.md](ADMIN_PROFILE_API.md)** - Tài liệu đầy đủ với examples, error handling, best practices

---

## 💡 **Mobile Development Tips**

1. **Always handle 401 responses** - Token có thể expire
2. **Cache profile data locally** - Giảm API calls
3. **Show loading states** - Especially cho image uploads
4. **Validate inputs client-side** - Trước khi call API
5. **Use secure storage** - Cho access tokens (Keychain/Keystore)

---

## ✅ **Ready to Use!**

API admin profile đã sẵn sàng cho mobile app development. Không cần thêm API endpoints nào khác cho basic profile management.