<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AI\PublicThumbnailController;

// AI Thumbnail API routes - accessible without CSRF
Route::prefix('ai-api/thumbnails')->name('ai.api.thumbnails.')->group(function () {
    Route::post('blog', [PublicThumbnailController::class, 'generateBlogThumbnail'])->name('blog.generate');
    Route::post('product', [PublicThumbnailController::class, 'generateProductThumbnail'])->name('product.generate');
    Route::get('statistics', [PublicThumbnailController::class, 'getStatistics'])->name('statistics');
});

