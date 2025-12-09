# Sơ đồ Luồng Đăng ký Tài khoản

## Flowchart Chi tiết

```
┌─────────────────────────────────────────────────────────────────┐
│                    NGƯỜI DÙNG TRUY CẬP                          │
│                  /auth/register (GET)                           │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              CustomerAuthController::showRegisterForm()         │
│                                                                 │
│  - Kiểm tra middleware guest:customer                          │
│  - Return view('auth.register')                                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                   HIỂN THỊ FORM ĐĂNG KÝ                         │
│                                                                 │
│  Fields:                                                        │
│  - Họ (first_name)                                             │
│  - Tên (last_name)                                             │
│  - Email                                                        │
│  - Mật khẩu (password)                                         │
│  - Xác nhận mật khẩu (password_confirmation)                   │
│                                                                 │
│  JavaScript Features:                                           │
│  - Password strength checker                                    │
│  - Password confirmation matching                               │
│  - Show/hide password toggle                                    │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              NGƯỜI DÙNG ĐIỀN THÔNG TIN & SUBMIT                 │
│                  POST /auth/register                            │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              CustomerAuthController::register()                 │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    VALIDATE DỮ LIỆU                             │
│              validateRegistration($request)                     │
│                                                                 │
│  Rules:                                                         │
│  - first_name: required|string|max:255                         │
│  - last_name: required|string|max:255                          │
│  - email: required|email|max:255|unique:customers              │
│  - password: required|string|min:6|confirmed                   │
│  - password_confirmation: required|string|min:6                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                    ┌────────┴────────┐
                    │                 │
                    ▼                 ▼
            ┌───────────┐      ┌──────────┐
            │ VALIDATION│      │VALIDATION│
            │   FAIL    │      │  PASS    │
            └─────┬─────┘      └────┬─────┘
                  │                 │
                  ▼                 ▼
        ┌──────────────────┐  ┌─────────────────────────────────┐
        │ Return back()    │  │  KIỂM TRA EMAIL ĐÃ TỒN TẠI     │
        │ withErrors()     │  │  Customer::where('email')       │
        │ withInput()      │  │         ->exists()              │
        └──────────────────┘  └────────┬────────────────────────┘
                                       │
                              ┌────────┴────────┐
                              │                 │
                              ▼                 ▼
                      ┌───────────┐      ┌──────────┐
                      │   EMAIL   │      │  EMAIL   │
                      │  TỒN TẠI  │      │ CHƯA CÓ  │
                      └─────┬─────┘      └────┬─────┘
                            │                 │
                            ▼                 ▼
                  ┌──────────────────┐  ┌─────────────────────────┐
                  │ Return back()    │  │   TẠO TÀI KHOẢN MỚI     │
                  │ withErrors()     │  │  createCustomer($data)  │
                  └──────────────────┘  └────────┬────────────────┘
                                                 │
                                                 ▼
                                    ┌────────────────────────────┐
                                    │  LẤY CUSTOMER GROUP        │
                                    │  findOneWhere(['code' =>   │
                                    │       'general'])          │
                                    └────────┬───────────────────┘
                                             │
                                             ▼
                                    ┌────────────────────────────┐
                                    │  Customer::create([        │
                                    │    first_name,             │
                                    │    last_name,              │
                                    │    email,                  │
                                    │    password (hashed),      │
                                    │    channel_id,             │
                                    │    customer_group_id,      │
                                    │    is_verified: 1,         │
                                    │    status: 1               │
                                    │  ])                        │
                                    └────────┬───────────────────┘
                                             │
                                             ▼
                                    ┌────────────────────────────┐
                                    │   GỬI EMAIL CHÀO MỪNG      │
                                    │   Mail::to($customer)      │
                                    │   ->send(WelcomeMail)      │
                                    └────────┬───────────────────┘
                                             │
                                    ┌────────┴────────┐
                                    │                 │
                                    ▼                 ▼
                            ┌───────────┐      ┌──────────┐
                            │   EMAIL   │      │  EMAIL   │
                            │   FAIL    │      │ SUCCESS  │
                            └─────┬─────┘      └────┬─────┘
                                  │                 │
                                  ▼                 │
                        ┌──────────────────┐        │
                        │ Log error        │        │
                        │ (không block)    │        │
                        └─────┬────────────┘        │
                              │                     │
                              └──────────┬──────────┘
                                         │
                                         ▼
                                ┌────────────────────────────┐
                                │   TỰ ĐỘNG ĐĂNG NHẬP        │
                                │   Auth::guard('customer')  │
                                │        ->login($customer)  │
                                └────────┬───────────────────┘
                                         │
                                         ▼
                                ┌────────────────────────────┐
                                │   REGENERATE SESSION       │
                                │   $request->session()      │
                                │        ->regenerate()      │
                                └────────┬───────────────────┘
                                         │
                                         ▼
                                ┌────────────────────────────┐
                                │   REDIRECT VỀ TRANG CHỦ    │
                                │   redirect(route('home'))  │
                                │   ->with('success', ...)   │
                                └────────┬───────────────────┘
                                         │
                                         ▼
                                ┌────────────────────────────┐
                                │  HIỂN THỊ THÔNG BÁO        │
                                │  "Đăng ký thành công!"     │
                                │  Người dùng đã đăng nhập   │
                                └────────────────────────────┘
```

