<?php

use App\Http\Controllers\Api\BlogManageController;

/*
|--------------------------------------------------------------------------
| Blog Management API Routes (api.key auth)
|--------------------------------------------------------------------------
|
| Header: X-Api-Key: {admin_api_token}
| Prefix: /api/manage/blogs
|
| Extends the existing BlogPublishController with admin management features:
| - CRUD operations with category/tag management
| - Statistics & analytics
| - Bulk operations
|
*/
Route::prefix('manage/blogs')->name('api.manage.blogs.')->middleware(['api.key', 'throttle:60,1'])->group(function () {

    // === Blog CRUD ===
    Route::get('/', [BlogManageController::class, 'list'])->name('list');
    Route::get('/statistics', [BlogManageController::class, 'statistics'])->name('statistics');
    Route::get('/{id}', [BlogManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
    Route::post('/', [BlogManageController::class, 'store'])->name('store')->middleware('throttle:10,1');
    Route::put('/{id}', [BlogManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
    Route::delete('/{id}', [BlogManageController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
    Route::post('/{id}/status', [BlogManageController::class, 'changeStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');

    // === Bulk Operations ===
    Route::post('/bulk/status', [BlogManageController::class, 'bulkChangeStatus'])->name('bulk-status')->middleware('throttle:10,1');
    Route::delete('/bulk/delete', [BlogManageController::class, 'bulkDelete'])->name('bulk-delete')->middleware('throttle:10,1');

    // === Categories ===
    Route::get('/categories', [BlogManageController::class, 'categoryList'])->name('categories.list');
    Route::post('/categories', [BlogManageController::class, 'categoryStore'])->name('categories.store')->middleware('throttle:10,1');
    Route::put('/categories/{id}', [BlogManageController::class, 'categoryUpdate'])->name('categories.update')->where('id', '[0-9]+')->middleware('throttle:10,1');
    Route::delete('/categories/{id}', [BlogManageController::class, 'categoryDestroy'])->name('categories.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');

    // === Tags ===
    Route::get('/tags', [BlogManageController::class, 'tagList'])->name('tags.list');
    Route::post('/tags', [BlogManageController::class, 'tagStore'])->name('tags.store')->middleware('throttle:10,1');
    Route::delete('/tags/{id}', [BlogManageController::class, 'tagDestroy'])->name('tags.destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
});
