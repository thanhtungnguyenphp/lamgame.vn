# Phân Tích Lỗi Tạo Job và Logic Xử Lý

## Lỗi Hiện Tại

```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'lamgame.job_skills' doesn't exist
SQL: insert into `job_skills` (`created_at`, `product_id`, `skill_option_id`, `updated_at`) 
     values (2025-12-08 11:15:32, 35, 95, 2025-12-08 11:15:32)
```

## Nguyên Nhân

Migration file đã tồn tại nhưng **chưa được chạy**:
- File: `database/migrations/2025_12_04_133837_create_job_skills_and_benefits_tables.php`
- Bảng cần tạo: `job_skills` và `job_benefits`

## Giải Pháp

### Chạy migration:

```bash
php artisan migrate
```

Hoặc chạy migration cụ thể:

```bash
php artisan migrate --path=/database/migrations/2025_12_04_133837_create_job_skills_and_benefits_tables.php
```

---

## Phân Tích Logic Tạo Job

### 1. Flow Tạo Job

**Controller**: `App\Http\Controllers\Admin\JobController::store()`

```
1. Validate Request
   ├── title (required)
   ├── description (required)
   ├── job_type (required)
   ├── experience_level (required)
   ├── job_location (required)
   ├── contact_email (required, email)
   └── company.name (required)

2. Begin Transaction

3. Xử Lý Company
   ├── Upload logo (nếu có)
   ├── Update company (nếu admin đã có company)
   └── Create company (nếu admin chưa có company)

4. Tạo Product (Job)
   ├── Generate SKU: JOB_{UNIQUE_ID}
   ├── Insert vào bảng `products`
   └── Lấy product_id

5. Tạo Product Flat
   ├── Insert vào bảng `product_flat`
   └── Generate URL key

6. Lưu Job Attributes
   ├── Lưu attributes cơ bản
   ├── Lưu skills vào `job_skills` ⚠️ (Lỗi ở đây)
   └── Lưu benefits vào `job_benefits` ⚠️

7. Commit Transaction

8. Redirect với success message
```

### 2. Cấu Trúc Bảng

#### Bảng `job_skills` (Pivot Table)

```sql
CREATE TABLE job_skills (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    skill_option_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY (product_id, skill_option_id),
    INDEX (skill_option_id)
);
```

**Mục đích**: Lưu quan hệ nhiều-nhiều giữa Job và Skills

#### Bảng `job_benefits` (Pivot Table)

```sql
CREATE TABLE job_benefits (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    benefit_option_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY (product_id, benefit_option_id),
    INDEX (benefit_option_id)
);
```

**Mục đích**: Lưu quan hệ nhiều-nhiều giữa Job và Benefits

### 3. Method `saveJobAttributes()`

**File**: `app/Http/Controllers/Admin/JobController.php` (line 333-380)

```php
private function saveJobAttributes($productId, $request)
{
    // 1. Lưu attributes cơ bản vào product_attribute_values
    $attributes = [
        40 => $request->job_type,           // Loại job
        41 => $request->experience_level,   // Cấp độ kinh nghiệm
        42 => $request->salary_range,       // Mức lương
        43 => $request->job_location,       // Địa điểm
        50 => $request->contact_email,      // Email liên hệ
        51 => $request->contact_phone       // SĐT liên hệ
    ];

    foreach ($attributes as $attributeId => $value) {
        if ($value) {
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => $attributeId,
                'text_value' => $value
            ]);
        }
    }
    
    // 2. Lưu skills vào bảng job_skills (PIVOT TABLE)
    if ($request->has('required_skills') && is_array($request->required_skills)) {
        $skillsData = array_map(function($skillId) use ($productId) {
            return [
                'product_id' => $productId,
                'skill_option_id' => $skillId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $request->required_skills);
        
        DB::table('job_skills')->insert($skillsData); // ⚠️ LỖI Ở ĐÂY
    }
    
    // 3. Lưu benefits vào bảng job_benefits (PIVOT TABLE)
    if ($request->has('job_benefits') && is_array($request->job_benefits)) {
        $benefitsData = array_map(function($benefitId) use ($productId) {
            return [
                'product_id' => $productId,
                'benefit_option_id' => $benefitId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $request->job_benefits);
        
        DB::table('job_benefits')->insert($benefitsData); // ⚠️ LỖI Ở ĐÂY
    }
}
```

