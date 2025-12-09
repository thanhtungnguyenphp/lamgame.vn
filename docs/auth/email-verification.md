# Tài liệu Email Verification - Xác thực Email

## Tổng quan

Hệ thống xác thực email yêu cầu người dùng xác nhận địa chỉ email của họ trước khi có thể đăng nhập vào hệ thống. Điều này giúp:
- Đảm bảo email hợp lệ
- Ngăn chặn spam registration
- Bảo mật tài khoản người dùng

---

## Luồng xử lý

```
1. Người dùng đăng ký tài khoản
   ↓
2. Tài khoản được tạo với is_verified=0, status=0
   ↓
3. Gửi email xác thực với signed URL
   ↓
4. Người dùng nhấn link trong email
   ↓
5. Validate signed URL và hash
   ↓
6. Cập nhật is_verified=1, status=1
   ↓
7. Gửi email chào mừng
   ↓
8. Tự động đăng nhập
   ↓
9. Redirect về trang chủ
```

---

## Cấu hình SMTP

### File: `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.smtp2go.com
MAIL_PORT=2525
MAIL_USERNAME=smtplamgame
MAIL_PASSWORD=0CIhntAmrwT1UIWH
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lamgame.vn
MAIL_FROM_NAME="${APP_NAME}"
```

### Ports hỗ trợ

- **TLS**: 2525, 8025, 587, 80, 25
- **SSL**: 465, 8465, 443

---

## Email Templates

### 1. Verification Email

**File**: `app/Mail/VerificationMail.php`

```php
class VerificationMail extends Mailable
{
    public function __construct(
        public Customer $customer,
        public string $verificationUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎮 Xác thực tài khoản LAMGAME',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verification',
            with: [
                'customer' => $this->customer,
                'verificationUrl' => $this->verificationUrl,
            ]
        );
    }
}
```

**Template**: `resources/views/emails/verification.blade.php`

Nội dung:
- Chào mừng người dùng
- Button "Xác thực tài khoản"
- Link xác thực (backup)
- Lưu ý về thời hạn (24 giờ)

### 2. Welcome Email

Gửi sau khi xác thực thành công (giữ nguyên như cũ).

---

## Controller Methods

### 1. register() - Đã cập nhật

```php
public function register(Request $request)
{
    $this->validateRegistration($request);

    if (Customer::where('email', $request->email)->exists()) {
        return back()->withErrors(['email' => 'Email này đã được đăng ký.']);
    }

    // Tạo tài khoản (chưa verified)
    $customer = $this->createCustomer($request->all());

    // Tạo signed URL (24 giờ)
    $verificationUrl = URL::temporarySignedRoute(
        'auth.verify',
        now()->addHours(24),
        ['id' => $customer->id, 'hash' => sha1($customer->email)]
    );

    // Gửi email xác thực
    Mail::to($customer->email)->send(new VerificationMail($customer, $verificationUrl));

    return redirect(route('auth.login'))
        ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
}
```

**Thay đổi**:
- Không tự động đăng nhập
- Gửi verification email thay vì welcome email
- Redirect về login thay vì home

### 2. createCustomer() - Đã cập nhật

```php
protected function createCustomer(array $data)
{
    $defaultGroup = $this->customerGroupRepository->findOneWhere(['code' => 'general']);
    
    return Customer::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'channel_id' => core()->getCurrentChannel()->id,
        'customer_group_id' => $defaultGroup ? $defaultGroup->id : 2,
        'is_verified' => 0, // Chưa xác thực
        'status' => 0,      // Chưa active
    ]);
}
```

**Thay đổi**:
- `is_verified` = 0 (thay vì 1)
- `status` = 0 (thay vì 1)

### 3. verifyEmail() - Mới

```php
public function verifyEmail(Request $request, $id)
{
    // Validate signed URL
    if (!$request->hasValidSignature()) {
        return redirect(route('auth.login'))
            ->withErrors(['email' => 'Link xác thực không hợp lệ hoặc đã hết hạn.']);
    }

    $customer = Customer::findOrFail($id);

    // Validate hash
    if (sha1($customer->email) !== $request->hash) {
        return redirect(route('auth.login'))
            ->withErrors(['email' => 'Link xác thực không hợp lệ.']);
    }

    // Kiểm tra đã verified chưa
    if ($customer->is_verified) {
        return redirect(route('auth.login'))
            ->with('info', 'Tài khoản đã được xác thực trước đó.');
    }

    // Kích hoạt tài khoản
    $customer->update([
        'is_verified' => 1,
        'status' => 1,
    ]);

    // Gửi welcome email
    Mail::to($customer->email)->send(new WelcomeMail($customer));

    // Tự động đăng nhập
    Auth::guard('customer')->login($customer);

    return redirect(route('home'))
        ->with('success', 'Xác thực thành công! Chào mừng bạn đến với LAMGAME.');
}
```

### 4. login() - Đã cập nhật

```php
public function login(Request $request)
{
    $this->validateLogin($request);

    // Kiểm tra verified
    $customer = Customer::where('email', $request->email)->first();
    
    if ($customer && !$customer->is_verified) {
        return back()->withErrors([
            'email' => 'Tài khoản chưa được xác thực. Vui lòng kiểm tra email.',
        ]);
    }

    // Attempt login
    if (Auth::guard('customer')->attempt(...)) {
        $request->session()->regenerate();
        return $this->sendLoginResponse($request);
    }

    return $this->sendFailedLoginResponse($request);
}
```

**Thay đổi**:
- Kiểm tra `is_verified` trước khi cho phép đăng nhập
- Hiển thị lỗi nếu chưa verified

---

## Routes

### File: `routes/web.php`

```php
Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        // ... existing routes
        
        // Email verification route
        Route::get('/verify/{id}/{hash}', [CustomerAuthController::class, 'verifyEmail'])
            ->name('verify');
    });
});
```

**URL**: `/auth/verify/{id}/{hash}?expires={timestamp}&signature={signature}`

---

## Security Features

### 1. Signed URL

```php
URL::temporarySignedRoute(
    'auth.verify',
    now()->addHours(24),
    ['id' => $customer->id, 'hash' => sha1($customer->email)]
);
```

**Bảo mật**:
- Có thời hạn (24 giờ)
- Có signature để chống giả mạo
- Laravel tự động validate

### 2. Email Hash

```php
'hash' => sha1($customer->email)
```

**Mục đích**:
- Đảm bảo link chỉ dùng cho đúng email
- Ngăn chặn verify cho email khác

### 3. Double Check

```php
if (!$request->hasValidSignature()) { ... }
if (sha1($customer->email) !== $request->hash) { ... }
```

**2 lớp bảo mật**:
1. Validate signature của Laravel
2. Validate hash của email

### 4. Prevent Re-verification

```php
if ($customer->is_verified) {
    return redirect(route('auth.login'))
        ->with('info', 'Tài khoản đã được xác thực trước đó.');
}
```

---

## Database Changes

### customers Table

**Trước verification**:
```sql
is_verified = 0
status = 0
```

**Sau verification**:
```sql
is_verified = 1
status = 1
```

---

## Testing

### Manual Testing

1. **Đăng ký tài khoản mới**
   - [ ] Form submit thành công
   - [ ] Redirect về login
   - [ ] Thông báo "Vui lòng kiểm tra email"
   - [ ] Email verification được gửi

2. **Kiểm tra email**
   - [ ] Email nhận được
   - [ ] Subject đúng
   - [ ] Button "Xác thực tài khoản" hiển thị
   - [ ] Link backup hiển thị

3. **Click link xác thực**
   - [ ] Redirect về trang chủ
   - [ ] Tự động đăng nhập
   - [ ] Thông báo "Xác thực thành công"
   - [ ] Welcome email được gửi

4. **Thử đăng nhập trước khi verify**
   - [ ] Hiển thị lỗi "Tài khoản chưa được xác thực"
   - [ ] Không cho phép đăng nhập

5. **Click link đã hết hạn**
   - [ ] Hiển thị lỗi "Link đã hết hạn"
   - [ ] Redirect về login

6. **Click link đã verify**
   - [ ] Hiển thị "Tài khoản đã được xác thực trước đó"
   - [ ] Redirect về login

### cURL Testing

```bash
# Test registration
curl -X POST https://lamgame.localhost/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Test",
    "last_name": "User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Expected: success message về check email
