# 🔐 Admin & User Authentication System Guide

## 📋 Overview

LamGame.vn sử dụng 2 hệ thống authentication riêng biệt:

1. **Admin System** - Quản lý backend (Bagisto admin panel)
2. **API System** - Mobile app và frontend API

## 🗄️ Database Tables

### `admins` Table
- **Purpose**: Admin users cho Bagisto admin panel
- **Access**: Admin dashboard tại `/admin`
- **Authentication**: Session-based
- **Fields**: `id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`

### `users` Table  
- **Purpose**: API users cho mobile app và customer authentication
- **Access**: API endpoints tại `/api/auth/*`
- **Authentication**: Sanctum token-based
- **Fields**: `id`, `name`, `email`, `password`, `phone`, `avatar`, `bio`, `status`, `created_at`, `updated_at`

## ⚠️ Common Issue

**Problem**: Admin tạo user trong admin panel nhưng API login báo "user không tồn tại"

**Reason**: Admin panel tạo user trong `admins` table, nhưng API login tìm trong `users` table

## ✅ Solution

### Option 1: Sync Admin Users to Users Table (Recommended)

Sử dụng artisan command để sync admin users:

```bash
# Sync tất cả admin users sang users table
php artisan admin:sync-users

# Hoặc trong Docker
docker-compose exec php php artisan admin:sync-users
```

**Output example:**
```
🔄 Syncing admin users to users table for API access...

Found 2 admin users.

Processing: admin@example.com
  ✅ Created user: admin@example.com
Processing: tamanh@gmail.com
  ⏭️  Skipped (already exists): tamanh@gmail.com

📊 Sync Summary:
+-----------------+-------+
| Action          | Count |
+-----------------+-------+
| Created         | 1     |
| Updated         | 0     |
| Skipped         | 1     |
| Total Processed | 2     |
+-----------------+-------+

✅ Admin users sync completed successfully!
💡 Admin users can now use API endpoints with their admin credentials.
```

### Option 2: Manual Creation

Tạo user trực tiếp trong `users` table:

```php
// Trong tinker hoặc code
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'User Name',
    'email' => 'user@example.com',
    'password' => Hash::make('password'),
    'status' => true,
]);
```

### Option 3: Use Registration API

Sử dụng API registration endpoint:

```bash
curl -X POST "https://lamgame.localhost/api/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "User Name",
    "email": "user@example.com", 
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "terms_accepted": 1
  }'
```

## 🔄 Automated Sync

### Schedule Sync (Optional)

Để tự động sync admin users, thêm vào `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Sync admin users daily at 2 AM
    $schedule->command('admin:sync-users')
             ->dailyAt('02:00')
             ->withoutOverlapping();
}
```

### Event-Based Sync (Advanced)

Tạo event listener để tự động sync khi admin user được tạo/cập nhật.

## 📝 API Usage After Sync

Sau khi sync, admin users có thể:

1. **Login via API**:
```bash
curl -X POST "https://lamgame.localhost/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "admin_password"
  }'
```

2. **Use API endpoints** với access token nhận được
3. **Manage profile** thông qua API endpoints

## 🛡️ Security Notes

### Password Security
- Admin và API users sử dụng **cùng password hash**
- Khi admin đổi password, cần chạy sync command để update users table
- Password được hash bằng bcrypt

### Token Management
- API sử dụng Laravel Sanctum tokens
- Tokens có thể được revoke individual
- Multiple device support với device_name

### Status Management
- Admin có thể disable user bằng cách set `status = 0`
- Disabled users không thể login API
- Sync command respect admin status

## 🚀 Best Practices

1. **Regular Sync**: Chạy sync command sau khi tạo/cập nhật admin users
2. **Monitoring**: Log API login attempts để monitor security
3. **Token Cleanup**: Định kỳ cleanup expired/unused tokens
4. **Password Policy**: Enforce strong password cho admin users

## 🔧 Troubleshooting

### User Not Found Error
```json
{
  "status": "error",
  "message": "Người dùng không tồn tại.",
  "errors": {
    "email": ["Email không tồn tại trong hệ thống."]
  }
}
```

**Solution**: Run sync command `php artisan admin:sync-users`

### Password Incorrect Error
```json
{
  "status": "error", 
  "message": "Đăng nhập không thành công.",
  "errors": {
    "password": ["Mật khẩu không chính xác."]
  }
}
```

**Solutions**:
1. Check admin password in admin panel
2. Re-sync users: `php artisan admin:sync-users`
3. Verify password hash consistency

### Account Disabled Error
```json
{
  "status": "error",
  "message": "Tài khoản bị khóa.",
  "errors": {
    "account": ["Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin."]
  }
}
```

**Solution**: Enable user in admin panel or update status directly:
```sql
UPDATE users SET status = 1 WHERE email = 'user@example.com';
UPDATE admins SET status = 1 WHERE email = 'user@example.com';
```

## 📊 Command Reference

```bash
# Sync admin users
php artisan admin:sync-users

# List all users
php artisan tinker --execute="DB::table('users')->select('id','name','email','status')->get()"

# List all admins  
php artisan tinker --execute="DB::table('admins')->select('id','name','email','status')->get()"

# Check specific user
php artisan tinker --execute="DB::table('users')->where('email','user@example.com')->first()"
```

## 🔗 Related Endpoints

- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration  
- `GET /api/auth/user` - Get user info
- `PUT /api/auth/profile` - Update profile
- `POST /api/auth/logout` - Logout

## 📚 See Also

- [REGISTER_API_DOCS.md](./REGISTER_API_DOCS.md) - Registration API documentation
- [API_INTEGRATION_GUIDE.md](./API_INTEGRATION_GUIDE.md) - Job API integration
- [WARP.md](./WARP.md) - Development guide