### 4. Ràng Buộc Thuộc Tính Job

#### Thuộc Tính Bắt Buộc (Required)

| Field | Attribute ID | Type | Validation |
|-------|--------------|------|------------|
| title | - | string | required, max:255 |
| description | - | text | required |
| job_type | 40 | select | required |
| experience_level | 41 | select | required |
| job_location | 43 | text | required |
| contact_email | 50 | email | required, email |
| company.name | - | string | required, max:255 |

#### Thuộc Tính Tùy Chọn (Optional)

| Field | Attribute ID | Type | Validation |
|-------|--------------|------|------------|
| short_description | - | text | nullable |
| salary_range | 42 | select | optional |
| contact_phone | 51 | string | optional |
| required_skills | - | array | optional, multiselect |
| job_benefits | - | array | optional, multiselect |
| education_level | 45 | select | optional |
| english_level | 48 | select | optional |
| company_size | - | select | optional |
| application_method | - | select | optional |

#### Company Fields

| Field | Type | Validation |
|-------|------|------------|
| company.name | string | required, max:255 |
| company.description | text | optional |
| company.website | url | optional |
| company.address | string | optional |
| company.size | string | optional |
| company_logo | file | optional, image |

### 5. Quan Hệ Dữ Liệu

```
products (jobs)
├── id (primary key)
├── sku (JOB_xxxxx)
├── type = 'job'
├── company_id (foreign key → companies)
├── created_by_admin_id (foreign key → admins)
└── timestamps

product_flat
├── product_id (foreign key → products)
├── name (job title)
├── description
├── short_description
├── url_key
└── locale = 'vi'

product_attribute_values
├── product_id (foreign key → products)
├── attribute_id (40, 41, 42, 43, 50, 51)
└── text_value

job_skills (PIVOT) ⚠️ MISSING TABLE
├── product_id (foreign key → products)
├── skill_option_id (foreign key → attribute_options)
└── timestamps

job_benefits (PIVOT) ⚠️ MISSING TABLE
├── product_id (foreign key → products)
├── benefit_option_id (foreign key → attribute_options)
└── timestamps

companies
├── id (primary key)
├── name
├── logo
├── description
├── website
└── created_by_admin_id
```

### 6. Update Job Logic

**Method**: `JobController::update($id)`

```
1. Validate Request (same as store)

2. Begin Transaction

3. Xử Lý Company (update existing)

4. Update Product Flat
   └── Update name, description, short_description

5. Update Product
   └── Update company_id, updated_at

6. Xóa Dữ Liệu Cũ
   ├── DELETE FROM product_attribute_values WHERE product_id = $id
   ├── DELETE FROM job_skills WHERE product_id = $id ⚠️
   └── DELETE FROM job_benefits WHERE product_id = $id ⚠️

7. Lưu Lại Attributes Mới
   └── Call saveJobAttributes()

8. Commit Transaction
```

### 7. Delete Job Logic

**Method**: `JobController::destroy($id)`

```
1. Begin Transaction

2. Xóa Dữ Liệu Liên Quan
   ├── DELETE FROM product_attribute_values WHERE product_id = $id
   ├── DELETE FROM job_skills WHERE product_id = $id ⚠️
   ├── DELETE FROM job_benefits WHERE product_id = $id ⚠️
   └── DELETE FROM product_flat WHERE product_id = $id

3. Xóa Product
   └── DELETE FROM products WHERE id = $id AND created_by_admin_id = $admin_id

4. Commit Transaction
```

---

## Tác Động Của Lỗi

### Các Chức Năng Bị Ảnh Hưởng

