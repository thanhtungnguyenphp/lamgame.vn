# 📝 Account Registration API - Comprehensive Guide

## 📋 Overview

API đăng ký tài khoản admin mới cho hệ thống LamGame.vn. API này sử dụng Bagisto Admin model để tạo tài khoản quản trị viên với đầy đủ quyền truy cập hệ thống.

## 🎯 Key Features

- ✅ **Admin Account Creation**: Tạo tài khoản admin với role mặc định
- ✅ **Auto Token Generation**: Tự động tạo access token sau khi đăng ký
- ✅ **Comprehensive Validation**: Validation đầy đủ với thông báo tiếng Việt
- ✅ **Security Features**: Hash password, validate email uniqueness
- ✅ **Audit Logging**: Log chi tiết các hoạt động đăng ký
- ✅ **Mobile Ready**: Hỗ trợ device_name cho mobile apps

## 🌐 Endpoint Information

### Base Information
```
Method: POST
URL: /api/auth/register
Content-Type: application/json
Accept: application/json
Rate Limit: 60 requests/minute
```

### Complete cURL Example
```bash
curl -X POST "https://lamgame.localhost/api/auth/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Nguyễn Văn Admin",
    "email": "admin@company.com",
    "password": "SecurePassword123!",
    "password_confirmation": "SecurePassword123!",
    "device_name": "iPhone 15 Pro",
    "terms_accepted": 1
  }'
```

## 📥 Request Format

### Required Fields

| Field | Type | Rules | Description |
|-------|------|-------|-------------|
| **name** | string | required, min:2, max:255 | Tên đầy đủ của admin |
| **email** | string | required, email:rfc, max:255, unique:admins | Email duy nhất trong hệ thống |
| **password** | string | required, min:8, confirmed | Mật khẩu bảo mật |
| **password_confirmation** | string | required, must match password | Xác nhận mật khẩu |
| **terms_accepted** | boolean | required, accepted | Đồng ý điều khoản (true/1/"yes"/"on") |

### Optional Fields

| Field | Type | Rules | Description |
|-------|------|-------|-------------|
| **device_name** | string | nullable, max:255 | Tên thiết bị cho API token |

### Example Request Body
```json
{
  "name": "Trần Thị Manager",
  "email": "manager@lamgame.vn",
  "password": "MySecurePass2024!",
  "password_confirmation": "MySecurePass2024!",
  "device_name": "MacBook Pro M3",
  "terms_accepted": true
}
```

## 📤 Response Format

### Success Response (HTTP 201)
```json
{
  "status": "success",
  "message": "Đăng ký tài khoản thành công.",
  "data": {
    "access_token": "14|CG179J0h06RADzeiegnPIYYEf1C3mRncVGUeHTf2dbd4b550",
    "token_type": "Bearer",
    "user": {
      "id": 6,
      "name": "Trần Thị Manager",
      "email": "manager@lamgame.vn",
      "image": null,
      "image_url": null,
      "status": true,
      "role_id": 1,
      "role": {
        "id": 1,
        "name": "Administrator",
        "permission_type": "all"
      },
      "created_at": "2025-10-01 07:50:50",
      "updated_at": "2025-10-01 07:50:50",
      "profile_completed": true
    }
  }
}
```

### Error Response (HTTP 422 - Validation Error)
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

### Error Response (HTTP 500 - Server Error)
```json
{
  "status": "error",
  "message": "Có lỗi xảy ra khi đăng ký tài khoản.",
  "errors": {
    "system": ["Vui lòng thử lại sau hoặc liên hệ admin."]
  }
}
```

## ✅ Validation Rules Detail

### Name Validation
```php
'name' => ['required', 'string', 'min:2', 'max:255']
```
- **Required**: Bắt buộc phải có
- **String**: Chỉ chấp nhận chuỗi ký tự
- **Min Length**: Ít nhất 2 ký tự
- **Max Length**: Tối đa 255 ký tự
- **Auto-trim**: Tự động loại bỏ khoảng trắng đầu/cuối

**Error Messages:**
- `Tên là bắt buộc.`
- `Tên phải có ít nhất 2 ký tự.`
- `Tên không được quá 255 ký tự.`

### Email Validation
```php
'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:admins,email']
```
- **Required**: Bắt buộc phải có
- **Email Format**: RFC email format validation
- **Unique**: Phải duy nhất trong bảng admins
- **Max Length**: Tối đa 255 ký tự
- **Auto-normalize**: Tự động chuyển thành lowercase và trim

**Error Messages:**
- `Email là bắt buộc.`
- `Email không đúng định dạng.`
- `Email này đã được sử dụng.`
- `Email không được quá 255 ký tự.`

