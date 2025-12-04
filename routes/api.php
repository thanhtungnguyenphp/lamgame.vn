<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PublicThumbnailController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserJobController;
use App\Http\Controllers\Api\JobAnalyticsController;
use App\Http\Controllers\Api\JobBulkController;
use App\Http\Controllers\Api\JobImportExportController;
use App\Http\Controllers\Api\JobOptionsController;
use App\Http\Controllers\Api\AiJobDescriptionController;

// Company logo API
Route::get('/company-logo/{filename}', function($filename) {
    $path = 'company-logos/' . $filename;
    
    if (!\Storage::disk('public')->exists($path)) {
        return response()->json(['error' => 'Logo not found'], 404);
    }
    
    try {
        $file = \Storage::disk('public')->get($path);
        $mimeType = \Storage::disk('public')->mimeType($path);
        $logoUrl = 'data:' . $mimeType . ';base64,' . base64_encode($file);
        
        return response()->json(['logo_url' => $logoUrl]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to load logo'], 500);
    }
})->where('filename', '[A-Za-z0-9\-_\.]+');
use App\Http\Controllers\Api\JobFileParserController;

// AI Job Description Routes
Route::middleware('auth:sanctum')->prefix('ai')->group(function () {
    Route::post('job-description/optimize', [AiJobDescriptionController::class, 'optimize']);
    Route::post('job-description/suggestions', [AiJobDescriptionController::class, 'generateSuggestions']);
    Route::post('job-description/parse-file', [JobFileParserController::class, 'parseJobFile']);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Include authentication routes
require __DIR__ . '/api/auth.php';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Banner Dynamic Content API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('banner')->group(function () {
    Route::get('/jobs', [BannerController::class, 'jobs'])
        ->middleware('throttle:60,1')
        ->name('api.banner.jobs');
        
    Route::get('/topics', [BannerController::class, 'topics'])
        ->middleware('throttle:60,1')
        ->name('api.banner.topics');
        
    Route::get('/blogs', [BannerController::class, 'blogs'])
        ->middleware('throttle:60,1')
        ->name('api.banner.blogs');
        
    Route::get('/sources', [BannerController::class, 'sources'])
        ->middleware('throttle:60,1')
        ->name('api.banner.sources');
        
    Route::get('/all', [BannerController::class, 'all'])
        ->middleware('throttle:30,1')
        ->name('api.banner.all');
});

// AI Thumbnail Generation API routes (without CSRF protection)
Route::prefix('ai/thumbnails')->name('api.ai.thumbnails.')->middleware('throttle:60,1')->group(function () {
    Route::post('blog', [PublicThumbnailController::class, 'generateBlogThumbnail'])->name('blog.generate');
    Route::post('product', [PublicThumbnailController::class, 'generateProductThumbnail'])->name('product.generate');
    Route::get('statistics', [PublicThumbnailController::class, 'getStatistics'])->name('statistics');
});

/*
|--------------------------------------------------------------------------
| Job Posting API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('jobs')->name('api.jobs.')->middleware('throttle:60,1')->group(function () {
    // Public endpoints (no auth required)
    Route::get('/', [\App\Http\Controllers\Api\JobController::class, 'index'])->name('index');
    Route::get('/categories', [\App\Http\Controllers\Api\JobController::class, 'getCategories'])->name('categories');
    Route::get('/attributes', [\App\Http\Controllers\Api\JobController::class, 'getAttributes'])->name('attributes');
    Route::get('/{id}', [\App\Http\Controllers\Api\JobController::class, 'show'])->name('show')->where('id', '[0-9]+');
    
    // Job Application endpoints
    Route::post('/{jobId}/apply', [\App\Http\Controllers\Api\JobApplicationController::class, 'apply'])->name('apply')->where('jobId', '[0-9]+');
    
    // Company management endpoints
    Route::get('/company/info', [JobController::class, 'getCompanyInfo'])->name('company.info');
    Route::post('/company/save', [JobController::class, 'saveCompanyInfo'])->name('company.save');
    
    // Protected endpoints (auth required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\JobController::class, 'store'])->name('store');
        Route::put('/{id}', [\App\Http\Controllers\Api\JobController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [\App\Http\Controllers\Api\JobController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::post('/bulk', [\App\Http\Controllers\Api\JobController::class, 'bulkStore'])->name('bulk-store');
        Route::post('/{id}/publish', [\App\Http\Controllers\Api\JobController::class, 'publish'])->name('publish')->where('id', '[0-9]+');
        Route::post('/{id}/unpublish', [\App\Http\Controllers\Api\JobController::class, 'unpublish'])->name('unpublish')->where('id', '[0-9]+');
    });
});

/*
|--------------------------------------------------------------------------
| Job Options & Filter API Routes (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('jobs/options')->name('api.jobs.options.')->middleware('throttle:120,1')->group(function () {
    // Get all filter options for search/filter forms
    Route::get('/filter-options', [JobOptionsController::class, 'getFilterOptions'])->name('filter-options');
    
    // Get job form data (combined endpoint for job creation forms)
    Route::get('/form-data', [JobOptionsController::class, 'getJobFormData'])->name('form-data');
    
    // Individual option endpoints
    Route::get('/skills', [JobOptionsController::class, 'getSkills'])->name('skills');
    Route::get('/companies', [JobOptionsController::class, 'getCompanies'])->name('companies');
    Route::get('/benefits', [JobOptionsController::class, 'getBenefits'])->name('benefits');
    Route::get('/salary-ranges', [JobOptionsController::class, 'getSalaryRanges'])->name('salary-ranges');
    Route::get('/industries', [JobOptionsController::class, 'getIndustries'])->name('industries');
    Route::get('/popular-keywords', [JobOptionsController::class, 'getPopularKeywords'])->name('popular-keywords');
    
    // Search across multiple option types
    Route::get('/search', [JobOptionsController::class, 'searchOptions'])->name('search');
});

/*
|--------------------------------------------------------------------------
| Dashboard API Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->name('api.dashboard.')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Main dashboard endpoint - returns 5 newest jobs and 5 recent applications
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    // Get applications for a specific job
    Route::get('/jobs/{jobId}/applications', [DashboardController::class, 'jobApplications'])
        ->name('job.applications')
        ->where('jobId', '[0-9]+');
    
    // Update application status (accept, reject, etc.)
    Route::put('/applications/{applicationId}/status', [DashboardController::class, 'updateApplicationStatus'])
        ->name('application.update-status')
        ->where('applicationId', '[0-9]+');
});

/*
|--------------------------------------------------------------------------
| User Job Management API Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('user/jobs')->name('api.user.jobs.')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Get user's own jobs (with advanced filtering)
    Route::get('/', [UserJobController::class, 'index'])->name('index');
    
    // Create new job posting
    Route::post('/', [UserJobController::class, 'store'])->name('store');
    
    // Get specific job owned by user
    Route::get('/{id}', [UserJobController::class, 'show'])
        ->name('show')
        ->where('id', '[0-9]+');
    
    // Update job owned by user
    Route::put('/{id}', [UserJobController::class, 'update'])
        ->name('update')
        ->where('id', '[0-9]+');
    
    // Delete job owned by user
    Route::delete('/{id}', [UserJobController::class, 'destroy'])
        ->name('destroy')
        ->where('id', '[0-9]+');
    
    // Toggle job status (activate/deactivate)
    Route::patch('/{id}/toggle-status', [UserJobController::class, 'toggleStatus'])
        ->name('toggle-status')
        ->where('id', '[0-9]+');
        
    // ========== NEW ADVANCED FEATURES ==========
    
    // Get job statistics for user
    Route::get('/statistics', [UserJobController::class, 'statistics'])->name('statistics');
    
    // Duplicate job with optional modifications
    Route::post('/{id}/duplicate', [UserJobController::class, 'duplicate'])
        ->name('duplicate')
        ->where('id', '[0-9]+');
        
    // Get filter options for UI
    Route::get('/filter-options', [UserJobController::class, 'getFilterOptions'])->name('filter-options');
    
    // Save search filter template
    Route::post('/filter-templates', [UserJobController::class, 'saveFilterTemplate'])->name('save-filter-template');
    
    // Get saved filter templates
    Route::get('/filter-templates', [UserJobController::class, 'getFilterTemplates'])->name('get-filter-templates');
    
    // ========== ADVANCED JOB MANAGEMENT ==========
    
    // Extend job application deadline
    Route::post('/{id}/extend-deadline', [UserJobController::class, 'extendDeadline'])
        ->name('extend-deadline')
        ->where('id', '[0-9]+');
        
    // Preview job as it appears to applicants
    Route::get('/{id}/preview', [UserJobController::class, 'preview'])
        ->name('preview')
        ->where('id', '[0-9]+');
        
    // Boost job (mark as featured/urgent)
    Route::post('/{id}/boost', [UserJobController::class, 'boost'])
        ->name('boost')
        ->where('id', '[0-9]+');
        
    // Get job templates
    Route::get('/templates', [UserJobController::class, 'getJobTemplates'])->name('templates');
    
    // Create job from template
    Route::post('/from-template/{templateId}', [UserJobController::class, 'createFromTemplate'])
        ->name('from-template');
});

/*
|--------------------------------------------------------------------------
| Job Analytics API Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('analytics/jobs')->name('api.analytics.jobs.')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Get analytics overview
    Route::get('/overview', [JobAnalyticsController::class, 'overview'])->name('overview');
    
    // Get individual job analytics
    Route::get('/{id}/analytics', [JobAnalyticsController::class, 'jobAnalytics'])
        ->name('individual')
        ->where('id', '[0-9]+');
    
    // Get trending metrics
    Route::get('/trends', [JobAnalyticsController::class, 'trends'])->name('trends');
    
    // Compare multiple jobs
    Route::post('/comparison', [JobAnalyticsController::class, 'comparison'])->name('comparison');
    
    // Get performance insights
    Route::get('/insights', [JobAnalyticsController::class, 'insights'])->name('insights');
});

/*
|--------------------------------------------------------------------------
| Job Bulk Operations API Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('user/jobs/bulk')->name('api.jobs.bulk.')->middleware(['auth:sanctum', 'throttle:30,1'])->group(function () {
    // Bulk create jobs
    Route::post('/create', [JobBulkController::class, 'bulkCreate'])->name('create');
    
    // Bulk update jobs
    Route::put('/update', [JobBulkController::class, 'bulkUpdate'])->name('update');
    
    // Bulk delete jobs
    Route::delete('/delete', [JobBulkController::class, 'bulkDelete'])->name('delete');
    
    // Bulk toggle status
    Route::patch('/toggle-status', [JobBulkController::class, 'bulkToggleStatus'])->name('toggle-status');
    
    // Bulk duplicate jobs
    Route::post('/duplicate', [JobBulkController::class, 'bulkDuplicate'])->name('duplicate');
    
    // Bulk archive jobs
    Route::post('/archive', [JobBulkController::class, 'bulkArchive'])->name('archive');
    
    // Get bulk operation status
    Route::get('/status/{operationId}', [JobBulkController::class, 'getBulkOperationStatus'])
        ->name('status')
        ->where('operationId', '[a-zA-Z0-9\-_]+');
});

/*
|--------------------------------------------------------------------------
| Job Import/Export API Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('user/jobs')->name('api.jobs.import-export.')->middleware(['auth:sanctum', 'throttle:20,1'])->group(function () {
    // Import jobs from CSV/Excel file
    Route::post('/import', [JobImportExportController::class, 'import'])->name('import');
    
    // Export jobs to CSV/Excel/PDF
    Route::get('/export', [JobImportExportController::class, 'export'])->name('export');
    Route::post('/export', [JobImportExportController::class, 'export'])->name('export.post');
    
    // Download import template
    Route::get('/import-template', [JobImportExportController::class, 'downloadTemplate'])->name('import-template');
    
    // Preview import data before actual import
    Route::post('/import-preview', [JobImportExportController::class, 'previewImport'])->name('import-preview');
    
    // Get import/export history
    Route::get('/import-history', [JobImportExportController::class, 'getImportHistory'])->name('import-history');
    
    // Get field mapping options for import
    Route::get('/field-mapping-options', [JobImportExportController::class, 'getFieldMappingOptions'])->name('field-mapping-options');
});

/*
|--------------------------------------------------------------------------
| Job Application API Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('applications')->name('api.applications.')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Get user's applications
    Route::get('/', [\App\Http\Controllers\Api\JobApplicationController::class, 'getUserApplications'])->name('user.index');
    
    // Get specific application status (requires email for guest users)
    Route::get('/{applicationId}/status', [\App\Http\Controllers\Api\JobApplicationController::class, 'getApplicationStatus'])
        ->name('status')
        ->where('applicationId', '[0-9]+');
});
