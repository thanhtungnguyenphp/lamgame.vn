# Frontend Migration Checklist

## ⚠️ Breaking Changes - Action Required

### 1. Admin Panel Routes
**Old:** `/admin/job-dashboard/*`
**New:** `/admin/jobs/*`

#### Update these URLs:
- [ ] `/admin/job-dashboard` → `/admin/jobs`
- [ ] `/admin/job-dashboard/jobs` → `/admin/jobs`
- [ ] `/admin/job-dashboard/create` → `/admin/jobs/create`
- [ ] `/admin/job-dashboard/edit/{id}` → `/admin/jobs/{id}/edit`

### 2. API Dashboard Routes
**Old:** `/api/dashboard/jobs`
**New:** `/api/user/jobs`

#### Update API calls:
- [ ] `GET /api/dashboard/jobs` → `GET /api/user/jobs`
- [ ] `POST /api/dashboard/jobs` → `POST /api/user/jobs`
- [ ] `PUT /api/dashboard/jobs/{id}` → `PUT /api/user/jobs/{id}`
- [ ] `DELETE /api/dashboard/jobs/{id}` → `DELETE /api/user/jobs/{id}`

### 3. Authentication Changes
**Important:** Protected routes now require authentication

#### Routes that NOW require auth token:
- [ ] `POST /api/jobs` (create job)
- [ ] `PUT /api/jobs/{id}` (update job)
- [ ] `DELETE /api/jobs/{id}` (delete job)
- [ ] `POST /api/jobs/{id}/publish`
- [ ] `POST /api/jobs/{id}/unpublish`

#### Add Sanctum token to headers:
```javascript
headers: {
  'Authorization': 'Bearer ' + token,
  'Accept': 'application/json'
}
```

### 4. Controller References
If importing controllers in frontend (unlikely but check):
- [ ] `JobDashboardController` → `Admin\JobController`
- [ ] `Api\Dashboard\JobController` → `Api\UserJobController`

## Testing Checklist

### Admin Panel
- [ ] Can access `/admin/jobs`
- [ ] Can create new job
- [ ] Can edit existing job
- [ ] Can delete job
- [ ] No 404 errors on navigation

### Public API
- [ ] Can view job list without auth
- [ ] Can view job detail without auth
- [ ] Can apply to job without auth
- [ ] Can get filter options without auth

### Authenticated API
- [ ] Can create job with auth token
- [ ] Can update own job with auth token
- [ ] Can delete own job with auth token
- [ ] Cannot modify jobs without auth (401 error)
- [ ] Cannot modify other users' jobs (403 error)

### User Dashboard
- [ ] Can view own jobs at `/api/user/jobs`
- [ ] Can see job statistics
- [ ] Can duplicate jobs
- [ ] Can toggle job status
- [ ] Can access analytics

### Bulk Operations
- [ ] Bulk create works
- [ ] Bulk update works
- [ ] Bulk delete works
- [ ] Bulk status toggle works

### Import/Export
- [ ] Can export jobs
- [ ] Can import jobs from CSV/Excel
- [ ] Can download import template
- [ ] Can view import history

## Search & Replace Guide

### For Vue/React Components
```bash
# Find old routes
grep -r "job-dashboard" src/
grep -r "api/dashboard/jobs" src/

# Replace in files
find src/ -type f -exec sed -i '' 's|/admin/job-dashboard|/admin/jobs|g' {} +
find src/ -type f -exec sed -i '' 's|/api/dashboard/jobs|/api/user/jobs|g' {} +
```

### For Blade Templates
```bash
# Find old routes
grep -r "job.dashboard" resources/views/
grep -r "job-dashboard" resources/views/

# Manual replacement needed in blade files
```

## API Response Changes

### No breaking changes in response format
All API responses maintain the same structure. Only URLs have changed.

## Rollback Plan

If issues occur, restore from backup:
```bash
git checkout HEAD~1 routes/
git checkout HEAD~1 app/Http/Controllers/
```

## Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for 404/401 errors
3. Verify auth token is being sent
4. Test with Postman/Insomnia first

## Completion

Once all items are checked:
- [ ] All tests passing
- [ ] No console errors
- [ ] No 404 errors in logs
- [ ] Frontend working as expected
- [ ] Update API documentation
- [ ] Notify team of changes