### Password Validation
```php
'password' => ['required', 'string', 'min:8', 'confirmed']
```
- **Required**: Bắt buộc phải có
- **Min Length**: Ít nhất 8 ký tự
- **Confirmed**: Phải khớp với password_confirmation
- **Security**: Được hash bằng bcrypt

**Error Messages:**
- `Mật khẩu là bắt buộc.`
- `Mật khẩu phải có ít nhất 8 ký tự.`
- `Xác nhận mật khẩu không khớp.`

### Terms Accepted Validation
```php
'terms_accepted' => ['required', 'accepted']
```
- **Required**: Bắt buộc phải có
- **Accepted Values**: `true`, `1`, `"yes"`, `"on"`
- **Purpose**: Xác nhận đồng ý điều khoản sử dụng

**Error Messages:**
- `Bạn phải đồng ý với điều khoản sử dụng.`

## 🔧 Technical Implementation

### Account Creation Process
1. **Input Validation**: Validate tất cả fields theo rules
2. **Data Normalization**: Clean email và name
3. **Admin Creation**: Tạo record trong bảng `admins`
4. **Token Generation**: Tạo Sanctum API token
5. **Audit Logging**: Log thông tin đăng ký
6. **Response**: Trả về admin info + access token

### Database Fields Created
```php
Admin::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'status' => true,     // Active by default
    'role_id' => 1,       // Administrator role
]);
```

### Security Features
- **Password Hashing**: bcrypt với Laravel's Hash facade
- **Email Uniqueness**: Database-level unique constraint
- **Role Assignment**: Default Administrator role (ID: 1)
- **Account Status**: Active by default
- **IP Logging**: Track registration IP address
- **User Agent**: Log device information

## 📱 Mobile App Integration

### Swift (iOS) Example
```swift
struct RegistrationRequest: Codable {
    let name: String
    let email: String
    let password: String
    let passwordConfirmation: String
    let deviceName: String
    let termsAccepted: Bool
    
    enum CodingKeys: String, CodingKey {
        case name, email, password
        case passwordConfirmation = "password_confirmation"
        case deviceName = "device_name"
        case termsAccepted = "terms_accepted"
    }
}

func registerAccount(request: RegistrationRequest) async throws -> AuthResponse {
    let url = URL(string: "https://lamgame.localhost/api/auth/register")!
    var urlRequest = URLRequest(url: url)
    urlRequest.httpMethod = "POST"
    urlRequest.setValue("application/json", forHTTPHeaderField: "Content-Type")
    urlRequest.setValue("application/json", forHTTPHeaderField: "Accept")
    urlRequest.httpBody = try JSONEncoder().encode(request)
    
    let (data, response) = try await URLSession.shared.data(for: urlRequest)
    return try JSONDecoder().decode(AuthResponse.self, from: data)
}
```

### Kotlin (Android) Example
```kotlin
data class RegistrationRequest(
    val name: String,
    val email: String,
    val password: String,
    @SerializedName("password_confirmation") val passwordConfirmation: String,
    @SerializedName("device_name") val deviceName: String,
    @SerializedName("terms_accepted") val termsAccepted: Boolean
)

suspend fun registerAccount(request: RegistrationRequest): AuthResponse {
    return apiService.register(request)
}

@POST("auth/register")
suspend fun register(@Body request: RegistrationRequest): AuthResponse
```

### React Native Example
```javascript
const registerAccount = async (userData) => {
  try {
    const response = await fetch('https://lamgame.localhost/api/auth/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        name: userData.name,
        email: userData.email,
        password: userData.password,
        password_confirmation: userData.passwordConfirmation,
        device_name: userData.deviceName || 'React Native App',
        terms_accepted: userData.termsAccepted,
      }),
    });

    const data = await response.json();
    
    if (data.status === 'success') {
      // Store token securely
      await AsyncStorage.setItem('access_token', data.data.access_token);
      return data.data.user;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Registration failed:', error);
    throw error;
  }
};
```

## 🧪 Testing Examples

### Test Cases Coverage
1. **✅ Valid Registration**: Complete data with all required fields
2. **❌ Missing Fields**: Test missing name, email, password, terms
3. **❌ Invalid Email**: Test invalid email formats
4. **❌ Duplicate Email**: Test email uniqueness validation
5. **❌ Password Mismatch**: Test password confirmation
6. **❌ Short Password**: Test minimum password length
7. **❌ Terms Not Accepted**: Test terms acceptance validation

### Automated Test Script
```bash
# Run the existing test script
php test_register_api.php

# Expected output:
# ✅ Valid Registration - SUCCESS
# ❌ Various validation failures with proper error messages
```

## 🔐 Security Considerations

