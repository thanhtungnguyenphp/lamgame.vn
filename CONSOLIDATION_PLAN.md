# Job Management Consolidation Plan

## 🎯 Mục Tiêu
Hợp nhất 2 hệ thống job management thành 1 hệ thống duy nhất trong Admin Panel

## 📋 Kế Hoạch Thực Hiện

### Phase 1: Tạo Admin Controllers
1. **AdminJobController** - Thay thế JobDashboardController
2. **AdminApplicationController** - Quản lý applications
3. **AdminCompanyController** - Quản lý companies

### Phase 2: Di Chuyển Views
1. Di chuyển logic từ `job-dashboard/` vào `admin/jobs/`
2. Cập nhật layout từ standalone sang admin layout
3. Tích hợp với admin sidebar

### Phase 3: Cập Nhật Routes
1. Thay thế routes `job-dashboard.php`
2. Tích hợp vào admin routes
3. Redirect old URLs

### Phase 4: Cleanup
1. Xóa JobDashboardController
2. Xóa job-dashboard views
3. Xóa job-dashboard routes

## 🔧 Implementation Steps

### Step 1: Tạo Admin Job Controller
```php
// app/Http/Controllers/Admin/JobController.php
class JobController extends Controller
{
    public function index() { /* Job listing */ }
    public function create() { /* Create form */ }
    public function store() { /* Store job */ }
    public function edit($id) { /* Edit form */ }
    public function update($id) { /* Update job */ }
    public function destroy($id) { /* Delete job */ }
}
```

### Step 2: Tạo Admin Routes
```php
// routes/admin.php
Route::prefix('admin')->middleware(['web', 'admin'])->group(function () {
    Route::resource('jobs', Admin\JobController::class);
    Route::resource('applications', Admin\ApplicationController::class);
    Route::resource('companies', Admin\CompanyController::class);
});
```

### Step 3: Di Chuyển Views
- `job-dashboard/index.blade.php` → `admin/jobs/index.blade.php`
- `job-dashboard/create.blade.php` → `admin/jobs/create.blade.php`
- `job-dashboard/edit.blade.php` → `admin/jobs/edit.blade.php`
- `job-dashboard/jobs.blade.php` → `admin/jobs/list.blade.php`

## 📊 Benefits
1. **Unified Interface** - Một giao diện admin duy nhất
2. **Better UX** - Consistent navigation và design
3. **Maintainability** - Dễ bảo trì và phát triển
4. **Security** - Centralized admin authentication
5. **Performance** - Giảm duplicate code

## ⚠️ Risks & Mitigation
1. **Data Loss** - Backup trước khi migrate
2. **URL Changes** - Setup redirects cho old URLs
3. **User Training** - Document new interface

## 🕒 Timeline
- **Week 1**: Tạo controllers và routes
- **Week 2**: Di chuyển và cập nhật views  
- **Week 3**: Testing và bug fixes
- **Week 4**: Cleanup và documentation