---

## Sequence Diagram

```
User                Browser              Controller           Database           Mail Service
 │                     │                     │                   │                    │
 │  Click "Đăng ký"    │                     │                   │                    │
 ├────────────────────>│                     │                   │                    │
 │                     │  GET /auth/register │                   │                    │
 │                     ├────────────────────>│                   │                    │
 │                     │                     │                   │                    │
 │                     │  Return view        │                   │                    │
 │                     │<────────────────────┤                   │                    │
 │                     │                     │                   │                    │
 │  Hiển thị form      │                     │                   │                    │
 │<────────────────────┤                     │                   │                    │
 │                     │                     │                   │                    │
 │  Điền thông tin     │                     │                   │                    │
 │  & Submit           │                     │                   │                    │
 ├────────────────────>│                     │                   │                    │
 │                     │ POST /auth/register │                   │                    │
 │                     ├────────────────────>│                   │                    │
 │                     │                     │                   │                    │
 │                     │                     │  Validate input   │                    │
 │                     │                     ├──────────┐        │                    │
 │                     │                     │          │        │                    │
 │                     │                     │<─────────┘        │                    │
 │                     │                     │                   │                    │
 │                     │                     │  Check email      │                    │
 │                     │                     ├──────────────────>│                    │
 │                     │                     │                   │                    │
 │                     │                     │  Email not exists │                    │
 │                     │                     │<──────────────────┤                    │
 │                     │                     │                   │                    │
 │                     │                     │  Get customer     │                    │
 │                     │                     │  group 'general'  │                    │
 │                     │                     ├──────────────────>│                    │
 │                     │                     │                   │                    │
 │                     │                     │  Return group     │                    │
 │                     │                     │<──────────────────┤                    │
 │                     │                     │                   │                    │
 │                     │                     │  Create customer  │                    │
 │                     │                     ├──────────────────>│                    │
 │                     │                     │                   │                    │
 │                     │                     │  Return customer  │                    │
 │                     │                     │<──────────────────┤                    │
 │                     │                     │                   │                    │
 │                     │                     │  Send welcome email                    │
 │                     │                     ├───────────────────────────────────────>│
 │                     │                     │                   │                    │
 │                     │                     │  Email sent       │                    │
 │                     │                     │<───────────────────────────────────────┤
 │                     │                     │                   │                    │
 │                     │                     │  Login customer   │                    │
 │                     │                     ├──────────┐        │                    │
 │                     │                     │          │        │                    │
 │                     │                     │<─────────┘        │                    │
 │                     │                     │                   │                    │
 │                     │  Redirect to home   │                   │                    │
 │                     │<────────────────────┤                   │                    │
 │                     │                     │                   │                    │
 │  Trang chủ +        │                     │                   │                    │
 │  Thông báo success  │                     │                   │                    │
 │<────────────────────┤                     │                   │                    │
 │                     │                     │                   │                    │
```

---

## State Diagram

