# 📄 Get Profile API - Complete Response Documentation

## 🔗 API Endpoint

```
GET /api/auth/user
```

## 🔐 Authentication
- **Required**: `Bearer Token` (Laravel Sanctum)
- **Middleware**: `auth:sanctum`

## 📋 Request Headers
```http
Authorization: Bearer {access_token}
Content-Type: application/json
```

## 📤 Complete Response Data

### ✅ Success Response (200)

```json
{
  "user": {
    "id": 1,
    "name": "Nguyễn Văn Admin",
    "email": "admin@lamgame.vn",
    "image": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "image_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "status": true,
    "role_id": 1,
    "role": {
      "id": 1,
      "name": "Administrator",
      "permission_type": "all"
    },
    "created_at": "2023-10-05 14:20:34",
    "updated_at": "2023-10-05 14:25:45",
    "profile_completed": true
  }
}
```

## 📊 Data Fields Explanation

### 👤 Basic User Information
| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `id` | integer | User ID duy nhất | `1` |
| `name` | string | Tên đầy đủ của admin user | `"Nguyễn Văn Admin"` |
| `email` | string | Email đăng nhập | `"admin@lamgame.vn"` |
| `status` | boolean | Trạng thái tài khoản (active/inactive) | `true` |

### 🖼️ Avatar/Image Fields
| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `image` | string/null | URL đầy đủ của ảnh avatar | `"https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg"` |
| `image_url` | string/null | URL ảnh (từ Admin model accessor) | `"https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg"` |
| `avatar_url` | string/null | URL avatar (mới thêm cho mobile) | `"https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg"` |

**Note**: Cả 3 fields `image`, `image_url`, và `avatar_url` sẽ có giá trị giống nhau khi user có avatar. Nếu không có avatar thì sẽ là `null`.

### 👥 Role Information
| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `role_id` | integer | ID của role | `1` |
| `role` | object/null | Thông tin chi tiết role (luôn được load) | `{"id": 1, "name": "Administrator", "permission_type": "all"}` |

**Role Object Structure:**
```json
{
  "id": 1,
  "name": "Administrator",
  "permission_type": "all"
}
```

### 📅 Timestamp Fields
| Field | Type | Description | Format |
|-------|------|-------------|--------|
| `created_at` | string | Ngày tạo tài khoản | `"2023-10-05 14:20:34"` |
| `updated_at` | string | Ngày cập nhật cuối cùng | `"2023-10-05 14:25:45"` |

### ✅ Profile Status
| Field | Type | Description | Logic |
|-------|------|-------------|-------|
| `profile_completed` | boolean | Hồ sơ có hoàn thiện không | `name` + `email` + `role_id` có giá trị |

## 🔍 Detailed Examples

### With Avatar
```json
{
  "user": {
    "id": 1,
    "name": "Tâm Anh",
    "email": "tamanh@gmail.com",
    "image": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "image_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg", 
    "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "status": true,
    "role_id": 1,
    "role": {
      "id": 1,
      "name": "Super Admin",
      "permission_type": "all"
    },
    "created_at": "2023-09-15 10:30:00",
    "updated_at": "2023-10-05 14:25:45",
    "profile_completed": true
  }
}
```

### Without Avatar
```json
{
  "user": {
    "id": 2,
    "name": "Nguyễn Văn B",
    "email": "nguyenb@lamgame.vn",
    "image": null,
    "image_url": null,
    "avatar_url": null,
    "status": true,
    "role_id": 2,
    "role": {
      "id": 2,
      "name": "Editor", 
      "permission_type": "custom"
    },
    "created_at": "2023-10-01 09:15:22",
    "updated_at": "2023-10-01 09:15:22",
    "profile_completed": true
  }
}
```

### Incomplete Profile
```json
{
  "user": {
    "id": 3,
    "name": "",
    "email": "newuser@lamgame.vn",
    "image": null,
    "image_url": null,
    "avatar_url": null,
    "status": true,
    "role_id": null,
    "role": null,
    "created_at": "2023-10-05 15:00:00",
    "updated_at": "2023-10-05 15:00:00",
    "profile_completed": false
  }
}
```

## ❌ Error Responses

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

## 🔄 Integration Notes

### Mobile App Usage
1. **Profile Screen Display**: Sử dụng tất cả fields để hiển thị thông tin user
2. **Avatar Display**: Ưu tiên `avatar_url` > `image` > default placeholder
3. **Profile Completion**: Dùng `profile_completed` để hiển thị progress/warnings
4. **Role Display**: Hiển thị `role.name` nếu có quyền xem

### Caching Considerations
- **Cache Key**: `user_profile_{user_id}`
- **TTL**: 15 minutes (cập nhật khi có thay đổi profile)
- **Invalidation**: Khi upload avatar hoặc update profile

## 🔗 Related APIs

### Profile Management
- `PUT /api/auth/profile` - Cập nhật name, email
- `POST /api/auth/avatar` - Upload avatar mới
- `PUT /api/auth/password` - Đổi mật khẩu

### Authentication Flow
1. `POST /api/auth/login` → Get access_token
2. `GET /api/auth/user` → Get full profile data  
3. Display profile in mobile app

## 🎯 Mobile Implementation Example

### React Native
```javascript
const fetchUserProfile = async () => {
  try {
    const response = await fetch('/api/auth/user', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'application/json',
      },
    });
    
    const data = await response.json();
    
    if (response.ok) {
      const user = data.user;
      
      // Set profile data
      setUserName(user.name);
      setUserEmail(user.email);
      setAvatarUrl(user.avatar_url);
      setIsProfileComplete(user.profile_completed);
      setUserRole(user.role?.name);
      
      // Show completion prompt if needed
      if (!user.profile_completed) {
        showCompleteProfilePrompt();
      }
    }
  } catch (error) {
    console.error('Failed to fetch profile:', error);
  }
};
```

### Flutter/Dart
```dart
Future<Map<String, dynamic>?> fetchUserProfile() async {
  try {
    final response = await http.get(
      Uri.parse('/api/auth/user'),
      headers: {
        'Authorization': 'Bearer $accessToken',
        'Content-Type': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      final user = data['user'];
      
      return {
        'name': user['name'],
        'email': user['email'], 
        'avatar_url': user['avatar_url'],
        'profile_completed': user['profile_completed'],
        'role_name': user['role']?['name'],
      };
    }
  } catch (e) {
    print('Error fetching profile: $e');
  }
  return null;
}
```

## 📝 Summary

**API trả về đầy đủ thông tin:**
- ✅ Basic info: `id`, `name`, `email`, `status`
- ✅ Avatar: 3 URL fields cho flexibility (`image`, `image_url`, `avatar_url`)
- ✅ Role: `role_id` và full `role` object với permissions
- ✅ Timestamps: `created_at`, `updated_at` 
- ✅ Status: `profile_completed` boolean
- ✅ No sensitive data exposed (password, api_token hidden)

**Perfect cho mobile app** - có tất cả data cần thiết để hiển thị profile screen hoàn chỉnh! 🚀