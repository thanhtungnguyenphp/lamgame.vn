# 📋 Get Profile API Documentation

## 🎯 Overview

API endpoint để lấy thông tin profile đầy đủ của admin user đã đăng nhập, bao gồm thông tin cơ bản và thông tin mở rộng (extended profile) nếu có.

---

## 📍 Endpoint Information

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/auth/user` | Lấy thông tin profile đầy đủ của user đã đăng nhập |

---

## 🔐 Authentication

### Required Headers
```http
Authorization: Bearer {access_token}
Content-Type: application/json
```

### Authentication Method
- **Type**: Bearer Token (Laravel Sanctum)
- **Required**: Yes
- **Scope**: Authenticated admin users only

---

## 📤 Response Structure

### ✅ Success Response (200 OK)

#### Basic Profile (No Extended Info)
```json
{
  "user": {
    "id": 1,
    "name": "Nguyễn Văn Admin",
    "email": "admin@lamgame.vn",
    "image": null,
    "image_url": null,
    "avatar_url": null,
    "status": true,
    "role_id": 1,
    "role": {
      "id": 1,
      "name": "Administrator",
      "permission_type": "all"
    },
    "phone": null,
    "location": null,
    "job_title": null,
    "bio": null,
    "extended_profile": null,
    "created_at": "2023-09-01 10:00:00",
    "updated_at": "2023-10-06 15:30:00",
    "profile_completed": true
  }
}
```

#### Complete Profile (With Extended Info & Avatar)
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
    
    // Quick Access Fields (từ Extended Profile)
    "phone": "0901 234 567",
    "location": "Ho Chi Minh City, Vietnam",
    "job_title": "Senior Full-Stack Developer",
    "bio": "Experienced web developer with 10+ years in the industry...",
    
    // Extended Profile Details
    "extended_profile": {
      "id": 1,
      "admin_id": 1,
      
      // Personal Information
      "personal_info": {
        "date_of_birth": "1990-01-15",
        "age": 34,
        "gender": "male",
        "gender_display": "Nam",
        "phone": "0901234567",
        "formatted_phone": "0901 234 567"
      },
      
      // Address Information
      "address_info": {
        "address": "123 Nguyễn Trãi, Phường 2",
        "city": "Ho Chi Minh City",
        "state": "Ho Chi Minh",
        "country": "Vietnam",
        "postal_code": "700000",
        "full_address": "123 Nguyễn Trãi, Phường 2, Ho Chi Minh City, Ho Chi Minh, Vietnam, 700000"
      },
      
      // Professional Information
      "professional_info": {
        "job_title": "Senior Full-Stack Developer",
        "company": "TechViet Solutions",
        "bio": "Experienced web developer with expertise in Laravel, React, and mobile development. Passionate about clean code and innovative solutions.",
        "website": "https://tamanh.dev"
      },
      
      // Social Links
      "social_links": {
        "facebook": "https://facebook.com/tamanh.dev",
        "twitter": null,
        "linkedin": "https://linkedin.com/in/tamanh-developer",
        "instagram": "https://instagram.com/tamanh.tech",
        "youtube": null,
        "tiktok": null
      },
      
      // User Preferences (filtered for privacy)
      "preferences": {
        "language": "vi",
        "timezone": "Asia/Ho_Chi_Minh",
        "date_format": "d/m/Y",
        "time_format": "H:i",
        "notifications": {
          "email": true,
          "push": true,
          "sms": false
        },
        "privacy": {
          "show_phone": false,
          "show_email": true,
          "show_address": false
        }
      },
      
      // Profile Status
      "profile_status": {
        "is_complete": true,
        "completion_percentage": 95,
        "is_public": false,
        "profile_completed_at": "2023-10-05 14:25:45"
      },
      
      "created_at": "2023-10-01 09:30:00",
      "updated_at": "2023-10-06 15:25:45"
    },
    
    "created_at": "2023-09-15 10:30:00",
    "updated_at": "2023-10-06 15:25:45",
    "profile_completed": true
  }
}
```

---

## 📊 Response Fields Explanation

### 👤 Basic User Information
| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `id` | integer | Unique user ID | `1` |
| `name` | string | Full name của admin user | `"Tâm Anh"` |
| `email` | string | Email đăng nhập | `"tamanh@gmail.com"` |
| `status` | boolean | Account status (active/inactive) | `true` |

### 🖼️ Avatar Information
| Field | Type | Description | Note |
|-------|------|-------------|------|
| `image` | string/null | Avatar URL (primary) | Same as avatar_url |
| `image_url` | string/null | Avatar URL (from model accessor) | Same as avatar_url |
| `avatar_url` | string/null | Avatar URL (mobile-optimized) | **Use this for mobile** |

