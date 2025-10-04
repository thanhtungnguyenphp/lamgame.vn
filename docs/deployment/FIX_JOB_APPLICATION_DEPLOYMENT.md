# 🔧 Fix lỗi "Column 'applicant_user_id' cannot be null" - Deploy Production

## ❌ **Lỗi gặp phải:**
```sql
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'applicant_user_id' cannot be null
```

**Nguyên nhân:** Bảng `job_applications` có column `applicant_user_id` được set NOT NULL, nhưng khi guest user nộp hồ sơ (chưa đăng nhập), giá trị này sẽ là `null`.

## ✅ **Giải pháp đã chuẩn bị:**

### 1. **Migration Fix đã có sẵn:**
- File: `2025_10_03_073508_fix_job_applications_for_guest_users.php`
- Chức năng: Chuyển column `applicant_user_id` thành nullable
- Cập nhật foreign key constraint
- Thêm unique constraint cho user đã đăng nhập

### 2. **Controller đã xử lý đúng:**
- `JobApplicationController::apply()` line 74
- Dùng `auth('sanctum')->user()?->id` → trả về `null` nếu guest

## 🚀 **Steps Deploy Production:**

### **Step 1: Backup Database**
```bash
# Tạo backup trước khi migrate
mysqldump -h [HOST] -u [USER] -p[PASSWORD] [DATABASE_NAME] > backup_before_fix_$(date +%Y%m%d_%H%M%S).sql

# Hoặc nếu dùng Docker/Laravel Sail
php artisan backup:run
```

### **Step 2: Deploy Code**
```bash
# 1. Pull code mới nhất
git pull origin main

# 2. Update dependencies
composer install --no-dev --optimize-autoloader

# 3. Clear các cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### **Step 3: Run Migration**
```bash
# 1. Kiểm tra migration status
php artisan migrate:status

# 2. Run migration (QUAN TRỌNG - đây là bước fix lỗi chính)
php artisan migrate --force

# 3. Verify migration thành công
php artisan migrate:status | grep job_applications
```

### **Step 4: Optimize Application**
```bash
# Cache lại config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Restart queue workers nếu có
php artisan queue:restart
```

### **Step 5: Test trên Production**
1. **Test với Guest User:**
   - Truy cập trang apply job
   - Điền thông tin KHÔNG đăng nhập
   - Submit form → Phải thành công

2. **Test với Logged User:**
   - Đăng nhập user
   - Apply job → Phải thành công
   - Apply cùng job lần nữa → Phải báo duplicate

## 🔍 **Kiểm tra sau Deploy:**

### **1. Kiểm tra Database Schema:**
```sql
DESCRIBE job_applications;
-- Kiểm tra column applicant_user_id có NULL: YES
```

### **2. Kiểm tra Constraint:**
```sql
SHOW CREATE TABLE job_applications;
-- Xem foreign key constraint đã đúng chưa
```

### **3. Test API:**
```bash
# Test guest application
curl -X POST "https://domain.com/api/jobs/23/apply" \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Test User",
    "email": "test@example.com", 
    "phone": "0123456789",
    "cover_letter": "Test cover letter"
  }'

# Response expected: success: true
```

## ⚠️ **Rollback Plan (nếu cần):**

```bash
# 1. Rollback migration
php artisan migrate:rollback --step=1

# 2. Rollback code (nếu cần)
git checkout [PREVIOUS_COMMIT_HASH]
composer install --no-dev --optimize-autoloader
php artisan optimize:clear && php artisan optimize

# 3. Restore backup database (last resort)
mysql -h [HOST] -u [USER] -p[PASSWORD] [DATABASE_NAME] < backup_before_fix_[timestamp].sql
```

## 📊 **Monitoring sau Deploy:**

### **1. Check Error Logs:**
```bash
tail -f storage/logs/laravel.log | grep "job_applications"
```

### **2. Monitor Application Metrics:**
- Response time của API `/api/jobs/{id}/apply`
- Success rate của job applications
- Database performance

### **3. Business Metrics:**
- Số lượng applications từ guest users
- Tỷ lệ thành công của job applications

## 🎯 **Expected Results:**
- ✅ Guest users có thể nộp hồ sơ thành công
- ✅ Logged users vẫn hoạt động bình thường  
- ✅ Không có duplicate applications từ cùng user
- ✅ Database performance không bị ảnh hưởng
- ✅ Lỗi "cannot be null" hoàn toàn biến mất

## 🆘 **Contact Support:**
Nếu gặp vấn đề trong quá trình deploy:
1. Stop deployment ngay lập tức
2. Check logs: `tail -f storage/logs/laravel.log`
3. Rollback nếu cần thiết
4. Report chi tiết lỗi để debug