# 🧪 cURL Test Commands for Profile APIs

## 🔐 Authentication & Profile Testing

### 1. Login và Get Access Token
```bash
# Login to get access token
curl -X POST "https://lamgame.localhost/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "your_password",
    "device_name": "test_device"
  }' | jq

# Expected Response:
# {
#   "status": "success",
#   "message": "Đăng nhập thành công.",
#   "data": {
#     "access_token": "1|xxxxxxxxxxxx",
#     "token_type": "Bearer",
#     "user": { ... }
#   }
# }
```

### 2. Get Complete Profile Data
```bash
# Replace YOUR_ACCESS_TOKEN with the token from login response
curl -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Content-Type: application/json" | jq

# Expected Complete Response:
# {
#   "user": {
#     "id": 1,
#     "name": "Admin Name",
#     "email": "admin@example.com",
#     "image": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
#     "image_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
#     "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
#     "status": true,
#     "role_id": 1,
#     "role": {
#       "id": 1,
#       "name": "Administrator",
#       "permission_type": "all"
#     },
#     "created_at": "2023-10-05 14:20:34",
#     "updated_at": "2023-10-05 14:25:45",
#     "profile_completed": true
#   }
# }
```

### 3. Upload Avatar
```bash
# Upload avatar image
curl -X POST "https://lamgame.localhost/api/auth/avatar" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -F "avatar=@/path/to/your/image.jpg" | jq

# Expected Response:
# {
#   "status": "success",
#   "message": "Tải lên ảnh đại diện thành công.",
#   "data": {
#     "avatar_url": "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg",
#     "user": { ... }
#   }
# }
```

### 4. Update Profile (Name & Email)
```bash
# Update basic profile info
curl -X PUT "https://lamgame.localhost/api/auth/profile" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Admin Name",
    "email": "newemail@lamgame.vn"
  }' | jq

# Expected Response:
# {
#   "status": "success", 
#   "message": "Cập nhật hồ sơ thành công.",
#   "data": {
#     "user": { ... }
#   }
# }
```

## 🔍 Data Verification Commands

### Check Avatar File Exists
```bash
# Verify avatar file was created in storage
ls -la /path/to/project/storage/app/public/admin/

# Check public symlink works
curl -I "https://lamgame.localhost/storage/admin/avatar_1_1696507234.jpg"
```

### Check Database Records
```bash
# Connect to database and check admin table
# (Replace with your database credentials)
mysql -u username -p database_name -e "
  SELECT id, name, email, image, role_id, status, created_at, updated_at 
  FROM admins 
  WHERE id = 1;
"
```

## ❌ Error Testing

### Test Invalid Token
```bash
curl -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer invalid_token" \
  -H "Content-Type: application/json" | jq

# Expected: 401 Unauthorized
```

### Test Missing Avatar File
```bash
curl -X POST "https://lamgame.localhost/api/auth/avatar" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" | jq

# Expected: 422 Validation Error
```

### Test Invalid Image Format
```bash
curl -X POST "https://lamgame.localhost/api/auth/avatar" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -F "avatar=@/path/to/document.pdf" | jq

# Expected: 422 Validation Error
```

### Test Large File
```bash
# Create a large test file (over 2MB)
dd if=/dev/zero of=/tmp/large_image.jpg bs=1M count=3

curl -X POST "https://lamgame.localhost/api/auth/avatar" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -F "avatar=@/tmp/large_image.jpg" | jq

# Expected: 422 Validation Error
rm /tmp/large_image.jpg
```

## 📱 Complete Mobile Integration Test

### Full User Flow Test
```bash
#!/bin/bash

# Step 1: Login
echo "=== Step 1: Login ==="
LOGIN_RESPONSE=$(curl -s -X POST "https://lamgame.localhost/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "your_password",
    "device_name": "mobile_test"
  }')

# Extract access token
ACCESS_TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.data.access_token')
echo "Access Token: $ACCESS_TOKEN"

# Step 2: Get Profile
echo -e "\n=== Step 2: Get Profile ==="
curl -s -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -H "Content-Type: application/json" | jq

# Step 3: Upload Avatar (if you have test image)
echo -e "\n=== Step 3: Upload Avatar ==="
curl -s -X POST "https://lamgame.localhost/api/auth/avatar" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -F "avatar=@/path/to/test_image.jpg" | jq

# Step 4: Get Updated Profile
echo -e "\n=== Step 4: Get Updated Profile ==="
curl -s -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -H "Content-Type: application/json" | jq

# Step 5: Update Profile
echo -e "\n=== Step 5: Update Profile ==="
curl -s -X PUT "https://lamgame.localhost/api/auth/profile" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Name",
    "email": "updated@lamgame.vn"
  }' | jq

# Step 6: Final Profile Check
echo -e "\n=== Step 6: Final Profile Check ==="
curl -s -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -H "Content-Type: application/json" | jq
```

## 🔧 Troubleshooting Commands

### Check Server Status
```bash
# Check if server is running
curl -I "https://lamgame.localhost/api/auth/login"

# Check routes are registered
# php artisan route:list | grep auth
```

### Check Storage Permissions
```bash
# Check storage directory permissions
ls -la storage/app/public/admin/

# Test file creation
touch storage/app/public/admin/test.txt && rm storage/app/public/admin/test.txt
```

### Check Logs
```bash
# Monitor Laravel logs during testing
tail -f storage/logs/laravel.log

# Check specific date logs
grep "$(date +%Y-%m-%d)" storage/logs/laravel.log | tail -20
```

## 📝 Response Format Validation

### JSON Response Structure Check
```bash
# Get profile and validate response structure
RESPONSE=$(curl -s -X GET "https://lamgame.localhost/api/auth/user" \
  -H "Authorization: Bearer $ACCESS_TOKEN")

# Check required fields exist
echo $RESPONSE | jq -e '.user.id' > /dev/null && echo "✅ ID field exists"
echo $RESPONSE | jq -e '.user.name' > /dev/null && echo "✅ Name field exists"  
echo $RESPONSE | jq -e '.user.email' > /dev/null && echo "✅ Email field exists"
echo $RESPONSE | jq -e '.user.avatar_url' > /dev/null && echo "✅ Avatar URL field exists"
echo $RESPONSE | jq -e '.user.role' > /dev/null && echo "✅ Role field exists"
echo $RESPONSE | jq -e '.user.profile_completed' > /dev/null && echo "✅ Profile completed field exists"
```

---

## 🚀 Quick Test Command

Save this as `test_profile_api.sh` and run:

```bash
#!/bin/bash
# Replace these with your actual credentials
EMAIL="admin@example.com"
PASSWORD="your_password"
BASE_URL="https://lamgame.localhost"

# Login and test profile APIs
TOKEN=$(curl -s -X POST "$BASE_URL/api/auth/login" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}" | jq -r '.data.access_token')

if [ "$TOKEN" != "null" ]; then
  echo "🔑 Login successful! Token: $TOKEN"
  echo -e "\n📄 Getting profile data:"
  curl -s -X GET "$BASE_URL/api/auth/user" \
    -H "Authorization: Bearer $TOKEN" | jq
else
  echo "❌ Login failed!"
fi
```

**Sử dụng:** `chmod +x test_profile_api.sh && ./test_profile_api.sh`