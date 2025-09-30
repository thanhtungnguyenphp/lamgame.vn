# 🔄 Migration from User Model to Admin Model

## 📋 Overview

This document outlines the migration from using `App\Models\User` to `Webkul\User\Models\Admin` for the authentication API system.

## 🎯 Why This Change?

**Previous Issue**: The system was using two different models:
- **Admin Panel**: Used `admins` table (Bagisto's admin system)
- **API Authentication**: Used `users` table (Laravel's default)

**Result**: Admin created users in admin panel but API couldn't find them because it looked in the wrong table.

**Solution**: Standardize on Bagisto's Admin model for consistency.

## 🔄 Changes Made

### 1. **AuthController Updates**

```php
// BEFORE
use App\Models\User;
use App\Http\Resources\UserResource;

$user = User::where('email', $request->email)->first();
return new UserResource($user);

// AFTER  
use Webkul\User\Models\Admin;
use App\Http\Resources\AdminResource;

$user = Admin::where('email', $request->email)->first();
return new AdminResource($user);
```

### 2. **Database Table Changes**

| Aspect | Before (users) | After (admins) |
|--------|----------------|----------------|
| **Table** | `users` | `admins` |
| **Fields** | name, email, password, phone, avatar, bio | name, email, password, image, role_id |
| **Authentication** | Laravel Sanctum | Bagisto Admin + Sanctum |
| **Permissions** | Simple status | Role-based permissions |

### 3. **Model Capabilities Comparison**

```php
// BEFORE - User Model
protected $fillable = [
    'name', 'email', 'password', 'phone', 'avatar', 'status', 'bio'
];

// AFTER - Admin Model (Bagisto)
protected $fillable = [
    'name', 'email', 'password', 'image', 'api_token', 'role_id', 'status'
];
```

### 4. **API Request/Response Changes**

**Registration Request**:
```json
// BEFORE - User Registration
{
  "name": "User Name",
  "email": "user@example.com", 
  "password": "password",
  "password_confirmation": "password",
  "phone": "0909123456",
  "terms_accepted": 1
}

// AFTER - Admin Registration  
{
  "name": "Admin Name",
  "email": "admin@example.com",
  "password": "password", 
  "password_confirmation": "password",
  "terms_accepted": 1
}
```

**API Response**:
```json
// BEFORE - User Response
{
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "phone": "0909123456",
    "avatar": "avatars/image.jpg",
    "bio": "User bio",
    "status": true,
    "profile_completed": true
  }
}

// AFTER - Admin Response
{
  "user": {
    "id": 1,
    "name": "Admin Name", 
    "email": "admin@example.com",
    "image": "admin/image.jpg",
    "image_url": "https://example.com/storage/admin/image.jpg",
    "status": true,
    "role_id": 1,
    "role": {
      "id": 1,
      "name": "Administrator",
      "permission_type": "all"
    },
    "profile_completed": true
  }
}
```

## 🔧 Technical Details

### Admin Model Features

1. **Built-in Sanctum Support**: `HasApiTokens` trait included
2. **Role-based Permissions**: Integration with Bagisto's role system
3. **Image Handling**: Automatic URL generation via `image_url` attribute
4. **Permission Checking**: `hasPermission()` method for authorization
5. **Password Reset**: Custom notification system

### Key Differences

| Feature | User Model | Admin Model |
|---------|------------|-------------|
| **Table** | `users` | `admins` |
| **Profile Image** | `avatar` field | `image` field + `image_url` accessor |
| **Phone Support** | ✅ | ❌ |
| **Bio Support** | ✅ | ❌ |
| **Roles** | Simple status | Full role-based system |
| **Permissions** | Basic | Advanced with granular control |

## 🚀 Benefits

### 1. **Unified System**
- Single source of truth for user management
- No more sync issues between admin and API
- Consistent user experience

### 2. **Enhanced Security**
- Role-based access control
- Granular permissions
- Better admin oversight

### 3. **Bagisto Integration**
- Native Bagisto features
- Consistent with platform architecture
- Future-proof for Bagisto updates

### 4. **Better Admin Experience**
- Admins created in panel work immediately with API
- No manual synchronization needed
- Unified user management interface

## 📝 Updated Validation Rules

### RegisterRequest
```php
// Email uniqueness check updated
'unique:admins,email' // was: 'unique:users,email'

// Phone field removed (Admin model doesn't have phone)
// 'phone' => [...] // REMOVED
```

### UpdateProfileRequest  
```php
// Image field instead of avatar
'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048']
// was: 'avatar' => [...]

// Bio field removed
// 'bio' => [...] // REMOVED
```

## 🧪 Testing

### API Endpoints Tested
- ✅ `POST /api/auth/login` - Login with admin credentials
- ✅ `POST /api/auth/register` - Register new admin
- ✅ `PUT /api/auth/profile` - Update admin profile
- ✅ `GET /api/auth/user` - Get admin details

### Test Results
```bash
# Login Test
curl -X POST "https://lamgame.localhost/api/auth/login" \
  -d '{"email": "admin@example.com", "password": "password"}'
# ✅ SUCCESS: Returns admin data with role info

# Registration Test  
curl -X POST "https://lamgame.localhost/api/auth/register" \
  -d '{"name": "New Admin", "email": "new@example.com", ...}'
# ✅ SUCCESS: Creates admin in admins table with role_id=1

# Profile Update Test
curl -X PUT "https://lamgame.localhost/api/auth/profile" \
  -H "Authorization: Bearer {token}" \
  -d '{"name": "Updated Name"}'
# ✅ SUCCESS: Updates admin profile
```

## 📚 Related Files Updated

1. **Controllers**
   - `app/Http/Controllers/Api/AuthController.php`

2. **Resources**
   - `app/Http/Resources/AdminResource.php` (new)

3. **Requests**
   - `app/Http/Requests/Auth/RegisterRequest.php`
   - `app/Http/Requests/Auth/UpdateProfileRequest.php`

4. **Tests**
   - `test_register_api.php`

5. **Documentation**
   - `REGISTER_API_DOCS.md`
   - `ADMIN_MODEL_MIGRATION.md` (this file)

## 🎯 Impact on Mobile App

### For Mobile Developers

**No Breaking Changes** - The API endpoints remain the same:
- `POST /api/auth/login`
- `POST /api/auth/register` 
- `PUT /api/auth/profile`
- `GET /api/auth/user`

**Response Changes**:
- `phone` field removed from responses
- `avatar` renamed to `image` (with backward compatibility via `image_url`)
- Added `role_id` and `role` information
- Enhanced permission system available

### Migration Checklist for Frontend

- [ ] Remove phone input from registration form
- [ ] Update image upload to use `image` field instead of `avatar`
- [ ] Handle new `role_id` and `role` fields in user profile
- [ ] Test all authentication flows
- [ ] Update user interface to reflect admin capabilities

## 🔮 Future Enhancements

With Admin model, new features become available:

1. **Role-based UI**: Different interfaces based on admin role
2. **Advanced Permissions**: Fine-grained access control
3. **Admin Hierarchy**: Multiple admin levels
4. **Audit Logging**: Track admin actions
5. **Enhanced Security**: Multi-factor authentication support

## 🏁 Conclusion

The migration to Admin model provides a more robust, scalable, and integrated authentication system that aligns with Bagisto's architecture while maintaining API compatibility for mobile applications.