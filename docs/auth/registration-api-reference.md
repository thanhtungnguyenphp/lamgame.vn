# API Reference - User Registration

## Endpoint Overview

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/auth/register` | Hiển thị form đăng ký | No |
| POST | `/auth/register` | Xử lý đăng ký tài khoản | No |

---

## GET /auth/register

Hiển thị trang form đăng ký tài khoản người dùng.

### Request

```http
GET /auth/register HTTP/1.1
Host: lamgame.localhost
Accept: text/html
```

### Response

**Status Code**: `200 OK`

**Content-Type**: `text/html`

Trả về HTML page với form đăng ký.

### Middleware

- `guest:customer` - Chỉ cho phép người dùng chưa đăng nhập truy cập

---

## POST /auth/register

Xử lý đăng ký tài khoản mới cho người dùng.

### Request

#### Headers

```http
POST /auth/register HTTP/1.1
Host: lamgame.localhost
Content-Type: application/x-www-form-urlencoded
Accept: application/json
X-CSRF-TOKEN: {csrf_token}
```

#### Body Parameters

| Parameter | Type | Required | Description | Validation |
|-----------|------|----------|-------------|------------|
| `first_name` | string | Yes | Họ của người dùng | required, max:255 |
| `last_name` | string | Yes | Tên của người dùng | required, max:255 |
| `email` | string | Yes | Email đăng ký | required, email, max:255, unique:customers |
| `password` | string | Yes | Mật khẩu | required, min:6, confirmed |
| `password_confirmation` | string | Yes | Xác nhận mật khẩu | required, min:6 |

#### Example Request (Form Data)

```http
POST /auth/register HTTP/1.1
Host: lamgame.localhost
Content-Type: application/x-www-form-urlencoded

first_name=Nguyễn&last_name=Văn A&email=user@example.com&password=password123&password_confirmation=password123
```

#### Example Request (JSON)

```json
{
    "first_name": "Nguyễn",
    "last_name": "Văn A",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Response

#### Success Response

**Status Code**: `200 OK` (Web) / `200 OK` (JSON)

**Web Response**: Redirect to home page with success message

```http
HTTP/1.1 302 Found
Location: https://lamgame.localhost
Set-Cookie: laravel_session=...
```

**JSON Response**:

```json
{
    "success": true,
    "message": "Đăng ký thành công! Chào mừng bạn đến với LAMGAME.",
    "redirect_url": "https://lamgame.localhost"
}
```

#### Error Responses

##### 1. Validation Error

**Status Code**: `422 Unprocessable Entity`

**Web Response**: Redirect back with errors

**JSON Response**:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "first_name": [
            "Họ là bắt buộc."
        ],
        "email": [
            "Email không đúng định dạng."
        ],
        "password": [
            "Mật khẩu phải có ít nhất 6 ký tự.",
            "Xác nhận mật khẩu không khớp."
        ]
    }
}
```

##### 2. Email Already Exists

**Status Code**: `422 Unprocessable Entity`

**JSON Response**:

```json
{
    "success": false,
    "message": "Email này đã được đăng ký."
}
```

**Web Response**: Redirect back with error

```http
HTTP/1.1 302 Found
Location: https://lamgame.localhost/auth/register
```

With session flash data:
```php
[
    'errors' => [
        'email' => 'Email này đã được đăng ký.'
    ],
    'old' => [
        'first_name' => 'Nguyễn',
        'last_name' => 'Văn A',
        'email' => 'user@example.com'
    ]
]
```

---

## Validation Rules

### first_name

- **Type**: string
- **Required**: Yes
- **Max Length**: 255 characters
- **Error Messages**:
  - `first_name.required`: "Họ là bắt buộc."

### last_name

- **Type**: string
- **Required**: Yes
- **Max Length**: 255 characters
- **Error Messages**:
  - `last_name.required`: "Tên là bắt buộc."

### email

- **Type**: string (email format)
- **Required**: Yes
- **Max Length**: 255 characters
- **Unique**: Must be unique in `customers` table
- **Error Messages**:
  - `email.required`: "Email là bắt buộc."
  - `email.email`: "Email không đúng định dạng."
  - `email.unique`: "Email này đã được đăng ký."

### password

- **Type**: string
- **Required**: Yes
- **Min Length**: 6 characters
- **Confirmed**: Must match `password_confirmation`
- **Error Messages**:
  - `password.required`: "Mật khẩu là bắt buộc."
  - `password.min`: "Mật khẩu phải có ít nhất 6 ký tự."
  - `password.confirmed`: "Xác nhận mật khẩu không khớp."

### password_confirmation

- **Type**: string
- **Required**: Yes
- **Min Length**: 6 characters
- **Error Messages**:
  - `password_confirmation.required`: "Xác nhận mật khẩu là bắt buộc."

---

## Business Logic

### 1. Customer Creation

Khi tạo tài khoản mới, hệ thống sẽ:

1. Hash mật khẩu bằng bcrypt
2. Lấy `channel_id` từ channel hiện tại
3. Gán vào nhóm khách hàng "general" (customer_group_id)
4. Set `is_verified = 1` (tự động xác thực)
5. Set `status = 1` (active)

**Created Customer Object**:

```php
[
    'first_name' => 'Nguyễn',
    'last_name' => 'Văn A',
    'email' => 'user@example.com',
    'password' => '$2y$10$...', // bcrypt hash
    'channel_id' => 1,
    'customer_group_id' => 2,
    'is_verified' => 1,
    'status' => 1,
    'created_at' => '2025-12-09 10:43:01',
    'updated_at' => '2025-12-09 10:43:01'
]
```

### 2. Welcome Email

Sau khi tạo tài khoản, hệ thống gửi email chào mừng:

- **Subject**: "🎮 Chào mừng bạn đến với LAMGAME!"
- **Template**: `emails.welcome-simple` (Markdown)
- **Data**:
  - `customer`: Customer object
  - `loginUrl`: Link đến trang đăng nhập
  - `homeUrl`: Link đến trang chủ

**Note**: Nếu gửi email thất bại, hệ thống chỉ log error và không block quá trình đăng ký.

### 3. Auto Login

Sau khi đăng ký thành công, người dùng được tự động đăng nhập:

```php
Auth::guard('customer')->login($customer);
$request->session()->regenerate();
```

### 4. Redirect

Người dùng được redirect về trang chủ với flash message thành công.

---

## Security Considerations

### 1. CSRF Protection

Tất cả POST requests phải có CSRF token hợp lệ.

**Web Form**:
```html
<form method="POST" action="/auth/register">
    @csrf
    <!-- form fields -->