```
                    ┌──────────────┐
                    │   INITIAL    │
                    │  (Guest)     │
                    └──────┬───────┘
                           │
                           │ Truy cập /auth/register
                           ▼
                    ┌──────────────┐
                    │ FORM DISPLAY │
                    │              │
                    └──────┬───────┘
                           │
                           │ Submit form
                           ▼
                    ┌──────────────┐
                    │ VALIDATING   │
                    │              │
                    └──────┬───────┘
                           │
                  ┌────────┴────────┐
                  │                 │
         Validation fail    Validation pass
                  │                 │
                  ▼                 ▼
          ┌──────────────┐   ┌──────────────┐
          │ FORM DISPLAY │   │  CHECKING    │
          │ (with errors)│   │  EMAIL       │
          └──────────────┘   └──────┬───────┘
                                    │
                           ┌────────┴────────┐
                           │                 │
                    Email exists      Email available
                           │                 │
                           ▼                 ▼
                   ┌──────────────┐   ┌──────────────┐
                   │ FORM DISPLAY │   │   CREATING   │
                   │ (with errors)│   │   ACCOUNT    │
                   └──────────────┘   └──────┬───────┘
                                             │
                                             ▼
                                      ┌──────────────┐
                                      │   SENDING    │
                                      │    EMAIL     │
                                      └──────┬───────┘
                                             │
                                             ▼
                                      ┌──────────────┐
                                      │   LOGGING    │
                                      │     IN       │
                                      └──────┬───────┘
                                             │
                                             ▼
                                      ┌──────────────┐
                                      │ AUTHENTICATED│
                                      │  (Customer)  │
                                      └──────────────┘
```

---

## Component Interaction

```
┌─────────────────────────────────────────────────────────────────┐
│                         FRONTEND LAYER                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │         register.blade.php (View)                        │  │
│  │                                                          │  │
│  │  - Form HTML                                             │  │
│  │  - CSS Styling                                           │  │
│  │  - JavaScript Validation                                 │  │
│  │  - Password Strength Checker                             │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│                              │ POST Request                     │
│                              ▼                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                       CONTROLLER LAYER                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │    CustomerAuthController                                │  │
│  │                                                          │  │
│  │  - showRegisterForm()                                    │  │
│  │  - register()                                            │  │
│  │  - validateRegistration()                                │  │
│  │  - createCustomer()                                      │  │
│  └──────────────────────────────────────────────────────────┘  │
│                              │                                  │
│                              │ Uses                             │
│                              ▼                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      REPOSITORY LAYER                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌────────────────────────┐    ┌──────────────────────────┐    │
│  │  CustomerRepository    │    │ CustomerGroupRepository  │    │
│  │                        │    │                          │    │
│  │  - create()            │    │  - findOneWhere()        │    │
│  │  - findByEmail()       │    │                          │    │
│  └────────────────────────┘    └──────────────────────────┘    │
│                              │                                  │
│                              │ Interacts with                   │
│                              ▼                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         MODEL LAYER                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌────────────────────────┐    ┌──────────────────────────┐    │
│  │   Customer Model       │    │  CustomerGroup Model     │    │
│  │                        │    │                          │    │
│  │  - $fillable           │    │  - $fillable             │    │
│  │  - $hidden             │    │  - relationships         │    │
│  │  - relationships       │    │                          │    │
│  └────────────────────────┘    └──────────────────────────┘    │
│                              │                                  │
│                              │ Maps to                          │
│                              ▼                                  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                        DATABASE LAYER                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌────────────────────────┐    ┌──────────────────────────┐    │
│  │  customers table       │    │  customer_groups table   │    │
│  │                        │    │                          │    │
│  │  - id                  │    │  - id                    │    │
│  │  - first_name          │    │  - code                  │    │
│  │  - last_name           │    │  - name                  │    │
│  │  - email (unique)      │    │                          │    │
│  │  - password            │    │                          │    │
│  │  - customer_group_id   │────┤                          │    │
│  │  - channel_id          │    │                          │    │
│  │  - is_verified         │    │                          │    │
│  │  - status              │    │                          │    │
│  │  - timestamps          │    │                          │    │
│  └────────────────────────┘    └──────────────────────────┘    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      EXTERNAL SERVICES                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │         Mail Service (WelcomeMail)                       │  │
│  │                                                          │  │
│  │  - Send welcome email                                    │  │
│  │  - Use markdown template                                 │  │
│  │  - Pass customer data                                    │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │         Auth Service (Laravel Auth)                      │  │
│  │                                                          │  │
│  │  - Hash password                                         │  │
│  │  - Login customer                                        │  │
│  │  - Manage session                                        │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## Data Flow

```
INPUT DATA (Form)
    │
    ├─ first_name: "Nguyễn"
    ├─ last_name: "Văn A"
    ├─ email: "user@example.com"
    ├─ password: "password123"
    └─ password_confirmation: "password123"
    │
    ▼
