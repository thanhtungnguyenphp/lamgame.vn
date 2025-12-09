# Tài liệu Hệ thống Xác thực Người dùng (User Authentication)

## Tổng quan

Thư mục này chứa tài liệu chi tiết về hệ thống xác thực người dùng (customer authentication) của LAMGAME, bao gồm đăng ký, đăng nhập, quên mật khẩu và quản lý profile.

**Lưu ý**: Đây là tài khoản **người dùng/khách hàng** (customer), không phải tài khoản quản trị (admin).

---

## Cấu trúc Tài liệu

### 1. [User Registration](./user-registration.md)
Tài liệu chi tiết về chức năng đăng ký tài khoản người dùng.

**Nội dung**:
- Luồng xử lý đăng ký
- Routes và Controller
- Validation rules
- Database schema
- Email notification
- Security features
- Testing checklist

**URL**: `https://lamgame.localhost/auth/register`

### 2. [Email Verification](./email-verification.md)
Tài liệu về hệ thống xác thực email sau khi đăng ký.

**Nội dung**:
- Luồng xác thực email
- SMTP configuration (SMTP2GO)
- Signed URL security
- Verification email template
- Account activation
- Error handling
- Testing guide

**URL**: `https://lamgame.localhost/auth/verify/{id}/{hash}`

### 3. [Registration Flow Diagram](./registration-flow-diagram.md)
Các sơ đồ trực quan về luồng đăng ký.

**Nội dung**:
- Flowchart chi tiết
- Sequence diagram
- State diagram
- Component interaction
- Data flow
- Error handling flow

### 3. [Registration API Reference](./registration-api-reference.md)
Tài liệu API reference cho đăng ký.

**Nội dung**:
- Endpoint specifications
- Request/Response examples
- Validation rules
- Error codes
- cURL examples
- JavaScript examples

---

## Kiến trúc Hệ thống

### Authentication Guard

Hệ thống sử dụng Laravel's multi-guard authentication với guard riêng cho customer:

```php
// config/auth.php
'guards' => [
    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
],

'providers' => [
    'customers' => [
        'driver' => 'eloquent',
        'model' => Webkul\Customer\Models\Customer::class,
    ],
],
```

### Middleware

- `guest:customer` - Chỉ cho phép guest truy cập (chưa đăng nhập)
- `auth:customer` - Yêu cầu đăng nhập

---

## Các Chức năng Chính

### 1. Đăng ký (Registration)
- **URL**: `/auth/register`
- **Methods**: GET (form), POST (submit)
- **Controller**: `CustomerAuthController@showRegisterForm`, `CustomerAuthController@register`
- **Features**:
  - Validate input
  - Check email unique
  - Hash password
  - Auto assign customer group
  - Send verification email (NEW)
  - Account inactive until verified (NEW)
  - Redirect to login

### 2. Email Verification (NEW)
- **URL**: `/auth/verify/{id}/{hash}`
- **Method**: GET
- **Controller**: `CustomerAuthController@verifyEmail`
- **Features**:
  - Validate signed URL (24h expiry)
  - Validate email hash
  - Activate account (is_verified=1, status=1)
  - Send welcome email
  - Auto login
  - Redirect to home

### 3. Đăng nhập (Login)
- **URL**: `/auth/login`
- **Methods**: GET (form), POST (submit)
- **Controller**: `CustomerAuthController@showLoginForm`, `CustomerAuthController@login`
- **Features**:
  - Email/password authentication
  - Check email verified (NEW)
  - Remember me option
  - Session management
  - Redirect to intended page

### 4. Đăng xuất (Logout)
- **URL**: `/auth/logout`
- **Method**: POST
- **Controller**: `CustomerAuthController@logout`
- **Features**:
  - Invalidate session
  - Regenerate CSRF token
  - Redirect to home

### 5. Quên mật khẩu (Forgot Password)
- **URL**: `/auth/forgot-password`
- **Methods**: GET (form), POST (send link)
- **Controller**: `CustomerAuthController@showForgotPasswordForm`, `CustomerAuthController@sendPasswordResetLink`
- **Features**:
  - Send reset link via email
  - Token-based reset
  - Expiration handling

### 6. Đặt lại mật khẩu (Reset Password)
- **URL**: `/auth/reset-password/{token}`
- **Methods**: GET (form), POST (reset)
- **Controller**: `CustomerAuthController@showPasswordResetForm`, `CustomerAuthController@resetPassword`
- **Features**:
  - Validate reset token
  - Update password
  - Auto login after reset

