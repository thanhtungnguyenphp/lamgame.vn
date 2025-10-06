# 🚀 Extended Profile System Documentation

## 📋 Tổng Quan

Hệ thống Extended Profile cho LamGame.vn cung cấp khả năng mở rộng thông tin cá nhân của admin users với các thông tin nâng cao như ngày sinh, giới tính, địa chỉ, thông tin nghề nghiệp, và nhiều hơn nữa.

## 🗂️ Cấu Trúc Database

### 📊 Bảng `admin_user_info`

```sql
CREATE TABLE admin_user_info (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT UNIQUE NOT NULL,
    
    -- Personal Information
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    phone VARCHAR(20) NULL,
    
    -- Address Information
    address TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    country VARCHAR(100) DEFAULT 'Vietnam',
    postal_code VARCHAR(20) NULL,
    
    -- Professional Information
    bio TEXT NULL,
    website VARCHAR(255) NULL,
    job_title VARCHAR(100) NULL,
    company VARCHAR(100) NULL,
    
    -- JSON Fields
    social_links JSON NULL,
    preferences JSON NULL,
    emergency_contact JSON NULL,
    custom_fields JSON NULL,
    
    -- Status & Tracking
    profile_completed_at TIMESTAMP NULL,
    is_public BOOLEAN DEFAULT FALSE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    -- Foreign Keys
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);
```

### 🔗 Relationships

- **admins** 1:1 **admin_user_info** (One-to-One)
- **admin_user_info** belongs to **admins**

## 🎯 API Endpoints

### 1. 📄 Get User Profile (với Extended Info)

```http
GET /api/auth/user
```

**Headers:**
```http
Authorization: Bearer {access_token}
```

**Response với Extended Profile:**
```json
{
  "user": {
    "id": 1,
    "name": "Nguyễn Văn Admin",
    "email": "admin@lamgame.vn",
    "image": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
    "status": true,
    "role_id": 1,
    "role": {
      "id": 1,
      "name": "Administrator",
      "permission_type": "all"
    },
    
    // Quick Access Fields
    "phone": "0901 234 567",
    "location": "Ho Chi Minh City, Vietnam",
    "job_title": "Senior Developer",
    "bio": "Experienced web developer...",
    
    // Extended Profile (if loaded)
    "extended_profile": {
      "id": 1,
      "admin_id": 1,
      "personal_info": {
        "date_of_birth": "1990-01-15",
        "age": 34,
        "gender": "male",
        "gender_display": "Nam",
        "phone": "0901234567",
        "formatted_phone": "0901 234 567"
      },
      "address_info": {
        "address": "123 Nguyen Trai St",
        "city": "Ho Chi Minh City", 
        "state": null,
        "country": "Vietnam",
        "postal_code": "700000",
        "full_address": "123 Nguyen Trai St, Ho Chi Minh City, Vietnam, 700000"
      },
      "professional_info": {
        "job_title": "Senior Developer",
        "company": "Tech Corp",
        "bio": "Experienced web developer with 10+ years...",
        "website": "https://johndoe.dev"
      },
      "social_links": {
        "facebook": "https://facebook.com/johndoe",
        "twitter": null,
        "linkedin": "https://linkedin.com/in/johndoe",
        "instagram": null,
        "youtube": null,
        "tiktok": null
      },
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
          "show_email": false,
          "show_address": false
        }
      },
      "profile_status": {
        "is_complete": true,
        "completion_percentage": 85,
        "is_public": false,
        "profile_completed_at": "2023-10-05 15:30:00"
      }
    },
    
    "created_at": "2023-09-01 10:00:00",
    "updated_at": "2023-10-05 15:30:00",
    "profile_completed": true
  }
}
```

### 2. 📋 Get Extended Profile Only

```http
GET /api/auth/profile/extended
```

**Headers:**
```http
Authorization: Bearer {access_token}
```

**Success Response (Profile Exists):**
```json
{
  "status": "success",
  "message": "Lấy thông tin hồ sơ mở rộng thành công.",
  "data": {
    "extended_profile": {
      // Same structure as above
    },
    "completion_percentage": 85
  }
}
```

