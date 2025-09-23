<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AI\PublicThumbnailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AI Thumbnail Generation API routes (without CSRF protection)
Route::prefix('ai/thumbnails')->name('api.ai.thumbnails.')->middleware('throttle:60,1')->group(function () {
    Route::post('blog', [PublicThumbnailController::class, 'generateBlogThumbnail'])->name('blog.generate');
    Route::post('product', [PublicThumbnailController::class, 'generateProductThumbnail'])->name('product.generate');
    Route::get('statistics', [PublicThumbnailController::class, 'getStatistics'])->name('statistics');
});