### 7. Profile
- **URL**: `/auth/profile`
- **Methods**: GET (view), POST (update)
- **Controller**: `CustomerAuthController@profile`, `CustomerAuthController@updateProfile`
- **Features**:
  - View profile information
  - Update personal details
  - Change email (with unique check)

---

## Database Schema

### customers Table

```sql
CREATE TABLE customers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    gender VARCHAR(50) NULL,
    date_of_birth DATE NULL,
    email VARCHAR(255) UNIQUE NULL,
    phone VARCHAR(255) UNIQUE NULL,
    image VARCHAR(255) NULL,
    status TINYINT DEFAULT 1,
    password VARCHAR(255) NULL,
    api_token VARCHAR(80) UNIQUE NULL,
    customer_group_id INT UNSIGNED NULL,
    subscribed_to_news_letter BOOLEAN DEFAULT 0,
    is_verified BOOLEAN DEFAULT 0,
    is_suspended TINYINT UNSIGNED DEFAULT 0,
    token VARCHAR(255) NULL,
    notes TEXT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (customer_group_id) 
        REFERENCES customer_groups(id) 
        ON DELETE SET NULL,
        
    INDEX idx_email (email),
    INDEX idx_customer_group (customer_group_id)
);
```

### password_resets Table

```sql
CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    
    INDEX idx_email (email)
);
```

---

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Auth/
│           └── CustomerAuthController.php    # Main auth controller
├── Mail/
│   └── WelcomeMail.php                       # Welcome email
└── Models/
    └── Customer.php                          # (Webkul package)

resources/
└── views/
    ├── auth/
    │   ├── register.blade.php                # Registration form
    │   ├── login.blade.php                   # Login form
    │   ├── forgot-password.blade.php         # Forgot password form
    │   ├── reset-password.blade.php          # Reset password form
    │   └── profile.blade.php                 # Profile page
    └── emails/
        └── welcome-simple.blade.php          # Welcome email template

routes/
└── web.php                                   # Auth routes

packages/
└── Webkul/
    └── Customer/
        ├── src/
        │   ├── Models/
        │   │   └── Customer.php              # Customer model
        │   ├── Repositories/
        │   │   ├── CustomerRepository.php
        │   │   └── CustomerGroupRepository.php
        │   └── Database/
        │       └── Migrations/
        │           └── 2018_07_24_082930_create_customers_table.php
        └── ...

docs/
└── auth/
    ├── README.md                             # This file
    ├── user-registration.md                  # Registration documentation
    ├── registration-flow-diagram.md          # Flow diagrams
    └── registration-api-reference.md         # API reference
```

---

## Routes Summary

```php
// routes/web.php

Route::prefix('auth')->name('auth.')->group(function () {
    // Registration
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
    
    // Email Verification (NEW)
    Route::get('/verify/{id}/{hash}', [CustomerAuthController::class, 'verifyEmail'])
        ->name('verify');
    
    // Login
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    
    // Logout
    Route::post('/logout', [CustomerAuthController::class, 'logout'])
        ->name('logout');
    
    // Forgot Password
    Route::get('/forgot-password', [CustomerAuthController::class, 'showForgotPasswordForm'])
        ->name('forgot-password');
    Route::post('/forgot-password', [CustomerAuthController::class, 'sendPasswordResetLink']);
    
    // Reset Password
    Route::get('/reset-password/{token}', [CustomerAuthController::class, 'showPasswordResetForm'])
        ->name('reset-password');
    Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword']);
    
    // Profile
    Route::get('/profile', [CustomerAuthController::class, 'profile'])
        ->name('profile')
        ->middleware('auth:customer');
    Route::post('/profile', [CustomerAuthController::class, 'updateProfile'])
        ->middleware('auth:customer');
});
```

---

## Security Best Practices

### 1. Password Security
- ✅ Passwords are hashed using bcrypt
- ✅ Minimum 6 characters required
- ✅ Password confirmation required
- ⚠️ Consider: Stronger password requirements (uppercase, numbers, special chars)

### 2. CSRF Protection
- ✅ All forms include CSRF token
- ✅ Laravel automatically validates CSRF tokens
- ✅ Token regenerated after login/logout

### 3. Session Security
- ✅ Session regenerated after authentication
- ✅ Separate guard for customers
- ✅ Remember token for "remember me" feature

### 4. Email Security
- ✅ Email uniqueness enforced
- ✅ Email format validation
- ✅ Email verification required (NEW)
- ✅ Signed URL with expiration (NEW)
- ✅ Email hash validation (NEW)

### 5. Input Validation
- ✅ All inputs validated
- ✅ XSS protection via Blade escaping
- ✅ SQL injection protection via Eloquent

### 6. Rate Limiting
- ⚠️ Consider: Add rate limiting to prevent brute force attacks
- ⚠️ Consider: Add CAPTCHA for registration

---

## Testing

### Manual Testing Checklist

**Registration**:
- [ ] Form displays correctly
- [ ] All validation rules work
- [ ] Email uniqueness is enforced
- [ ] Password is hashed
- [ ] Welcome email is sent
- [ ] User is auto-logged in
- [ ] Redirect to home works
- [ ] Success message displays

**Login**:
- [ ] Form displays correctly
- [ ] Valid credentials work
- [ ] Invalid credentials show error
- [ ] Remember me works
- [ ] Redirect to intended page works

**Logout**:
- [ ] User is logged out
- [ ] Session is invalidated
- [ ] Redirect to home works

**Forgot Password**:
- [ ] Form displays correctly
- [ ] Reset link is sent
- [ ] Invalid email shows error

**Reset Password**:
- [ ] Form displays correctly
- [ ] Valid token works
- [ ] Invalid token shows error
- [ ] Password is updated
- [ ] User can login with new password

### Automated Testing

**Example PHPUnit Test**:

```php
// tests/Feature/Auth/RegistrationTest.php