**Success Response (Profile Not Created):**
```json
{
  "status": "success", 
  "message": "Hồ sơ mở rộng chưa được tạo.",
  "data": {
    "extended_profile": null,
    "completion_percentage": 0,
    "suggestions": [
      "Cập nhật thông tin cá nhân để hoàn thiện hồ sơ",
      "Thêm số điện thoại và địa chỉ liên hệ",
      "Bổ sung thông tin nghề nghiệp"
    ]
  }
}
```

### 3. ✏️ Update Extended Profile

```http
PUT /api/auth/profile/extended
```

**Headers:**
```http
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "date_of_birth": "1990-01-15",
  "gender": "male",
  "phone": "0901234567",
  "address": "123 Nguyen Trai Street",
  "city": "Ho Chi Minh City",
  "state": null,
  "country": "Vietnam",
  "postal_code": "700000",
  "bio": "Experienced web developer with passion for clean code...",
  "website": "https://johndoe.dev",
  "job_title": "Senior Full-Stack Developer",
  "company": "Tech Innovation Corp",
  "social_links": {
    "facebook": "https://facebook.com/johndoe",
    "linkedin": "https://linkedin.com/in/johndoe",
    "twitter": "https://twitter.com/johndoe"
  },
  "preferences": {
    "language": "vi",
    "timezone": "Asia/Ho_Chi_Minh",
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
  "emergency_contact": {
    "name": "Jane Doe",
    "phone": "0987654321",
    "relationship": "Spouse"
  },
  "is_public": false
}
```

**Success Response:**
```json
{
  "status": "success",
  "message": "Cập nhật hồ sơ mở rộng thành công.",
  "data": {
    "extended_profile": {
      // Updated profile data
    },
    "completion_percentage": 95,
    "is_complete": true,
    "user": {
      // Updated user data with extended profile
    }
  }
}
```

## 🛡️ Validation Rules

### Personal Information
- `date_of_birth`: nullable, date, before today, after 1900-01-01, age >= 16
- `gender`: nullable, enum (male, female, other) 
- `phone`: nullable, max 20 chars, Vietnamese phone format

### Address Information
- `address`: nullable, text, max 500 chars
- `city`: nullable, string, max 100 chars
- `state`: nullable, string, max 100 chars
- `country`: nullable, string, max 100 chars
- `postal_code`: nullable, string, max 20 chars

### Professional Information
- `bio`: nullable, text, max 1000 chars
- `website`: nullable, valid URL, max 255 chars
- `job_title`: nullable, string, max 100 chars
- `company`: nullable, string, max 100 chars

### Social Links (all optional URLs)
- `social_links.facebook`
- `social_links.twitter` 
- `social_links.linkedin`
- `social_links.instagram`
- `social_links.youtube`
- `social_links.tiktok`

## 📱 Mobile Integration Examples

### React Native

```javascript
// Get Extended Profile
const getExtendedProfile = async () => {
  try {
    const response = await fetch('/api/auth/profile/extended', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'application/json',
      },
    });
    
    const data = await response.json();
    
    if (data.status === 'success') {
      setExtendedProfile(data.data.extended_profile);
      setCompletionPercentage(data.data.completion_percentage);
    }
  } catch (error) {
    console.error('Failed to fetch extended profile:', error);
  }
};

// Update Extended Profile
const updateExtendedProfile = async (profileData) => {
  try {
    const response = await fetch('/api/auth/profile/extended', {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${accessToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(profileData),
    });
    
    const data = await response.json();
    
    if (data.status === 'success') {
      setExtendedProfile(data.data.extended_profile);
      Alert.alert('Thành công', 'Hồ sơ đã được cập nhật!');
    } else {
      Alert.alert('Lỗi', data.message);
    }
  } catch (error) {
    console.error('Failed to update profile:', error);
  }
};
```

### Flutter/Dart

```dart
class ExtendedProfileService {
  Future<Map<String, dynamic>?> getExtendedProfile() async {
    try {
      final response = await http.get(
        Uri.parse('/api/auth/profile/extended'),
        headers: {
          'Authorization': 'Bearer $accessToken',
          'Content-Type': 'application/json',
        },
      );
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['data'];
      }
    } catch (e) {
      print('Error fetching extended profile: $e');
    }
    return null;
  }
  
  Future<bool> updateExtendedProfile(Map<String, dynamic> profileData) async {
    try {
      final response = await http.put(
        Uri.parse('/api/auth/profile/extended'),
        headers: {
          'Authorization': 'Bearer $accessToken',
          'Content-Type': 'application/json',
        },
        body: json.encode(profileData),
      );
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['status'] == 'success';
      }
    } catch (e) {
      print('Error updating profile: $e');
    }
    return false;
  }
}
```

