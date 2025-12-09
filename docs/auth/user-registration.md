# Tài liệu Đăng ký Tài khoản Người dùng (User Registration)

## Tổng quan
Hệ thống đăng ký tài khoản người dùng cho phép khách hàng tạo tài khoản mới trên nền tảng LAMGAME để truy cập các khóa học và tham gia cộng đồng.

**URL**: `https://lamgame.localhost/auth/register`  
**Phương thức**: GET (hiển thị form), POST (xử lý đăng ký)

---

## Luồng xử lý (Flow)

```
1. Người dùng truy cập /auth/register
   ↓
2. Hiển thị form đăng ký (showRegisterForm)
   ↓
3. Người dùng điền thông tin và submit
   ↓
4. Validate dữ liệu đầu vào
   ↓
5. Kiểm tra email đã tồn tại chưa
   ↓
6. Tạo tài khoản mới trong database
   ↓
7. Gửi email chào mừng
   ↓
8. Tự động đăng nhập người dùng
   ↓
9. Redirect về trang chủ với thông báo thành công
```

---

## Routes

**File**: `routes/web.php`

```php
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});
```

- **GET /auth/register**: Hiển thị form đăng ký
- **POST /auth/register**: Xử lý đăng ký tài khoản

---

## Controller

**File**: `app/Http/Controllers/Auth/CustomerAuthController.php`

### 1. showRegisterForm()
Hiển thị trang đăng ký.

```php
public function showRegisterForm()
{
    return view('auth.register');
}
```

### 2. register(Request $request)
Xử lý đăng ký tài khoản mới.

**Các bước xử lý**:
1. Validate dữ liệu đầu vào
2. Kiểm tra email đã tồn tại
3. Tạo tài khoản mới
4. Gửi email chào mừng
5. Tự động đăng nhập
6. Redirect về trang chủ

```php
public function register(Request $request)
{
    $this->validateRegistration($request);

    // Kiểm tra email đã tồn tại
    if (Customer::where('email', $request->email)->exists()) {
        return back()->withErrors(['email' => 'Email này đã được đăng ký.']);
    }

    // Tạo tài khoản
    $customer = $this->createCustomer($request->all());

    // Gửi email chào mừng
    Mail::to($customer->email)->send(new WelcomeMail($customer));

    // Tự động đăng nhập
    Auth::guard('customer')->login($customer);

    return redirect(route('home'))->with('success', 'Đăng ký thành công!');
}
```

### 3. validateRegistration(Request $request)
Validate dữ liệu đầu vào.

**Validation Rules**:
- `first_name`: required, string, max 255 ký tự
- `last_name`: required, string, max 255 ký tự
- `email`: required, email, max 255 ký tự, unique trong bảng customers
- `password`: required, string, min 6 ký tự, confirmed
- `password_confirmation`: required, string, min 6 ký tự

**Thông báo lỗi tiếng Việt**:
```php
[
    'first_name.required' => 'Họ là bắt buộc.',
    'last_name.required' => 'Tên là bắt buộc.',
    'email.required' => 'Email là bắt buộc.',
    'email.email' => 'Email không đúng định dạng.',
    'email.unique' => 'Email này đã được đăng ký.',
    'password.required' => 'Mật khẩu là bắt buộc.',
    'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
    'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
]
```

### 4. createCustomer(array $data)
Tạo tài khoản mới trong database.

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
        'is_verified' => 1,
        'status' => 1,
    ]);
}
```

**Giải thích**:
- Mật khẩu được hash bằng `Hash::make()`
- Tự động gán vào nhóm khách hàng "general"
- `is_verified = 1`: Tài khoản được xác thực ngay
- `status = 1`: Tài khoản active
- `channel_id`: Lấy từ channel hiện tại

---

## View (Frontend)

**File**: `resources/views/auth/register.blade.php`

### Form Fields

1. **Họ (first_name)**
   - Type: text
   - Required: Yes
   - Placeholder: "Nguyễn"

2. **Tên (last_name)**
   - Type: text
   - Required: Yes
   - Placeholder: "Văn A"

3. **Email**
   - Type: email
   - Required: Yes
   - Placeholder: "nhap@email.com"

4. **Mật khẩu (password)**
   - Type: password
   - Required: Yes
   - Min: 6 ký tự
   - Có kiểm tra độ mạnh mật khẩu

5. **Xác nhận mật khẩu (password_confirmation)**
   - Type: password
   - Required: Yes
   - Phải khớp với password

### JavaScript Features

1. **Password Strength Checker**
   - Yếu: < 6 ký tự
   - Trung bình: 6-11 ký tự, ít loại ký tự
   - Mạnh: ≥ 12 ký tự, nhiều loại ký tự (chữ hoa, chữ thường, số, ký tự đặc biệt)

2. **Password Confirmation Matching**
   - Kiểm tra real-time khi nhập
   - Hiển thị border đỏ nếu không khớp

3. **Show/Hide Password**
   - Toggle hiển thị mật khẩu bằng icon 👁️/🙈

4. **Form Submission**
   - Disable button khi submit
   - Đổi text thành "Đang tạo tài khoản..."
   - Auto re-enable sau 10 giây nếu chưa submit

---

## Model

**File**: `packages/Webkul/Customer/src/Models/Customer.php`

### Bảng: `customers`

**Fillable Fields**:
```php
protected $fillable = [
    'first_name',
    'last_name',
    'gender',
    'date_of_birth',
    'email',
    'phone',
    'password',
    'api_token',
    'token',
    'customer_group_id',
    'channel_id',
    'subscribed_to_news_letter',
    'status',
    'is_verified',
    'is_suspended',
];
```

**Hidden Fields**:
```php
protected $hidden = [
    'password',
    'api_token',
];
```

### Database Schema

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
    FOREIGN KEY (customer_group_id) REFERENCES customer_groups(id) ON DELETE SET NULL
);
```