```

---

## Error Handling

### 1. Email Sending Failed

```php
try {
    Mail::to($customer->email)->send(new VerificationMail(...));
} catch (\Exception $e) {
    \Log::error('Failed to send verification email: ' . $e->getMessage());
}
```

**Xử lý**: Log error nhưng không block đăng ký

### 2. Invalid Signature

```php
if (!$request->hasValidSignature()) {
    return redirect(route('auth.login'))
        ->withErrors(['email' => 'Link xác thực không hợp lệ hoặc đã hết hạn.']);
}
```

### 3. Invalid Hash

```php
if (sha1($customer->email) !== $request->hash) {
    return redirect(route('auth.login'))
        ->withErrors(['email' => 'Link xác thực không hợp lệ.']);
}
```

### 4. Already Verified

```php
if ($customer->is_verified) {
    return redirect(route('auth.login'))
        ->with('info', 'Tài khoản đã được xác thực trước đó.');
}
```

---

## Troubleshooting

### Email không gửi được

**Kiểm tra**:
1. SMTP credentials trong `.env`
2. Port và encryption
3. Firewall/network
4. Logs: `storage/logs/laravel.log`

**Test SMTP**:
```bash
php artisan tinker
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

### Link verification không hoạt động

**Kiểm tra**:
1. `APP_URL` trong `.env` đúng chưa
2. Signature có hợp lệ không
3. Link đã hết hạn chưa (24 giờ)
4. Hash có khớp với email không

### Tài khoản không active sau verify

**Kiểm tra**:
1. Database: `is_verified` và `status` = 1 chưa
2. Logs có error không
3. Method `verifyEmail()` có chạy đến cuối không

---

## Future Improvements

1. **Resend Verification Email**
   - Thêm button "Gửi lại email xác thực"
   - Rate limiting để chống spam

2. **Email Queue**
   - Queue email để tăng performance
   - Retry mechanism khi gửi thất bại

3. **Notification**
   - Thông báo khi có người đăng ký
   - Admin dashboard để quản lý

4. **Customization**
   - Cho phép thay đổi thời hạn link
   - Template email tùy chỉnh

---

## API Response

### Registration Success

```json
{
    "success": true,
    "message": "Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản."
}
```

### Login Before Verification

```json
{
    "success": false,
    "message": "Tài khoản chưa được xác thực. Vui lòng kiểm tra email."
}
```

---

## Related Files

- `app/Mail/VerificationMail.php` - Verification email class
- `resources/views/emails/verification.blade.php` - Email template
- `app/Http/Controllers/Auth/CustomerAuthController.php` - Auth logic
- `routes/web.php` - Verification route
- `.env` - SMTP configuration

---

## Changelog

### Version 1.1.0 (2025-12-09)
- Added email verification
- Updated registration flow
- Added SMTP2GO configuration
- Updated login to check verification status