VALIDATION
    │
    ├─ Check required fields
    ├─ Check email format
    ├─ Check password length (min 6)
    ├─ Check password confirmation match
    └─ Check email unique in database
    │
    ▼
TRANSFORMATION
    │
    ├─ Hash password: bcrypt("password123")
    ├─ Get channel_id: core()->getCurrentChannel()->id
    └─ Get customer_group_id: findOneWhere(['code' => 'general'])
    │
    ▼
DATABASE INSERT
    │
    ├─ first_name: "Nguyễn"
    ├─ last_name: "Văn A"
    ├─ email: "user@example.com"
    ├─ password: "$2y$10$..."
    ├─ channel_id: 1
    ├─ customer_group_id: 2
    ├─ is_verified: 1
    ├─ status: 1
    └─ timestamps: auto
    │
    ▼
CUSTOMER OBJECT
    │
    ├─ id: 123
    ├─ first_name: "Nguyễn"
    ├─ last_name: "Văn A"
    ├─ email: "user@example.com"
    └─ ... (other fields)
    │
    ▼
EMAIL NOTIFICATION
    │
    ├─ To: "user@example.com"
    ├─ Subject: "🎮 Chào mừng bạn đến với LAMGAME!"
    └─ Template: emails.welcome-simple
    │
    ▼
AUTO LOGIN
    │
    ├─ Auth::guard('customer')->login($customer)
    └─ Session regenerate
    │
    ▼
REDIRECT
    │
    ├─ URL: route('home')
    └─ Flash message: "Đăng ký thành công!"
```

---

## Error Handling Flow

```
                    ┌──────────────┐
                    │   SUBMIT     │
                    │    FORM      │
                    └──────┬───────┘
                           │
                           ▼
                    ┌──────────────┐
                    │  VALIDATION  │
                    └──────┬───────┘
                           │
                  ┌────────┴────────┐
                  │                 │
            PASS  │                 │  FAIL
                  │                 │
                  ▼                 ▼
          ┌──────────────┐   ┌──────────────────┐
          │ CHECK EMAIL  │   │ ValidationException│
          │   EXISTS     │   │                  │
          └──────┬───────┘   │ - Return errors  │
                 │           │ - Keep old input │
        ┌────────┴────────┐  │ - Highlight fields│
        │                 │  └──────────────────┘
  EXISTS│                 │NOT EXISTS
        │                 │
        ▼                 ▼
┌──────────────┐   ┌──────────────┐
│ Return back()│   │CREATE ACCOUNT│
│ withErrors() │   └──────┬───────┘
│ withInput()  │          │
└──────────────┘          ▼
                   ┌──────────────┐
                   │  SEND EMAIL  │
                   └──────┬───────┘
                          │
                 ┌────────┴────────┐
                 │                 │
           SUCCESS│                 │FAIL
                 │                 │
                 ▼                 ▼
          ┌──────────────┐   ┌──────────────┐
          │   CONTINUE   │   │  Log error   │
          │              │   │  (continue)  │
          └──────┬───────┘   └──────┬───────┘
                 │                  │
                 └────────┬─────────┘
                          │
                          ▼
                   ┌──────────────┐
                   │ AUTO LOGIN   │
                   └──────┬───────┘
                          │
                          ▼
                   ┌──────────────┐
                   │   SUCCESS    │
                   │   REDIRECT   │
                   └──────────────┘
```