### 👥 Role Information
| Field | Type | Description |
|-------|------|-------------|
| `role_id` | integer | Role ID |
| `role` | object | Complete role information |
| `role.id` | integer | Role ID |
| `role.name` | string | Role name (e.g., "Administrator") |
| `role.permission_type` | string | Permission type ("all", "custom") |

### ⚡ Quick Access Fields
*Available when extended profile exists*

| Field | Type | Description | Source |
|-------|------|-------------|---------|
| `phone` | string/null | Formatted phone number | From extended_profile |
| `location` | string/null | City, Country format | From extended_profile |
| `job_title` | string/null | Job title | From extended_profile |
| `bio` | string/null | Bio/description | From extended_profile |

### 📋 Extended Profile Structure

#### Personal Info
- `date_of_birth`: Date (Y-m-d format)
- `age`: Calculated age in years
- `gender`: enum (male/female/other)
- `gender_display`: Vietnamese display name
- `phone`: Raw phone number
- `formatted_phone`: Formatted for display

#### Address Info
- `address`: Street address
- `city`: City name
- `state`: State/Province
- `country`: Country (default: Vietnam)
- `postal_code`: Postal/ZIP code
- `full_address`: Formatted complete address

#### Professional Info
- `job_title`: Current job title
- `company`: Company name
- `bio`: Professional bio/description
- `website`: Personal/professional website

#### Social Links
- Platform URLs (facebook, linkedin, etc.)
- `null` for platforms not provided

#### Preferences
- `language`: User language preference
- `timezone`: User timezone
- `date_format`: Preferred date format
- `time_format`: Preferred time format
- `notifications`: Notification preferences
- `privacy`: Privacy settings

#### Profile Status
- `is_complete`: Boolean indicating completion
- `completion_percentage`: 0-100% completion
- `is_public`: Public profile visibility
- `profile_completed_at`: Completion timestamp

---

## ❌ Error Responses

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Invalid Token (401)
```json
{
  "message": "Token has expired"
}
```

### Server Error (500)
```json
{
  "status": "error",
  "message": "Có lỗi xảy ra khi lấy thông tin profile.",
  "errors": {
    "system": ["Vui lòng thử lại sau hoặc liên hệ admin."]
  }
}
```

---

## 📱 Mobile Integration Examples

### React Native

```javascript
import AsyncStorage from '@react-native-async-storage/async-storage';

const getUserProfile = async () => {
  try {
    const token = await AsyncStorage.getItem('access_token');
    
    const response = await fetch('/api/auth/user', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });
    
    if (response.ok) {
      const data = await response.json();
      const user = data.user;
      
      // Basic info always available
      setUserName(user.name);
      setUserEmail(user.email);
      setAvatarUrl(user.avatar_url);
      setUserRole(user.role?.name);
      
      // Extended info (if available)
      if (user.extended_profile) {
        const profile = user.extended_profile;
        setPhone(profile.personal_info?.formatted_phone);
        setLocation(user.location);
        setJobTitle(user.job_title);
        setBio(user.bio);
        setCompletionPercentage(profile.profile_status?.completion_percentage || 0);
      }
      
      return user;
    } else {
      throw new Error('Failed to fetch profile');
    }
  } catch (error) {
    console.error('Profile fetch error:', error);
    Alert.alert('Lỗi', 'Không thể tải thông tin profile');
  }
};

// Usage in component
useEffect(() => {
  getUserProfile();
}, []);
```

### Flutter/Dart

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ProfileService {
  Future<Map<String, dynamic>?> getUserProfile() async {
    try {
      final token = await getStoredToken();
      
      final response = await http.get(
        Uri.parse('/api/auth/user'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
        },
      );
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final user = data['user'];
        
        return {
          'id': user['id'],
          'name': user['name'],
          'email': user['email'],
          'avatar_url': user['avatar_url'],
          'phone': user['phone'],
          'location': user['location'],
          'job_title': user['job_title'],
          'bio': user['bio'],
          'role_name': user['role']?['name'],
          'has_extended_profile': user['extended_profile'] != null,
          'completion_percentage': user['extended_profile']?['profile_status']?['completion_percentage'] ?? 0,
        };
      }
    } catch (e) {
      print('Error fetching profile: $e');
    }
    return null;
  }
}
```

### JavaScript/Axios

```javascript
import axios from 'axios';

