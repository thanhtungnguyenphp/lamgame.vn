# Job Management Routes Structure (After Cleanup)

## Admin Routes (Web)
**File:** `routes/admin.php`
- GET    /admin/jobs              → Admin\JobController@index
- GET    /admin/jobs/create       → Admin\JobController@create
- POST   /admin/jobs              → Admin\JobController@store
- GET    /admin/jobs/{id}         → Admin\JobController@show
- GET    /admin/jobs/{id}/edit    → Admin\JobController@edit
- PUT    /admin/jobs/{id}         → Admin\JobController@update
- DELETE /admin/jobs/{id}         → Admin\JobController@destroy

## Public API Routes
**File:** `routes/api.php`

### Job Listing & Details (Public)
- GET    /api/jobs                → Api\JobController@index
- GET    /api/jobs/{id}           → Api\JobController@show
- GET    /api/jobs/categories     → Api\JobController@getCategories
- GET    /api/jobs/attributes     → Api\JobController@getAttributes
- POST   /api/jobs/{id}/apply     → Api\JobApplicationController@apply

### Job Management (Auth Required)
- POST   /api/jobs                → Api\JobController@store
- PUT    /api/jobs/{id}           → Api\JobController@update
- DELETE /api/jobs/{id}           → Api\JobController@destroy
- POST   /api/jobs/bulk           → Api\JobController@bulkStore
- POST   /api/jobs/{id}/publish   → Api\JobController@publish
- POST   /api/jobs/{id}/unpublish → Api\JobController@unpublish

### Job Options (Public)
- GET    /api/jobs/options/filter-options    → Api\JobOptionsController@getFilterOptions
- GET    /api/jobs/options/form-data         → Api\JobOptionsController@getJobFormData
- GET    /api/jobs/options/skills            → Api\JobOptionsController@getSkills
- GET    /api/jobs/options/companies         → Api\JobOptionsController@getCompanies
- GET    /api/jobs/options/benefits          → Api\JobOptionsController@getBenefits
- GET    /api/jobs/options/salary-ranges     → Api\JobOptionsController@getSalaryRanges

### User Job Management (Auth Required)
- GET    /api/user/jobs           → Api\UserJobController@index
- POST   /api/user/jobs           → Api\UserJobController@store
- GET    /api/user/jobs/{id}      → Api\UserJobController@show
- PUT    /api/user/jobs/{id}      → Api\UserJobController@update
- DELETE /api/user/jobs/{id}      → Api\UserJobController@destroy
- PATCH  /api/user/jobs/{id}/toggle-status → Api\UserJobController@toggleStatus
- POST   /api/user/jobs/{id}/duplicate     → Api\UserJobController@duplicate

### Job Analytics (Auth Required)
- GET    /api/analytics/jobs/overview        → Api\JobAnalyticsController@overview
- GET    /api/analytics/jobs/{id}/analytics  → Api\JobAnalyticsController@jobAnalytics
- GET    /api/analytics/jobs/trends          → Api\JobAnalyticsController@trends
- POST   /api/analytics/jobs/comparison      → Api\JobAnalyticsController@comparison

### Bulk Operations (Auth Required)
- POST   /api/user/jobs/bulk/create          → Api\JobBulkController@bulkCreate
- PUT    /api/user/jobs/bulk/update          → Api\JobBulkController@bulkUpdate
- DELETE /api/user/jobs/bulk/delete          → Api\JobBulkController@bulkDelete
- PATCH  /api/user/jobs/bulk/toggle-status   → Api\JobBulkController@bulkToggleStatus

### Import/Export (Auth Required)
- POST   /api/user/jobs/import               → Api\JobImportExportController@import
- GET    /api/user/jobs/export               → Api\JobImportExportController@export
- GET    /api/user/jobs/import-template      → Api\JobImportExportController@downloadTemplate
- GET    /api/user/jobs/import-history       → Api\JobImportExportController@getImportHistory

### AI Features (Auth Required)
- POST   /api/ai/job-description/optimize    → Api\AiJobDescriptionController@optimize
- POST   /api/ai/job-description/suggestions → Api\AiJobDescriptionController@generateSuggestions
- POST   /api/ai/job-description/parse-file  → Api\JobFileParserController@parseJobFile

## Controllers Summary

| Controller | Purpose | Auth |
|------------|---------|------|
| Admin\JobController | Admin panel management | Admin middleware |
| Api\JobController | Public job listing & CRUD | Mixed (public GET, auth POST/PUT/DELETE) |
| Api\UserJobController | User manages own jobs | Required |
| Api\JobOptionsController | Filter options & metadata | Public |
| Api\JobAnalyticsController | Statistics & insights | Required |
| Api\JobBulkController | Bulk operations | Required |
| Api\JobImportExportController | Import/export data | Required |
| Api\JobApplicationController | Job applications | Public |
| Api\AiJobDescriptionController | AI optimization | Required |
| Api\JobFileParserController | Parse job files | Required |

## Removed (Deprecated)

- ❌ /admin/job-dashboard/* → Use /admin/jobs instead
- ❌ /api/dashboard/jobs → Use /api/user/jobs instead
- ❌ JobDashboardController → Use Admin\JobController
- ❌ Api\Dashboard\JobController → Use Api\UserJobController
- ❌ Api\JobControllerOptimized → Removed (unused)
- ❌ Api\JobStatsController → Merged into JobAnalyticsController