</form>
```

**AJAX Request**:
```javascript
fetch('/auth/register', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify(data)
});
```

### 2. Password Hashing

Mật khẩu được hash bằng bcrypt (Laravel's `Hash::make()`):

```php
'password' => Hash::make($data['password'])
```

**Never** lưu plain text password.

### 3. Email Uniqueness

Email được kiểm tra unique 2 lần:
1. Validation rule: `unique:customers`
2. Manual check: `Customer::where('email', $request->email)->exists()`

### 4. Session Security

- Session được regenerate sau khi đăng nhập
- Sử dụng guard 'customer' riêng biệt
- Remember token được tạo tự động

### 5. Input Sanitization

Laravel tự động escape XSS trong Blade templates và validate input.

---

## Rate Limiting

**Recommended**: Implement rate limiting để chống spam registration.

```php
// routes/web.php
Route::post('/register', [CustomerAuthController::class, 'register'])
    ->middleware('throttle:5,1'); // 5 requests per minute
```

---

## Testing Examples

### cURL Examples

#### 1. Successful Registration

```bash
curl -X POST https://lamgame.localhost/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "first_name": "Nguyễn",
    "last_name": "Văn A",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response**:
```json
{
    "success": true,
    "message": "Đăng ký thành công! Chào mừng bạn đến với LAMGAME.",
    "redirect_url": "https://lamgame.localhost"
}
```

#### 2. Validation Error

```bash
curl -X POST https://lamgame.localhost/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "first_name": "",
    "last_name": "Văn A",
    "email": "invalid-email",
    "password": "123",
    "password_confirmation": "456"
  }'
```

**Response**:
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "first_name": ["Họ là bắt buộc."],
        "email": ["Email không đúng định dạng."],
        "password": [
            "Mật khẩu phải có ít nhất 6 ký tự.",
            "Xác nhận mật khẩu không khớp."
        ]
    }
}
```

#### 3. Email Already Exists

```bash
curl -X POST https://lamgame.localhost/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{
    "first_name": "Nguyễn",
    "last_name": "Văn A",
    "email": "existing@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response**:
```json
{
    "success": false,
    "message": "Email này đã được đăng ký."
}
```

### JavaScript/Fetch Example

```javascript
async function register(formData) {
    try {
        const response = await fetch('/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            // Redirect to home
            window.location.href = data.redirect_url;
        } else {
            // Show error message
            alert(data.message);
        }
    } catch (error) {
        console.error('Registration error:', error);
    }
}

// Usage
register({
    first_name: 'Nguyễn',
    last_name: 'Văn A',
    email: 'user@example.com',
    password: 'password123',
    password_confirmation: 'password123'
});
```

---

## Database Changes

### customers Table

**Insert Query**:

```sql
INSERT INTO customers (
    first_name,
    last_name,
    email,
    password,
    channel_id,
    customer_group_id,
    is_verified,
    status,
    created_at,
    updated_at
) VALUES (
    'Nguyễn',
    'Văn A',
    'user@example.com',
    '$2y$10$...',
    1,
    2,
    1,
    1,
    NOW(),
    NOW()
);
```

---

## Events & Listeners

**Potential Events** (not currently implemented):

- `CustomerRegistered`: Fired after successful registration
- `WelcomeEmailSent`: Fired after welcome email sent
- `CustomerVerified`: Fired when customer is verified

**Example Implementation**:

```php
// app/Events/CustomerRegistered.php
class CustomerRegistered
{
    public function __construct(public Customer $customer) {}
}

// In Controller
event(new CustomerRegistered($customer));
```

---

## Logging

### Success Log

```php
Log::info('Customer registered successfully', [
    'customer_id' => $customer->id,
    'email' => $customer->email,
    'ip' => $request->ip()
]);
```

### Error Log

```php
Log::error('Failed to send welcome email', [
    'customer_id' => $customer->id,
    'email' => $customer->email,
    'error' => $e->getMessage()
]);
```

---

## Performance Considerations

1. **Database Indexes**
   - `email` column has unique index
   - Consider adding index on `customer_group_id`

2. **Email Queue**
   - Consider queuing welcome email for better performance
   - Implement `ShouldQueue` interface

3. **Caching**
   - Cache customer groups to reduce DB queries

---

## Related Endpoints

- [POST /auth/login](./login-api-reference.md) - Đăng nhập
- [POST /auth/logout](./logout-api-reference.md) - Đăng xuất
- [POST /auth/forgot-password](./forgot-password-api-reference.md) - Quên mật khẩu
- [GET /auth/profile](./profile-api-reference.md) - Xem profile
- [PUT /auth/profile](./profile-api-reference.md) - Cập nhật profile