1. ✅ **Tạo Job** - LỖI khi có `required_skills` hoặc `job_benefits`
2. ✅ **Update Job** - LỖI khi xóa và lưu lại skills/benefits
3. ✅ **Delete Job** - LỖI khi xóa skills/benefits
4. ❌ **List Jobs** - Không bị ảnh hưởng
5. ❌ **View Job Detail** - Không bị ảnh hưởng (nếu không load skills/benefits)

### Workaround Tạm Thời

Nếu không thể chạy migration ngay, có thể comment code liên quan:

```php
// Comment trong saveJobAttributes()
// Lines 355-366: job_skills insert
// Lines 369-380: job_benefits insert

// Comment trong update()
// Line 286: job_skills delete
// Line 287: job_benefits delete

// Comment trong destroy()
// Line 311: job_skills delete
// Line 312: job_benefits delete
```

**⚠️ Lưu ý**: Workaround này sẽ làm mất chức năng lưu skills và benefits!

---

## Kiểm Tra Sau Khi Fix

### 1. Kiểm tra bảng đã tạo

```sql
SHOW TABLES LIKE 'job_%';
-- Kết quả mong đợi:
-- job_skills
-- job_benefits
-- job_applications
-- job_batches
-- job_import_logs
```

### 2. Kiểm tra cấu trúc bảng

```sql
DESCRIBE job_skills;
DESCRIBE job_benefits;
```

### 3. Test tạo job

```bash
# Test với skills và benefits
POST /admin/jobs
{
  "title": "Test Job",
  "description": "Test description",
  "job_type": 1,
  "experience_level": 9,
  "job_location": "TP.HCM",
  "contact_email": "test@example.com",
  "company": {
    "name": "Test Company"
  },
  "required_skills": [95, 96],
  "job_benefits": [100, 101]
}
```

### 4. Kiểm tra dữ liệu

```sql
-- Kiểm tra job vừa tạo
SELECT * FROM products WHERE id = 35;

-- Kiểm tra skills
SELECT * FROM job_skills WHERE product_id = 35;

-- Kiểm tra benefits
SELECT * FROM job_benefits WHERE product_id = 35;
```

---

## Tổng Kết

### Nguyên Nhân Chính
- Migration đã tồn tại nhưng chưa chạy
- Bảng `job_skills` và `job_benefits` chưa được tạo trong database

### Giải Pháp
```bash
php artisan migrate
```

### Các Bảng Cần Thiết
1. ✅ `products` - Đã có
2. ✅ `product_flat` - Đã có
3. ✅ `product_attribute_values` - Đã có
4. ✅ `companies` - Đã có
5. ⚠️ `job_skills` - **CẦN TẠO**
6. ⚠️ `job_benefits` - **CẦN TẠO**

### Validation Rules Summary

**Required Fields**:
- title, description, job_type, experience_level, job_location, contact_email, company.name

**Optional Fields**:
- short_description, salary_range, contact_phone, required_skills, job_benefits, education_level, english_level, company_size, application_method

**File Upload**:
- company_logo (optional, image)

---

**Date**: 2025-12-08
**Status**: ✅ RESOLVED - Migration Completed Successfully

## Migration Result

### Executed Command
```bash
docker exec lamgame-php php artisan migrate
```

### Tables Created

#### 1. job_skills
```
id                  bigint unsigned (PK, AUTO_INCREMENT)
product_id          int unsigned (FK → products.id)
skill_option_id     int unsigned (INDEX)
created_at          timestamp
updated_at          timestamp

UNIQUE KEY: (product_id, skill_option_id)
FOREIGN KEY: product_id → products(id) ON DELETE CASCADE
```

#### 2. job_benefits
```
id                  bigint unsigned (PK, AUTO_INCREMENT)
product_id          int unsigned (FK → products.id)
benefit_option_id   int unsigned (INDEX)
created_at          timestamp
updated_at          timestamp

UNIQUE KEY: (product_id, benefit_option_id)
FOREIGN KEY: product_id → products(id) ON DELETE CASCADE
```

### Fix Applied
Changed column types from `unsignedBigInteger` to `unsignedInteger` to match `products.id` type.

---
