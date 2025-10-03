<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PublicThumbnailController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserJobController;

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
    
    // Protected endpoints (auth required) - uncomment when auth is implemented
    /*
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\JobController::class, 'store'])->name('store');
        Route::put('/{id}', [\App\Http\Controllers\Api\JobController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [\App\Http\Controllers\Api\JobController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::post('/bulk', [\App\Http\Controllers\Api\JobController::class, 'bulkStore'])->name('bulk-store');
        Route::post('/{id}/publish', [\App\Http\Controllers\Api\JobController::class, 'publish'])->name('publish')->where('id', '[0-9]+');
        Route::post('/{id}/unpublish', [\App\Http\Controllers\Api\JobController::class, 'unpublish'])->name('unpublish')->where('id', '[0-9]+');
    });
    */
    
    // Temporary: Allow all endpoints without auth for testing
    Route::post('/', [\App\Http\Controllers\Api\JobController::class, 'store'])->name('store');
    Route::put('/{id}', [\App\Http\Controllers\Api\JobController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('/{id}', [\App\Http\Controllers\Api\JobController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    Route::post('/bulk', [\App\Http\Controllers\Api\JobController::class, 'bulkStore'])->name('bulk-store');
    Route::post('/{id}/publish', [\App\Http\Controllers\Api\JobController::class, 'publish'])->name('publish')->where('id', '[0-9]+');
    Route::post('/{id}/unpublish', [\App\Http\Controllers\Api\JobController::class, 'unpublish'])->name('unpublish')->where('id', '[0-9]+');
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
    // Get user's own jobs
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