---

## Email Notification

**File**: `app/Mail/WelcomeMail.php`

### WelcomeMail Class

```php
class WelcomeMail extends Mailable
{
    public function __construct(public Customer $customer) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎮 Chào mừng bạn đến với LAMGAME!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome-simple',
            with: [
                'customer' => $this->customer,
                'loginUrl' => config('app.url') . '/auth/login',
                'homeUrl' => config('app.url'),
            ]
        );
    }
}
```

**Gửi email**:
- Sử dụng Markdown template
- Gửi ngay sau khi tạo tài khoản
- Nếu lỗi, chỉ log error không block đăng ký

---

## Security Features

1. **Password Hashing**
   - Sử dụng `Hash::make()` (bcrypt)
   - Không lưu plain text password

2. **CSRF Protection**
   - Form có `@csrf` token
   - Laravel tự động validate

3. **Email Validation**
   - Kiểm tra format email
   - Kiểm tra unique trong database

4. **Session Security**
   - Regenerate session sau khi đăng nhập
   - Sử dụng guard 'customer' riêng biệt

5. **Input Sanitization**
   - Laravel tự động escape XSS
   - Validation rules chặt chẽ

---

## API Support

Controller hỗ trợ cả web và API request:

```php
if ($request->expectsJson()) {
    return response()->json([
        'success' => true,
        'message' => 'Đăng ký thành công!',
        'redirect_url' => route('home')
    ]);
}
```

**API Response Format**:
```json
{
    "success": true,
    "message": "Đăng ký thành công! Chào mừng bạn đến với LAMGAME.",
    "redirect_url": "https://lamgame.localhost"
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "Email này đã được đăng ký."
}
```

---

## Error Handling

### 1. Email đã tồn tại
```php
if (Customer::where('email', $request->email)->exists()) {
    return back()->withErrors(['email' => 'Email này đã được đăng ký.']);
}
```

### 2. Validation Errors
- Hiển thị lỗi dưới mỗi field
- Giữ lại dữ liệu đã nhập (`old()`)
- Highlight field lỗi bằng class 'error'

### 3. Email Sending Failure
```php
try {
    Mail::to($customer->email)->send(new WelcomeMail($customer));
} catch (\Exception $e) {
    \Log::error('Failed to send welcome email: ' . $e->getMessage());
}
```
- Không block quá trình đăng ký
- Chỉ log error để admin kiểm tra

---

## Testing Checklist

- [ ] Form hiển thị đúng với đầy đủ fields
- [ ] Validation hoạt động cho tất cả fields
- [ ] Email unique được kiểm tra
- [ ] Password được hash đúng cách
- [ ] Tài khoản được tạo với đúng thông tin
- [ ] Email chào mừng được gửi
- [ ] Tự động đăng nhập sau khi đăng ký
- [ ] Redirect về trang chủ
- [ ] Thông báo success hiển thị
- [ ] Password strength checker hoạt động
- [ ] Show/hide password hoạt động
- [ ] Responsive trên mobile
- [ ] API response đúng format

---

## Cải tiến có thể thực hiện

1. **Email Verification**
   - Gửi link xác thực email
   - Chỉ cho phép đăng nhập sau khi verify

2. **Social Login**
   - Đăng ký qua Google, Facebook
   - OAuth integration

3. **Captcha**
   - Thêm reCAPTCHA để chống bot
   - Bảo vệ khỏi spam registration

4. **Phone Verification**
   - OTP qua SMS
   - Xác thực số điện thoại

5. **Terms & Conditions**
   - Checkbox đồng ý điều khoản
   - Link đến privacy policy

6. **Profile Picture**
   - Upload avatar khi đăng ký
   - Hoặc chọn avatar mặc định

7. **Referral Code**
   - Nhập mã giới thiệu
   - Tích điểm cho người giới thiệu

---

## Dependencies

- **Laravel Framework**: Core framework
- **Webkul Customer Package**: Customer model và repositories
- **Laravel Mail**: Gửi email
- **Laravel Hash**: Hash password
- **Laravel Auth**: Authentication system
- **Laravel Validation**: Validate input

---

## Liên quan

- [Đăng nhập người dùng](./user-login.md)
- [Quên mật khẩu](./forgot-password.md)
- [Cập nhật profile](./update-profile.md)
- [Email Templates](./email-templates.md)