### Input Security
- **SQL Injection**: Protected by Eloquent ORM
- **XSS Prevention**: JSON API doesn't render HTML
- **CSRF Protection**: Not applicable for API endpoints
- **Rate Limiting**: 60 requests/minute per IP

### Password Security
- **Hashing**: bcrypt algorithm
- **Minimum Length**: 8 characters
- **No Plain Text Storage**: Passwords never stored in plain text
- **Confirmation Required**: Prevents typos

### Email Security
- **Format Validation**: RFC-compliant email format
- **Uniqueness**: Database-level constraint
- **Normalization**: Lowercase conversion
- **No Verification**: Auto-active (add email verification if needed)

## 📊 Response Data Structure

### User Object Fields
```typescript
interface AdminUser {
  id: number;
  name: string;
  email: string;
  image: string | null;
  image_url: string | null;
  status: boolean;
  role_id: number;
  role?: {
    id: number;
    name: string;
    permission_type: string;
  };
  created_at: string;
  updated_at: string;
  profile_completed: boolean;
}
```

### Token Information
- **Type**: Bearer token (Laravel Sanctum)
- **Usage**: Add to Authorization header: `Bearer {token}`
- **Scope**: Full admin access
- **Expiration**: Configurable (default: no expiration)
- **Revocation**: Can be revoked individually

## 🚨 Error Handling Best Practices

### Client-Side Error Handling
```javascript
const handleRegistration = async (formData) => {
  try {
    const response = await registerAccount(formData);
    // Handle success
    console.log('Registration successful:', response.user);
    
  } catch (error) {
    if (error.status === 422) {
      // Validation errors
      const errors = error.errors;
      Object.keys(errors).forEach(field => {
        showFieldError(field, errors[field][0]);
      });
    } else if (error.status === 500) {
      // Server error
      showGeneralError('Server error. Please try again.');
    } else {
      // Other errors
      showGeneralError(error.message || 'Registration failed');
    }
  }
};
```

### Common Error Scenarios

| HTTP Code | Scenario | Action |
|-----------|----------|---------|
| **422** | Validation failed | Show field-specific errors |
| **429** | Rate limit exceeded | Show "Try again later" message |
| **500** | Server error | Show generic error + retry option |
| **503** | Service unavailable | Show maintenance message |

## 🔄 Post-Registration Flow

### Immediate Actions After Registration
1. **Store Token**: Save access_token securely
2. **Update UI**: Redirect to dashboard/home screen
3. **Profile Check**: Check `profile_completed` status
4. **Welcome Flow**: Show onboarding if needed

### Token Usage Example
```javascript
// Store token after successful registration
localStorage.setItem('admin_token', response.data.access_token);

// Use token in subsequent API calls
const apiCall = async () => {
  const token = localStorage.getItem('admin_token');
  const response = await fetch('/api/some-endpoint', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
    },
  });
  return response.json();
};
```

## 📋 Integration Checklist

### Frontend Integration
- [ ] Create registration form with all required fields
- [ ] Implement proper validation feedback
- [ ] Handle success/error responses appropriately  
- [ ] Store access token securely
- [ ] Redirect user after successful registration
- [ ] Add terms of service page/modal
- [ ] Test on different devices/browsers

### Backend Integration
- [ ] Verify API endpoint is accessible
- [ ] Test rate limiting configuration
- [ ] Configure proper CORS settings
- [ ] Set up proper error logging
- [ ] Configure email services (if verification needed)
- [ ] Set up monitoring for registration metrics

## 🎯 Best Practices

### Form Design
- **Clear Labels**: Use descriptive field labels
- **Validation Feedback**: Show errors inline as user types
- **Password Strength**: Provide password strength indicator
- **Terms Link**: Make terms of service easily accessible
- **Loading States**: Show progress during API calls

### UX Considerations
- **Mobile First**: Design for mobile devices primarily
- **Accessibility**: Support screen readers and keyboard navigation
- **Error Recovery**: Clear instructions for fixing errors
- **Success Feedback**: Clear confirmation of successful registration

## 📚 Related Documentation

- [ADMIN_MODEL_MIGRATION.md](./ADMIN_MODEL_MIGRATION.md) - Migration from User to Admin model
- [ADMIN_USER_AUTH_GUIDE.md](./ADMIN_USER_AUTH_GUIDE.md) - Authentication system overview  
- [API_INTEGRATION_GUIDE.md](./API_INTEGRATION_GUIDE.md) - General API integration guide

## 📞 Support

For technical issues or questions about the registration API:
1. Check error logs in Laravel logs
2. Verify database connectivity
3. Test API endpoints with provided examples
4. Review validation rules and requirements

**Status**: ✅ **PRODUCTION READY** - Fully tested and documented