## 🎨 Mobile UI Considerations (Mobile-First Design)

### Profile Completion Progress
```javascript
// Progress indicator component
const ProfileCompletionProgress = ({ percentage }) => (
  <View style={styles.progressContainer}>
    <Text style={styles.progressLabel}>
      Hoàn thành hồ sơ: {percentage}%
    </Text>
    <View style={styles.progressBar}>
      <View 
        style={[
          styles.progressFill, 
          { width: `${percentage}%` }
        ]} 
      />
    </View>
  </View>
);
```

### Form Sections
```javascript
// Personal Info Section
const PersonalInfoSection = () => (
  <Section title="Thông tin cá nhân">
    <DatePicker 
      label="Ngày sinh"
      value={dateOfBirth}
      onChange={setDateOfBirth}
    />
    <Picker 
      label="Giới tính"
      options={[
        { value: 'male', label: 'Nam' },
        { value: 'female', label: 'Nữ' },
        { value: 'other', label: 'Khác' }
      ]}
    />
    <TextInput
      label="Số điện thoại"
      value={phone}
      onChangeText={setPhone}
      keyboardType="phone-pad"
    />
  </Section>
);
```

## 🔧 Migration Guide

### 1. Chạy Migration

```bash
# Chạy migration để tạo bảng admin_user_info
php artisan migrate

# Hoặc trong Docker
docker-compose exec php php artisan migrate
```

### 2. Update AuthController Import

Đảm bảo AuthController sử dụng custom Admin model:

```php
// In AuthController
use App\Models\Admin as CustomAdmin;

// Update methods to load userInfo relationship
$user = $request->user()->load(['role', 'userInfo']);
```

### 3. Testing Data

Tạo test data cho extended profiles:

```bash
php artisan make:factory AdminUserInfoFactory
php artisan db:seed --class=AdminUserInfoSeeder
```

## 🚨 Error Handling

### Common Validation Errors

```json
{
  "status": "error",
  "message": "Dữ liệu không hợp lệ.",
  "errors": {
    "date_of_birth": ["Bạn phải từ 16 tuổi trở lên."],
    "phone": ["Số điện thoại Việt Nam không hợp lệ."],
    "website": ["Website phải là URL hợp lệ."]
  }
}
```

### Server Errors

```json
{
  "status": "error",
  "message": "Có lỗi xảy ra khi cập nhật hồ sơ.",
  "errors": {
    "system": ["Vui lòng thử lại sau hoặc liên hệ admin."]
  }
}
```

## 📊 Profile Completion Logic

### Required Fields (for completion)
- `phone`
- `date_of_birth`
- `address`
- `city`

### Completion Percentage Calculation
```php
$allFields = [
    'date_of_birth', 'gender', 'phone', 'address', 'city', 
    'state', 'postal_code', 'bio', 'website', 'job_title', 'company'
];

$filledFields = count(array_filter($allFields, fn($field) => !empty($this->$field)));
$percentage = round(($filledFields / count($allFields)) * 100);
```

## 🎯 Best Practices

### 1. Privacy & Security
- Sensitive data (emergency_contact) hidden từ API responses
- User privacy preferences được respect
- Secure validation cho phone numbers và dates

### 2. Performance Optimization  
- Eager loading relationships khi cần thiết
- Caching profile completion percentage
- Lazy loading extended profile chỉ khi cần

### 3. Mobile-First Design
- Responsive form layouts
- Touch-friendly input controls
- Progress indicators for completion
- Vietnamese error messages
- Date/time pickers optimized cho mobile

### 4. Extensibility
- JSON fields cho custom_fields
- Trait-based architecture
- Flexible validation rules
- Easy to add new fields

---

## ✨ Summary

**Extended Profile System đã sẵn sàng:**
- ✅ Database migration với comprehensive fields
- ✅ Model relationships và business logic  
- ✅ API endpoints với full CRUD operations
- ✅ Comprehensive validation với Vietnamese messages
- ✅ Mobile-optimized resource formatting
- ✅ Privacy và security considerations
- ✅ Extensible architecture cho future enhancements

**Perfect cho Mobile App Integration! 🚀📱**