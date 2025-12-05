# Phân Tích: Admin có 1 job nhưng Frontend hiển thị 2 jobs

**Issue:** Trang admin `/admin/jobs` hiển thị 1 job, nhưng trang public `/viec-lam-game` hiển thị 2 jobs

---

## 🔍 ROOT CAUSE ANALYSIS

### Admin Query (JobController@getUserJobs)
**File:** `app/Http/Controllers/Admin/JobController.php` line 393-407

```php
private function getUserJobs($page = 1)
{
    $userId = Auth::guard('admin')->id();
    
    $jobs = DB::table('products')
        ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
        ->leftJoin('companies', 'products.company_id', '=', 'companies.id')
        ->select('products.*', 'product_flat.name as title', 'product_flat.description', 'companies.name as company_name')
        ->where('products.type', 'job')                    // ✅ Filter by type
        ->where('products.created_by_admin_id', $userId)   // ✅ Filter by admin
        ->orderBy('products.created_at', 'desc')
        ->paginate(10, ['*'], 'page', $page);
}
```

**Filters:**
- ✅ `type = 'job'`
- ✅ `created_by_admin_id = {current_admin_id}`
- ❌ **KHÔNG filter** `sku LIKE 'JOB_%'`

---

### Frontend Query (LamGamePageController@viecLamGame)
**File:** `app/Http/Controllers/LamGamePageController.php` line 226-260

```php
public function viecLamGame(Request $request)
{
    $jobsQuery = \DB::table('products as p')
        ->leftJoin('product_flat as pf', function($join) {
            $join->on('p.id', '=', 'pf.product_id')
                 ->where('pf.locale', '=', 'vi');
        })
        ->leftJoin('companies as c', 'p.company_id', '=', 'c.id')
        // ... many more joins ...
        ->where('p.sku', 'LIKE', 'JOB_%')           // ✅ Filter by SKU pattern
        ->where('pf.status', 1)                     // ✅ Filter by status
        ->where('pf.visible_individually', 1)       // ✅ Filter by visibility
        ->select(/* ... */);
}
```

**Filters:**
- ✅ `sku LIKE 'JOB_%'`
- ✅ `pf.status = 1`
- ✅ `pf.visible_individually = 1`
- ❌ **KHÔNG filter** `type = 'job'`
- ❌ **KHÔNG filter** `created_by_admin_id`

---

## 🐛 THE PROBLEM

### Scenario 1: Job có `type != 'job'` nhưng `sku LIKE 'JOB_%'`
```sql
-- Job này KHÔNG hiển thị trong admin (vì type != 'job')
-- Nhưng HIỂN THỊ ở frontend (vì sku LIKE 'JOB_%')

products:
  id: 123
  sku: 'JOB_ABC123'
  type: 'simple'  ← KHÔNG phải 'job'
  created_by_admin_id: 1
```

### Scenario 2: Job của admin khác
```sql
-- Job này KHÔNG hiển thị trong admin (vì created_by_admin_id khác)
-- Nhưng HIỂN THỊ ở frontend (không filter admin)

products:
  id: 456
  sku: 'JOB_XYZ789'
  type: 'job'
  created_by_admin_id: 2  ← Admin khác
```

### Scenario 3: Job có `type = 'job'` nhưng `sku` không bắt đầu bằng 'JOB_'
```sql
-- Job này HIỂN THỊ trong admin (vì type = 'job')
-- Nhưng KHÔNG hiển thị ở frontend (vì sku không match)

products:
  id: 789
  sku: 'PRODUCT_123'  ← Không bắt đầu bằng JOB_
  type: 'job'
  created_by_admin_id: 1
```

---

## 🔎 DIAGNOSIS QUERIES

### Query 1: Tìm jobs trong admin
```sql
SELECT 
    p.id,
    p.sku,
    p.type,
    p.created_by_admin_id,
    pf.name,
    pf.status,
    pf.visible_individually
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id
WHERE p.type = 'job'
  AND p.created_by_admin_id = {current_admin_id}
ORDER BY p.created_at DESC;
```

### Query 2: Tìm jobs ở frontend
```sql
SELECT 
    p.id,
    p.sku,
    p.type,
    p.created_by_admin_id,
    pf.name,
    pf.status,
    pf.visible_individually
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
WHERE p.sku LIKE 'JOB_%'
  AND pf.status = 1
  AND pf.visible_individually = 1
ORDER BY p.created_at DESC;
```

### Query 3: Tìm jobs chỉ hiển thị ở frontend (không ở admin)
```sql
SELECT 
    p.id,
    p.sku,
    p.type,
    p.created_by_admin_id,
    pf.name,
    'Reason' = CASE
        WHEN p.type != 'job' THEN 'Type is not job'
        WHEN p.created_by_admin_id != {current_admin_id} THEN 'Different admin'
        ELSE 'Unknown'
    END
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
WHERE p.sku LIKE 'JOB_%'
  AND pf.status = 1
  AND pf.visible_individually = 1
  AND (p.type != 'job' OR p.created_by_admin_id != {current_admin_id});
```

---

## ✅ SOLUTIONS

### Option 1: Sync Admin với Frontend (Recommended)
**Thay đổi admin query để match với frontend**

```php
// Admin\JobController.php - getUserJobs()
private function getUserJobs($page = 1)
{
    $userId = Auth::guard('admin')->id();
    
    $jobs = DB::table('products')
        ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
        ->leftJoin('companies', 'products.company_id', '=', 'companies.id')
        ->select('products.*', 'product_flat.name as title', 'product_flat.description', 'companies.name as company_name')
        ->where('products.sku', 'LIKE', 'JOB_%')           // ✅ ADD THIS
        ->where('product_flat.status', 1)                   // ✅ ADD THIS
        ->where('product_flat.visible_individually', 1)     // ✅ ADD THIS
        ->where('products.created_by_admin_id', $userId)
        ->orderBy('products.created_at', 'desc')
        ->paginate(10, ['*'], 'page', $page);
}
```