public function test_registration_screen_can_be_rendered()
{
    $response = $this->get('/auth/register');
    $response->assertStatus(200);
}

public function test_new_users_can_register()
{
    $response = $this->post('/auth/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated('customer');
    $response->assertRedirect(route('home'));
}

public function test_registration_fails_with_duplicate_email()
{
    Customer::factory()->create(['email' => 'test@example.com']);

    $response = $this->post('/auth/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
}
```

---

## API Support

Tất cả endpoints hỗ trợ cả web và API requests:

**Web Request**: Returns HTML or redirects  
**API Request**: Returns JSON response

**Detect API Request**:
```php
if ($request->expectsJson()) {
    return response()->json([...]);
}
```

**API Headers**:
```http
Accept: application/json
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

---

## Email Templates

### Welcome Email

**File**: `resources/views/emails/welcome-simple.blade.php`

**Subject**: "🎮 Chào mừng bạn đến với LAMGAME!"

**Variables**:
- `$customer` - Customer object
- `$loginUrl` - Login page URL
- `$homeUrl` - Home page URL

### Password Reset Email

**Subject**: "Khôi phục mật khẩu LAMGAME"

**Variables**:
- `$token` - Reset token
- `$resetUrl` - Reset password URL

---

## Troubleshooting

### Common Issues

**1. Email not sending**
- Check mail configuration in `.env`
- Check mail logs
- Verify SMTP credentials
- Test with `php artisan tinker` and `Mail::raw()`

**2. Session not persisting**
- Check session driver in `.env`
- Clear session: `php artisan session:clear`
- Check session table if using database driver

**3. CSRF token mismatch**
- Clear cache: `php artisan cache:clear`
- Check `APP_URL` in `.env`
- Verify CSRF token in form

**4. Password reset not working**
- Check password_resets table
- Verify email is sent
- Check token expiration (default 60 minutes)

---

## Future Improvements

### Short-term
1. Add email verification
2. Implement rate limiting
3. Add CAPTCHA to registration
4. Stronger password requirements
5. Add phone number field

### Medium-term
1. Social login (Google, Facebook)
2. Two-factor authentication (2FA)
3. Account activity log
4. Profile picture upload
5. Email change verification

### Long-term
1. OAuth2 API authentication
2. Single Sign-On (SSO)
3. Biometric authentication
4. Advanced security features
5. Customer analytics dashboard

---

## Dependencies

- **Laravel Framework** (^10.0)
- **Webkul Customer Package**
- **Laravel Mail**
- **Laravel Hash**
- **Laravel Auth**
- **Laravel Validation**

---

## Support & Contact

Nếu có vấn đề hoặc câu hỏi về hệ thống authentication:

1. Kiểm tra tài liệu này
2. Xem logs: `storage/logs/laravel.log`
3. Kiểm tra database
4. Contact: dev@lamgame.vn

---

## Changelog

### Version 1.1.0 (2025-12-09)
- ✅ Added email verification system
- ✅ Configured SMTP2GO for production emails
- ✅ Updated registration flow (no auto-login)
- ✅ Added signed URL security
- ✅ Updated login to check verification status
- ✅ Added verification email template
- ✅ Documentation for email verification

### Version 1.0.0 (2025-12-09)
- Initial documentation
- User registration flow
- API reference
- Flow diagrams

---

## License

Tài liệu này là tài sản của LAMGAME và chỉ dành cho mục đích nội bộ.
