<?php

use App\Http\Controllers\Api\ForumManageController;

/*
|--------------------------------------------------------------------------
| Forum Management API Routes (api.key auth)
|--------------------------------------------------------------------------
|
| Header: X-Api-Key: {admin_api_token}
| Prefix: /api/manage/forum
|
*/
Route::prefix('manage/forum')->name('api.manage.forum.')->middleware(['api.key', 'throttle:60,1'])->group(function () {

    // Dashboard
    Route::get('dashboard', [ForumManageController::class, 'dashboard'])->name('dashboard');

    // Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [ForumManageController::class, 'postList'])->name('list');
        Route::get('/{id}', [ForumManageController::class, 'postDetail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [ForumManageController::class, 'postStore'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [ForumManageController::class, 'postUpdate'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'postDestroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/status', [ForumManageController::class, 'postChangeStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::patch('/bulk/status', [ForumManageController::class, 'postBulkStatus'])->name('bulk-status')->middleware('throttle:10,1');
        Route::delete('/bulk', [ForumManageController::class, 'postBulkDelete'])->name('bulk-delete')->middleware('throttle:10,1');
    });

    // Comments
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [ForumManageController::class, 'commentList'])->name('list');
        Route::get('/{id}', [ForumManageController::class, 'commentDetail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/{id}/status', [ForumManageController::class, 'commentChangeStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'commentDestroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::patch('/bulk/status', [ForumManageController::class, 'commentBulkStatus'])->name('bulk-status')->middleware('throttle:10,1');
        Route::delete('/bulk', [ForumManageController::class, 'commentBulkDelete'])->name('bulk-delete')->middleware('throttle:10,1');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [ForumManageController::class, 'categoryList'])->name('list');
        Route::post('/', [ForumManageController::class, 'categoryStore'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [ForumManageController::class, 'categoryUpdate'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'categoryDestroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // Tags
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [ForumManageController::class, 'tagList'])->name('list');
        Route::post('/', [ForumManageController::class, 'tagStore'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [ForumManageController::class, 'tagUpdate'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ForumManageController::class, 'tagDestroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/bulk', [ForumManageController::class, 'tagBulkDelete'])->name('bulk-delete')->middleware('throttle:10,1');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ForumManageController::class, 'reportList'])->name('list');
        Route::post('/{id}/resolve', [ForumManageController::class, 'reportResolve'])->name('resolve')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::patch('/bulk/resolve', [ForumManageController::class, 'reportBulkResolve'])->name('bulk-resolve')->middleware('throttle:10,1');
    });

    // Leaderboard
    Route::get('leaderboard', [ForumManageController::class, 'leaderboard'])->name('leaderboard');
});