// Create axios instance with default config
const apiClient = axios.create({
  baseURL: 'https://lamgame.localhost',
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add token interceptor
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Get user profile
const getUserProfile = async () => {
  try {
    const response = await apiClient.get('/api/auth/user');
    const { user } = response.data;
    
    return {
      basicInfo: {
        id: user.id,
        name: user.name,
        email: user.email,
        avatar_url: user.avatar_url,
        role: user.role?.name,
        status: user.status,
      },
      quickAccess: {
        phone: user.phone,
        location: user.location,
        job_title: user.job_title,
        bio: user.bio,
      },
      extendedProfile: user.extended_profile,
      profileStatus: {
        completed: user.profile_completed,
        completionPercentage: user.extended_profile?.profile_status?.completion_percentage || 0,
      },
    };
  } catch (error) {
    if (error.response?.status === 401) {
      // Handle unauthorized - redirect to login
      window.location.href = '/login';
    } else {
      console.error('Profile fetch error:', error);
      throw new Error('Không thể tải thông tin profile');
    }
  }
};
```

---

## 🎨 UI Implementation Suggestions

### Profile Header Component

```javascript
const ProfileHeader = ({ user }) => (
  <View style={styles.headerContainer}>
    <Image 
      source={{ uri: user.avatar_url || 'default-avatar.png' }}
      style={styles.avatar}
    />
    <View style={styles.userInfo}>
      <Text style={styles.userName}>{user.name}</Text>
      <Text style={styles.userRole}>{user.role?.name}</Text>
      {user.job_title && (
        <Text style={styles.jobTitle}>{user.job_title}</Text>
      )}
      {user.location && (
        <Text style={styles.location}>📍 {user.location}</Text>
      )}
    </View>
  </View>
);
```

### Profile Completion Indicator

```javascript
const ProfileCompletion = ({ extendedProfile }) => {
  if (!extendedProfile) {
    return (
      <TouchableOpacity style={styles.completionPrompt}>
        <Text style={styles.promptText}>
          🚀 Hoàn thiện profile để tăng tính chuyên nghiệp
        </Text>
      </TouchableOpacity>
    );
  }
  
  const percentage = extendedProfile.profile_status?.completion_percentage || 0;
  
  return (
    <View style={styles.completionContainer}>
      <Text style={styles.completionLabel}>
        Độ hoàn thiện profile: {percentage}%
      </Text>
      <View style={styles.progressBar}>
        <View 
          style={[styles.progressFill, { width: `${percentage}%` }]}
        />
      </View>
    </View>
  );
};
```

---

## 🔧 Testing

### Manual Testing with cURL

```bash
# Get access token first (login)
ACCESS_TOKEN=$(curl -s -X POST "https://lamgame.localhost/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"your_password"}' | \
  jq -r '.data.access_token')

# Get profile
curl -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -H "Content-Type: application/json" | jq
```

### Test Cases

| Test Case | Expected Result |
|-----------|----------------|
| Valid token, no extended profile | Basic user info with nulls for extended fields |
| Valid token, with extended profile | Complete user info with extended_profile object |
| Invalid token | 401 Unauthenticated |
| Expired token | 401 Token expired |
| No token | 401 Unauthenticated |

---

## 📈 Performance Considerations

### Optimization Notes
- **Eager Loading**: Role and userInfo relationships loaded automatically
- **Caching**: Consider caching profile data for 15-30 minutes
- **Conditional Loading**: Extended profile only loaded when relationship exists
- **Lazy Loading**: Social links and preferences loaded on-demand

### Response Size
- **Basic Profile**: ~0.5KB
- **With Extended Profile**: ~2-3KB
- **With Avatar**: +0.1KB (just URL)

---

## 🔄 Related APIs

| API | Description | Documentation |
|-----|-------------|---------------|
| `POST /api/auth/login` | User login | [Login API](./login-api.md) |
| `GET /api/auth/profile/extended` | Extended profile only | [Extended Profile API](./extended-profile-api.md) |
| `PUT /api/auth/profile` | Update basic profile | [Update Profile API](./update-profile-api.md) |
| `POST /api/auth/avatar` | Upload avatar | [Avatar Upload API](./avatar-upload-api.md) |
| `PUT /api/auth/profile/extended` | Update extended profile | [Extended Profile API](./extended-profile-api.md) |

---

## 📝 Changelog

| Version | Date | Changes |
|---------|------|---------|
| v1.0 | 2023-10-06 | Initial API implementation |
| v1.1 | 2023-10-06 | Added extended profile support |
| v1.2 | 2023-10-06 | Added quick access fields for mobile |

---

## 💡 Best Practices

### Mobile Development
1. **Cache Profile Data**: Store locally for offline access
2. **Handle Null Values**: Always check for null/undefined extended profile
3. **Progress Indicators**: Show completion percentage to encourage profile completion
4. **Fallback UI**: Provide default values for missing profile fields
5. **Error Handling**: Graceful handling of network errors

### API Usage
1. **Token Management**: Refresh tokens before expiry
2. **Rate Limiting**: Respect API rate limits
3. **Error Handling**: Handle all HTTP status codes appropriately
4. **Data Validation**: Validate response data structure
5. **Loading States**: Show loading indicators during API calls

---

**📱 Perfect for Mobile App Integration - Mobile-First Design! 🚀**