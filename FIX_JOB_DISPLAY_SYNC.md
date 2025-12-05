# Fix: Sync Job Display Between Admin & Frontend

**Date:** 2025-12-04  
**Issue:** Admin shows 1 job, Frontend shows 2 jobs

---

## ✅ Changes Applied

### 1. Admin\JobController - getUserJobs()
**File:** `app/Http/Controllers/Admin/JobController.php`

**Before:**
```php
->where('products.type', 'job')
->where('products.created_by_admin_id', $userId)
```

**After:**
```php
->where('products.sku', 'LIKE', 'JOB_%')
->where('products.created_by_admin_id', $userId)
// Also added locale filter for product_flat join
```

**Changes:**
- ✅ Changed from `type = 'job'` to `sku LIKE 'JOB_%'`
- ✅ Added locale filter to product_flat join
- ✅ Added publish_status to show draft vs published
- ✅ Now matches frontend filter logic

---

### 2. Admin\JobController - getJobStats()
**File:** `app/Http/Controllers/Admin/JobController.php`

**Before:**
```php
->where('type', 'job')
```

**After:**
```php
->where('sku', 'LIKE', 'JOB_%')
// Separate count for published vs draft
```

**Changes:**
- ✅ Changed filter to match getUserJobs()
- ✅ Added separate count for published jobs
- ✅ Calculate pending_jobs = total - published

---

### 3. LamGamePageController - viecLamGame()
**File:** `app/Http/Controllers/LamGamePageController.php`

**Before:**
```php
->where('p.sku', 'LIKE', 'JOB_%')
->where('pf.status', 1)
```

**After:**
```php
->where('p.type', 'job')
->where('p.sku', 'LIKE', 'JOB_%')
->where('pf.status', 1)
```

**Changes:**
- ✅ Added `type = 'job'` filter
- ✅ Now requires BOTH type AND sku to match
- ✅ Prevents non-job products with JOB_ SKU from showing

---

### 4. LamGamePageController - jobDetail()
**File:** `app/Http/Controllers/LamGamePageController.php`

**Changes:**
- ✅ Added `->where('p.type', 'job')`
- ✅ Consistent with listing page

---

### 5. LamGamePageController - totalJobs & topCompanies
**File:** `app/Http/Controllers/LamGamePageController.php`

**Changes:**
- ✅ Added `->where('p.type', 'job')` to totalJobs query
- ✅ Added `->where('p.type', 'job')` to topCompanies query
- ✅ Ensures accurate counts

---

### 6. LamGamePageController - similarJobs
**File:** `app/Http/Controllers/LamGamePageController.php`

**Changes:**
- ✅ Added `->where('p.type', 'job')`
- ✅ Only shows actual job products

---

## 🎯 Result

### Filter Logic (Now Consistent)

**Admin:**
```sql
WHERE products.sku LIKE 'JOB_%'
  AND products.created_by_admin_id = {current_admin}
  AND product_flat.locale = 'vi'
```

**Frontend:**
```sql
WHERE products.type = 'job'
  AND products.sku LIKE 'JOB_%'
  AND product_flat.status = 1
  AND product_flat.visible_individually = 1
  AND product_flat.locale = 'vi'
```

**Key Points:**
- ✅ Both use `sku LIKE 'JOB_%'` as primary filter
- ✅ Frontend adds `type = 'job'` for extra safety
- ✅ Admin filters by creator, Frontend shows all published
- ✅ Both use locale filter for product_flat

---

## 📊 Expected Behavior

### Scenario 1: Admin creates new job
```
Admin Panel:
- Shows immediately (even if draft)
- Status: "draft" or "published"

Frontend:
- Shows only if status = 1 (published)
- Hidden if draft
```

### Scenario 2: Multiple admins
```
Admin A Panel:
- Shows only Admin A's jobs

Admin B Panel:
- Shows only Admin B's jobs

Frontend:
- Shows all published jobs from all admins
```

### Scenario 3: Product with JOB_ SKU but type != 'job'
```
Admin Panel:
- Shows (because sku matches)
- Can be edited/deleted

Frontend:
- HIDDEN (because type != 'job')
- Won't appear in public listing
```

---

## 🧪 Testing Checklist

### Test 1: Count Consistency
- [ ] Create new job in admin
- [ ] Verify count in admin panel
- [ ] Publish job (status = 1)
- [ ] Verify count in frontend matches
- [ ] Unpublish job (status = 0)
- [ ] Verify frontend count decreases

### Test 2: Multiple Admins
- [ ] Login as Admin A
- [ ] Create 2 jobs
- [ ] Verify Admin A sees 2 jobs
- [ ] Login as Admin B
- [ ] Verify Admin B sees 0 jobs
- [ ] Verify frontend shows 2 jobs (if published)

### Test 3: Data Integrity
- [ ] Check all jobs have `type = 'job'`
- [ ] Check all jobs have `sku LIKE 'JOB_%'`
- [ ] Check product_flat has matching records
- [ ] Check no orphaned records

### Test 4: Edge Cases
- [ ] Job with no product_flat record
- [ ] Job with status = 0 (draft)
- [ ] Job with visible_individually = 0
- [ ] Job with wrong locale

---

## 🔧 Data Cleanup (If Needed)

### Find Inconsistent Jobs
```sql
-- Jobs with type != 'job' but SKU like JOB_
SELECT id, sku, type 
FROM products 
WHERE sku LIKE 'JOB_%' AND type != 'job';

-- Jobs with type = 'job' but SKU not like JOB_
SELECT id, sku, type 
FROM products 
WHERE type = 'job' AND sku NOT LIKE 'JOB_%';

-- Jobs without product_flat
SELECT p.id, p.sku 
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id
WHERE p.sku LIKE 'JOB_%' AND pf.id IS NULL;
```

### Fix Inconsistent Data
```sql
-- Fix type for jobs with JOB_ SKU
UPDATE products 
SET type = 'job' 
WHERE sku LIKE 'JOB_%' AND type != 'job';

-- Fix SKU for jobs without JOB_ prefix
UPDATE products 
SET sku = CONCAT('JOB_', UPPER(SUBSTRING(MD5(RAND()), 1, 10)))
WHERE type = 'job' AND sku NOT LIKE 'JOB_%';

-- Ensure product_flat exists for all jobs
INSERT INTO product_flat (product_id, sku, type, locale, channel, status, visible_individually, created_at, updated_at)
SELECT 
    p.id,
    p.sku,
    'job',
    'vi',
    'default',
    1,
    1,
    NOW(),
    NOW()
FROM products p
LEFT JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
WHERE p.sku LIKE 'JOB_%' 
  AND pf.id IS NULL;
```

---

## 📝 Summary

**Problem:**
- Admin and Frontend used different filter conditions
- Admin: `type = 'job'`
- Frontend: `sku LIKE 'JOB_%'`
- Result: Different job counts

**Solution:**
- Standardized both to use `sku LIKE 'JOB_%'` as primary filter
- Added `type = 'job'` to frontend for extra validation
- Added locale filter to admin queries
- Separated draft vs published counts in admin

**Benefits:**
- ✅ Consistent data display
- ✅ Admin sees what users will see (when published)
- ✅ Better draft/published workflow
- ✅ Prevents data inconsistencies
- ✅ Accurate job counts

**Breaking Changes:**
- ⚠️ Admin may now see different jobs if data was inconsistent
- ⚠️ Jobs without `sku LIKE 'JOB_%'` won't show in admin
- ⚠️ Run data cleanup queries if needed
