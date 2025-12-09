# Hướng dẫn Test Email Verification

## Test SMTP Connection

### 1. Test bằng Tinker

```bash
php artisan tinker
```

```php
// Test gửi email đơn giản
Mail::raw('Test email from LAMGAME', function($msg) {
    $msg->to('your-email@example.com')
        ->subject('Test SMTP Connection');
});

// Kiểm tra kết quả
// Nếu không có lỗi = thành công
```

### 2. Test Verification Email

```php
// Trong tinker
$customer = \Webkul\Customer\Models\Customer::first();
$url = 'https://lamgame.localhost/auth/verify/1/test';

Mail::to($customer->email)->send(new \App\Mail\VerificationMail($customer, $url));
```

### 3. Test Welcome Email

```php
// Trong tinker
$customer = \Webkul\Customer\Models\Customer::first();

Mail::to($customer->email)->send(new \App\Mail\WelcomeMail($customer));
```

---

## Test Registration Flow

### 1. Đăng ký tài khoản mới

**Via Browser**:
1. Truy cập: `https://lamgame.localhost/auth/register`
2. Điền form:
   - Họ: Test
   - Tên: User
   - Email: test@yourdomain.com
   - Password: password123
   - Confirm: password123
3. Submit
4. Kiểm tra redirect về `/auth/login`
5. Kiểm tra message: "Vui lòng kiểm tra email"

**Via cURL**:
```bash
curl -X POST https://lamgame.localhost/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Test",
    "last_name": "User",
    "email": "test@yourdomain.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Kiểm tra Database

```sql
SELECT id, first_name, last_name, email, is_verified, status 
FROM customers 
WHERE email = 'test@yourdomain.com';
```

**Expected**:
- `is_verified` = 0
- `status` = 0

### 3. Kiểm tra Email

1. Check inbox của email đã đăng ký
2. Tìm email với subject: "🎮 Xác thực tài khoản LAMGAME"
3. Kiểm tra:
   - Button "Xác thực tài khoản" có hiển thị
   - Link backup có hiển thị
   - Link có format: `/auth/verify/{id}/{hash}?expires=...&signature=...`

### 4. Click Link Verification

1. Click button hoặc link trong email
2. Kiểm tra redirect về trang chủ
3. Kiểm tra tự động đăng nhập
4. Kiểm tra message: "Xác thực thành công!"

### 5. Kiểm tra Database sau Verify

```sql
SELECT id, first_name, last_name, email, is_verified, status 
FROM customers 
WHERE email = 'test@yourdomain.com';
```

**Expected**:
- `is_verified` = 1
- `status` = 1

### 6. Kiểm tra Welcome Email

Check inbox, phải nhận được email thứ 2:
- Subject: "🎮 Chào mừng bạn đến với LAMGAME!"

---

## Test Login Before Verification

### 1. Tạo tài khoản mới (chưa verify)

```bash
curl -X POST https://lamgame.localhost/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Test2",
    "last_name": "User2",
    "email": "test2@yourdomain.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Thử đăng nhập

```bash
curl -X POST https://lamgame.localhost/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test2@yourdomain.com",
    "password": "password123"
  }'
```

**Expected Response**:
```json
{
    "success": false,
    "message": "Tài khoản chưa được xác thực. Vui lòng kiểm tra email."
}
```

---

## Test Invalid Verification Link

### 1. Link hết hạn

1. Tạo tài khoản mới
2. Đợi 24 giờ (hoặc modify code để test)
3. Click link verification
4. Expected: "Link xác thực không hợp lệ hoặc đã hết hạn"

### 2. Link sai hash

Manually modify hash trong URL:
```
/auth/verify/1/wronghash?expires=...&signature=...
```

Expected: "Link xác thực không hợp lệ"

### 3. Link sai signature

Manually modify signature trong URL:
```
/auth/verify/1/hash?expires=...&signature=wrongsignature
```

Expected: "Link xác thực không hợp lệ hoặc đã hết hạn"

---

## Test Re-verification

### 1. Verify tài khoản

Click link verification lần đầu → Success

### 2. Click lại link đó

Expected: "Tài khoản đã được xác thực trước đó"

---

## Troubleshooting

### Email không gửi được

**Check logs**:
```bash
tail -f storage/logs/laravel.log
```

**Common issues**:

1. **SMTP credentials sai**
   ```
   Failed to authenticate on SMTP server
   ```
   → Check `.env`: MAIL_USERNAME, MAIL_PASSWORD

2. **Port bị block**
   ```
   Connection refused
   ```
   → Thử port khác: 587, 8025, 465

3. **TLS/SSL issue**
   ```
   stream_socket_enable_crypto(): SSL operation failed
   ```
   → Check MAIL_ENCRYPTION: tls hoặc ssl

4. **From address invalid**
   ```
   Sender address rejected
   ```
   → Check MAIL_FROM_ADDRESS

### Link verification không hoạt động

**Check APP_URL**:
```bash
# .env
APP_URL=https://lamgame.localhost
```

**Clear cache**:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

**Check route**:
```bash
php artisan route:list | grep verify
```

Expected:
```
GET|HEAD  auth/verify/{id}/{hash} ... auth.verify
```

### Database không update

**Check migration**:
```bash
php artisan migrate:status
```

**Check model fillable**:
```php
// Customer model
protected $fillable = [
    'is_verified',
    'status',
    // ...
];
```

---

## Performance Testing

### 1. Test gửi nhiều email

```php
// Tinker
for ($i = 1; $i <= 10; $i++) {
    Mail::raw("Test email $i", function($msg) use ($i) {
        $msg->to("test$i@example.com")->subject("Test $i");
    });
    echo "Sent email $i\n";
}
```

### 2. Monitor SMTP rate limit

SMTP2GO free tier:
- 1000 emails/month
- Check dashboard: https://www.smtp2go.com/

---

## Automated Testing

### PHPUnit Test Example

```php
// tests/Feature/Auth/EmailVerificationTest.php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\VerificationMail;
use Webkul\Customer\Models\Customer;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_verification_email()
    {
        Mail::fake();

        $response = $this->post('/auth/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        Mail::assertSent(VerificationMail::class);
    }

    public function test_user_cannot_login_before_verification()
    {
        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'is_verified' => 0,
        ]);

        $response = $this->post('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('customer');
    }

    public function test_user_can_verify_email()
    {
        $customer = Customer::factory()->create([
            'is_verified' => 0,
            'status' => 0,
        ]);

        $url = URL::temporarySignedRoute(
            'auth.verify',
            now()->addHours(24),
            ['id' => $customer->id, 'hash' => sha1($customer->email)]
        );

        $response = $this->get($url);

        $customer->refresh();
        $this->assertEquals(1, $customer->is_verified);
        $this->assertEquals(1, $customer->status);
        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_verification_link_expires()
    {
        $customer = Customer::factory()->create([
            'is_verified' => 0,
        ]);

        // Create expired URL (1 second ago)
        $url = URL::temporarySignedRoute(
            'auth.verify',
            now()->subSecond(),
            ['id' => $customer->id, 'hash' => sha1($customer->email)]
        );

        $response = $this->get($url);

        $response->assertRedirect('/auth/login');
        $response->assertSessionHasErrors('email');
    }
}
```

**Run tests**:
```bash
php artisan test --filter EmailVerificationTest
```

---

## Checklist

### Pre-deployment

- [ ] SMTP credentials configured in `.env`
- [ ] Test email sending works
- [ ] Verification link works
- [ ] Welcome email works
- [ ] Login blocks unverified users
- [ ] Database migrations run
- [ ] Routes registered
- [ ] Cache cleared

### Post-deployment

- [ ] Register test account
- [ ] Receive verification email
- [ ] Click verification link
- [ ] Account activated
- [ ] Welcome email received
- [ ] Can login successfully
- [ ] Monitor SMTP logs
- [ ] Check error logs

---

## Monitoring

### Check email logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log | grep -i mail

# SMTP2GO dashboard
# https://www.smtp2go.com/activity/
```

### Database queries

```sql
-- Count unverified users
SELECT COUNT(*) FROM customers WHERE is_verified = 0;

-- Recent registrations
SELECT id, email, is_verified, created_at 
FROM customers 
ORDER BY created_at DESC 
LIMIT 10;

-- Verification rate
SELECT 
    COUNT(*) as total,
    SUM(is_verified) as verified,
    ROUND(SUM(is_verified) * 100.0 / COUNT(*), 2) as verification_rate
FROM customers;
```