**Pros:**
- Admin thấy chính xác những gì user thấy
- Consistent data giữa admin và frontend

**Cons:**
- Admin không thấy draft jobs (status = 0)

---

### Option 2: Sync Frontend với Admin
**Thay đổi frontend query để match với admin**

```php
// LamGamePageController.php - viecLamGame()
$jobsQuery = \DB::table('products as p')
    // ... joins ...
    ->where('p.type', 'job')                    // ✅ ADD THIS
    ->where('p.sku', 'LIKE', 'JOB_%')
    ->where('pf.status', 1)
    ->where('pf.visible_individually', 1);
```

**Pros:**
- Đảm bảo chỉ hiển thị products có type = 'job'
- Tránh hiển thị products khác có SKU giống

**Cons:**
- Nếu có job không có type = 'job', sẽ không hiển thị

---

### Option 3: Standardize SKU Generation (Best Practice)
**Đảm bảo tất cả jobs đều có:**
- `type = 'job'`
- `sku LIKE 'JOB_%'`
- `status = 1` (khi published)
- `visible_individually = 1`

```php
// Admin\JobController.php - store()
$productData = [
    'type' => 'job',                                    // ✅ Always set
    'sku' => 'JOB_' . strtoupper(uniqid()),            // ✅ Always JOB_ prefix
    'attribute_family_id' => 1,
    'created_by_admin_id' => $admin->id,
    'created_at' => now(),
    'updated_at' => now()
];

// Ensure product_flat has correct values
DB::table('product_flat')->insert([
    'product_id' => $productId,
    'sku' => $sku,
    'type' => 'job',                                    // ✅ Always set
    'status' => 1,                                      // ✅ Published by default
    'visible_individually' => 1,                        // ✅ Visible by default
    // ... other fields
]);
```

---

## 🎯 RECOMMENDED FIX

**Combine Option 1 + Option 2 + Option 3:**

1. ✅ Update admin query để include SKU filter
2. ✅ Update frontend query để include type filter
3. ✅ Ensure store() method sets correct values
4. ✅ Add validation để prevent inconsistent data

---

## 📝 IMPLEMENTATION STEPS

### Step 1: Fix Admin Query
```php
// File: app/Http/Controllers/Admin/JobController.php
// Method: getUserJobs()

->where('products.sku', 'LIKE', 'JOB_%')
->where('product_flat.status', 1)
->where('product_flat.visible_individually', 1)
```

### Step 2: Fix Frontend Query
```php
// File: app/Http/Controllers/LamGamePageController.php
// Method: viecLamGame()

->where('p.type', 'job')
->where('p.sku', 'LIKE', 'JOB_%')
```

### Step 3: Add Data Validation
```php
// File: app/Http/Controllers/Admin/JobController.php
// Method: store()

// After creating product, verify data
if ($productId) {
    $product = DB::table('products')->find($productId);
    if ($product->type !== 'job' || !str_starts_with($product->sku, 'JOB_')) {
        throw new \Exception('Invalid job product data');
    }
}
```

### Step 4: Clean Up Existing Data
```sql
-- Find inconsistent jobs
SELECT 
    p.id,
    p.sku,
    p.type,
    pf.status,
    pf.visible_individually,
    CASE
        WHEN p.type != 'job' THEN 'Fix type'
        WHEN p.sku NOT LIKE 'JOB_%' THEN 'Fix SKU'
        WHEN pf.status != 1 THEN 'Fix status'
        WHEN pf.visible_individually != 1 THEN 'Fix visibility'
        ELSE 'OK'
    END as issue
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id
WHERE p.sku LIKE 'JOB_%' OR p.type = 'job';

-- Fix type
UPDATE products 
SET type = 'job' 
WHERE sku LIKE 'JOB_%' AND type != 'job';

-- Fix SKU
UPDATE products 
SET sku = CONCAT('JOB_', UPPER(SUBSTRING(MD5(RAND()), 1, 10)))
WHERE type = 'job' AND sku NOT LIKE 'JOB_%';

-- Fix product_flat
UPDATE product_flat pf
JOIN products p ON pf.product_id = p.id
SET pf.type = 'job',
    pf.status = 1,
    pf.visible_individually = 1
WHERE p.type = 'job' AND p.sku LIKE 'JOB_%';
```

---

## 🧪 TESTING

### Test Case 1: Create new job
- ✅ Job appears in admin list
- ✅ Job appears in frontend list
- ✅ Count matches

### Test Case 2: Update job
- ✅ Changes reflect in both admin and frontend
- ✅ Count remains consistent

### Test Case 3: Delete job
- ✅ Job removed from both admin and frontend
- ✅ Count decreases by 1 in both

### Test Case 4: Multiple admins
- ✅ Admin A only sees their jobs in admin panel
- ✅ All published jobs visible in frontend
- ✅ Counts are correct

---

## 📊 SUMMARY

**Current State:**
- Admin: 1 job (filtered by `type='job'` AND `created_by_admin_id`)
- Frontend: 2 jobs (filtered by `sku LIKE 'JOB_%'`)

**Root Cause:**
- Inconsistent filter conditions between admin and frontend
- Possible data inconsistency (type vs SKU)

**Solution:**
- Sync filter conditions
- Standardize data creation
- Clean up existing data
- Add validation

**Expected Result:**
- Admin and frontend show same jobs (for that admin)
- Consistent counts
- No data leakage
