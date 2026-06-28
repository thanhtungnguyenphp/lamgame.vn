<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PublicThumbnailController;
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

// Include lottery routes
require __DIR__ . '/api/lottery.php';

// Include subscription routes
require __DIR__ . '/api/subscription.php';

// Include AI Tools routes
require __DIR__ . '/api/ai-tools.php';

// Include AI Chat routes (OHHA Core proxy)
require __DIR__ . '/api/ai-chat.php';

// Include notification routes
require __DIR__ . '/api/notifications.php';

// Include sport routes
require __DIR__ . '/api/sport.php';

// Include forum API routes
Route::prefix('v1/forum')->group(base_path('routes/api/forum.php'));

// Blog Publish API (key-based auth)
Route::prefix('blog')->name('api.blog.')->middleware('api.key')->group(function () {
    Route::post('/publish', [\App\Http\Controllers\Api\BlogPublishController::class, 'publish'])->middleware('throttle:10,1')->name('publish');
    Route::post('/update/{slug}', [\App\Http\Controllers\Api\BlogPublishController::class, 'update'])->middleware('throttle:10,1')->name('update');
    Route::delete('/delete/{slug}', [\App\Http\Controllers\Api\BlogPublishController::class, 'destroy'])->middleware('throttle:10,1')->name('delete');
    Route::post('/status', [\App\Http\Controllers\Api\BlogPublishController::class, 'status'])->middleware('throttle:60,1')->name('status');
    Route::post('/status/{slug}', [\App\Http\Controllers\Api\BlogPublishController::class, 'changeStatus'])->middleware('throttle:10,1')->name('change-status');
    Route::get('/list', [\App\Http\Controllers\Api\BlogPublishController::class, 'list'])->middleware('throttle:60,1')->name('list');
    Route::get('/detail/{slug}', [\App\Http\Controllers\Api\BlogPublishController::class, 'detail'])->middleware('throttle:60,1')->name('detail');
});

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

// AI Thumbnail Generation API routes (protected)
Route::prefix('ai/thumbnails')->name('api.ai.thumbnails.')->middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::post('blog', [PublicThumbnailController::class, 'generateBlogThumbnail'])->name('blog.generate');
    Route::post('product', [PublicThumbnailController::class, 'generateProductThumbnail'])->name('product.generate');
    Route::get('statistics', [PublicThumbnailController::class, 'getStatistics'])->name('statistics');
});

/*
|--------------------------------------------------------------------------
| Job API Routes — V1 removed, use V2 (api-job-v2.php)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Mini Game Leaderboard API
|--------------------------------------------------------------------------
*/
Route::prefix('games/{gameKey}/leaderboard')
    ->middleware('throttle:60,1')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\GameLeaderboardController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\GameLeaderboardController::class, 'store']);
        Route::get('/player/{player}', [\App\Http\Controllers\Api\GameLeaderboardController::class, 'player']);
    });
