# Company Migration Implementation Guide

## ✅ Completed Changes

### 1. **Admin Model** (`packages/Webkul/User/src/Models/Admin.php`)
- ✅ Added `company_id` to `$fillable`
- ✅ Added `company()` relationship method
- 📦 Backup: `Admin.php.backup`

### 2. **Company Model** (`app/Models/Company.php`)
- ✅ Added `SoftDeletes` trait
- ✅ Improved `getLogoUrlAttribute()` - now returns URL instead of base64
- ✅ Added `getLogoBase64()` method for explicit base64 usage
- ✅ Added proper imports and type hints

### 3. **Migrations**

#### `2025_11_08_124420_create_companies_table.php`
- ✅ Added `softDeletes()` column
- ✅ Added indexes: `status`, `created_by_admin_id`, `email`, `name`
- ✅ Removed foreign key (moved to separate migration)

#### `2025_11_08_125041_add_company_id_to_admins_table.php`
- ✅ Added `company_id` column to admins table
- ✅ Added index on `company_id`

#### `2025_11_08_124451_add_company_id_to_products_table.php`
- ✅ Already correct (no changes needed)

#### `2025_11_10_064600_add_company_foreign_keys.php` (NEW)
- ✅ Created new migration for foreign keys
- ✅ Avoids circular dependency
- ✅ Runs after both tables are created

### 4. **JobController** (`app/Http/Controllers/Api/JobController.php`)
- ✅ Added `Rule` import
- ✅ Improved `saveCompanyInfo()`:
  - Added unique company name validation
  - Added logo upload handling (max 2MB)
  - Added old logo deletion
  - Added logo_url to response
- 📦 Backup: `JobController.php.backup`

---

## 🚀 Migration Steps

### Step 1: Run Migrations
```bash
# Run all migrations
php artisan migrate

# If you need to refresh (DANGER - will lose data)
# php artisan migrate:fresh
```

### Step 2: Verify Database Structure
```bash
# Check companies table
docker exec lg-mysql mysql -u lg -plg -D lamgame -e "DESCRIBE companies;"

# Check admins table has company_id
docker exec lg-mysql mysql -u lg -plg -D lamgame -e "DESCRIBE admins;" | grep company

# Check products table has company_id
docker exec lg-mysql mysql -u lg -plg -D lamgame -e "DESCRIBE products;" | grep company

# Check foreign keys
docker exec lg-mysql mysql -u lg -plg -D lamgame -e "
SELECT 
    TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, 
    REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'lamgame' 
AND REFERENCED_TABLE_NAME IN ('companies', 'admins');"
```

### Step 3: Test API Endpoints
```bash
# Get company info (should return null if no company)
curl -X GET http://localhost/api/company/info \
  -H "Authorization: Bearer YOUR_TOKEN"

# Save company info
curl -X POST http://localhost/api/company/save \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: multipart/form-data" \
  -F "name=Test Company" \
  -F "description=Test Description" \
  -F "email=test@company.com" \
  -F "logo=@/path/to/logo.png"
```

---

## 📝 Key Improvements

1. **Performance**
   - Added indexes on frequently queried columns
   - Logo URL now returns storage URL instead of base64 (much lighter)

2. **Data Integrity**
   - Foreign keys with proper cascade rules
   - Unique company names
   - Soft deletes for audit trail

3. **Validation**
   - Logo file validation (type, size)
   - Unique company name per admin
   - Proper URL and email validation

4. **File Management**
   - Automatic old logo deletion on update
   - Proper storage disk usage

5. **Database Design**
   - Avoided circular dependency with separate FK migration
   - Proper indexing for performance

---

## 🔄 Rollback Plan

If needed, rollback migrations:
```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback specific migration
php artisan migrate:rollback --step=1

# Restore backups
cp packages/Webkul/User/src/Models/Admin.php.backup packages/Webkul/User/src/Models/Admin.php
cp app/Http/Controllers/Api/JobController.php.backup app/Http/Controllers/Api/JobController.php
```

---

## 📊 Migration Order

1. `2025_11_08_124420_create_companies_table.php` - Creates companies table
2. `2025_11_08_124451_add_company_id_to_products_table.php` - Adds company_id to products
3. `2025_11_08_125041_add_company_id_to_admins_table.php` - Adds company_id to admins
4. `2025_11_10_064600_add_company_foreign_keys.php` - Adds foreign key constraints

---

## ⚠️ Important Notes

- All changes are backward compatible
- Old logo files are automatically deleted on update
- Soft deletes allow recovery of deleted companies
- Foreign keys use `SET NULL` on delete (safe)

---

Generated: 2025-11-10
