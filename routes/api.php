<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;

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
